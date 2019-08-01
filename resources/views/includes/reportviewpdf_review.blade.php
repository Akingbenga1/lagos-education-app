<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
         <link type="text/css" media="all" rel="stylesheet" href="{{ asset('CSS/ReportPagePDF.css')}}">
         
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
         
         </head>


         <body>
         <?php   use App\Models\Subjects; use App\Http\Controllers\GradeController; ?>
        
<div style='max-width:1400px; margin-left: 40px;'>

    @php
    //dd($ThisStudent);
    @endphp
 
<!-- <h3> Student report </h3> -->
  @if(isset($ThisStudent) )


             @if(isset($SubjectScore) and isset($RequestedTerm) and !empty($RequestedTerm)
                and !empty($SubjectScore))



            <div class="PageHeading">
                 <div class = 'LagosLogo'>
                     <img src="{{asset('/Images/Logos/LagosSeal.jpg')}}" class="col s12 l3 m12 responsive-img"   width= '150px' height = '150px'  />
                 </div>
                 <div class="SealStatement"> 
                     Lagos State  Ministry Of Education<br />
                     (Education District 1) <br />
                    Continuous Assessment Report For<br /><br />
                     @php
                         //dd($ThisStudent)
                     @endphp
                     {{  isset($ThisStudent) ?  strtoupper($ThisStudent ) : " "  }} <br />
                      {{ isset($School) ? strtoupper($School->school_name ) : " " }}<br />
                  </div>
                  <div class="ThisTermName">
                      {{ isset($AcademicYear) ? strtoupper($AcademicYear->academic_year ) : " " }}
                      {{ isset($RequestedTerm) ? strtoupper($RequestedTerm->term ) : " " }} Term
                      {{ isset($ClassLevel) ? strtoupper($ClassLevel ) : " " }}
                      {{ isset($ClassSubDivision) ? strtoupper($ClassSubDivision ) : " " }}
                   </div>
                <div class = 'IjayeLogo'>
                    <img src="{{asset('/Images/Logos/IjayeSchool.jpg')}}" class="col s12 l3 m12 responsive-img"  width= '150px' height = '150px' />
                </div>
            </div>

            <br />
            <br />

        <table  class="StudentDataTable" >
         <tr>
             <td colspan="4" bgcolor="grey" >
             <p style="text-align:center; height:18px;color:white;">
             <b> STUDENT'S PERSONAL DATA </b> </p> </td>
        </tr>

        <tr>
             <td > Name <font size=2>(Surname First )</font> </td>
             <td colspan="3"> 
            {{  isset($ThisStudent) ?  strtoupper(  $ThisStudent) : " "  }}
            </td>
        </tr>

        <tr>
             <td> SPIN </td>
             <td colspan="3">
            </td> 
        </tr>

         <tr>
             <td> Student Admission Number</td>
             <td colspan="3">
                    {{  isset($StudentAdmissionNumber) ? $StudentAdmissionNumber : " " }}
            </td> 
        </tr>

        <tr>
             <td> Date of Birth  Name</td>
             <td colspan="3">

             </td>
        </tr>

        <tr>
             <td> Sex</td>
             <td colspan="3">

             </td>

            
        </tr>

        <tr>
             <td> School</td>
             <td colspan="3">
                 {{ isset($School) ? ucwords($School->school_name ) : " " }}
             </td>
             
             
        </tr>

        <tr>
             <td> Class</td>
             <td>

             </td>
             <td bgcolor="grey" > <font color=white> SCHOOL CODE </font> </td>
             <td bgcolor="grey" ><font color=white> 807 </font> </td>
        </tr>

        <tr>
             <td> Education District</td>
             <td>01</td>
             <td bgcolor="grey"> <font color=white> ED. ZONE </font> </td>
             <td bgcolor="grey"><font color=white> 03</font></td>
        </tr>
    
        </table>


        @if($RequestedTerm === 'third term')
            <table border= 1  class='AbsenceDataTable AdjustThirdTerm'>
        @else
            <table border= 1  class="AbsenceDataTable" >
        @endif

         <tr>
             <td colspan="3" bgcolor="grey" style="padding:0; margin:0;">
             <p style="text-align:center; height:18px;color:white;">
             <b> ATTENDANCE </b> </p> </td>
        </tr>

    <tr>
        <td style="text-align:center;"> No. of <br /> Days School Opened</td>
        <td style="text-align:center;"> No. of <br /> Days  Present</td>
        <td style="text-align:center;"> No. of <br /> Days Absent </td>
    </tr>

        <tr>
             <td><b>  </b></td>
             <td><b>  </b></td>
             <td><b>  </b></td>
        </tr>     
