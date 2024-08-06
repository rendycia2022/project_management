<?php

namespace App\Http\Controllers\v2\project;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

use App\Models\projectModels\dbModel;
use App\Models\projectModels\updateModel;
use App\Models\projectModels\documentModel;



class AFController extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->updateModel = new updateModel;
        $this->dbModel = new dbModel;
        $this->documentModel = new documentModel;

    }

    public function show(Request $request){

        $token = $request->token;

        $data = array();

        $status = "404";
        $message = "Not found.";

        $document_af = $this->documentModel->af_document('db_af', '');
        if(count($document_af)>0){
            foreach($document_af as $listAF){
                $af_id = $listAF->id;

                $item_af = $this->documentModel->af_item('db_af', $af_id);

                if(count($item_af)>0){
                    foreach($item_af as $item_list){
                        $item_po = $item_list->purchase_order;

                        $item_price = $item_list->price;
                        $item_qty = $item_list->qty;
                        $item_subtotal = $item_price * $item_qty;

                        $data['list'][$item_po]['po'] = $item_po;
                        $data['list'][$item_po]['subtotal'][] = $item_subtotal;
                    }
                }
            }

            $data['list'] = array_values($data['list']);
            $count_dataList = count($data['list']);
            for($h=0; $h<$count_dataList; $h++){
                $totalList = 0;
                
                $dataListItem = $data['list'][$h]['subtotal'];
                $count_dataListItem = count($dataListItem);
                if($count_dataListItem > 0){
                    for($i=0; $i<$count_dataListItem; $i++){
                        $totalList = $totalList + $dataListItem[$i];
                    }
                }

                $data['list'][$h]['total'] = $totalList;
                $data['list'][$h]['total_label'] = 'Rp.'.number_format($totalList,2,",",".");

            }

            $status = "200";
            $message = "Ok.";
        }

        $response = $data;

        $response = array(
            "status"=>$status,
            "message"=>$message,
            "response"=>$response,
        );

        return response()->json($response);
    }

}

?>