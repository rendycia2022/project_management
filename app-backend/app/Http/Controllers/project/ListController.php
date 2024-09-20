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

        $year = $request['year'];
        $statusRequest = $request['status'];

        $where = array(
            array('active', 1),
        );
        $list = $this->ListModel->getData($where);

        $raw = array();
        $count_list = count($list);
        if($count_list > 0){
            for($i=0; $i<$count_list; $i++){
                $date_create = date_create($list[$i]['date']);
                $date_formated = date_format($date_create,"Y");

                if($year == "All"){
                    $raw[] = $list[$i];
                }else{
                    if($year == $date_formated){
                        $raw[] = $list[$i];
                    }
                }
            }

            // filtering status
            if($statusRequest != "All"){
                $count_raw = count($raw);
                $filteredStatus = array();
                for($j=0; $j<$count_raw; $j++){
                    $rawStatus = $raw[$j]['status'];
                    if($rawStatus == $statusRequest){
                        $filteredStatus[] = $raw[$j];
                    }
                }

                $raw = $filteredStatus;
            }
        }

        $response = array(
            "status"=>200,
            "message"=>"Ok.",
            "list"=>$raw,
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