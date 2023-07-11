<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\AdjustmentServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\Adjustment;
    use App\Validator\AdjustmentValidator;
    use App\Traits\Commons;
    use Illuminate\Support\Facades\DB;
    
    class AdjustmentServiceImplement implements AdjustmentServiceInterface {

        use Commons;

        private $adjustment;
        private $validator;

        function __construct(AdjustmentValidator $validator){
            $this->adjustment = new Adjustment;
            $this->validator = $validator;
        }    

        function list(){
            try {
                $sql = $this->adjustment->from('adjustments as a')
                    ->select(
                        'a.id as id',
                        DB::Raw("IF(a.type = 'A', 'Aumento', 'Disminución') as type"),
                        'y.name as yard',
                        'm.name as material',
                        DB::Raw("DATE_FORMAT(a.date, '%d/%m/%Y') as date"),
                        DB::Raw("FORMAT(a.amount, 2) as amount")
                    )                   
                    ->join('yards as y', 'a.yard', 'y.id')
                    ->join('materials as m', 'a.material', 'm.id')
                    ->orderBy('date', 'desc')
                    ->get();

                if (count($sql) > 0){
                    return response()->json([
                        'data' => $sql
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'No hay ajustes para mostrar',
                                'detail' => 'Aun no ha registrado ningun ajuste'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al cargar los ajustes',
                            'detail' => 'intente recargando la página'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }


        function create(array $adjustment){
            try {
                $validation = $this->validate($this->validator, $adjustment, null, 'registrar', 'ajuste', null);
                if ($validation['success'] === false) {
                    return response()->json([
                        'message' => $validation['message']
                    ], Response::HTTP_BAD_REQUEST);
                }
                $this->adjustment::create([
                    'type' => $adjustment['type'],
                    'yard' => $adjustment['yard'],
                    'material' => $adjustment['material'],
                    'amount' => $adjustment['amount'],
                    'observation' => $adjustment['observation'],
                    'date' => $adjustment['date']
                ]);
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Ajuste registrado con éxito',
                            'detail' => null
                        ]
                    ]
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => /*'Advertencia al registrar el ajuste'*/$e->getMessage(),
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function update(array $adjustment, int $id){
            try {
                $validation = $this->validate($this->validator, $adjustment, $id, 'actualizar', 'ajuste', null);
                if ($validation['success'] === false) {
                    return response()->json([
                        'message' => $validation['message']
                    ], Response::HTTP_BAD_REQUEST);
                }
                $sql = $this->adjustment::find($id);
                if(!empty($sql)) {
                    $sql->type = $adjustment['type'];
                    $sql->yard = $adjustment['yard'];
                    $sql->material = $adjustment['material'];
                    $sql->amount = $adjustment['amount'];
                    $sql->observation = $adjustment['observation'];
                    $sql->date = $adjustment['date'];
                    $sql->save();
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Ajuste actualizado con éxito',
                                'detail' => null
                            ]
                        ]
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al actualizar el ajuste',
                                'detail' => 'La ajuste no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al actualizar el ajuste',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function delete(int $id){   
            try {
                $sql = $this->adjustment::find($id);
                if(!empty($sql)) {
                    $sql->delete();
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Ajuste eliminado con éxito',
                                'detail' => null
                            ]
                        ]
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al eliminar el ajuste',
                                'detail' => 'El ajuste no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                if ($e->getCode() !== "23000") {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al eliminar el ajuste',
                                'detail' => 'Si este problema persiste, contacte con un administrador'
                            ]
                        ]
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'No se permite eliminar el registro',
                                'detail' => 'El ajuste se encuentra asociada a otro registro'
                            ]
                        ]
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }

        function get(int $id){
            try {
                $sql = $this->adjustment::select(
                    'type',
                    'yard',
                    'material',
                    DB::Raw("FORMAT(amount, 2) as amount"),
                    'observation',
                    DB::Raw("DATE_FORMAT(date, '%d/%m/%Y') as date")
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
                                'text' => 'La ajuste no existe',
                                'detail' => 'por favor recargue la página'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al buscar el ajuste',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
?>