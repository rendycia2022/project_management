<?php

namespace App\Http\Controllers;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class Generator extends BaseController
{
    function getId_class1(Request $request, $total_data){

        for($i=0; $i<$total_data; $i++){
            $id[] = Uuid::uuid1();
        };
        
        $response = array(
            "status"=>"200",
            "metadata"=>array(
                "message"=>"success",
                "data"=>$id,
            )
        );

        return response()->json($response);
        
    }

    function getId_class4(Request $request, $jumlah){

        for($i=0; $i<$jumlah; $i++){
            $id[] = Uuid::uuid4();
        };
        
        $response = array(
            "status"=>"200",
            "metadata"=>array(
                "message"=>"success",
                "data"=>$id,
            )
        );

        return response()->json($response);
        
    }

    function createId_class4(Request $request, $table, $jumlah){

        for($i=0; $i<$jumlah; $i++){
            $id = Uuid::uuid4();

            $data = array(
                "id"=>$id,
            );
            DB::connection('db_af')->table($table)
            ->insert(
                $data
            );

            $list[] = $id;
        };
        
        $response = array(
            "status"=>"200",
            "metadata"=>array(
                "message"=>"success",
                "data"=>$list,
            )
        );

        return response()->json($response);
        
    }
}

?>