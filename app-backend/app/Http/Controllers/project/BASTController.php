<?php

namespace App\Http\Controllers\project;
use Laravel\Lumen\Routing\Controller as BaseController;

// method
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

// excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// import
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BASTImport;

use App\Models\projectModels\bastModels;


class BASTController extends BaseController
{

    public function __construct(){

        $this->middleware('auth');

        // models
        $this->bastModels = new bastModels;

    }

    public function showByPo(Request $request){
        $code = $request['code'];

        $timeline = array();
        $whereBAST = array(
            array('active', 1),
            array('po_number', $code),
        );
        $bast = $this->bastModels->show($whereBAST);

        if(count($bast)>0){
            foreach($bast as $ba){

                $paymentLabel = $ba->payment;
                $wherePayment = array(
                    array('active', 1),
                    array('label', $paymentLabel),
                );
                $payment = $this->bastModels->payment($wherePayment);
                if(count($payment)>0){
                    foreach($payment as $pay){
                        $payment_id = $pay->id;
                    }

                    $price = $ba->price;
                    $term = $ba->term;
                    $qty = $ba->qty;

                    $price_percent = $price * ($term / 100);
                    $subtotal = $price_percent * $qty;

                    $timeline[$payment_id]['id'] = $payment_id;
                    $timeline[$payment_id]['label'] = $paymentLabel;
                    $timeline[$payment_id]['raw'][] =array(
                        "id"=>$ba->id,
                        "po_number"=>$ba->po_number,
                        "price"=>$ba->price,
                        "payment"=>$ba->payment,
                        "term"=>$ba->term,
                        "qty"=>$ba->qty,
                        "date"=>$ba->date,
                        "invoice_number"=>$ba->invoice_number,
                        "subtotal"=>$subtotal,
                    );
                }

            }

            if(count($timeline)>0){
                $timeline = array_values($timeline);
                $key_timeline = array_column($timeline, 'id');
                array_multisort($key_timeline, SORT_DESC, $timeline);
            }
        }

        $response = array(
            "status"=>200,
            "message"=>"Ok.",
            "events"=>$timeline,
        );

        return response()->json($response);
    }

    public function store(Request $request, $user_id){

        $data = array();

        $message = "Not found.";
        // import process
        if ($request->hasFile('file')) {
            $message = "Ok.";
            $data = [
                "user_id"=>$user_id,
            ];
            
            $file = $request->file('file'); // GET FILE

            // IMPORT BAST
            $BASTImport = new BASTImport($data);
            Excel::import($BASTImport, $file);

        }

        $response = array(
            "status"=>200,
            "message"=>$message,
        );
      
        return response()->json($response); 
    }

    public function backend(Request $request){
        
        $response = env('HOST_NAME').env('HOST_PORT').'/api';
        return response()->json($response);

    }

    public function download(){

        $template_file = base_path('public/template/Template_BAST.xlsx');

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($template_file);
        $filename = "Template_BAST_Import.xlsx";
        
        // references
        $worksheet = $spreadsheet->getSheet(1);
        $start_row = 2;

        // customer
        $wherePayment = array(
            array('active', 1),
        );
        $payment = $this->bastModels->payment($wherePayment);
        if(count($payment)>0){
            $start_row_data = $start_row;
            $number = 1;
            foreach($payment as $pay){
                
                // number
                $cell_number = "B".$start_row_data;
                $worksheet->getCell($cell_number)->setValue($number);

                // label
                $label = $pay->label;
                $cell_code = "C".$start_row_data;
                $worksheet->getCell($cell_code)->setValue($label);

                // remarks
                $remarks = $pay->remarks;
                $cell_code = "D".$start_row_data;
                $worksheet->getCell($cell_code)->setValue($remarks);
                

                // increment
                $number = $number + 1;
                $start_row_data = $start_row_data + 1;
            }
        }

        try{
            
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save(base_path('public/template/export/'.$filename));
            
        }catch (Exception $e){
            //handle exception
            return $e;
        }

        return response()->download(base_path('public/template/export/'.$filename))->deleteFileAfterSend(true);
        
    }

    public function destroy(Request $request){

        $data = $request['data'];
        $timestamp = gmdate('Y-m-d H:i:s', time()+(60*60*7));
        $user_id = $request->input('user_id');

        for($i=0; $i<count($data); $i++){
            $id = $data[$i]['id'];

            $update = array(
                "active"=>0,
                "updated_at"=>$timestamp,
                "updated_by"=>$user_id,
            );

            DB::connection('db_project_management')
            ->table('bast')
            ->where('id', $id)
            ->update(
                $update
            );
        }

        $message = "Success";

        $response = array(
            "status"=>200,
            "message"=>$message,
        );
      
        return response()->json($response); 
    }

}

?>