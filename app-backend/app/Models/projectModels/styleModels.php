<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// models
use App\Models\projectModels\ListModel;

class styleModels extends Model
{
    public function __construct(){

        // models
        $this->ListModel = new ListModel;
        

    }

    public function colors(){

        $data = array(
            '#febe98',
            '#b38a6a',
            '#f97272',
            '#b36f6a',
            '#b38a6a',
            '#ffbe98',
            '#ff9f98',
            '#ffd998',
        );
        
        return $data;
    }

    public function project_color($code){

        $data['SLB_XL'] = '#febe98';
        $data['SLB_IOH'] = '#b38a6a';
        $data['IOH'] = '#f97272';
        
        return $data[$code];
    }
}