<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\ZoneServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\Zone;
    
    class ZoneServiceImplement implements ZoneServiceInterface {
        
        private $zone;

        function __construct(){
            $this->zone = new Zone;
        }    

        function list(){
            try {
                $sql = $this->zone->select('id', 'name', 'code')
                            ->get();

                if (count($sql) > 0){
                    return response()->json([
                        'data' => $sql
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => ['No hay zonas para mostrar']
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => ['Se ha presentado un error al cargar las zonas, intente recargando la página']
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }


        function create(array $zone){
            try {
                $status = $this->zone::create([
                    'code' => $zone['code'],
                    'name' => $zone['name']
                ]);
                return response()->json([
                    'message' => ['Zona creada con éxito']
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => ['Se ha presentado un error al registrar la zona']
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function update(array $zone, int $id){
            try {
                $zone = $this->zone::find($id);
                if(!empty($sql)) {
                    $zone->name = $zone['name'];
                    $zone->code = $zone['code'];
                    $zone->save();
                    return response()->json([
                        'message' => ['Zona actualizada con éxito']
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => ['La zona que intenta eliminar, no existe']
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => ['Se ha presentado un error al actualizar la zona']
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function delete(int $id){   
            try {
                $deleted = $this->zone::where('id', $id)->delete();
                return response()->json([
                    'message' => ['Zona eliminada con éxito']
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => ['Se ha presentado un error al eliminar la zona']
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function get(int $id){   
            try {
                $sql = $this->zone::select('id', 'code', 'name')
                            ->where('id', $id)   
                            ->first();
                if(!empty($sql)) {
                    return response()->json([
                        'data' => $sql
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => ['Esta zona no existe']
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => ['Se ha presentado un error al eliminar la zona']
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
?>