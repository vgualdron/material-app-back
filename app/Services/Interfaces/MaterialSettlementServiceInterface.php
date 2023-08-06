<?php
    namespace App\Services\Interfaces;
    
    interface MaterialSettlementServiceInterface
    {
        function list();
        function getTickets(string $type, string $startDate, string $finalDate, int $third, int $material, string $materialType);
        function settle(array $data);
        function print(int $id);
        function get(int $id);
        function addInformation(array $data, int $id);
    }
?>