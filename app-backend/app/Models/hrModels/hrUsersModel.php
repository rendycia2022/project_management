<?php

namespace App\Models\hrModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class hrUsersModel extends Model
{
    public function get($condition){

        $data = array();

        $query = DB::connection('db_hr')->table('users')
        ->select(
            'users.id',
            'users.email',
            'users.password',
            'employee.name',
        )
        ->leftjoin('employee',"employee.user_id","=","users.id")
        ->where($condition)
        ->get();

        if(count($query)>0){
            foreach($query as $list){
                $data = array(
                    "id"=>$list->id,
                    "email"=>$list->email,
                    "password"=>$list->password,
                    "name"=>$list->name,
                );
            }
        }
        
        return $data;
        
    }



}