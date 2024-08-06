<?php

namespace App\Models\pmModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class authModel extends Model
{
    public function get($condition){

        $data = array();

        $query = DB::connection('db_project_management')->table('auth')
        ->select(
            'auth.user_id',
            'auth.token',
            'auth.client',
            'auth.agent',
        )
        ->where($condition)
        ->get();

        if(count($query)>0){
            foreach($query as $list){
                $data[] = array(
                    "user_id"=>$list->user_id,
                    "token"=>$list->token,
                    "client"=>$list->client,
                    "agent"=>$list->agent,
                );
            }
        }
        
        return $data;
        
    }



}