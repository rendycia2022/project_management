<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\projectModels\getProjectModel;

class revenueModel extends Model
{

    public function __construct(){

        // models
        $this->getProjectModel = new getProjectModel;

    }

    public function revenueTotal($po_number){

        $po_number = trim($po_number);

        $total = 0;
        $totalQty = 0;

        $where_project = array(
            array('po_number', $po_number),
            array('active', 1),
        );
        $project = $this->getProjectModel->show($where_project);
        if(count($project)>0){
            foreach($project as $p){
                $code = $p->code;
            }

            $where = array(
                array('project_code', $code),
                array('active', 1),
            );
    
            $items = $this->show($where);
    
            
            if(count($items)>0){
                foreach($items as $item){
                    $price = $item->price;
                    $qty = $item->qty;
    
                    $subtotal = $price * $qty;
    
                    $total = $total + $subtotal; 
                    $totalQty = $totalQty + $qty;
                }
            }

        }

        $response = array(
            "total"=>$total,
            "totalQty"=>$totalQty,
        );

        return $response;
    }

    public function show($where){
        $data = DB::connection('db_project')
        ->table("new_project_revenue")
        ->where($where)
        ->orderBy('created_at', 'ASC')
        ->get();

        return $data;
    }

    public function list(){

        $where = array(
            array('active', 1),
            array('created_by', '!=', '8ddcfaf8-865e-46b9-9421-fc6d8b933be2'),
            array('no_document', '!=', null),
            array('item', '!=', null),
            array('qty', '!=', null),
            array('price', '!=', null),
        );

        $query = DB::connection('db_project')
        ->table('project_revenue')
        ->where($where)
        ->orderBy('date', 'DESC')
        ->get();
        
        return $query;
    }


}