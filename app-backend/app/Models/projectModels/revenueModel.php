<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class revenueModel extends Model
{
    public function list(){
        $data[0] = array(
            'label'=>'XL',
            'port'=>'58101',
            'connection'=>'db_xl',
            'icon'=>'pi pi-fw pi-bookmark',
        );
        $data[1] = array(
            'label'=>'Indosat',
            'port'=>'58102',
            'connection'=>'db_isat',
            'icon'=>'pi pi-fw pi-bookmark',
        );
        $data[2] = array(
            'label'=>'Approval Form',
            'port'=>'8022',
            'connection'=>'db_af',
            'icon'=>'pi pi-fw pi-bookmark',
        );

        return $data;
    }
}