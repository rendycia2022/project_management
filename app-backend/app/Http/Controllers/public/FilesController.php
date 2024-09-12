<?php

namespace App\Http\Controllers\public;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

use App\Models\projectModels\getProjectModel;


class FilesController extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        $this->getProjectModel = new getProjectModel;
    }

    public function get_po(Request $request){

        $code = $request['document'];

        // document_po
        $document_po = $this->getProjectModel->renameForPoFile($code);
        $file = null;

        $path = base_path('public/document_po/'.$document_po.'.pdf');
        if (file_exists($path)) {
            $file = env('HOST_NAME').env('HOST_PORT')."/document_po/".$document_po.".pdf";
        }

        $response = array(
            "link"=>$file,
        );

        return response()->json($response);
    }

}

?>