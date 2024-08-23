<?php

namespace App\Http\Controllers\project;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

use App\Models\projectModels\dbModel;
use App\Models\projectModels\updateModel;
use App\Models\projectModels\documentModel;
use App\Models\projectModels\afModel;


class AFController extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->updateModel = new updateModel;
        $this->dbModel = new dbModel;
        $this->documentModel = new documentModel;
        $this->afModel = new afModel;

    }

    public function showByPO(Request $request){

        $no_document = $request->no_document;

        $where_af = array(
            array('approval_items.purchase_order', $no_document),
            array('approval_items.active', 1),
            array('approval_header.active', 1),
        );

        $af_items = $this->afModel->approval_items($where_af);
        
        $response = array(
            "status"=>200,
            "message"=>"Ok.",
            "list"=>$af_items,
            "backend_af"=>env('HOST_NAME').env('BACKEND_AF_PORT'),
        );

        return response()->json($response);
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