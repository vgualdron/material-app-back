<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Implementations\SynchronizationServiceImplement;

class SynchronizationController extends Controller
{
    private $service;
    private $request;

    public function __construct(Request $request, SynchronizationServiceImplement $service) { 
            $this->request = $request;
            $this->service = $service;
    }

    function upload(){
        return $this->service->upload($this->request->all());
    }

    function download(){
        return $this->service->download();
    }
}
