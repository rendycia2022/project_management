<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class afModel extends Model
{

    public function approval_items($where){

        $query = DB::connection('db_af')
        ->table('approval_items')
        ->join('approval_header','approval_header.id',"=","approval_items.document_id")
        ->where($where)
        ->whereYear('approval_header.date', '>=' , 2024)
        ->orderBy('approval_items.date', 'ASC')
        ->get();
        
        return $query;
    }

    public function indirectTotal($po_number){

        $af_total = 0;
        $where_af = array(
            array('approval_items.purchase_order', $po_number),
            array('approval_items.active', 1),
            array('approval_header.active', 1),
        );

        $af_items = $this->approval_items($where_af);
        if(count($af_items)>0){
            foreach($af_items as $afi){
                $af_item_price = $afi->price;

                $af_total = $af_total + $af_item_price;
            }
        }

        $response = array(
            "total"=>$af_total,
        );
        
        return $response;
    }
}