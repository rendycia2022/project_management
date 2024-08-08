<?php

namespace App\Http\Controllers\project;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

use App\Models\projectModels\dbModel;
use App\Models\projectModels\updateModel;
use App\Models\projectModels\documentModel;



class UpdateController extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->updateModel = new updateModel;
        $this->dbModel = new dbModel;
        $this->documentModel = new documentModel;

    }

    public function show(Request $request, $day, $all){

        $token = $request->token;

        $connection = $this->dbModel->list();

        $count = count($connection);
        $index = 0;
        $data = array();

        $minus_interval = $day." days";

        for($c=0; $c<$count; $c++){
            $db_conn = $connection[$c]['connection'];
            $db_label = $connection[$c]['label'];
            
            $db_port = $connection[$c]['port'];
            $db_url = "http://103.188.175.175:".$db_port."/";

            
            if($db_conn == "db_af"){
                // db af start

                $document_af = $this->documentModel->af_document($db_conn, $minus_interval);

                if(count($document_af)>0){
                    foreach($document_af as $listAF){

                        $af_id = $listAF->id;
                        $documnt_number = $listAF->document_number;
                        $submission_date = $listAF->submission_date;
                        $division_name = $listAF->division_name;
                        $account = $listAF->account;
                        
                        $item_af = $this->documentModel->af_item($db_conn, $af_id);
                        $item_total = 0;
                        if(count($item_af)>0){
                            foreach($item_af as $item_list){
                                $item_price = $item_list->price;
                                $item_qty = $item_list->qty;
                                $item_subtotal = $item_price * $item_qty;
                                
                                $item_total = $item_total + $item_subtotal;
                            }
                        }

                        $titleAF = $account.' - Rp.'.number_format($item_total,2,",",".");
                        $bodyAF = $documnt_number.', Submission at: '.$submission_date;
                        $detailAF = $titleAF.', '.$bodyAF.', '.$db_label;
                        $borderAF = 'border-green-500';

                        $data[$index] = array(
                            "db_conn"=>$db_conn,
                            "label"=>$db_label,
                            "created_at"=>$listAF->created_at,
                            "title"=>$titleAF,
                            "body"=>$bodyAF,
                            "detail"=>$detailAF,
                            "border"=>$borderAF,
                            "url"=>$db_url.'/api/af/approval/pdf/'.$af_id.'?token='.$token,
                        );
        
                        $index++;
                    }
                }
                
                // db af end
            }else{
                // db project start

                if($db_conn == "db_xl"){
                    $select = array(
                        "report_foto_item.workorder_id",
                        "report_foto_item.created_at",
                        "workorders.code",
                        "workorders.program",
                        "workorders.po_number",
                        "workorders.site_id",
                        "workorders.site_name",
                        "workorders.tower_id",
                        "workorders.area",
                        "workorders.ring",
                        "workorders.island",
                        "workorders.scope_ring as scope",
                        "form_groups.display as group_name",
                        "scope_activities.name as sow_name",
                        "scope_activities.id as sow_id",
                        
                    );
                }else if($db_conn == "db_isat"){
                    $select = array(
                        "report_foto_item.workorder_id",
                        "report_foto_item.created_at",
                        "workorders.code",
                        "workorders.project",
                        "workorders.site_id",
                        "workorders.site_name",
                        "workorders.hostname",
                        "workorders.area",
                        "workorders.sow as scope",
                        "form_groups.name as group_name",
                        "scope_activities.name as sow_name",
                        "scope_activities.id as sow_id",
                    );
                }
            
                $updates = $this->updateModel->report_foto($db_conn, $minus_interval, $select);
                $count_update = count($updates);
                if($count_update > 0){
                    $body = array();
                    foreach($updates as $list){

                        // general data
                        $workorder_id = $list->workorder_id;
                        $code = $list->code;
                        $created_at = $list->created_at;
                        $site_id = $list->site_id;
                        $site_name = $list->site_name;
                        $group_name = $list->group_name;
                        $sow_id = $list->sow_id;
                        $sow_name = $list->sow_name;
                        $scope = $list->scope;

                        // spesific data
                        $project = '';
                        $po_number = '';
                        $host = '';
                        $area = '';

                        $data_host = '';
                        $data_site = '';
                        $data_area = '';
                        $data_other = '';
                        if($db_conn == "db_xl"){

                            $border = "border-blue-500";

                            $project = $list->program;
                            $po_number = $list->po_number;
                            $host = $list->tower_id;

                            $area = $list->area.', '.$list->ring.', '.$list->island;

                            // new data
                            $data_host = 'Tower: '.$host;
                            $data_site = 'Site: '.$site_id.' - '.$site_name;
                            $data_area = 'Area: '.$area;
                            $data_other = 'PO: '.$po_number.', Program: '.$project.' - '.$db_label;
                            


                        }else if($db_conn == "db_isat"){

                            $border = "border-yellow-500";

                            $project = $list->project;
                            $host = $list->hostname;

                            $area = $list->area;

                            // new data
                            $data_host = 'Host: '.$host;
                            $data_site = 'Site: '.$site_id.' - '.$site_name;
                            $data_area = 'Area: '.$area;
                            $data_other = 'Program: '.$project.' - '.$db_label;
                            
                        }

                        // collect document data start
                        $document_index = $code.'_'.$sow_id.'_'.$db_label;

                        $data_title = $data_host.', '.$data_site.' - Doc: '.$sow_name.', Scope: '.$scope;
                        $data_subtitle = $data_host.', '.$data_title.', '.$data_area.', '.$data_other;
                        $data_detail = $data_title.', '.$data_subtitle.', '.$db_label;

                        $data[$document_index] = array(
                            "db_conn"=>$db_conn,
                            "created_at"=>$created_at,
                            "label"=>$db_label,
                            "title"=>$data_title,
                            "subtitle"=>$data_subtitle,
                            "detail"=>$data_detail,
                            "border"=>$border,
                            "url"=>$db_url.'workorder/'.$code.'/scope'.'/'.$sow_id,
                            "params"=>array(
                                "workorder_id"=>$workorder_id,
                                "sow_id"=>$sow_id,
                            ),
                        );

                        // collect document data end
                    }
                }
                
                // db project end
            } 

        }

        $status = "404";
        $message = "Not found.";

        $data = array_values($data);
        if(count($data)>0){

            for($d=0; $d<count($data); $d++){

                if($data[$d]['db_conn'] == "db_xl" || $data[$d]['db_conn'] == "db_isat"){

                    $params = $data[$d]['params'];

                    $where_foto_group = array(
                        array("report_foto_item.workorder_id", $params['workorder_id']),
                        array("report_foto_item.sow_id", $params['sow_id']),
                    );
                    $foto_group = $this->updateModel->report_foto_GetByParams($data[$d]['db_conn'], $where_foto_group);

                    if(count($foto_group)>0){
                        $foto_raw = array();
                        foreach($foto_group as $fg){
                            $group_id = $fg->group_id;
                            $sow_id = $fg->sow_id;
                            $item_number = $fg->item_number;
                            $index_foto = $group_id.$sow_id.$item_number;
                        
                            $group_name = $fg->name;
                            $foto_created_at = $fg->created_at;

                            $foto_raw[$index_foto] = $group_name.' - '.$item_number.' at '.$foto_created_at;
                        }

                        $foto_raw = array_values($foto_raw);

                        $foto = '';
                        for($fi=0; $fi<count($foto_raw); $fi++){
                            $foto .= $foto_raw[$fi]."\n";
                        }

                        $data[$d]['body'] = $foto;
                    }
                }
            }

            $key_values = array_column($data, 'created_at');
            array_multisort($key_values, SORT_DESC, $data);

            if($all == 'false'){
                $data = array_slice($data, 0, 100);
            }

            $status = "200";
            $message = "Ok.";
        }

        $response = array(
            "status"=>$status,
            "message"=>$message,
            "list"=>$data,
        );

        return response()->json($response);
    }

}

?>