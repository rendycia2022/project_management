<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// models
use App\Models\projectModels\afModel;
use App\Models\projectModels\directModel;
use App\Models\projectModels\revenueModel;
use App\Models\projectModels\getProjectModel;
use App\Models\projectModels\batchProjectModel;

class ListModel extends Model
{

    public function __construct(){

        // models
        $this->afModel = new afModel;
        $this->directModel = new directModel;
        $this->revenueModel = new revenueModel;
        $this->getProjectModel = new getProjectModel;
        $this->batchProjectModel = new batchProjectModel;

    }

    public function getData($where){

        $list = array();

        $query = DB::connection('db_project')
        ->table('new_project')
        ->where($where)
        ->orderBy('date', 'ASC')
        ->get();

        if(count($query)>0){
            foreach($query as $item){
                $po_number = $item->po_number;
                $code = $item->code;

                // document_po
                $document_po = $this->getProjectModel->renameForPoFile($po_number);
                $file = null;

                $path = base_path('public/document_po/'.$document_po.'.pdf');
                if (file_exists($path)) {
                    $file = env('HOST_NAME').env('HOST_PORT')."/document_po/".$document_po.".pdf"; 
                }

                // pps link
                $project_link = env('HOST_NAME').env('FRONTEND_PORT')."/project/new/dashboard/".$code;

                $list[] = array(
                    "code"=>$item->code,
                    "po_number"=>$item->po_number,
                    "name"=>$item->name,
                    "type"=>$item->type,
                    "customer"=>$item->customer,
                    "ref"=>$item->ref,
                    "date"=>$item->date,
                    "project"=>array(
                        "code"=>$item->project,
                        "label"=>$item->customer,
                    ),
                    "direct"=>$this->directModel->directTotal($po_number),
                    "indirect"=>$this->afModel->indirectTotal($po_number),
                    "revenue"=>$this->revenueModel->revenueTotal($po_number),
                    "remarks"=>$this->remarks($po_number),
                    "file_document"=>$file,
                    "project_link"=>$project_link,
                    "invoice"=>$this->batchProjectModel->invoice($po_number),
                );
            }
        }
        
        return $list;
    }

    public function remarks($code){

        $remarks = '';

        $query = DB::connection('db_project_management')
        ->table('batch_remarks')
        ->where('code', $code)
        ->get();

        if(count($query)>0){
            foreach($query as $item){
                $remarks = $item->remarks;
            }
        }

        return $remarks;
    }
}