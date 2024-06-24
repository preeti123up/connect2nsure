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
                  <td style="width:300px; border: 1px solid black;text-align: center;font-weight: 600;" rowspan="2" colspan="1">{{ $branch }}</td>               
                  @for($i = 1; $i <= $maxDays; $i++)
                  <?php
                $timestamp = mktime(0, 0, 0, $month, $i, $year);
                $dayName = date("l", $timestamp);
                $formattedDate = date("d M Y", $timestamp);
                 ?>
               @if($date)
               @if($i === (int)$date)
               <td style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; " colspan="2">{{ $dayName }} {{$formattedDate}}</td>
               @endif
               @else
                   @if($current_month == $month && $i <= date('j'))
             <td style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; " colspan="2">{{ $dayName }} {{$formattedDate}}</td>
                @endif
                @if($current_month !== $month)
               <td style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600; " colspan="2">{{ $dayName }} {{$formattedDate}}</td>
                @endif
        
               @endif
            
                   @endfor 
                   <td style="border: 1px solid black; text-align: center; color: #034b5d; font-weight: 600;" colspan="{{ count($employeeDetailsArray); }}">Total</td>
                </tr>
              <tr>
                @for($i = 1; $i <= $maxDays; $i++)
             
                @if($date)
                @if($i === (int)$date)
                <td style="border: 1px solid black; text-align: center; background: #9FA91F; font-weight: 600;" colspan="1">In</td>
                <td style="border: 1px solid black; text-align: center; background: #9FA91F; font-weight: 600;" colspan="1">Out</td>                
                @endif
                @else
                 @if($current_month == $month && $i <= date('j'))
                 <td style="border: 1px solid black; text-align: center; background: #9FA91F; font-weight: 600;" colspan="1">In</td>
                  <td style="border: 1px solid black; text-align: center; background: #9FA91F; font-weight: 600;" colspan="1">Out</td>  
                @endif
                @if($current_month !== $month)
                  <td style="border: 1px solid black; text-align: center; background: #9FA91F; font-weight: 600;" colspan="1">In</td>
                  <td style="border: 1px solid black; text-align: center; background: #9FA91F; font-weight: 600;" colspan="1">Out</td>  
                @endif
                @endif
           
                  @endfor 
                  @foreach($employeeDetailsArray as $key => $value)
                  @if($date)
                @if($key === "total_hours_worked")
                <td style="width: 150px; border: 1px solid black; text-align: center; background: yellow; font-weight: 600;">
                  Woring Hours
              </td>
                @endif
                @else
                <td style="width: 150px; border: 1px solid black; text-align: center; background: yellow; font-weight: 600;">
                  {{ $key }}
              </td>
                @endif
                 
              @endforeach
              
                  
                </tr>
            </thead>
            <tbody>
			   @foreach($data as $employeeID => $attendance)
          <tr>
            <?php  $exploded = explode('#', $employeeID); ?>
              <td style="width: 200px; text-align: center; color:blue">{{  htmlspecialchars($exploded[1])  }}</td>
              {{-- <td style="width: 200px; text-align: center; color:blue">{{ htmlspecialchars($employeeID) }}</td> --}}
              @foreach($attendance as $dayAttendance)
              @if(!$loop->last)
                  @if(is_array($dayAttendance))
                      @if($dayAttendance['status'] == "P")
                      <td style="width: 120px; border: 1px solid black; text-align: center;  @if(count($dayAttendance['leave'])) background-color:{{ $dayAttendance['color']}}; @else  color:{{ $dayAttendance['text-color'] }}; @endif">{{ $dayAttendance['clockIn']?htmlspecialchars($dayAttendance['clockIn']):''  }} 
                        <small>
                          {{ count($dayAttendance['leave']) && $dayAttendance['leave']['half_day_type'] === "first_half"?$dayAttendance['leave']['type']:'' }}
                        </small>
                        <small>
                          {{ count($dayAttendance['leave']) && $dayAttendance['leave']['type'] === "SL" && $dayAttendance['leave']['start_time']?" (SL)":'' }}
                        </small>
                       
                      </td>
                      <td style="width: 120px;  border: 1px solid black; text-align: center; @if(count($dayAttendance['leave'])) background-color:{{ $dayAttendance['color']}}; @else  color:{{ $dayAttendance['text-color'] }}; @endif">{{ $dayAttendance['clockOut']?htmlspecialchars($dayAttendance['clockOut']):''  }} 
                        <small>
                          {{ count($dayAttendance['leave']) && $dayAttendance['leave']['half_day_type'] === "second_half"?  $dayAttendance['leave']['type'] :'' }}
                        </small>
                        <small>
                          {{ count($dayAttendance['leave']) && $dayAttendance['leave']['type'] === "SL" && $dayAttendance['leave']['end_time']?" (SL)":'' }}
                        </small>
                      </td>
                      @endif
                      @if($dayAttendance['status'] == "Week Off" || $dayAttendance['status'] == "H")
<td colspan="2" style="width: 120px;  border: 1px solid black; text-align:center; color:{{ $dayAttendance['color'] }} "> {{ $dayAttendance['status'] }} </td>
                      @endif
                      @if($dayAttendance['status'] == "A")
                      <td colspan="2" style="width: 120px; border: 1px solid black;  text-align: center; background-color:{{ $dayAttendance['color'] }} "> {{ $dayAttendance['status'] }} </td>
                      @endif
                      @if($dayAttendance['status'] == "L")
                      <td colspan="2" style="width: 120px; border: 1px solid black; text-align: center; background-color:{{ $dayAttendance['color'] }} "><small>{{ $dayAttendance['leave']['type'] }}</small> </td>
                      @endif
                      @if($dayAttendance['status'] == "NR")
                      <td colspan="2" style="width: 120px; border: 1px solid black;  text-align: center; background-color:{{ $dayAttendance['color'] }} "> -- </td>
                      @endif
                      
                  @else
                      <td >{{ htmlspecialchars($dayAttendance)}}</td>
                  @endif
                  @else
                  @foreach($dayAttendance as $key => $total)
                  @if($date)
                  @if($key === "total_hours_worked")
                  <td style="border: 1px solid black;  text-align: center;">{{ $total  }}</td>               
                  @endif
                  @else
                  <td style="border: 1px solid black;  text-align: center;">{{ $total  }}</td>
                  @endif
                  @endforeach
                 
                  @endif
              @endforeach
          </tr>
      @endforeach
             
             
            </tbody>
          </table>
    </div>
</body>
</html>
