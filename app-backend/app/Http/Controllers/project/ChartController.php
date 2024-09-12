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


class ChartController extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->ListModel = new ListModel;
        

    }

    public function show(Request $request){

        $year = $request['year'];

        $where = array(
            array('active', 1),
        );
        $list = $this->ListModel->getData($where);

        $filtered_data = array();

        $optionYears = array();

        $count_list = count($list);
        if($count_list>0){
            for($i=0; $i<$count_list; $i++){
                $date_create = date_create($list[$i]['date']);
                $date_formated = date_format($date_create,"Y");

                $optionYears[$date_formated] = $date_formated;

                if($year == "false"){
                    $filtered_data[] = $list[$i];
                }else{
                    if($year == $date_formated){
                        $filtered_data[] = $list[$i];
                    }
                }
            }
        }
        
        // reindexing
        $optionYears = array_values($optionYears);

        $response = array(
            "status"=>200,
            "message"=>"Ok.",
            "filtered_data"=>$filtered_data,
            "year"=>$year,
            "optionYears"=>$optionYears,
        );

        return response()->json($response);
    }

}

?>