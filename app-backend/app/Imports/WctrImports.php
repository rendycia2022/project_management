<?php

namespace App\Imports;

// method
use Illuminate\Support\Facades\DB; 

// import excel modules
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class WctrImports implements ToCollection, WithStartRow, WithChunkReading
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(Collection $rows){
        
        foreach ($rows as $idx =>$row) 
        {
            DB::connection('db_project')->table('data_wctr_batch')
            ->insert(
                [
                    "id"=>$this->data['id'],
                    "customer"=>trim($row[0]),
                    "project_manager"=>trim($row[1]),
                    "scope_of_work"=>trim($row[2]),
                    "work_description"=>trim($row[3]),
                    "tower_id"=>trim($row[4]),
                    "site_name"=>trim($row[5]),
                    "ring_id"=>trim($row[6]),
                    "po_number"=>trim($row[7]),
                    "on_this_day"=>trim($row[8]),
                    "date_issued"=>trim($row[9]),
                    "approver_1"=>trim($row[10]),
                    "approver_2"=>trim($row[11]),
                    "approver_3"=>trim($row[12]),
                    "tssr"=>trim($row[13]),
                    "fop"=>trim($row[14]),
                    "roh"=>trim($row[15]),
                    "start_date"=>trim($row[16]),
                    "completion_date"=>trim($row[17]),
                    "remaining_material"=>trim($row[18]),
                    "remarks"=>trim($row[19]),
                    "remarks_1"=>trim($row[20]),
                ]
            );
        }

    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }
    
}

?>