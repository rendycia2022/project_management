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

    public function show($where){

        $query = DB::connection('db_project')
        ->table('new_project')
        ->where($where)
        ->get();
        
        return $query;
    }

    function renameForPoFile($string){

        $string = str_replace("PO/", "", $string);

        $string = str_replace("/", "_", $string);

        return $string;
    }
}