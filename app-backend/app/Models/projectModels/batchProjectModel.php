<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class batchProjectModel extends Model
{
    public function remarks($code){
        $query = DB::connection('db_project_management')
        ->table('batch_remarks')
        ->where('code', $code)
        ->get();

        return $query;
    }

    public function get($where){

        $data =array();

        $query = DB::connection('db_project_management')
        ->table('batch')
        ->where($where)
        ->orderBy('date', 'ASC')
        ->get();

        if(count($query)>0){
            foreach($query as $list){
                
                $no_document = $list->po_number;
                $project = $list->project;
                $customer = $list->customer;
                $cust_init = $list->initial;
                $value = $list->value;
                $revenue = $list->revenue;
                $cost = $list->cost;
                $date = $list->date;
                $status = $list->status;

                $data[$no_document]['representative'] = array(
                    "customer"=>$customer,
                    "cust_init"=>$cust_init,
                );

                $data[$no_document]['project'] = $project;
                $data[$no_document]['po_date'] = $date;
                $data[$no_document]['no_document'] = $no_document;
                $data[$no_document]['status'] = $status;

                if(isset($data[$no_document]['revenue'])){
                    $data[$no_document]['revenue'] = $data[$no_document]['revenue'] + $revenue;
                    $data[$no_document]['af_total'] = $data[$no_document]['af_total'] + $cost;
                    $data[$no_document]['po_value'] = $data[$no_document]['po_value'] + $value;
                }else{
                    $data[$no_document]['revenue'] = $revenue;
                    $data[$no_document]['af_total'] = $cost;
                    $data[$no_document]['po_value'] = $value;
                }

            }
        }
        
        return $data;
    }

    public function getByYear($where, $year){

        $data =array();

        $query = DB::connection('db_project_management')
        ->table('batch')
        ->where($where)
        ->whereYear('date', $year)
        ->orderBy('date', 'ASC')
        ->get();

        if(count($query)>0){
            foreach($query as $list){
                
                $no_document = $list->po_number;
                $project = $list->project;
                $customer = $list->customer;
                $cust_init = $list->initial;
                $value = $list->value;
                $revenue = $list->revenue;
                $cost = $list->cost;
                $date = $list->date;
                $status = $list->status;

                $data[$no_document]['representative'] = array(
                    "customer"=>$customer,
                    "cust_init"=>$cust_init,
                );

                $data[$no_document]['po_date'] = $date;
                $data[$no_document]['no_document'] = $no_document;
                $data[$no_document]['status'] = $status;

                if(isset($data[$no_document]['revenue'])){
                    $data[$no_document]['revenue'] = $data[$no_document]['revenue'] + $revenue;
                    $data[$no_document]['af_total'] = $data[$no_document]['af_total'] + $cost;
                    $data[$no_document]['po_value'] = $data[$no_document]['po_value'] + $value;
                }else{
                    $data[$no_document]['revenue'] = $revenue;
                    $data[$no_document]['af_total'] = $cost;
                    $data[$no_document]['po_value'] = $value;
                }

            }
        }
        
        return $data;
    }
}