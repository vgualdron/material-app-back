<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\UserServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\User;    
    
    class UserServiceImplement implements UserServiceInterface {
        
        private $user;

        function __construct(){
            $this->user = new User;
        }    

        function login(string $documentNumber, string $password){
            
        }

        /*function get(int $id){
            return $this->model->where('id', $id)->first();
        }

        function insert(array $zone){ 
            $model = $this->model->create($zone);
            return $model;          
        }

        function update(array $zone, int $id){  
            $this->model->where('id', $id)->first()
            ->fill($zone)->save();
            $model = $this->model->where('id', $id)->first();            
            return $model;
        }

        function delete(int $id){         
            $zone = $this->model->find($id);                    
            $zone->delete();
        }*/
    }
?>