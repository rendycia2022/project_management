<?php

namespace App\Imports;

// method
use Illuminate\Support\Facades\DB; 
use Ramsey\Uuid\Uuid;

// import excel modules
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

// models
use App\Models\projectModels\bastModels;
use App\Models\projectModels\ListModel;

class BASTImport implements ToCollection, WithStartRow, WithChunkReading, WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data; 

        // models
        $this->bastModels = new bastModels;
        $this->ListModel = new ListModel;
    }

    public function sheets(): array
    {
        return [
            'BAST' => $this,
        ];
    }

    public function collection(Collection $rows){
        
        $timestamp = gmdate('Y-m-d H:i:s', time()+(60*60*7));

        $user_id = $this->data['user_id'];


        if(count($rows)>0){
            foreach ($rows as $idx =>$row) 
            {
                $newId = Uuid::uuid4();

                $po_number = strtoupper(trim($row[1]));
                $price = $row[2];
                $paymentLabel = trim($row[3]);
                $term = $row[4];
                $date = trim($row[5]);
                $qty = $row[6];
                $invoice_number = trim($row[7]);

                $wherePayment = array(
                    array('active', 1),
                    array('label', $paymentLabel),
                );
                $payment = $this->bastModels->payment($wherePayment);
                if(count($payment)>0){
                    // insert

                    DB::table('bast')
                    ->insert(
                        [
                            "id"=>$newId,
                            "created_at"=>$timestamp,
                            "updated_at"=>$timestamp,
                            "created_by"=>$user_id,
                            "updated_by"=>$user_id,

                            "po_number"=>$po_number,
                            "date"=>$date,
                            "price"=>$price,
                            "payment"=>$paymentLabel,
                            "term"=>$term,
                            "qty"=>$qty,
                            "invoice_number"=>$invoice_number,
                        ]
                    );
                }

                // $whereList = array(
                //     array('active', 1),
                //     array('po_number', $po_number),
                // );
                // $list = $this->ListModel->getData($whereList);
                // if(count($list)>0){

                    
                    
                // }
                 
            }
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