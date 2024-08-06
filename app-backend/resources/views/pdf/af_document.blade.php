<!DOCTYPE html>
<html>
<head>
    <title>CIA - APPS PROJECT</title>
</head>
<body>
    <div style="text-align: center;">
        <span><b><u>APPROVAL FORM</u></b></span>
        <br><span>{{ $header_document_number }}</span>
    </div>
    <br>
    <!-- header -->
    <div>
        <table style="font-size: 12px; width:100%;">
            <tr>
                <td nowrap style="width:10%;">Company Name</td>
                <td style="width:1%;">:</td>
                <td nowrap style="width:20%;">{{ $header_company }}</td>
                <td style="width:39%;"></td>
                <td nowrap style="text-align: right; width:10%;" >Account Name :</td>
                <td nowrap style="width:20%;">{{ $bank_account }}</td>
            </tr>
            <tr>
                <td nowrap style="width:10%;">Division</td>
                <td style="width:1%;">:</td>
                <td nowrap style="width:20%;">{{ $header_division_name }}</td>
                <td style="width:39%;"></td>
                <td nowrap style="text-align: right; width:10%;" >Account Code :</td>
                <td nowrap style="width:20%;">{{ $bank_rekening }}</td>
            </tr>
            <tr>
                <td nowrap style="width:10%;"></td>
                <td style="width:1%;"></td>
                <td nowrap style="width:20%;"></td>
                <td style="width:39%;"></td>
                <td nowrap style="text-align: right; width:10%;" ></td>
                <td nowrap style="width:20%;">{{ $bank_name }}</td>
            </tr>
            <tr>
                <td nowrap style="width:10%;"></td>
                <td style="width:1%;"></td>
                <td nowrap style="width:20%;"></td>
                <td style="width:39%;"></td>
                <td nowrap style="text-align: right; width:10%;" >Date :</td>
                <td nowrap style="width:20%;">{{ $header_date }}</td>
            </tr>
        </table>
    </div>
    <!-- body -->
    <div>
        <table style="font-family: arial, sans-serif; font-size: 8px; border-collapse: collapse; width: 100%;">
            <tr style="text-align: center; border: 1px solid #dddddd;" >
                <th style="text-align: center; border: 1px solid #dddddd;" >ITEM</th>
                <th style="text-align: center; border: 1px solid #dddddd;" >REMARKS</th>
                <th style="text-align: center; border: 1px solid #dddddd;" >QTY</th>
                <th style="text-align: center; border: 1px solid #dddddd;" >PRICE</th>
                <th style="text-align: center; border: 1px solid #dddddd;" >TOTAL PRICE</th>
            </tr>
            @php

                $count_items = count($items_list);
                $no = 1;
                $total = 0;
                $grand_total = 0;
                for($i=0; $i<$count_items; $i++){
                    $total_price = $items_list[$i]['price'] * $items_list[$i]['qty'];
                    $total = $total + $total_price;
                    echo '<tr>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd; width:5%" >'.$no.'</td>';
                        echo '<td style="border: 1px solid #dddddd; word-wrap: break-word; width:30%" >'.$items_list[$i]['remarksDisplay'].'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd; width:5%" >'.$items_list[$i]['qty'].'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd; width:20%" >Rp. '.number_format($items_list[$i]['price'],2,",",".").'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd; width:40%" >Rp. '.number_format($total_price,2,",",".").'</td>';
                    echo '</tr>';
                    $no++;
                }

                $grand_total = $total;

                echo '<tr>';
                    echo '<td colspan="4" style="text-align: right; border: 1px solid #dddddd;" ><b>Total:</b></td>';
                    echo '<td style="text-align: center; border: 1px solid #dddddd;" >Rp. '.number_format($total,2,",",".").'</td>';
                echo '</tr>';

                echo '<tr><td colspan="5"></td></tr>';
                echo '<tr>';
                    echo '<td colspan="4" style="text-align: right; border: 1px solid #dddddd;" >Pph:</td>';
                    echo '<td style="text-align: center; border: 1px solid #dddddd;" ></td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td colspan="4" style="text-align: right; border: 1px solid #dddddd;" ><b>Grand Total:</b></td>';
                    echo '<td style="text-align: center; border: 1px solid #dddddd;" ><b>Rp. '.number_format($grand_total,2,",",".").'</b></td>';
                echo '</tr>';
                
                

            @endphp
        </table>
    </div>
    <br>
    <!-- actual finance -->
    <div style="text-align: center; font-size:10;">
        <span>Financial Bugdet v.s Actual</span>
    </div>
    <div>
        <table style="font-family: arial, sans-serif; font-size: 8px; border-collapse: collapse; width: 100%;">
            <tr style="border: 1px solid #dddddd;" >
                <td style="text-align: left; border: 1px solid #dddddd;">
                    Budget Tahun:
                </td>
                <td colspan="2" style="text-align: center; border: 1px solid #dddddd;" >Month: {{ $financial_month_date }}</th>
                <td colspan="2" style="text-align: center; border: 1px solid #dddddd;" >Requisition</th>
            </tr>
            <tr>
                <td style="text-align: center; border: 1px solid #dddddd;" >Periode: {{ $financial_periode }}</td>
                <td style="text-align: center; border: 1px solid #dddddd;" >Actual</td>
                <td style="text-align: center; border: 1px solid #dddddd;" >Balance</td>
                <td style="text-align: center; border: 1px solid #dddddd;" >Amount</td>
                <td style="text-align: center; border: 1px solid #dddddd;" >Balance</td>
            </tr>
            <tr>
                
                @php
                    
                    echo '<td colspan="2" style="text-align: center; border: 1px solid #dddddd;"></td>';
                    echo '<td style="text-align: center; border: 1px solid #dddddd;" >Rp. '.number_format($financial_actual_balance,2,",",".").'</td>';
                    echo '<td style="text-align: center; border: 1px solid #dddddd;" >Rp. '.number_format($financial_requisition_amount,2,",",".").'</td>';
                    
                    $financial_total = $financial_actual_balance - $financial_requisition_amount;
                    echo '<td style="text-align: center; border: 1px solid #dddddd;" >Rp. '.number_format($financial_total,2,",",".").'</td>';

                @endphp
            </tr>
        </table>
    </div>
    &nbsp;

    <!-- notes -->
    <div>
        <span style="font-size:12px;">Notes:</span><br/>
        @php
            echo '<div style="font-size:8px; margin-left: 50px;">'.$notes.'</div>';
        @endphp
    </div>
    <br/>
    <!-- sign -->
    <div>
        <table style="width: 100%; font-size:12px">
            <tr>
                <td>{{ $date_sign }}</td>
                <td colspan="2">Support by, &nbsp; Date:</td>
                <td>Approved by,</td>
                <td>Date:</td>
            </tr>
            <tr>
                <td>Proposed by,</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><br><br><br><br></td>
                <td><br><br><br><br></td>
                <td><br><br><br><br></td>
                <td><br><br><br><br></td>
                <td><br><br><br><br></td>
            </tr>
            <tr>
                <td style="text-align:center; " >{{ $sign_proposed }}</td>
                <td colspan="2" style="text-align:center; " >{{ $sign_finance }}</td>
                <td style="text-align:center; " >{{ $sign_approved }}</td>
                <td style="text-align:center; " >{{ $sign_executive }}</td>
            </tr>
        </table>
    </div>



    <!-- new page -->
    <div style = "display:block; clear:both; page-break-after:always;"></div>
    <!-- content in new page here -->
    <div style="text-align: center;">
        <span><b><u>CLAIM REIMBURSEMENT</u></b></span>
        <br><span>{{ $header_document_number }}</span>
    </div>
    <br>

    <div>
        <table style="font-family: arial, sans-serif; font-size: 8px; border-collapse: collapse; width: 100%;">
            <tr style="text-align: center; border: 1px solid #dddddd;" >
                <th style="text-align: center; border: 1px solid #dddddd;" ><b>No.</b></th>
                <th style="text-align: center; border: 1px solid #dddddd;" ><b>Tanggal</b></th>
                <th style="text-align: center; border: 1px solid #dddddd;" ><b>Keterangan</b></th>
                <th style="text-align: center; border: 1px solid #dddddd;" ><b>Pemakaian</b></th>
            </tr>
            @php

                $count_items = count($items_list);
                $no = 1;
                $total = 0;
                $grand_total = 0;
                for($i=0; $i<$count_items; $i++){
                    $total_price = $items_list[$i]['price'] * $items_list[$i]['qty'];
                    $total = $total + $total_price;
                    echo '<tr>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd; width:5%" >'.$no.'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd; width:20%" >'.$items_list[$i]['date_display'].'</td>';
                        echo '<td style="border: 1px solid #dddddd; word-wrap: break-word; width:35%" >'.$items_list[$i]['remarksDisplay'].'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd; width:40%" >Rp. '.number_format($total_price,2,",",".").'</td>';
                    echo '</tr>';
                    $no++;
                }

                $grand_total = $total;

                echo '<tr>';
                    echo '<td colspan="3" style="text-align: center; border: 1px solid #dddddd;" ><b>Total Pemakaian</b></td>';
                    echo '<td style="text-align: center; border: 1px solid #dddddd;" >Rp. '.number_format($total,2,",",".").'</td>';
                echo '</tr>';
                
                

            @endphp
        </table>
    </div>



    <!-- new page -->
    @php
    $entertain = false; 
    for($i=0; $i<$count_items; $i++){
        $cost_type_id = $items_list[$i]['cost_type_id'];
        if($cost_type_id == '75955ebc-6d79-4297-bfb0-5b321207a2ed'){
            $entertain = true;
            break;
        }
    }
    if($entertain){
    @endphp
    <div style = "display:block; clear:both; page-break-after:always;"></div>
    <!-- content in new page here -->
    <div style="text-align: center;">
        <span><b><u>ENTERTAIN</u></b></span>
        <br><span>{{ $header_document_number }}</span>
    </div>
    <br>
    <div>
        <ol style="font-size:9;">
            <li>Claim should only be for entertainment expense incurred on hehalf of the company in the course of official duties:</li>
            <li>Receipt with details of the person entertained must suport all claims</li>
            <li>In cases where a group of employees in involved in the entertainment ofn exteranal parties the most senior staff should be responsible for seeking approval for the entertaintment claim and he must seeking approval for the entertaintment claim and he must seek approval from a more senior person.</li>
            <li>Expenses incurred for entertainning own or other ST employees (including in course of doing business) will not reimbursed</li>
            <li>Only reasonable ammount will be reimbursed as tips. Where tips are deemed to be excessive, the company reserves the right no to reimburse the full ammount of tip as a guide</li>
            <ol style="list-style-type: lower-alpha;">
                <li>In country where it is the culture to give tips, the ammount tipped shall no exceed 5% of the bill if service charge is included in the bill and shall not exceed 10% of the bill if there is no service charge imposed</li>
                <li>In S'pore or country where it is not the culture to give tips, the ammount tipped will not be reimbursed by the company if service charge is included in the bill and the ammount tipped shall not exceed 10% of the bill if there is no service charge imposed</li>
                
            </ol>
            <li>All claims must reach finance by the first week of the following month.</li>
        </ol>  
    </div>
    <div>
        <div style="">
            <span><b>B. PARTICULARS OF CLAIMANT</b></span>
        </div>
    </div>
    <br/>
    <div>
        <table style="font-size: 12px; width:100%;">
            <tr>
                <td nowrap style="width:40%;"><b>Name :</b></td>
                <td nowrap style="width:28%;">Appointment :</td>
                <td nowrap style="width:15%;">Ext :</td>
                <td nowrap style="width:17%;">Division :</td>
            </tr>
        </table>
    </div>
    <br/>
    <div>
        <table style="font-family: arial, sans-serif; font-size: 8px; border-collapse: collapse; width: 100%;">
            <tr style="text-align: center; border: 1px solid #dddddd;" >
                <th style="text-align: center; border: 1px solid #dddddd;" rowspan="2" >Date</th>
                <th style="text-align: center; border: 1px solid #dddddd;" colspan="2">Person Entertained</th>
                <th style="text-align: center; border: 1px solid #dddddd;" >Reason for entertainment</th>
                <th style="text-align: center; border: 1px solid #dddddd;" rowspan="2">Amount</th>
                <th style="text-align: center; border: 1px solid #dddddd;" >Executive Management</th>
            </tr>
            <tr style="text-align: center; border: 1px solid #dddddd;" >
                <th style="text-align: center; border: 1px solid #dddddd;" >Name</th>
                <th style="text-align: center; border: 1px solid #dddddd;" >Company</th>
                <th style="text-align: center; border: 1px solid #dddddd;" >(Reason must be specific)</th>
                <th style="text-align: center; border: 1px solid #dddddd;" >Approval</th>
            </tr>
            @php

                $count_items = count($items_list);
                $no = 1;
                $total = 0;
                $grand_total = 0;
                for($i=0; $i<$count_items; $i++){

                    $cost_type_id = $items_list[$i]['cost_type_id'];
                    if($cost_type_id == '75955ebc-6d79-4297-bfb0-5b321207a2ed'){
                        $total_price = $items_list[$i]['price'] * $items_list[$i]['qty'];
                        $total = $total + $total_price;
                        echo '<tr>';
                            echo '<td style="text-align: center; border: 1px solid #dddddd; width:12%" >'.$items_list[$i]['date_display'].'</td>';
                            echo '<td style="text-align: center; border: 1px solid #dddddd; width:18%" >'.$items_list[$i]['person'].'</td>';
                            echo '<td style="text-align: center; border: 1px solid #dddddd; width:10%" >'.$items_list[$i]['company'].'</td>';
                            echo '<td style="border: 1px solid #dddddd; word-wrap: break-word; width:28%" >'.$items_list[$i]['remarksDisplay'].'</td>';
                            echo '<td style="text-align: center; border: 1px solid #dddddd; width:15%" >Rp. '.number_format($total_price,2,",",".").'</td>';
                            echo '<td style="text-align: center; border: 1px solid #dddddd; width:17%" ></td>';
                        echo '</tr>';
                        $no++;

                    }
                }
                
                echo '<tr>';
                    echo '<td colspan="4" style="text-align: center; border: 1px solid #dddddd;" ><b>Total</b></td>';
                    echo '<td colspan="2" style="text-align: center; border: 1px solid #dddddd;" ><b>Rp. '.number_format($total,2,",",".").'</b></td>';
                echo '</tr>';

            @endphp
        </table>
    </div>
    
    <div>
        <table style="width: 100%; font-size:12px">
            <tr>
                <td><br/><br/></td>
            </tr>
            <tr>
                <td><br/></td>
                <td style="text-align: right;" >
                    Cheque payment to be made payable to : <br/>(if different from claimant)
                </td>
            </tr>
            <tr>
                <td style="" >Signature of claimant & date</td>
                <td><br/><br/><br/><br/><br/><br/></td>
            </tr>
            <tr>
                <td><br/><br/><br/></td>
                <td style="text-align: right;" >
                    (Name as in bank account - pls write in block letters)
                </td>
            </tr>
            <tr>
                <td style="" >Signature of Finance Controller </td>
            </tr>
        </table>
    </div>

    @php
    }
    @endphp

</body>
</html>