</table>

<br />

        @if($RequestedTerm === 'third term')
            <table border= 1  class='TermDurationTable AdjustThirdTerm'>
        @else
            <table border= 1 class="TermDurationTable" >
        @endif

     <tr>
             <td colspan="3" bgcolor="grey" style="padding:0; margin:0;">
             <p style="text-align:center; height:18px;color:white;">
             <b> TERMINAL DURATION (..................) WEEKS </b> </p> </td>
        </tr>

    <tr>
        <td style="text-align:center;"> Term Begins</td>
        <td style="text-align:center;"> Term Ends</td>
        <td style="text-align:center;"> Next Term</td>
    </tr>
    <tr>
        <td>
            <b>{{ ( isset($TermDuration) and 
                                 is_array($TermDuration) and !empty($TermDuration['termbegins']) )?  
                                 date('d/m/Y', strtotime($TermDuration['termbegins'])): "&nbsp;" }}
            </b> 
        </td>
        <td>
            <b> {{ ( isset($TermDuration) and 
                                 is_array($TermDuration) and !empty($TermDuration['termend']) )?  
                                 date('d/m/Y', strtotime($TermDuration['termend'])): "&nbsp;" }}
            </b> 
        </td>
        <td>
            <b>{{ ( isset($TermDuration) and 
                                 is_array($TermDuration) and !empty($TermDuration['nexttermbegins']) )?  
                                 date('d/m/Y', strtotime($TermDuration['nexttermbegins'])): "&nbsp;" }}
            </b>
        </td>
    </tr>    
</table>

        

            @if($RequestedTerm === 'second term')
            <table border= 1  class="AcademicTable w3-table w3-striped">
            <tr>
            
               <td colspan="9" bgcolor="grey">
                 <p style="text-align:center; height:18px;color:white;">
                 <b> ACADEMIC PERFORMANCE </b> </p> </td>
            
            @elseif($RequestedTerm === 'third term')
            <table border= 1  class="ThirdTermAcademicTable" >
            <tr>
            
               <td colspan="10" bgcolor="grey">
                 <p style="text-align:center; height:18px;color:white;">
                 <b> ACADEMIC PERFORMANCE </b> </p> </td>
            
            @else
            <table border= 1  class="AcademicTable">
            <tr>
            
               <td colspan="6" bgcolor="grey">
                 <p style="text-align:center; height:18px;color:white;">
                 <b> ACADEMIC PERFORMANCE </b> </p> </td>
            
            @endif
                 
             </tr>
             <tr class="AcademicHeading">
            <th class="SubjectsStyle EveryFirstColumn"> Subjects </th>
            <th>Exam Score </th>
            @if($RequestedTerm === 'second term')
            
                <th>2nd term scores (100%)</th>
                <th>1st term scores (100%)</th>
                <th>Cummu.<br/>scores (200%)</th>
                <th>Weight Average (100%)</th>
            
            @elseif($RequestedTerm === 'third term')
            
                <th>3rd term scores (100%)</th>
                <th>2nd term scores (100%)</th>
                <th>1st term scores (100%)</th>
                <th>Cummu. <br/>scores (300%)</th>
                <th>Weight Average (100%)</th>
            
            @else
            
                <th>Weight Average (100%)</th>
            

            @endif
            <th>Grade </th>
            <th>Teacher Comment </th>
            <th>Teacher Sign </th>
          </tr>
            <?php  $AllSujects = Subjects::all();   ?>
            @foreach( $AllSujects as  $allsubjects)
                @if( !is_null( $SubjectScore->get($allsubjects->subject_slug) ) )
                    @php
                    $student_exam_score =  property_exists($SubjectScore->get($allsubjects->subject_slug)->get("0"), "exam_score") ? $SubjectScore->get($allsubjects->subject_slug)->get("0")->exam_score  : " ";
                    @endphp
                   <tr class="AcademicCells" style="page-break-inside: avoid;">

                  <td class="EveryFirstColumn">

                      <b> {{trim($allsubjects['subject'])}}</b>

                  </td>

                 <td>
                     @php

                     //dd($SubjectScore->get($allsubjects->subject_slug));
                     @endphp
                     {{ $student_exam_score  }}

                 </td>

                 <td>
                     {{  $student_exam_score }}

                 </td>

                 @if($RequestedTerm === 'second term')
            
                      <td>

                      </td>

                      <td>

                      </td>

                      <td>

                      </td>

                      <td>

                      </td>

                      <td>

                      </td>

                      <td>

                       </td>
            

                 @elseif($RequestedTerm === 'third term')
            
                    <td>

                 </td>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>


                    </td>

                     <td>

                </td>

                     <td>

                      </td>

                     <td>

                 </td>
            
            @else
            <!-- This place is for first term -->
                   <td>
                       {{  is_array(GradeController::getGrade((int)$student_exam_score))
                       ?GradeController::getGrade((int)$student_exam_score)['Grade']:'' }}

                 </td>
                   <td>

                       {{  is_array(GradeController::getGrade((int)$student_exam_score))
                      ?GradeController::getGrade((int)$student_exam_score)['Comment']:'' }}

                    </td>
                <td>

                </td>
            
            @endif
            </tr>
                @endif
            @endforeach

        </table> 
        
     @if($RequestedTerm === 'third term')
         <table border= 1 class="GradeTable AdjustThirdTermBelow" >
    @else
          <table border= 1 class="GradeTable">
    @endif

                         <tr>
                             <td colspan="3" bgcolor="grey" style="padding:0; margin:0;">
                             <p style="text-align:center; height:18px;color:white;">
                             <b> GRADE </b> </p> </td>
                        </tr>

                     <tr>
                        <td> A1 75 - 100 (EXCELLENT)</td>
                        <td> C4 60 - 64 (CREDIT)</td>
                        <td> D7 45 - 59 (PASS)</td>


                    </tr>

                     <tr>
                        <td> B2 70 - 74 (VERY GOOD) </td>
                        <td> C5 55 - 59 (CREDIT)</td>
                        <td> D8 40 - 44 (PASS)</td>

                    </tr>

                     <tr>
                        <td> B3 65 - 69 (GOOD) </td>
                        <td> C6 50 - 54 (CREDIT)</td>
                        <td> F9 0 -  39 (FAIL)</td>
                    </tr>
                </table>

