<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
  
 
</style>
</head>
<body>
    <div class="container">
        <table style="width: 50%; border-collapse: collapse;" class="table">
            <tbody>
                @foreach($data as $emp)
                @foreach($emp as $index => $user)
                    <tr @if($index === 0) style="font-size:12px;" @endif>
                        <td>STPLKMB</td>
                        <td>SALPAY</td>
                        <?php 
                            $companyBankName = $user['company_bank_name'];
                            $userBankName = $user['bank_name'];
                            $bankType = $companyBankName == $userBankName ? 'IFT' : 'NFET'; 
                        ?>
                        <td>{{ $bankType }}</td>
                        <td>{{ now()->format('d/m/Y') }}</td>
                        <td>{{$user['company_acount_no']}}</td>
                        <td>{{ $user['net_salary'] }}</td>
                        <td>M</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['ifsc_code'] }}</td>
                        <td style="width:200px">{{$user['account_no'] }}</td>
                    </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
