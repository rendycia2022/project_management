<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class documentModel extends Model
{
    public function af_document($db, $minus_interval){

        $today = gmdate('Y-m-d H:i:s', time()+(60*60*7));
        
        $interval = env('PARAMS_DATE');
        if($minus_interval != ''){
            $create_date = date_create($today);
            date_sub($create_date,date_interval_create_from_date_string($minus_interval));
            $interval = date_format($create_date,"Y-m-d");
        }
        

        $query = DB::connection($db)
        ->table("approval_header")
        ->select(
            "approval_header.id",
            "approval_header.document_number",
            "approval_header.created_at",
            "approval_header.submission_date",

            "divisions.name as division_name",

            "approval_bank.account"
        )
        ->join("divisions","divisions.code","=","approval_header.division")
        ->join("approval_bank","approval_bank.document_id","=","approval_header.id")
        ->where('approval_header.active', 1)
        ->whereDate('approval_header.created_at', '>=', $interval)
        ->whereDate('approval_header.created_at', '>=', env('PARAMS_DATE'))
        ->get();
        
        return $query;
    }

    public function af_item($db, $code){

        $query = DB::connection($db)
        ->table("approval_items")
        ->select(
            "approval_items.id",
            "approval_items.price",
            "approval_items.qty",
            "approval_items.purchase_order",
        )
        ->where('approval_items.document_id', $code)
        ->where('approval_items.active', 1)
        ->get();
        
        return $query;
    }


}