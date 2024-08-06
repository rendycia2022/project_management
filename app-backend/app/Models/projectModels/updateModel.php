<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class updateModel extends Model
{
    public function report_foto($db, $minus_interval, $select){

        $today = gmdate('Y-m-d H:i:s', time()+(60*60*7));
        
        $interval = env('PARAMS_DATE');
        if($minus_interval != ''){
            $create_date = date_create($today);
            date_sub($create_date,date_interval_create_from_date_string($minus_interval));
            $interval = date_format($create_date,"Y-m-d");
        }

        $query = DB::connection($db)
        ->table("report_foto_item")
        ->select($select)
        ->join("workorders","report_foto_item.workorder_id","=","workorders.id")
        ->join("form_groups","report_foto_item.group_id","=","form_groups.id")
        ->join("scope_activities","report_foto_item.sow_id","=","scope_activities.id")
        ->whereDate('report_foto_item.created_at', '>=', $interval)
        ->whereDate('report_foto_item.created_at', '>=', env('PARAMS_DATE'))
        ->get();
        
        return $query;
    }

    public function report_foto_GetByParams($db, $where){

        $query = DB::connection($db)
        ->table("report_foto_item")
        ->join("form_groups","report_foto_item.group_id","=","form_groups.id")
        ->where($where)
        ->orderBy('report_foto_item.id', 'DESC')
        ->get();
        
        return $query;
    }
}