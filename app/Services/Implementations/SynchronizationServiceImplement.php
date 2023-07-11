<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\SynchronizationServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\{
        Ticket,
        Yard,
        Material,
        Third,
    };
    use App\Validator\TicketValidator;
    use App\Traits\Commons;
    use Illuminate\Support\Facades\{DB, Auth};
    
    class SynchronizationServiceImplement implements SynchronizationServiceInterface {

        use Commons;

        private $ticket;
        private $yard;
        private $material;
        private $third;
        private $validator;

        function __construct(TicketValidator $validator){
            $this->ticket = new Ticket;
            $this->yard = new Yard;
            $this->material = new Material;
            $this->third = new Third;
            $this->validator = $validator;
        }    

        function upload(array $data){
            try {
                $data = [];
                return response()->json([
                    'data' => $data
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al subir los tiquetes locales',
                            'detail' => 'Intente recargando la página'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }


        function download(){
            try {
                $data = [];
                
                $data['yards'] = $this->yard
                    ->select('id', 'code', 'name', 'active')
                    ->get();
                
                $data['materials'] = $this->material
                    ->select('id', 'code', 'name', 'unit', 'active')
                    ->get();

                $data['thirds'] = $this->third
                    ->select('id', 'nit', 'name', 'customer', 'associated', 'contractor', 'active')
                    ->get();

                $data['tickets'] = $this->ticket
                    ->select(
                        'id',
                        'type',
                        'user',
                        'origin_yard as originYard',
                        'destiny_yard as destinyYard',
                        'supplier',
                        'customer',
                        'material',
                        'ash_percentage as ashPercentage',
                        'receipt_number as receiptNumber',
                        'referral_number as referralNumber',
                        'date',
                        'time',
                        'license_plate as licensePlate',
                        'trailer_number as trailerNumber',
                        'driver_document as driverDocument',
                        'driver_name as driverName',
                        'gross_weight as grossWeight',
                        'tare_weight as tareWeight',
                        'net_weight as netWeight',
                        'conveyor_company as conveyorCompany',
                        'observation',
                        'seals',
                        'round_trip as roundTrip',
                        'local_created_at as localCreatedAt',
                        'consecutive'
                    )
                    ->where('user', Auth::id())
                    ->where('date', '>=', DB::Raw("DATE_ADD(CURRENT_DATE(), INTERVAL -5 day)"))
                    ->where('date', '<=',  DB::Raw("DATE_ADD(CURRENT_DATE(), INTERVAL 5 day)"))
                    ->get();
                
                return response()->json([
                    'data' => $data
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al descargar datos del servidor',
                            'detail' => 'Si este problema persiste, contacte con un administrador'
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
?>