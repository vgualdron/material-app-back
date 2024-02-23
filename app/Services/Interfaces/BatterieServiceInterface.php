<?php
    namespace App\Services\Interfaces;
    
    interface BatterieServiceInterface
    {
        function list(int $yard);
        function create(array $batterie);
        function update(array $batterie, int $id);
        function delete(int $id); 
        function get(int $id);
    }
?>