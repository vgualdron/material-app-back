<?php
    namespace App\Services\Interfaces;
    
    interface UserServiceInterface
    {
        function login(string $documentNumber, string $password);
        /*function get(int $id);
        function insert(array $zone);
        function update(array $zone, int $id);
        function delete(int $id);        */
    }
?>