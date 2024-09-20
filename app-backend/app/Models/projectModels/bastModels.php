<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class bastModels extends Model
{
    public function __construct(){
        

    }

    public function bastTotal($po_number){
        $total = 0;
        
        $whereBAST = array(
            array('active', 1),
            array('po_number', $po_number),
        );
        $bast = $this->show($whereBAST);

        if(count($bast)>0){
            foreach($bast as $ba){
                $price = $ba->price;
                $qty = $ba->qty;
                $term = $ba->term;

                $subtotal = ($price * ($term/100)) * $qty;

                $total = $total + $subtotal;
            }
        }

        $response = array(
            "total"=>$total
        );

        return $response;
    }

    public function show($where){
        $data = DB::connection('db_project_management')
        ->table("bast")
        ->where($where)
        ->get();

        return $data;
    }

    public function payment($where){

        $query = DB::connection('db_project_management')
        ->table("bast_payment")
        ->where($where)
        ->orderBy('id', 'ASC')
        ->get();

        return $query;
    }
}