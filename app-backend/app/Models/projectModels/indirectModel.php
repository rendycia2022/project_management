<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\projectModels\getProjectModel;

class indirectModel extends Model
{

    public function __construct(){

        // models
        $this->getProjectModel = new getProjectModel;

    }

    public function indirectTotal($po_number){
        $total = 0;
        $po_number = trim($po_number);

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
            $subtotal = 0;
            if(count($items)>0){
                foreach($items as $item){
                    $price = $item->price;
                    $qty = $item->qty;
    
                    $item_total = $price * $qty;
    
                    $subtotal = $subtotal + $item_total;
                }
    
                $total = $total + $subtotal;
            }
        }

        $response = array(
            "total"=>$total
        );

        return $response;
    }

    public function show($where){
        $data = DB::connection('db_project')
        ->table("new_project_indirect")
        ->where($where)
        ->get();

        return $data;
    }
}