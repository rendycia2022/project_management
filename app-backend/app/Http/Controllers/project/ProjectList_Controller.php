<?php

namespace App\Http\Controllers\project;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;


class ProjectList_Controller extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models

    }

    public function show(Request $request){
        
        $status = "200";
        $message = "Ok.";
        $response = "router ready.";

        $response = array(
            "status"=>$status,
            "message"=>$message,
            "response"=>$response,
        );

        return response()->json($response);
    }

}

?>