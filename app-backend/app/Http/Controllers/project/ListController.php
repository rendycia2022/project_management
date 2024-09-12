<?php

namespace App\Http\Controllers\project;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

// models
use App\Models\projectModels\ListModel;


class ListController extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->ListModel = new ListModel;
        

    }

    public function show(Request $request){

        $where = array(
            array('active', 1),
        );
        $list = $this->ListModel->getData($where);

        $response = array(
            "status"=>200,
            "message"=>"Ok.",
            "list"=>$list,
        );

        return response()->json($response);
    }

    public function updateRemarks(Request $request){

        $payload = $request->json()->all();
        $code = $payload['po_number'];
        $payload_remarks = $payload['remarks'];

        $remarks = DB::connection('db_project_management')
        ->table('batch_remarks')
        ->where('code', $code)
        ->get();

        if(count($remarks)>0){
            // update
            $update = array(
                "remarks"=>$payload_remarks,
            );

            DB::connection('db_project_management')
            ->table('batch_remarks')
            ->where('code', $code)
            ->update(
                $update
            );
        }else{
            // create
            $create = array(
                "code"=>$code,
                "remarks"=>$payload_remarks,
            );
    
            DB::connection('db_project_management')
            ->table('batch_remarks')
            ->insert(
                $create
            );

        }

        $response = array(
            "status"=>200,
            "message"=>$payload,
        );

        return response()->json($response);
    }

}

?>