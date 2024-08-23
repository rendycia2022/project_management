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
}