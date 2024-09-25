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
use App\Models\projectModels\styleModels;


class ChartController extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->ListModel = new ListModel;
        $this->styleModels = new styleModels;
        

    }

    public function show(Request $request){

        $year = $request['year'];
        $statusRequest = $request['status'];

        $where = array(
            array('active', 1),
        );
        $list = $this->ListModel->getData($where);

        // filtering data
        $raw = array();
        $optionYears = array();

        $count_list = count($list);
        if($count_list > 0){
            for($i=0; $i<$count_list; $i++){
                $date_create = date_create($list[$i]['date']);
                $date_formated = date_format($date_create,"Y");

                $optionYears[$date_formated] = $date_formated;

                if($year == "All"){
                    $raw[] = $list[$i];
                }else{
                    if($year == $date_formated){
                        $raw[] = $list[$i];
                    }
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

        // data chart builder
        // define data
        $charts = array();
        $count_raw = count($raw);
        if($count_raw > 0){
            for($j=0; $j<$count_raw; $j++){
                $project_code = $raw[$j]['project']['code'];

                $charts[$project_code]['title'] = $project_code;

                // build data
                $revenue = $raw[$j]['revenue']['total'];
                $invoice = $raw[$j]['invoice']['total'];
                $indirect = $raw[$j]['indirect']['total'];
                $direct = $raw[$j]['direct']['total'];
                $bast = $raw[$j]['bast']['total'];
                if(isset($charts[$project_code]['raw'])){
                    // expected revenue
                    $charts[$project_code]['raw']['revenue'] = $charts[$project_code]['raw']['revenue'] + $revenue;

                    // invoice
                    $charts[$project_code]['raw']['invoice'] = $charts[$project_code]['raw']['invoice'] + $invoice;

                    // indirect
                    $charts[$project_code]['raw']['indirect'] = $charts[$project_code]['raw']['indirect'] + $indirect;

                    // direct
                    $charts[$project_code]['raw']['direct'] = $charts[$project_code]['raw']['direct'] + $direct;

                    // bast
                    $charts[$project_code]['raw']['bast'] = $charts[$project_code]['raw']['bast'] + $bast;
                }else{
                    // expected revenue
                    $charts[$project_code]['raw']['revenue'] = $revenue;

                    // invoice
                    $charts[$project_code]['raw']['invoice'] = $invoice;

                    // indirect
                    $charts[$project_code]['raw']['indirect'] = $indirect;

                    // direct
                    $charts[$project_code]['raw']['direct'] = $direct;

                    // bast
                    $charts[$project_code]['raw']['bast'] = $bast;
                }
            }

            // build chart
            $charts = array_values($charts);

            for($c=0; $c<count($charts); $c++){
                $dataset =  $charts[$c]['raw'];

                $chart_title = $charts[$c]['title'];

                $invoice = $dataset['invoice'];
                $balance = $dataset['revenue'] - $invoice;
                $balance_label = "Expected Revenue's left";
                if($chart_title == "SLB_IOH"){
                    $invoice = $dataset['bast'];
                    $balance = $dataset['revenue'] - $invoice;
                }
                if($balance < 0){
                    $balance = $balance*-1;
                    $invoice = $invoice - $balance;

                    $balance_label = "Extra Revenue";
                }

                $charts[$c]['dataset'] = [$invoice, $balance];

                // legend
                $charts[$c]['labels'] = ["Invoice", $balance_label];
                
                $progress = round(($balance / $dataset['revenue']) * 100);
                if($invoice >= $dataset['revenue']){
                    $progress = 100;
                }

                $charts[$c]['progress'] = $progress;

                // generating color
                $colors = $this->styleModels->project_color($charts[$c]['title']);

                $charts[$c]['color'] = $colors;
                $charts[$c]['datasetColor'] = ['#cccccc', $colors];
            }

            $key_values = array_column($charts, 'title');
            array_multisort($key_values, SORT_DESC, $charts);
        }
        
        // reindexing
        if(count($optionYears)>0){
            $optionYears = array_values($optionYears);
            rsort($optionYears);
        }
        
        // send to front
        $response = array(
            "status"=>200,
            "message"=>"Ok.",
            "raw"=>$raw,
            "year"=>$year,
            "optionYears"=>$optionYears,
            "charts"=>$charts,
        );

        return response()->json($response);
    }

}

?>