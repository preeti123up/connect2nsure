<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <table style="width: 50%; border-collapse: collapse;" class="table table-striped">
            <thead>
                <tr>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:200px; ">Name</th>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:100px; ">Monthly Salary</th>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:100px; ">TDS</th>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:100px; ">Extra Salary</th>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:100px; ">Reimbursement</th>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:100px; ">Net Salary</th>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600;width:100px; ">Days in Month</th>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600;width:100px; ">Pay Days</th>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:200px;  ">Total Working Days</th>
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:200px;  ">Extra Working Days</th>
                    <!--<th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:100px;   ">Week Off</th>-->
                    <!--<th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:100px;  ">Holidays</th>-->
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; width:100px;  ">Present</th>
                    <!--@foreach($data['users'][0]['leaveTypesCount'] as $leaveType => $count)-->
                    <!--    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600;  width:100px; ">{{ $leaveType }}</th>-->
                    <!--@endforeach-->
                    <th style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600;  width:100px; ">Absent</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $emp)
                    @foreach($emp as $user)
                        <tr>
                            <td style="border: 1px solid black; text-align: center; color:#151B54; font-weight: 600; background: #B3D9D9; ">{{ $user['name'] }}</td>
                            <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['monthly_salary'] }}</td>
                            <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['tds'] }}</td>
                            <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ round($user['extra_salary']) }}</td>
                             <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['expense_claims'] }}</td>
                              <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['net_salary'] }}</td>
                            <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['daysInMonth'] }}</td>
                            <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['pay_days'] }}</td>
                            <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['totalWorkingDays'] }}</td>
                            <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['extraWorkingDays'] }}</td>
                            <!--<td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['week_off'] }}</td>-->
                            <!--<td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['holidays'] }}</td>-->
                            <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['presentCount'] }}</td>
                            <!--@foreach($user['leaveTypesCount'] as $leaveType => $count)-->
                            <!--    <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $count }}</td>-->
                            <!--@endforeach-->
                            <td style="border: 1px solid black; text-align: center; color:blue; font-weight: 400; ">{{ $user['absentCount'] + (isset($user['leaveTypesCount']['LWP']) ? $user['leaveTypesCount']['LWP'] : 0) }}</td>

                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
