<?php

namespace App\Http\Controllers\project;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

// models
use App\Models\projectModels\batchProjectModel;
use App\Models\projectModels\styleModels;


class ProjectChart_Controller extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->batchProjectModel = new batchProjectModel;
        $this->styleModels = new styleModels;

    }

    public function revenue(Request $request){

        $data = array();

        $where = array(
            array('active', 1),
        );
        $data = $this->batchProjectModel->get($where);

        $data = array_values($data);

        $charts = array();
        
        for($i=0; $i<count($data); $i++){
            $project = $data[$i]['representative']['cust_init'];

            $charts[$project]['title'] = $project;

            // title
            $charts[$project]['labels'] = ['Invoice', 'Sisa Nilai PO'];

            

            // dataset
            if(isset($charts[$project]['dataRaw'])){

                // revenue or Invoice
                $revenue = $data[$i]['revenue'];
                $charts[$project]['dataRaw']['revenue'] = $charts[$project]['dataRaw']['revenue'] + $revenue;

                // po_value or nilai
                $po_value = $data[$i]['po_value'];
                $charts[$project]['dataRaw']['po_value'] = $charts[$project]['dataRaw']['po_value'] + $po_value;

                // total af
                $af_total = $data[$i]['af_total'];
                $charts[$project]['dataRaw']['af_total'] = $charts[$project]['dataRaw']['af_total'] + $af_total;

            }else{

                // revenue or Invoice
                $revenue = $data[$i]['revenue'];
                $charts[$project]['dataRaw']['revenue'] = $revenue;

                // po_value or nilai
                $po_value = $data[$i]['po_value'];
                $charts[$project]['dataRaw']['po_value'] = $po_value;

                // total af
                $af_total = $data[$i]['af_total'];
                $charts[$project]['dataRaw']['af_total'] = $af_total;
            }
        }

        $charts = array_values($charts);

        for($c=0; $c<count($charts); $c++){
            $dataset =  $charts[$c]['dataRaw'];
            $margin = $dataset['po_value'] - $dataset['revenue'];
            $charts[$c]['dataset'] = [$dataset['revenue'], $margin];
            

            $margin = round(($dataset['revenue'] / $dataset['po_value']) * 100);
            if($dataset['revenue'] >= $dataset['po_value']){
                $margin = 100;
            }

            $charts[$c]['margin'] = $margin;

            // generating color
            $colors = $this->styleModels->colors();

            $charts[$c]['color'] = $colors[$c];
            $charts[$c]['datasetColor'] = [$colors[$c], '#cccccc'];
        }

        $response = $charts;

        return response()->json($response);
    }

}

?>