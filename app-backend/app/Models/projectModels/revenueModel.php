<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class revenueModel extends Model
{

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