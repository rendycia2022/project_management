<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class afModel extends Model
{

    public function approval_items($where){

        $query = DB::connection('db_af')
        ->table('approval_items')
        ->where($where)
        ->orderBy('date', 'ASC')
        ->get();
        
        return $query;
    }
}