<!DOCTYPE html>
<html>
<head>
    <title>CIA - APPS PROJECT</title>
</head>
<body>
    <h1>INDIRECT REQUEST</h1>
    <table style="font-family: arial, sans-serif;
        border-collapse: collapse;">
        <tr>
            <td style="text-align: left; padding: 2px;">
                No.
            </td>
            <td style="text-align: left; padding: 2px;">
                : {{ $no_document }}
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 2px;">
                Date
            </td>
            <td style="text-align: left; padding: 2px;">
                : {{ $date }}
            </td>
        </tr>
    </table>
    <br/>
    @php
        $count_header = count($data_request['data']);
        for($i=0; $i<$count_header; $i++){
    @endphp
    <h3>{{ $data_request['data'][$i]['title'] }}</h3>
    <table style="font-family: arial, sans-serif;
        border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="text-align: center; width: 5%; border: 1px solid #dddddd;">No.</th>
                <th style="text-align: center; width: 20%; border: 1px solid #dddddd;">Cost</th>
                <th style="text-align: center; width: 20%; border: 1px solid #dddddd;">Qty</th>
                <th style="text-align: center; width: 25%; border: 1px solid #dddddd;">Unit</th>
                <th style="text-align: center; width: 25%; border: 1px solid #dddddd;">Duration</th>
                <th style="text-align: center; width: 25%; border: 1px solid #dddddd;">@Price</th>
                <th style="text-align: center; width: 25%; border: 1px solid #dddddd;">Total</th>
                <th style="text-align: center; width: 25%; border: 1px solid #dddddd;">Notes</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $count_list = count($data_request['data'][$i]['list']);
                for($j=0; $j<$count_list; $j++){
                    $no = $j+1;
                    echo '<tr>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;">'.$no.'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;" nowrap>'.$data_request['data'][$i]['list'][$j]['option']['label'].'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;" >'.$data_request['data'][$i]['list'][$j]['qty'].'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;" >'.$data_request['data'][$i]['list'][$j]['unit'].'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;" >'.$data_request['data'][$i]['list'][$j]['duration'].'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;" nowrap>Rp. '.number_format($data_request['data'][$i]['list'][$j]['cost'],2,",",".").'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;" nowrap>Rp. '.number_format($data_request['data'][$i]['list'][$j]['subtotal'],2,",",".").'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;" >'.$data_request['data'][$i]['list'][$j]['note'].'</td>';
                    echo '</tr>';
                    $total = $total + $data_request['data'][$i]['list'][$j]['subtotal'];
                };

                echo '<tr>';
                    echo '<td style="text-align: center; border: 1px solid #dddddd;" colspan="6" >Total</td>';
                    echo '<td style="text-align: center; border: 1px solid #dddddd;" colspan="2" >Rp. '.number_format($total,2,",",".").'</td>';
                echo '</tr>';
                
            @endphp
        </tbody>
    </table>
    @php
        }
    @endphp
    <br/>
    <h3>Grand Total: Rp. {{ number_format($data_request['total'],2,",",".") }}</h3>
    <br/>
    <br/>
    <table style="font-family: arial, sans-serif;
        border-collapse: collapse; width: 100%;">
        <tr>
            <th style="text-align: left; width: 60%;"></th>
            <th style="text-align: center; width: 20%;">Request By</th>
            <th style="text-align: center; width: 20%;">Approve By</th>
        </tr>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <tr>
            <td></td>
            <td style="text-align: center;">Project Manager</td>
            <td style="text-align: center;">Purchasing</td>
        </tr>
        
    </table> 
</body>
</html>