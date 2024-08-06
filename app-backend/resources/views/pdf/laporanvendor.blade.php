<!DOCTYPE html>
<html>
<head>
    <title>CIA - APPS PROJECT</title>
</head>
<body>
    <h1>LAPORAN VENDOR</h1>
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
                : {{ $date_document }}
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 2px;">
                Project
            </td>
            <td style="text-align: left; padding: 2px;">
                : {{ $project_name }}
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 2px;">
                Vendor
            </td>
            <td style="text-align: left; padding: 2px;">
                : {{ $vendor }}
            </td>
        </tr>
    </table>
    <br/>
    <br/>
    <table style="font-family: arial, sans-serif;
        border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="text-align: center; width: 5%; border: 1px solid #dddddd;">No.</th>
                <th style="text-align: center; width: 30%; border: 1px solid #dddddd;">Description</th>
                <th style="text-align: center; width: 20%; border: 1px solid #dddddd;">Quantity</th>
                <th style="text-align: center; width: 20%; border: 1px solid #dddddd;">@Price</th>
                <th style="text-align: center; width: 25%; border: 1px solid #dddddd;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $counting = count($detail);
                for($i=0; $i<$counting; $i++){
                    echo '<tr>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;">'.$detail[$i]['no'].'</td>';
                        echo '<td style="border: 1px solid #dddddd;">'.$detail[$i]['item']['label'].'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;">'.$detail[$i]['qty'].' '.$detail[$i]['unit'].'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;">Rp. '.number_format($detail[$i]['cost'],2,",",".").'</td>';
                        echo '<td style="text-align: center; border: 1px solid #dddddd;">Rp. '.number_format($detail[$i]['total'],2,",",".").'</td>';
                    echo '</tr>';
                    $site_count = count($detail[$i]['sites']);
                    if($site_count>0){
                        for($j=0; $j<$site_count; $j++){
                            $sub_counter = $j+1;
                            echo '<tr>';
                                echo '<td style="text-align: right; border: 1px solid #dddddd;">'.$detail[$i]['no'].'.'.$sub_counter.'</td>';
                                echo '<td style="border: 1px solid #dddddd; text-indent: 30px;" colspan="4">'.$detail[$i]['sites'][$j]['site_id'].'-'.$detail[$i]['sites'][$j]['site_name'].' '.$detail[$i]['sites'][$j]['site_area'].'</td>';
                            echo '</tr>';
                        }
                    }
                }
            @endphp
        </tbody>
    </table>

    <br/>
    <br/>
    <table style="font-family: arial, sans-serif;
        border-collapse: collapse; width: 90%;">
        <tr>
            <td>Keterangan:</td>
        </tr>
        <tr>
            <td><plaintext style="font-size: 12px;">{{ $description }}</plaintext></td>
        </tr>
    </table>
    

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