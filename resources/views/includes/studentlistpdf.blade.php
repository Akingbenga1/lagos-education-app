<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <link type="text/css" media="all" rel="stylesheet" href="{{ asset('CSS/bootstrap.min.css')}} ">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>         
    </head>
    <body>
    
            <div style='max-width:1400px; margin-left: 80px;'>
             
                    <!-- <h3> Student report </h3> -->
                    @if(isset($ThisStudentTerm) and is_array($ThisStudentTerm) and is_array($ChoosenTerm))  
                         <table border = "1" style="border-collapse:collapse;" >
                                <tr>
                                  <td colspan="5" bgcolor="grey" style="padding:0; margin:0;">
                                         <p style="text-align:center;color:white;"> 
                                         <b> List of students in  {{ $ChoosenTerm['Class']." "
                                             .strtoupper($ChoosenTerm['SubClass'])." ". $ChoosenTerm['Year'] . "/". ($ChoosenTerm['Year'] + 1 ) . " session"  }}   
                                             </b> 
                                         </p>
                                  </td>
                                </tr>

                                <tr>
                                  <td style="text-align:center,font-weight:bold;" ><b>S/N</b> </td>
                                   <td style="text-align:center;"> Name of Student</td>
                                  <td style="text-align:center;"> Admission Number</td>
                                  <td style="text-align:center;"> Login Email </td>
                                  <td style="text-align:center;"> Login Password </td>
                                </tr>
                            
                                @foreach($ThisStudentTerm as $EachStudentTerm)
                                    <tr> 
                                        <td  style='text-align:center;'> {{ $EachStudentTerm["SerialNumber"]}} </td>
                                        <td  style='text-align:center;'><b> {{ $EachStudentTerm["FullName"]}} </b></td>
                                        <td  style='text-align:center;'> {{ $EachStudentTerm["AdmissionNumber"]}}</td>
                                        <td  style='text-align:center;' >{{ $EachStudentTerm["LoginEmail"]}}</td>
                                        <td style='text-align:center;font-family: Inconsolata'> <b> {{ $EachStudentTerm["LoginPassword"]}}  </b></td>
                                    </tr>
                                @endforeach
                        </table> 
    
                    @else
                        Your record cannot be found on the system.
                    @endif
            </div>
    </body>
</html>