<br />
<!--
 @if($RequestedTerm === 'third term')
         <table border= 1  class="SportTable AdjustThirdTermSport" sstyle="page-break-inside: always; page-break-after: none;page-break-before: always;">
    @else
        <table border= 1 class="SportTable" sstyle="page-break-inside: always; page-break-after: none;page-break-before: always;"  >
@endif
 
         <tr>
             <td colspan="9" bgcolor="grey" style="padding:0; margin:0;">
             <p style="text-align:center; height:18px;color:white;">
             <b>SPORT</b> </p> </td>
        </tr>

    <tr>
        <td> <b>EVENTS</b>: </td>
        <td>Indoor Games</td>
        <td>Ball Games</td>
        <td>Combative Games</td>
        <td>Track</td>
        <td>Jumps</td>
        <td>Throw</td>
        <td>Swimming</td>
        <td>Weight lifting</td>
    </tr>
        <tr>
             <td><b>LEVEL ATTAINED</b>:</td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
        </tr>    


         <tr>
            <td  colspan="9"> Comment...................................................................................................................................................................................</td>
        </tr>    
</table>

<br />

     @if($RequestedTerm === 'third term')
         <table border= 1  class="ClubTable" id='AdjustThirdTermClub' sstyle="page-break-inside: always; page-break-after: none;page-break-beore: always;">
    @else
    <table border= 1 class="ClubTable" sstyle="page-break-inside: always; page-break-after: none;page-break-beore: always;">
    @endif
        <tr>
            <td colspan="3" bgcolor="grey" style="padding:0; margin:0;">
            <p style="text-align:center; height:18px;color:white;">
            <b>CLUBS, YOUTH ORGANISATION, E.T.C</b> </p> </td>
        </tr>
        <tr>
           <td>Organisation</td>
           <td>Office Held</td>
           <td>Significant Contributions</td>
        </tr>
        <tr>
           <td>&nbsp; </td>
           <td> </td>
           <td> </td>
        </tr>
    </table> -->

     @if($RequestedTerm === 'third term')
         <div class="ReportBottom AdjustThirdTermReportBottom">
     @else
        <div class="ReportBottom">
     @endif

    </div>

        @else
          <b> 'You do not have any result at the moment'</b>
        @endif

    @else
       Your record cannot be found on the system.
     @endif


     </body>
     </html>