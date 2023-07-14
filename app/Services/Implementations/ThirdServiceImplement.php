<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\ThirdServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\Third;
    use App\Validator\ThirdValidator;
    use App\Validator\ThirdFileValidator;
    use App\Validator\ThirdBasicValidator;
    use App\Traits\Commons;
    
    class ThirdServiceImplement implements ThirdServiceInterface {

        use Commons;

        private $third;
        private $validator;
        private $fileValidator;
        private $basicValidator;

        function __construct(ThirdValidator $validator, ThirdFileValidator $fileValidator, ThirdBasicValidator $basicValidator){
            $this->third = new Third;
            $this->validator = $validator;
            $this->fileValidator = $fileValidator;
            $this->basicValidator = $basicValidator;
        }    

        function list(int $displayAll, string $type, string $third){
            try {
                $type = urldecode($type) === 'CU' ? 'customer' : (urldecode($type) === 'AS' ? 'associated' : (urldecode($type) === 'CO' ? 'contractor' : null));
                $third = explode(',', $third);
                $sql = $this->third->select(
                    'id',
                    'nit',
                    'name',
                    'active',
                    'customer',
                    'associated',
                    'contractor',
                    'active'
                )
                    ->when($displayAll === 0 && $type === null, function ($query) use ($third)  {
                        return $query->where('active', 1)
                            ->orWhereIn('id', $third);
                            
                    })
                    ->when($displayAll === 0 && $type !== null, function ($query) use ($type, $third)  {
                        return $query->where(function ($query) use($type) {
                            $query->where($type, 1)
                                ->where('active', 1);
                        })->orWhereIn('id', $third);
                    })
                    ->get();

                if (count($sql) > 0){
                    return response()->json([
                        'data' => $sql
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'No hay terceros para mostrar',
                                'detail' => 'Aun no ha registrado ningun tercero'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al cargar los terceros',
                            'detail' => 'intente recargando la página'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }


        function create(array $third){
            try {
                $validation = $this->validate($this->validator, $third, null, 'registrar', 'tercero', null);
                if ($validation['success'] === false) {
                    return response()->json([
                        'message' => $validation['message']
                    ], Response::HTTP_BAD_REQUEST);
                }
                $this->third::create([
                    'nit' => $third['nit'],
                    'name' => $third['name'],
                    'customer' => $third['customer'],
                    'associated' => $third['associated'],
                    'contractor' => $third['contractor'],
                    'active' => $third['active']
                ]);
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Tercero registrado con éxito',
                            'detail' => null
                        ]
                    ]
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al registrar el tercero',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function update(array $third, int $id){
            try {
                $validation = $this->validate($this->validator, $third, $id, 'actualizar', 'tercero', null);
                if ($validation['success'] === false) {
                    return response()->json([
                        'message' => $validation['message']
                    ], Response::HTTP_BAD_REQUEST);
                }
                $sql = $this->third::find($id);
                if(!empty($sql)) {
                    $sql->name = $third['name'];
                    $sql->nit = $third['nit'];
                    $sql->customer = $third['customer'];
                    $sql->associated = $third['associated'];
                    $sql->contractor = $third['contractor'];
                    $sql->active = $third['active'];
                    $sql->save();
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Tercero actualizado con éxito',
                                'detail' => null
                            ]
                        ]
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al actualizar el tercero',
                                'detail' => 'El tercero no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al actualizar el tercero',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function delete(int $id){   
            try {
                $sql = $this->third::find($id);
                if(!empty($sql)) {
                    $sql->delete();
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Tercero eliminado con éxito',
                                'detail' => null
                            ]
                        ]
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al eliminar el tercero',
                                'detail' => 'El tercero no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                if ($e->getCode() !== "23000") {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al eliminar el tercero',
                                'detail' => 'Si este problema persiste, contacte con un administrador'
                            ]
                        ]
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'No se permite eliminar el tercero',
                                'detail' => 'El tercero se encuentra asociada a otro registro'
                            ]
                        ]
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }

        function get(int $id){
            try {
                $sql = $this->third::select(
                    'id',
                    'nit',
                    'name',
                    'customer',
                    'associated',
                    'contractor',
                    'active'
                )
                    ->where('id', $id)   
                    ->first();
                if(!empty($sql)) {
                    return response()->json([
                        'data' => $sql
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'El tercero no existe',
                                'detail' => 'por favor recargue la página'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al buscar el tercero',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function createInBatch(array $data){
            try {
                $validation = $this->validate($this->fileValidator, $data, null, 'registrar', 'terceros', null);
                if ($validation['success'] === false) {
                    return response()->json([
                        'message' => $validation['message']
                    ], Response::HTTP_BAD_REQUEST);
                }
                $content = file_get_contents($data['file']);
                $header = $this->third->getFillable();
                if(!empty($content)) {
                    $lines = explode(PHP_EOL, $content);
                    $countLines = count($lines);
                    $dataInsert = [];
                    for ($i = 0; $i < $countLines; $i++) {
                        if(trim(strlen($lines[$i])) === 0) {
                            return response()->json([
                                'message' => [
                                    [
                                        'text' => 'Se ha presentado un error al registrar los terceros',
                                        'detail' => 'La linea "'.($i+1).'" del archivo no tiene data'
                                    ]
                                ]
                            ], Response::HTTP_BAD_REQUEST);
                        }
                        $arrayData = explode(',', $lines[$i]);
                        if(count($arrayData) !== 5) {
                            return response()->json([
                                'message' => [
                                    [
                                        'text' => 'Se ha presentado un error al registrar los terceros',
                                        'detail' => 'La linea "'.($i+1).'" del archivo tiene mas o menos columnas de las requeridas'
                                    ]
                                ]
                            ], Response::HTTP_BAD_REQUEST);
                        }
                        $arrayData[2] = str_replace(["SI", "NO"], [1, 0]);
                        $arrayData[3] = str_replace(["SI", "NO"], [1, 0]);
                        $arrayData[4] = str_replace(["SI", "NO"], [1, 0]);
                        array_unshift($arrayData, null);
                        array_push($arrayData, 1);
                        $arrayInsert = array_combine($header, $arrayData);
                        $validation = $this->validate($this->basicValidator, $arrayInsert, null, 'registrar', 'terceros', ($i+1));
                        if ($validation['success'] === false) {
                            return response()->json([
                                'message' => $validation['message']
                            ], Response::HTTP_BAD_REQUEST);
                        }
                        $dataInsert[] = $arrayInsert;
                    }
                    $this->third::upsert($dataInsert,
                        ['nit'],
                        ['name', 'customer', 'associated', 'contractor']
                    );
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Terceros registrados con éxito',
                                'detail' => null
                            ]
                        ]
                    ], Response::HTTP_OK);                    
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Se ha presentado un error al registrar los terceros',
                                'detail' => 'El archivo cargado no presenta data'
                            ]
                        ]
                    ], Response::HTTP_BAD_REQUEST);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al registrar los terceros',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
?>