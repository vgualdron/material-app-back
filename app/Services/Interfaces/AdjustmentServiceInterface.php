<?php
    namespace App\Services\Interfaces;
    
    interface AdjustmentServiceInterface
    {
        function list();
        function create(array $adjustment);
        function update(array $adjustment, int $id);
        function delete(int $id); 
        function get(int $id);
        function createFromProccess(array $data);
        function listProccess(string $startDate, string $finalDate, string $origin, string $yard);
        function deleteProccess(string $uuid);
        function listEmptyOvens(int $yard);
        function createFromBaking(array $data);
        function listFilledOvens(int $yard);
        function createFromBakingRelease(array $data);
    }
?>