<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
         {{-- <link type="text/css" media="all" rel="stylesheet" href="{{ asset('CSS/ReportPagePDF.css')}}"> --}}
        
         <style type="text/css">
         {
            margin: 0px;
            padding: 0px;
            font-size: 10pt;
            }
            table.ThirdTermAcademicTable
            {
            /*+placement: -1px -92px;*/
            position: relative;
            left: -1px;
            top: -92px;
            width: 955px;
            }
            .StudentDataTable
            {
            /*+placement: 2px 12px;*/
            position: relative;
            left: 2px;
            top: 12px;
            width: 320px!important;

            }
            .YearLabel
            {
            font-weight: bold;
            color: #7F6E6E;
            }
            h3
            {
            border-bottom: 2px solid #E07575;
            }
            .YearError
            {
            background-color: #E1DDDD;
            padding-top: 11px;
            padding-bottom: 7px;
            padding-left: 8px;
            }
            .TermError
            {
            background-color: #DAF0C2;
            padding-left: 4px;
            padding-top: 11px;
            padding-bottom: 7px;
            /*+placement: -1px -31px;*/
            position: relative;
            left: -1px;
            top: -31px;
            }
            .ClassError
            {
            /*+placement: 302px -69px;*/
            position: relative;
            left: 302px;
            top: -69px;
            background-color: #DFB7B7;
            padding-top: 12px;
            padding-bottom: 6px;
            padding-left: 4px;
            }
            .YearSelect
            {
            background-color: #C5C0C0;
            width: 280px;
            /*+placement: -35px 34px;*/
            position: relative;
            left: -35px;
            top: 34px;
            height: 40px;
            }
            .TermSelect
            {
            /*+placement: 277px -5px !important;*/
            position: relative !important;
            left: 277px !important;
            top: -5px !important;
            background-color: #B6E683 !important;
            width: 280px;
            height: 40px;
            }
            .TermLabel
            {
            /*+placement: 316px -41px;*/
            position: relative;
            left: 316px;
            top: -41px;
            font-weight: bold;
            color: #63BB06;
            }
            body
            {
            margin: 0px;
            padding: 0px;
            background-color: white;
            }
            .ClassSelect
            {
            /*+placement: 582px -51px;*/
            position: relative;
            left: 582px;
            top: -51px;
            background-color: #D4AEAE;
            width: 310px;
            height: 40px;
            }
            .StudentLabel
            {
            /*+placement: -8px -15px;*/
            position: relative;
            left: -8px;
            top: -15px;
            }
            .StudentNumberLabel
            {
            /*+placement: -5px -39px;*/
            position: relative;
            left: -5px;
            top: -39px;
            }
            .ReportButton
            {
            /*+placement: 63px -39px !important;*/
            position: relative !important;
            left: 63px !important;
            top: -39px !important;
            /*+border-radius: 0;*/
            -moz-border-radius: 0;
            -webkit-border-radius: 0;
            -khtml-border-radius: 0;
            border-radius: 0;
            background-color: #EE3135;
            border: 1px solid #EFEFEF;
            color: #EFEBEB;
            }
            .StudentError
            {
            /*+placement: 622px -5px;*/
            position: relative;
            left: 622px;
            top: -5px;
            padding-top: 14px;
            background-color: #D6C8C8;
            padding-bottom: 16px;
            padding-right: 65px;
            padding-left: 38px;
            }
            .LogintError
            {
            padding-top: 14px;
            background-color: #ECBABA;
            padding-bottom: 16px;
            padding-right: 65px;
            padding-left: 9px;
            /*+placement: 2px 7px;*/
            position: relative;
            left: 2px;
            top: 7px;
            border: 1px dashed #E96868;
            }
            .AcademicHeading th
            {
            background-color: #E0DEDE;
            color: #5D5454;
            padding-left: 7px;
            }
            .StudentInput
            {
            /*+placement: 1px -38px;*/
            position: relative;
            left: 1px;
            top: -38px;
            }
            .ClassLabel
            {
            /*+placement: 625px -85px;*/
            position: relative;
            left: 625px;
            top: -85px;
            font-weight: bold;
            color: #ED7070;
            }
            .EmailLabel
            {
            font-weight: bold;
            font-size: 15px;
            color: #C34242;
            }
            .PasswordLabel
            {
            font-weight: bold;
            font-size: 15px;
            color: #C34242;
            }
            .LoginEmail
            {
            /*+placement: 29px -1px;*/
            position: relative;
            left: 29px;
            top: -1px;
            }
            .SignIn
            {
            /*+placement: 175px 14px;*/
            position: relative;
            left: 175px;
            top: 14px;
            /*+border-radius: 0;*/
            -moz-border-radius: 0;
            -webkit-border-radius: 0;
            -khtml-border-radius: 0;
            border-radius: 0;
            background-color: #EE3135;
            border: 0px solid #FFFFFF;
            padding-top: 8px;
            padding-left: 10px;
            padding-bottom: 8px;
            padding-right: 10px;
            font-weight: bold;
            text-align: center;
            font-size: 1.3em;
            color: #FFFFFF;
            }
            .ForgotLabel
            {
            /*+placement: 113px 24px;*/
            position: relative;
            left: 113px;
            top: 24px;
            color: #D37272;
            }
            .AcademicCells td
            {
            padding-left: 5px;
            word-wrap: normal;
            }
            .EveryFirstColumn
            {
            width: 280px;
            height: 20px!important;
            }
            .GradeTable
            {
            /*+placement: 1px -79px;*/
            position: relative;
            left: 1px;
            top: -79px;
            width: 700px;
            }
            .GradeTable tbody tr td
            {
            padding-left: 6px;
            }
            .SportTable tbody tr td
            {
            padding-left: 7px;
            padding-right: 16.5px;
            }
            .SportTable
            {
            /*+placement: 1px -92px;*/
            position: relative;
            left: 1px;
            top: -92px;
            width: 926px;
            }
            .ClubTable
            {
            width: 928px;
            /*+placement: -2px -98px;*/
            position: relative;
            left: -2px;
            top: -98px;
            }
            .StudentDataTable tbody tr td
            {
            padding-left: 6px;
            border: 1px solid black;
            }
            table
            {
            border-collapse: collapse;
            }
            table td
            {
            font-size: smaller;
            }
            .PageHeading
            {
            height: 150px;
            }
            .AbsenceDataTable
            {
            /*+placement: 546px -131px;*/
            position: relative;
            left: 420px;
            top: -131px;
            width: 280px;
            text-align: center;
            }
            .AbsenceDataTable, .AdjustThirdTerm, .TermDurationTable tr
            {
            text-align: center;
            }
            .TermDurationTable
            {
            /*+placement: 546px -116px;*/
            position: relative;
            left: 420px;
            top: -116px;
            width: 280px;
            text-align: center;
            }
            .AdjustThirdTerm
            {
            width: 407px !important;
            }
            .AdjustThirdTermBelow
            {
            width: 954px !important;
            /*+placement: -2px -74px;*/
            position: relative;
            left: -2px;
            top: -74px;
            }
            #AdjustThirdTermClub
            {
            width: 954px !important;
            /*+placement: -3px -164px;*/
            position: relative;
            left: -3px;
            top: -164px;
            }
            .AdjustThirdTermSport
            {
            width: 954px !important;
            /*+placement: -2px -25px;*/
            position: relative;
            left: -2px;
            top: -25px;
            }
            /*.PageHeading
            {
            }*/
            .AcademicTable
            {
            /*+placement: -1px -92px;*/
            position: relative;
            left: -1px;
            top: -92px;
            width: 700px;
            }
            .PageHeading .SealStatement
            {
            text-align: center;
            width: 370px;
            /*+placement: 138px -130px;*/
            position: relative;
            left: 170px;
            top: -130px;
            /*border: 1px solid black;*/
            font-size: 15px;
            font-weight: bold;
            height: 130px;
            }
            .PageHeading .IjayeLogo
            {
            width: 190px;
            height: 193px;
            /*+placement: 755px -245px;*/
            position: relative;
            left: 530px;
            top: -297px;
            text-align: center;
            }
            .PageHeading .LagosLogo
            {
            /*+placement: 0px -1px;*/
            position: relative;
            left: 0px;
            top: -1px;
            }
            .ReportBottom
            {
            /*+placement: -13px -105px;*/
            position: relative;
            left: -13px;
            top: -105px;
            }
            .ClubTable tbody tr td
            {
            padding-left: 10px;
            }
            .ThisTermName
            {
            margin-top: 0;
            /*+placement: 103px -117px;*/
            position: relative;
            left: 0px;
            top: -157px;
            padding-left: 238px;
            color: #000000;
            font-size: 20px!important;
            font-weight: 700;
            }
            div.ReportBottom b.ClassTeacherCommentText
            {
            display: inline-block;
            border-bottom: 1px dashed black;
            width: 650px;
            }
            .ClassTeacherCommentSecond
            {
            width: 551px;
            display: inline-block;
            border-bottom: 1px dashed black;
            }
            div.ReportBottom span.ParentSignatureStatement
            {
            width: 551px;
            display: inline-block;
            }
            div.ReportBottom span.ClassTeacherSignatureDate
            {
            display: inline-block;
            }
         </style>

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
                     <img src="data:image/jpeg;base64,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" class="col s12 l3 m12 responsive-img"   width= '150px' height = '150px'  />
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
                    <img src="{{ isset($School) ? ucwords($School->logo_uri ) : " " }}" class="col s12 l3 m12 responsive-img"  width= '150px' height = '150px' />
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