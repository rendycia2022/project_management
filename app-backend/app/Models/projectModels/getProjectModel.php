<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class getProjectModel extends Model
{

    public function getByPONumber($where){

        $query = DB::connection('db_project')
        ->table('project_header')
        ->where($where)
        ->get();
        
        return $query;
    }
}