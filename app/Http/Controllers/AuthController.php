<?php

namespace App\Http\Controllers;

use App\Services\Implementations\AuthServiceImplement;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;
    private $request;   

    public function __construct(Request $request, AuthServiceImplement $service){
        $this->service = $service;
        $this->request = $request;
    }

    function getActiveToken(){
        return $this->service->getActiveToken();
    }
    
    function login(){
        $document_number = $this->request->document_number;
        $password = $this->request->password;
        return $this->service->login($document_number, $password);
    }
}
