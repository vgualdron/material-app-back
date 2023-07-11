<?php
    namespace App\Services\Interfaces;
    
    interface SynchronizationServiceInterface
    {
        function upload(array $data);
        function download();
    }
?>