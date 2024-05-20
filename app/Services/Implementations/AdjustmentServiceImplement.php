<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\AdjustmentServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\Adjustment;
    use App\Models\Yard;
    use App\Models\Material;
    use App\Validator\AdjustmentValidator;
    use App\Traits\Commons;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
    
    class AdjustmentServiceImplement implements AdjustmentServiceInterface {

        use Commons;

        private $adjustment;
        private $yard;
        private $material;
        private $validator;

        function __construct(AdjustmentValidator $validator){
            $this->adjustment = new Adjustment;
            $this->yard = new Yard;
            $this->material = new Material;
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
                    ->where('a.origin', 'A')
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
                            'text' => 'Advertencia al registrar el ajuste',
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

        function createFromProccess(array $data){
            try {
                if(!isset($data['yard']) || !isset($data['date']) || !isset($data['origin']) || !isset($data['material']) || count($data['material']) < 1) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al registrar el proceso',
                                'detail' => 'No suministró suficiente información'
                            ]
                        ]
                    ], Response::HTTP_BAD_REQUEST);
                }
                $yard = $this->yard::find($data['yard']);
                if (is_null($yard)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al registrar el proceso',
                                'detail' => 'El patio ingresado, no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $materialIds = array_column($data['material'], 'material');
                $materials = $this->material::select('id')
                    ->whereIn('id', $materialIds)
                    ->count();
                if($materials !== count($materialIds)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al registrar el proceso',
                                'detail' => 'Uno o mas patios seleccionados, no existen'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $adjustmentsToSave = [];
                $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                $uuid = md5(Auth::id().'/'.$now->format("m-d-Y H:i:s.u"));
                foreach ($data['material'] as $item) {
                    $adjustmentsToSave[] = [
                        'origin' => $data['origin'],
                        'type' => $item['type'],
                        'yard' => $data['yard'],
                        'material' => $item['material'],
                        'amount' => $item['amount'],
                        'date' => $data['date'],
                        'uuid' => $uuid,
                    ];
                }
                $this->adjustment::upsert($adjustmentsToSave,
                    ['id'],
                    [
                        'origin',
                        'type',
                        'yard',
                        'material',
                        'amount',
                        'date',
                        'uuid'
                    ]
                );
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Proceso registrado con éxito',
                            'detail' => null
                        ]
                    ]
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al registrar el proceso',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function listProccess(string $startDate, string $finalDate, string $origin, string $yard){
            try {
                if(!isset($startDate) || !isset($finalDate) || !isset($yard) || !isset($origin)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al obtener procesos',
                                'detail' => 'No suministró suficiente información'
                            ]
                        ]
                    ], Response::HTTP_BAD_REQUEST);
                }
                $exists = $this->yard::find($yard);
                if (is_null($exists)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al obtener procesos',
                                'detail' => 'El patio ingresado, no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $proccess = $this->adjustment::from('adjustments as a')
                    ->select(
                        'a.uuid as uuid',
                        'a.date as date',
                        'm.id as material',
                        'm.name as materialName',
                        'a.amount as amount',
                        'a.origin as origin',
                        'a.type as type',
                        'a.yard as yard'
                    )
                    ->join('materials as m', 'a.material', 'm.id')
                    ->whereBetween('a.date', [$startDate, $finalDate])
                    ->where('a.origin', $origin)
                    ->where('a.yard', $yard)
                    ->get()
                    ->toArray();
                if(count($proccess) === 0) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al obtener procesos',
                                'detail' => 'No existen procesos con los criterios especificados'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $data = [];
                foreach ($proccess as $item) {
                    $uuid = $item['uuid'];
                    $key = array_search($uuid , array_column($data, 'uuid'));
                    if($key !== false) {
                        $data[$key]['material'][] = [
                            'id' => $item['material'],
                            'name' => $item['materialName'],
                            'type' => $item['type'],
                            'amount' => $item['amount']
                        ];
                    } else {
                        $data[] = [
                            'uuid' => $uuid,
                            'origin' => $item['origin'],
                            'date' => $item['date'],
                            'yard' => $item['yard'],
                            'material' => [
                                [
                                    'id' => $item['material'],
                                    'name' => $item['materialName'],
                                    'type' => $item['type'],
                                    'amount' => $item['amount']
                                ]
                            ]
                        ];
                    }
                }
                return response()->json([
                    'data' => $data
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al obtener procesos',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function deleteProccess(string $uuid){
            try {
                if(!isset($uuid)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al eliminar proceso',
                                'detail' => 'No suministró suficiente información'
                            ]
                        ]
                    ], Response::HTTP_BAD_REQUEST);
                }
                $proccess = $this->adjustment::select('id')
                    ->where('uuid', $uuid)
                    ->get()
                    ->toArray();
                if (count($proccess) === 0) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al eliminar proceso',
                                'detail' => 'No hay proceso con el codigo ingresado'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $proccessIds = array_column($proccess, 'id');
                $this->adjustment::whereIn('id', $proccessIds)->delete();
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Proceso eliminado con éxito',
                            'detail' => null
                        ]
                    ]
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al obtener procesos',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function listEmptyOvens(int $yard){
            try {
                $yardExists = $this->yard::find($yard);
                if (is_null($yardExists)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al obtener los hornos libres',
                                'detail' => 'El patio ingresado, no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                
                $filledOvens = $this->adjustment::select(
                    'oven'
                )
                ->where('origin', 'H')
                ->where('type', 'D')
                ->where('release_status', 0)
                ->get()
                ->pluck('oven')
                ->toArray();
                
                $data = $this->yard::from('yards as y')
                ->select(
                    'b.id as battery',
                    'b.name as batteryName',
                    'o.name as ovenName',
                    'o.id as oven',
                )
                ->join('batteries as b', 'y.id', 'b.yard')
                ->join('ovens as o', 'b.id', 'o.batterie')
                ->where('b.yard', $yard)
                ->whereNotIn('o.id', $filledOvens)
                ->get()
                ->toArray();
                
                if(count($data) === 0) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al obtener los hornos libres',
                                'detail' => 'No hay hornos disponibles para el patio'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                
                $ovens = [];
                foreach ($data as $item) {
                    $battery = $item['battery'];
                    $key = array_search($battery , array_column($ovens, 'battery'));
                    if($key !== false) {
                        $ovens[$key]['oven'][] = [
                            'id' => $item['oven'],
                            'name' => $item['ovenName']
                        ];
                    } else {
                        $ovens[]= [
                            'battery' => $item['battery'],
                            'name' => $item['batteryName'],
                            'oven' => [
                                [
                                    'id' => $item['oven'],
                                    'name' => $item['ovenName']
                                ]
                            ]
                        ];
                    }
                }
                $data = [
                    'yardName' => $yardExists->name, 
                    'yard' => $yardExists->id,
                    'batteries' => $ovens
                ];
                return response()->json([
                    'data' => $data
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al obtener los hornos libres',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function createFromBaking(array $data){
            try {
                if (!isset($data['ovens']) || count($data['ovens']) === 0) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al registrar proceso de horneado',
                                'detail' => 'No se ingresaron datos de hornos'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $material = isset($data['material']) ? $data['material'] : null;
                $yard = isset($data['yard']) ? $data['yard'] : null;
                $materialExists = $this->material::select('id')
                    ->where('id', $material)
                    ->first();
                if(is_null($materialExists)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al registrar proceso de horneado',
                                'detail' => 'El material seleccionado no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $yardExists = $this->yard::select('id')
                    ->where('id', $yard)
                    ->first();
                if(is_null($yardExists)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al registrar proceso de horneado',
                                'detail' => 'El patio seleccionado no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $adjustments = [];
                $date = date('Y-m-d');
                $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                $uuid = md5(Auth::id().'/'.$now->format("m-d-Y H:i:s.u"));
                foreach ($data['ovens'] as $item) {
                    $adjustments[] = [
                        'origin' => 'H',
                        'type' => 'D',
                        'yard' =>  $yard,
                        'material' =>  $material,
                        'oven' =>  $item['id'],
                        'amount' =>  $item['amount'],
                        'date' =>  $date,
                        'release_time' => $item['time'],
                        'release_status' => 0,
                        'uuid' => $uuid
                    ];
                }
                DB::transaction(function () use ($adjustments) {
                    $this->adjustment::upsert($adjustments, ['id']);
                });
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Horneados registrados con éxito',
                            'detail' => null
                        ]
                    ]
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al registrar proceso de horneado',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function listFilledOvens(int $yard){
            try {
                $yardExists = $this->yard::select('id', 'name')
                    ->where('id', $yard)
                    ->first();
                if(is_null($yardExists)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al listar los horneados en proceso',
                                'detail' => 'El patio seleccionado no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $sql = $this->adjustment::from('adjustments as a')
                ->select(
                    'a.id as baking',
                    'a.uuid as uuid',
                    'y.name as yardName',
                    'y.id as yard',
                    'b.name as batteryName',
                    'b.id as battery',
                    'o.name as ovenName',
                    'o.id as oven',
                    'm.name as materialName',
                    'm.id as material',
                    'a.amount as amount',
                    DB::Raw('IF((((a.release_time*60) - TIMESTAMPDIFF(MINUTE, a.created_at, NOW())) / 60) < 0, "P", "T") as status'),
                    DB::Raw('ROUND(ABS((((a.release_time*60) - TIMESTAMPDIFF(MINUTE, a.created_at, NOW())) / 60)), 2) as timeDifference')
                )
                ->join('materials as m', 'a.material', 'm.id')
                ->join('ovens as o', 'a.oven', 'o.id')
                ->join('batteries as b', 'o.batterie', 'b.id')
                ->join('yards as y', 'b.yard', 'y.id')
                ->where('origin', 'H')
                ->where('type', 'D')
                ->where('release_status', 0)
                ->get()
                ->toArray();
                if(count($sql) === 0) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al listar los horneados en proceso',
                                'detail' => 'No hay horneados para el patio ingresado'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $ovens = [
                    
                ];
                foreach($sql as $item) {
                    $uuidKey = array_search($item['uuid'] , array_column($ovens, 'uuid'));
                    if($uuidKey !== false) {
                        $batteryKey = array_search($item['battery'] , array_column($ovens[$uuidKey]['batteries'], 'id'));
                        if($batteryKey !== false) {
                            $ovens[$uuidKey]['batteries'][$batteryKey]['ovens'][] = [
                                'baking' => $item['baking'],
                                'oven' => $item['oven'],
                                'ovenName' => $item['ovenName'],
                                'amount' => $item['amount'],
                                'status' => $item['status'],
                                'timeDifference' => $item['timeDifference'],
                            ];
                        } else {
                            $ovens[$uuidKey]['batteries'][] = [
                                'name' => $item['batteryName'],
                                'id' => $item['battery'],
                                'ovens' => [
                                    [
                                        'baking' => $item['baking'],
                                        'oven' => $item['oven'],
                                        'ovenName' => $item['ovenName'],
                                        'amount' => $item['amount'],
                                        'status' => $item['status'],
                                        'timeDifference' => $item['timeDifference']
                                    ]
                                ]
                            ];
                        }
                    } else {
                        $ovens[] = [
                            'uuid' => $item['uuid'],
                            'material' => $item['materialName'],
                            'batteries' => [
                                [
                                    'name' => $item['batteryName'],
                                    'id' => $item['battery'],
                                    'ovens' => [
                                        [
                                            'baking' => $item['baking'],
                                            'oven' => $item['oven'],
                                            'ovenName' => $item['ovenName'],
                                            'amount' => $item['amount'],
                                            'status' => $item['status'],
                                            'timeDifference' => $item['timeDifference'],
                                        ]
                                    ]
                                ]
                            ]
                        ];
                    }
                }
                $data = [
                    'yard' => $yardExists->id,
                    'yardName' => $yardExists->name,
                    'bakings' => $ovens
                ];
                return response()->json([
                    'data' => $data
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                //dd($e);
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al listar los horneados en proceso',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function createFromBakingRelease(array $data){
            try {
                $count = isset($data['bakings']) ? count($data['bakings']) : 0;
                if ($count === 0) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al descargar horneados',
                                'detail' => 'No se ingresaron datos de horneados'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $material = isset($data['material']) ? $data['material'] : null;
                $materialExists = $this->material::select('id')
                    ->where('id', $material)
                    ->first();
                if(is_null($materialExists)) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al descargar horneados',
                                'detail' => 'El material seleccionado no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $bakingIds = array_column($data['bakings'], 'id');
                $bakings = $this->adjustment::select(
                    'id',
                    'uuid',
                    'yard',
                    'oven'
                )
                ->whereIn('id', $bakingIds)
                ->where('type', 'D')
                ->where('origin', 'H')
                ->where('release_status', 0)
                ->get()
                ->toArray();
                if(count($bakings) < $count) {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al descargar horneados',
                                'detail' => 'Uno o mas horneados ingresados no existen o no son válidos para descarga'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
                $releases = [];
                $date = date('Y-m-d');
                foreach ($bakings as $item) {
                    $key = array_search($item['id'] , array_column($data['bakings'], 'id'));
                    $releases[] = [
                        'origin' => 'H',
                        'type' => 'A',
                        'yard' => $item['yard'],
                        'material' => $material,
                        'oven' => $item['oven'],
                        'amount' => $data['bakings'][$key]['amount'],
                        'date' => $date,
                        'uuid' => $item['uuid']
                    ];
                }
                
                DB::transaction(function () use ($releases, $bakingIds) {
                    $this->adjustment::upsert($releases, ['id']);
                    $this->adjustment::whereIn('id', $bakingIds)
                    ->update(['release_status' => 1]);
                });
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Descargas de horneado realizadas con éxito',
                            'detail' => null
                        ]
                    ]
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al registrar proceso de horneado',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
?>