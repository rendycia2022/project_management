<?php

namespace App\Models\projectModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class sitesModel extends Model
{
    function db_list(){
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

        return $data;
    }

    public function getXl(){

        $query = DB::connection('db_xl')
        ->table('workorders')
        ->select('code','island','area','site_name', 'scope', 'po_number')
        ->get();

        $data = array();
        foreach($query as $list){
            $data[] = array(
                "code"=>$list->code,
                "project"=>$list->island,
                "area"=>$list->area,
                "site_name"=>$list->site_name,
                "sow"=>$list->scope,
                "po_number"=>$list->po_number,
            );
        }
        
        return $data;
    }

    public function getIndosat(){

        $query = DB::connection('db_isat')
        ->table('workorders')
        ->select('code','project','area','site_name','sow')
        ->get();

        $data = array();
        foreach($query as $list){
            $data[] = array(
                "code"=>$list->code,
                "project"=>$list->project,
                "area"=>$list->area,
                "site_name"=>$list->site_name,
                "sow"=>$list->sow,
                "po_number"=>null,
            );
        }
        
        return $data;
    }

    public function getMenus(){

        $data = array();

        $headerIndex = 0;

        // project menu start
        $data[$headerIndex] = array(
            'label'=>'Projects'
        );

        $index = 0;
        // summary
        $data[$headerIndex]['items'][$index] = array(
            'label'=>'Summary',
            'icon'=>'pi pi-fw pi-desktop',
            'to'=>'/cia/project/summary',
        );

        $index++;
        $db_list = $this->db_list();
        for($i=0; $i<count($db_list); $i++){
            $data[$headerIndex]['items'][$index] = array(
                'label'=>$db_list[$i]['label'],
                'icon'=>$db_list[$i]['icon'],
            );

            if($db_list[$i]['port'] == '58101'){
                $query = DB::connection($db_list[$i]['connection'])
                ->table('workorders')
                ->select('code','island','area','site_name')
                ->whereDate('created_at', '>=', env('PARAMS_DATE'))
                ->get();
                if(count($query)>0){
                    foreach($query as $list){
                        $island = $list->island;
                        $area = $list->area;
                        $site_name = $list->site_name;
                        
                        $code = $list->code;

                        
                        $data[$headerIndex]['items'][$index]['items'][$island]['label'] = $island;
                        $data[$headerIndex]['items'][$index]['items'][$island]['icon'] = 'pi pi-fw pi-map';

                        $data[$headerIndex]['items'][$index]['items'][$island]['items'][$area]['label'] = $area;
                        $data[$headerIndex]['items'][$index]['items'][$island]['items'][$area]['icon'] = 'pi pi-fw pi-sitemap';

                        $data[$headerIndex]['items'][$index]['items'][$island]['items'][$area]['items'][$site_name]['label'] = $site_name;
                        $data[$headerIndex]['items'][$index]['items'][$island]['items'][$area]['items'][$site_name]['icon'] = 'pi pi-fw pi-map-marker';
                        $data[$headerIndex]['items'][$index]['items'][$island]['items'][$area]['items'][$site_name]['to'] = '/cia/project/sites/'.$db_list[$i]['port'].'/'.$code;
                    }

                    // reindexing start
                    // island
                    $data[$headerIndex]['items'][$index]['items'] = array_values($data[$headerIndex]['items'][$index]['items']);

                    // area
                    $island_list = $data[$headerIndex]['items'][$index]['items'];
                    $count_island = count($island_list);

                    if($count_island>0){
                        for($is=0; $is<$count_island; $is++){
                            $area_list = $data[$headerIndex]['items'][$index]['items'][$is]['items'];
                            $data[$headerIndex]['items'][$index]['items'][$is]['items'] = array_values($area_list);
        
                            // site
                            $count_area = count($area_list);
                            if($count_area>0){
                                for($ar=0; $ar<$count_area; $ar++){
                                    $site_list = $data[$headerIndex]['items'][$index]['items'][$is]['items'][$ar]['items'];
                                    $data[$headerIndex]['items'][$index]['items'][$is]['items'][$ar]['items'] = array_values($site_list);
                                }
                            }
        
                        }
                    }
                    // reindexing end
                }
            }
            if($db_list[$i]['port'] == '58102'){
                $query = DB::connection($db_list[$i]['connection'])
                ->table('workorders')
                ->select('code','project','area','site_name')
                ->whereDate('created_at', '>=', env('PARAMS_DATE'))
                ->get();
                if(count($query)>0){
                    foreach($query as $list){
                        $project = $list->project;
                        $area = $list->area;
                        $site_name = $list->site_name;
                        
                        $code = $list->code;

                        
                        $data[$headerIndex]['items'][$index]['items'][$project]['label'] = $project;
                        $data[$headerIndex]['items'][$index]['items'][$project]['icon'] = 'pi pi-fw pi-map';

                        $data[$headerIndex]['items'][$index]['items'][$project]['items'][$area]['label'] = $area;
                        $data[$headerIndex]['items'][$index]['items'][$project]['items'][$area]['icon'] = 'pi pi-fw pi-sitemap';

                        $data[$headerIndex]['items'][$index]['items'][$project]['items'][$area]['items'][$site_name]['label'] = $site_name;
                        $data[$headerIndex]['items'][$index]['items'][$project]['items'][$area]['items'][$site_name]['icon'] = 'pi pi-fw pi-map-marker';
                        $data[$headerIndex]['items'][$index]['items'][$project]['items'][$area]['items'][$site_name]['to'] = '/cia/project/sites/'.$db_list[$i]['port'].'/'.$code;
                    }

                    // reindexing start
                    // island
                    $data[$headerIndex]['items'][$index]['items'] = array_values($data[$headerIndex]['items'][$index]['items']);

                    // area
                    $list = $data[$headerIndex]['items'][$index]['items'];
                    $count_list = count($list);

                    if($count_list>0){
                        for($is=0; $is<$count_list; $is++){
                            $area_list = $data[$headerIndex]['items'][$index]['items'][$is]['items'];
                            $data[$headerIndex]['items'][$index]['items'][$is]['items'] = array_values($area_list);
        
                            // site
                            $count_area = count($area_list);
                            if($count_area>0){
                                for($ar=0; $ar<$count_area; $ar++){
                                    $site_list = $data[$headerIndex]['items'][$index]['items'][$is]['items'][$ar]['items'];
                                    $data[$headerIndex]['items'][$index]['items'][$is]['items'][$ar]['items'] = array_values($site_list);
                                }
                            }
        
                        }
                    }
                    // reindexing end
                }
            }

            $index++;
            
        }

        $headerIndex++;
        // project menu end
        
        return $data;
        
    }
}