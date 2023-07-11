<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\AuthServiceInterface;
    use Illuminate\Support\Facades\{Hash, Auth};
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\{OauthClient, User, OauthAccessToken};
    class AuthServiceImplement implements AuthServiceInterface{

        private $oauthClient;
        private $oauthAccessToken;
        private $user;  

        function __construct(){            
            $this->oauthClient = new OauthClient;
            $this->user = new User;
            $this->oauthAccessToken = new OauthAccessToken;
        }    

        function getActiveToken(){
            try {
                $sql = $this->oauthClient->select('secret as key')
                            ->where('password_client', 1)
                            ->where('revoked', 0)
                            ->first();
                  
                $oauthClient = !empty($sql) ? $sql->key : null;

                if (!empty($oauthClient)){
                    return response()->json([
                        'key' => $oauthClient
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => ['Actualmente no es posible iniciar sesión, por favor contacte con un administrador']
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => ['Se ha presentado un error al preparar el inicio de sesión, por favor contacte con un administrador']
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function login(string $documentNumber, string $password){
            try {
                $user = $this->user::where('document_number', $documentNumber)->first();
                if (!empty($user)) {
                    if(Auth::attempt(['document_number' => $documentNumber, 'password' => $password])){
                        $grantClient = $this->oauthClient->select('secret as key')
                            ->where('password_client', 1)
                            ->where('revoked', 0)
                            ->first();

                        $grantClient = !empty($grantClient) ? $grantClient->key : null;

                        if (!empty($grantClient)) {
                            $this->oauthAccessToken::where('user_id', '=', $user->id)
                                ->delete();
                            $token = $user->createToken($grantClient)->accessToken;
                            $permissions = $user->getPermissionsViaRoles();
                            $roles = $user->getRoleNames();
                            $dataPermissions = [];
                            $menu = [];
                            foreach ($permissions as $permission) {
                                $menu[] = [
                                    'route' => $permission->route,
                                    'name' => $permission->group,
                                    'menu' => $permission->menu
                                ];
                                $dataPermissions[] = [
                                    'name' => $permission->name,
                                    'displayName' => $permission->display_name
                                ];
                            }
                            $userData = array(
                                'name' => $user->name,
                                'document' => $user->document_number,
                                'yard' => $user->yard,
                                'user' => $user->id
                            );
                            return response()->json([
                                'token' => $token,
                                'user' => $userData,
                                'permissions' => array_values(array_unique($dataPermissions, SORT_REGULAR)),
                                'menu' => array_values(array_unique($menu, SORT_REGULAR)),
                                'roles' => $roles
                            ], Response::HTTP_OK);
                        } else {
                            return response()->json([
                                'message' => [
                                    [
                                        'text' => 'Error de autenticación',
                                        'detail' => 'Se ha presentado un inconveniente al generar el token de sesión'
                                    ]
                                ]
                            ], Response::HTTP_NOT_FOUND);
                        }
                    } else {
                        return response()->json([
                            'message' => [
                                [
                                    'text' => 'Error de autenticación',
                                    'detail' => 'La contraseña ingresada es incorrecta'
                                ]
                            ]
                        ], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Error de autenticación',
                                'detail' => 'El usuario con el número de documento "'.$documentNumber.'" no se encuentra registrado'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $t) {
                dd($t->getMessage());
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error en el servicio',
                            'detail' => 'por favor, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function logout(){
            try {
                $id = Auth::user()->id;
                $this->oauthAccessToken::where('user_id', '=', $id)
                    ->delete();
                return response()->json([], Response::HTTP_OK);
            } catch (\Throwable $t) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error en el servicio',
                            'detail' => 'por favor, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
?>