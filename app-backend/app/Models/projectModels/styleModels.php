<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class styleModels extends Model
{

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
}