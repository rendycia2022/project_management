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


class ProjectList_Controller extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->revenueModel = new revenueModel;

    }

    public function show(Request $request){

        $data = array();

        $revenue = $this->revenueModel->list();
        if(count($revenue)>0){
            foreach($revenue as $r){
                $no_document = $r->no_document;
                $revenue_qty = $r->qty;
                $revenue_price = $r->price;

                $revenue_total = $revenue_price * $revenue_qty;

                $data[] = array(
                    "no_document"=>$no_document,
                    "revenue_date"=>$r->date,
                    "revenue_total"=>$revenue_total,
                );
            }
        }

        
        $status = "200";
        $message = "Ok.";
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