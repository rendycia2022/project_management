<?php

namespace App\Http\Controllers\project;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

use App\Models\projectModels\sitesModel;


class SitesController extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->projectSitesModel = new sitesModel;

    }

    public function getMenus(Request $request){

        $sites = $this->projectSitesModel->getMenus();
        
        $status = "200";
        $message = "Ok.";
        $response = $sites;

        $response = array(
            "status"=>$status,
            "message"=>$message,
            "response"=>$response,
        );

        return response()->json($response);
    }

}

?>