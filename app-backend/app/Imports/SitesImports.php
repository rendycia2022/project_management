<?php

namespace App\Imports;

// method
use Illuminate\Support\Facades\DB; 

// import excel modules
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SitesImports implements ToCollection, WithStartRow, WithChunkReading
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(Collection $rows){
        
        foreach ($rows as $idx =>$row) 
        {
            DB::connection('db_project')->table('project_sites_batch')
            ->insert(
                [
                    "site_id"=>$row[0],
                    "site_name"=>$row[1],
                    "site_area"=>$row[2],
                    "revenue_id"=>$this->data['revenue_id'],
                    "project_id"=>$this->data['project_id'],
                    "user_id"=>$this->data['user_id'],
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