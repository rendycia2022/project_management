<?php

namespace App\Http\Controllers\project;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

// models
use App\Models\projectModels\revenueModel;
use App\Models\projectModels\afModel;
use App\Models\projectModels\sitesModel;


class ProjectList_Controller extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->revenueModel = new revenueModel;
        $this->afModel = new afModel;
        $this->sitesModel = new sitesModel;

    }

    public function show(Request $request){

        $data = array();

        $revenue = $this->revenueModel->list();

        $link = env('HOST_NAME').env('FRONTEND_PORT').env('APP_PROJECT');

        if(count($revenue)>0){
            foreach($revenue as $r){
                $project_id = $r->project_id;
                $no_document = $r->no_document;
                $revenue_qty = $r->qty;
                $revenue_price = $r->price;

                $af_total = 0;
                $where_af = array(
                    array('purchase_order', $no_document),
                    array('active', 1),
                );

                $af_items = $this->afModel->approval_items($where_af);
                if(count($af_items)>0){
                    foreach($af_items as $afi){
                        $af_item_price = $afi->price;

                        $af_total = $af_total + $af_item_price;
                    }
                }

                $workorders = $this->sitesModel->workorders($no_document);
                $count_workorders = count($workorders);

                $data[] = array(
                    "no_document"=>$no_document,
                    "revenue_link"=>$link.'/summary/'.$project_id.'/detail',
                    "revenue_price"=>$revenue_price,
                    "revenue_qty"=>$revenue_qty,
                    "af_total"=>$af_total,
                    "count_workorders"=>$count_workorders,
                );
            }
        }

        
        $status = "200";
        $message = "Ok.";
        $list = $data;

        $response = array(
            "status"=>$status,
            "message"=>$message,
            "list"=>$list,
        );

        return response()->json($response);
    }

}

?>