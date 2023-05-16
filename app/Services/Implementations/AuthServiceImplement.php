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
                                ->update(['revoked' => 1]);
                            $token = $user->createToken($grantClient)->accessToken;
                            return response()->json([
                                'token' => $token,
                                'user' => $user
                            ], Response::HTTP_OK);
                        } else {
                            return response()->json([
                                'message' => ['Se ha presentado un inconveniente al generar el token de sesión']
                            ], Response::HTTP_NOT_FOUND);
                        }
                    } else {
                        return response()->json([
                            'message' => ['Las credenciales ingresadas son incorrectas']
                        ], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return response()->json([
                        'message' => ['El usuario con el número de documento "'.$documentNumber.'" no se encuentra registrado']
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $t) {
                return response()->json([
                    'message' => ['Se ha presentado un error en nuestro servicio, por favor, contacte con un administrador']
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
?>