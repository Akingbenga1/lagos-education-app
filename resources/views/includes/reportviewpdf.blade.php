<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
         <link type="text/css" media="all" rel="stylesheet" href="{{ asset('CSS/ReportPagePDF.css')}}">
         
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
         
         </head>


         <body>
        
<div style='max-width:1400px; margin-left: 80px;'>

<?php use App\Http\Controllers\GradeController;use App\Models\OfficialSignatures;use App\Models\Subjects;$AllSujects = Subjects::all()->toArray();   ?>
<!-- <h3> Student report </h3> -->
  @if(isset($ThisStudent) and is_array($ThisStudent) and !empty($ThisStudent))  
         <!--  <br />  Hello, <b> {{ isset($ThisStudent['user_belong']['firstname'])?$ThisStudent['user_belong']['firstname']:'student'}}</b>
                    {{ isset($ThisStudent['user_belong']['middlename'])?$ThisStudent['user_belong']['middlename']:''}}
                    {{ isset($ThisStudent['user_belong']['surname'])?$ThisStudent['user_belong']['surname']:''}} -->
         
             @if(isset($SubjectScore) and isset($RequestedTerm) and !empty($RequestedTerm) and  is_array($SubjectScore)
                and !empty($SubjectScore))  
                   <!--  this is your report sheet page for 
                     <b>{{ $SubjectScore[0]['termname']. " " . $SubjectScore[0]['year']}}</b>
                     when you were in <b>  {{  $SubjectScore[0]['classname']}}</b>
         
            Please check you report below and download it as pdf below <br />

            <br />
            <br />
            -->
            <div class="PageHeading">
                 <div class = 'LagosLogo'>
                     <img src="{{asset('/Images/Logos/LagosSeal.jpg')}}" class="col s12 l3 m12 responsive-img"   />
                 </div>
                 <div class="SealStatement"> 
                     LAGOS STATE MINISTRY OF EDUCATION <br />
                    CONTINUOUS ASSESSMENT REPORT<br />
                     FOR SENIOR SECONDARY SCHOOLS<br />
                     IJAIYE HOUSING ESTATE SENIOR GRAMMAR SCHOOL , ALAKASHI
                  </div>
                  <div class="ThisTermName">
                     <strong>
                            {{ $SubjectScore[0]['classname']." " .strtoupper($SubjectScore[0]['termname']). 
                                " " .$SubjectScore[0]['year']."/2016"
                            }} 
                    </strong>
                   </div>
                <div class = 'IjayeLogo'>
                    {{--{{HTML::image("/Images/Logos/IjayeSchool.jpg", --}}
                                                {{--'',--}}
                {{--array('width' => '150px', 'height' => '150px') )}}--}}
                    <img src="{{asset('/Images/Logos/IjayeSchool.jpg')}}" alt="Ijaye Senior Grammar School Logo" width = '150px' height = '150px' class="col s12 l3 m12 responsive-img"   /></div>
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
                            {{is_array($ThisStudent['user_belong'])?
                                $ThisStudent['user_belong']['surname'] . " ".  
                                $ThisStudent['user_belong']['middlename']. " ".
                                $ThisStudent['user_belong']['firstname']: ''
                            }}
            </td>
        </tr>

        <tr>
             <td> SPIN </td>
             <td colspan="3">
            </td> 
        </tr>

         <tr>
             <td> STUDENT ADMISSION NUMBER</td>
             <td colspan="3">
                    {{isset($ThisStudent['school_admission_number'])?
                            $ThisStudent['school_admission_number']: ''
                    }}
            </td> 
        </tr>

        <tr>
             <td> Date of Birth  Name</td>
             <td colspan="3">
                     {{is_array($ThisStudent['user_belong'])?
                                $ThisStudent['user_belong']['date_of_birth'] : ''
                     }}
             </td>
        </tr>

        <tr>
             <td> Sex</td>
             <td colspan="3">
                        {{is_array($ThisStudent['user_belong'])?
                                $ThisStudent['user_belong']['sex'] : ''
                        }}
             </td>

            
        </tr>

        <tr>
             <td> School</td>
             <td colspan="3"> I . H. E. S. G. S </td>
             
             
        </tr>

        <tr>
             <td> Class</td>
             <td>
              {{is_array($SubjectScore)?
                                $SubjectScore[0]['classname']." ". strtoupper($SubjectScore[0]['class_subdivision'] ): ''
                        }}
             </td>
             <td bgcolor="grey" > <font color=white> SCHOOL CODE </font> </td>
             <td bgcolor="grey" ><font color=white> 807 </font> </td>
        </tr>

        <tr>
             <td> Ed. District</td>
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
             <td><b> {{( isset($Attendance) and is_array($Attendance) and 
                              ($Attendance['dayspresent'] !== 500) )?$Attendance['schoolopen']: "&nbsp;" }} </b></td>
             <td><b>{{( isset($Attendance) and is_array($Attendance) and 
                              ($Attendance['dayspresent'] !== 500) )? $Attendance['dayspresent']: "&nbsp;" }}</b></td>
             <td><b> {{(isset($Attendance) and  is_array($Attendance) and 
                              ($Attendance['dayspresent'] !== 500) )? $Attendance['daysabent']: "&nbsp;" }}</b></td>
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
            
               <td colspan="10" bgcolor="grey">
                 <p style="text-align:center; height:18px;color:white;">
                 <b> ACADEMIC PERFORMANCE </b> </p> </td>
            
            @elseif($RequestedTerm === 'third term')
            <table border= 1  class="ThirdTermAcademicTable" >
            <tr>
            
               <td colspan="11" bgcolor="grey">
                 <p style="text-align:center; height:18px;color:white;">
                 <b> ACADEMIC PERFORMANCE </b> </p> </td>
            
            @else
            <table border= 1  class="AcademicTable">
            <tr>
            
               <td colspan="7" bgcolor="grey">
                 <p style="text-align:center; height:18px;color:white;">
                 <b> ACADEMIC PERFORMANCE </b> </p> </td>
            
            @endif
                 
             </tr>
             <tr class="AcademicHeading">
            <th class="SubjectsStyle EveryFirstColumn"> Subjects </th>
            <th>Cont.<br /> Assess.<br />(40%)</th>
            <th>Exam Score (60%)</th>
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

            @foreach( $AllSujects as $allsubjects)
            <tr class="AcademicCells" style="page-break-inside: avoid;">
                  <td class="EveryFirstColumn"> <b> {{trim($allsubjects['subject'])}}</b> </td>

                 <td>  @foreach($SubjectScore as $subjectscore)  
                        <?php if($subjectscore['subjectid'] === $allsubjects['subjectid']){ echo $subjectscore['cont_assess_40'];}
                              else{ echo'';} ?>  @endforeach</td>

                 <td>  @foreach($SubjectScore as $subjectscore)
                        <?php if($subjectscore['subjectid'] === $allsubjects['subjectid']){ echo $subjectscore['exam_score_60'];}
                             else{ echo'';} ?> @endforeach</td>

                 @if($RequestedTerm === 'second term')
            
                  <td>@foreach($SubjectScore as $subjectscore)
                             <?php  if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                            { echo (int)$weight = (int)$subjectscore['cont_assess_40'] + (int)$subjectscore['exam_score_60'];}
                             else{ echo '';} ?> 
                      @endforeach</td>


                <td>@foreach($FirstTermSubjectScore as $firsttermsubjectscore)
                             <?php if($firsttermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                            { 
                                            echo $firsttermweight = (int)$firsttermsubjectscore['cont_assess_40'] 
                                            + (int)$firsttermsubjectscore['exam_score_60'];
                                   
                                
                            }
                             else{ echo'';} ?> 
                      @endforeach</td>

                 <td> 
                  @foreach($FirstTermSubjectScore as $firsttermsubjectscore)
                             <?php  if($firsttermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                            { 
                                 foreach ($SubjectScore as  $subjectscore) 
                                    { 
                                        if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                        {
                                           echo  $cummulative = (int)$subjectscore['cont_assess_40'] 
                                           + (int)$subjectscore['exam_score_60']
                                            + (int)$firsttermsubjectscore['cont_assess_40'] 
                                            + (int)$firsttermsubjectscore['exam_score_60'];
                                        }
                                   }
                                }
                             else{ echo'';} ?> 
                      @endforeach </td> 


                <td> @foreach($FirstTermSubjectScore as $firsttermsubjectscore)
                             <?php if($firsttermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                            {
                                 foreach ($SubjectScore as  $subjectscore) 
                                    {
                                        if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                        { $TermWeight = '';
                                         echo  (int)$TermWeight = (int)ceil ( (   (   (int)$subjectscore['cont_assess_40'] 
                                           + (int)$subjectscore['exam_score_60']
                                            + (int)$firsttermsubjectscore['cont_assess_40'] 
                                            + (int)$firsttermsubjectscore['exam_score_60'])  / 2 ));
                                        }
                                     }
                                        }
                             else{ echo'';} ?> 
                      @endforeach 
                </td>
              <td>@foreach($FirstTermSubjectScore as $firsttermsubjectscore)
                     <?php if($firsttermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                            { //echo $TermWeight;
                                foreach ($SubjectScore as  $subjectscore) 
                                        {
                                            if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                {
                                                    echo is_array(GradeController::getGrade((int)$TermWeight))
                                                    ?GradeController::getGrade((int)$TermWeight)['Grade']:'';
                                                 }       
                                        }   
                                }       
                             else{ echo '';} ?>
                      @endforeach</td> 

                       <td>@foreach($FirstTermSubjectScore as $firsttermsubjectscore)
                        <?php if($firsttermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                                {
                                     foreach ($SubjectScore as  $subjectscore) 
                                        {
                                            if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                {
                                                     echo is_array(GradeController::getGrade((int)$TermWeight))
                                                        ?GradeController::getGrade((int)$TermWeight)['Comment']:'';
                                                }       
                                        }   
                                }       // Grades::find($subjectscore['gradeid'])->toArray()['grade'] ;}
                             
                             else{ echo'';} 
                        ?> 
                    @endforeach</td> 
            
            @elseif($RequestedTerm === 'third term')
            
                 <td>@foreach($SubjectScore as $subjectscore)
                             <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                            { echo $ThirdTermWeight = (int)$subjectscore['cont_assess_40'] +
                                                     (int)$subjectscore['exam_score_60'];}
                             else{ echo'';} ?> 
                      @endforeach</td> 
                <td>@foreach($SecondTermSubjectScore as $subjectscore)
                             <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                            { echo $SecondTermWeight = (int)$subjectscore['cont_assess_40'] +
                                                       (int)$subjectscore['exam_score_60'];}
                             else{ echo'';} ?> 
                      @endforeach</td>
                <td>@foreach($FirstTermSubjectScore as $subjectscore)
                             <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                            { echo $FirstTermWeight = (int)$subjectscore['cont_assess_40'] +
                                                      (int)$subjectscore['exam_score_60'];}
                             else{ echo'';} ?> 
                      @endforeach</td> 
                <td>@foreach($FirstTermSubjectScore as $firsttermsubjectscore)
                        <?php if($firsttermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                                     {
                                        foreach ($SecondTermSubjectScore as  $secondtermsubjectscore) 
                                            {
                                                if($secondtermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                                                { 
                                                      foreach ($SubjectScore as  $subjectscore) 
                                                         {
                                                            if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                                { 
                                                                    echo $ThirdTermCummulative = (int)$ThirdTermWeight + 
                                                                    (int)$SecondTermWeight +
                                                                     (int)$FirstTermWeight;
                                                                 }
                                                          }
                                                }
                                            }
                                    }
                             else{ echo'';} ?> 
                      @endforeach</td> 

                <td>@foreach($FirstTermSubjectScore as $firsttermsubjectscore)
                             <?php if($firsttermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                                     { 
                                        foreach ($SecondTermSubjectScore as  $secondtermsubjectscore) 
                                            {
                                                if($secondtermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                                                { 
                                                      foreach ($SubjectScore as  $subjectscore) 
                                                         {
                                                            if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                                { 
                                                                    echo (int)$TermWeight =
                                                                     (int)ceil ( ( (int)$ThirdTermWeight + 
                                                                    (int)$SecondTermWeight+ 
                                                                    (int)$FirstTermWeight ) / 3 );
                                                                 }
                                                          }
                                                  }
                                            }
                                      }
                             else{ echo'';} ?> 
                      @endforeach </td>

                      <td>@foreach($FirstTermSubjectScore as $firsttermsubjectscore)
                       <?php if($firsttermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                            {  foreach ($SecondTermSubjectScore as  $secondtermsubjectscore) 
                                            {
                                                if($secondtermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                                                {
                                                    foreach ($SubjectScore as  $subjectscore) 
                                                     {
                                                         if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                            {
                                                                echo is_array(GradeController::getGrade((int)$TermWeight))
                                                                 ?GradeController::getGrade((int)$TermWeight)['Grade']:'';
                                                            } 
                                                      }   
                                                }   
                                           }   
                                }       
                             else{ echo '';} ?> 
                      @endforeach</td>

                 <td>@foreach($FirstTermSubjectScore as $firsttermsubjectscore)
                        <?php if($firsttermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                                { foreach ($SecondTermSubjectScore as  $secondtermsubjectscore) 
                                            {
                                                if($secondtermsubjectscore['subjectid'] === $allsubjects['subjectid'])
                                                {
                                                     foreach ($SubjectScore as  $subjectscore) 
                                                     {
                                                         if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                            {
                                                               echo is_array(GradeController::getGrade((int)$TermWeight))
                                                               ?GradeController::getGrade((int)$TermWeight)['Comment']:'';
                                                            }  
                                                    }     
                                               } 
                                           }
                                }       // Grades::find($subjectscore['gradeid'])->toArray()['grade'] ;}
                             
                             else{ echo'';} 
                        ?> 
                    @endforeach</td>
            
            @else
            <!-- This place is for first term -->
                 <td>@foreach($SubjectScore as $subjectscore)
                             <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                            { echo (int)$TermWeight = (int)((int)$subjectscore['cont_assess_40'] + 
                                                            (int)$subjectscore['exam_score_60']);}
                             else{ echo'';} ?> 
                      @endforeach</td>
                    <td>@foreach($SubjectScore as $subjectscore)
                       <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                            { //echo $TermWeight;                               
                                     echo is_array(GradeController::getGrade((int)$TermWeight))
                                     ?GradeController::getGrade((int)$TermWeight)['Grade']:'';                                                  
                                }       
                             else{ echo '';} ?> 
                      @endforeach</td>

                 <td>@foreach($SubjectScore as $subjectscore)
                        <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                {                                   
                                  echo is_array(GradeController::getGrade((int)$TermWeight))
                                  ?GradeController::getGrade((int)$TermWeight)['Comment']:'';                                              
                                }       // Grades::find($subjectscore['gradeid'])->toArray()['grade'] ;}
                             
                             else{ echo'';} 
                        ?> 
                    @endforeach</td>
            
            @endif
                 <td>
                      @foreach($SubjectScore as $subjectscore)  
                                    <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                      { 
                                         if($subjectscore['teachersignatureid'] === 34)
                                          {
                                              echo "";
                                          }
                                          else
                                          {
                                             $SignatureRecord = OfficialSignatures::with('UserBelong')
                                                               ->find($subjectscore['teachersignatureid']);
                                             //var_dump($SignatureRecord); //die();      
                                            $SignatureOwner = !is_null($SignatureRecord)?
                                            $SignatureRecord->toArray()['user_belong']['surname'].
                                            $SignatureRecord->toArray()['user_belong']['firstname'].
                                            $SignatureRecord->toArray()['user_belong']['middlename']
                                            :'Name Unavailable';

                                            echo   !is_null($SignatureRecord)?
//                                            HTML::image("/Images/Signatures/".
//                                            $SignatureRecord->toArray()['signatureimage'],
//                                            $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                             ))
                                                ' <img src="'. asset("/Images/Signatures/".
                                                    $SignatureRecord->toArray()['signatureimage']). '" class="col s12 l3 m12 responsive-img" alt="'. $SignatureOwner . '" />':
                                            'Error! Cannot find signature';
                                          }
                                      }
                                  ?>  
                              @endforeach      
                 </td>   
            </tr>   
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
     <br/><br />
        <p class="ClassTeacherCommenStatement">Class Teacher's Comment:
         <b  class="ClassTeacherCommentText">{{ ( is_array($OfficialComments) 
        and !empty($OfficialComments['classteacher']) )? $OfficialComments['classteacher']:'' }}</b></p> <br />
       <p><b  class="ClassTeacherCommentSecond">&nbsp;</b>&nbsp;&nbsp;Signature & Date:
         @if(isset($OfficialComments) and is_array($OfficialComments)  )  
                        <?php if($OfficialComments['classteachersignatureid'] === 34)
                                  {
                                    echo "";
                                  }
                              else
                                  {
                                    $SignatureRecord = OfficialSignatures::with('UserBelong')
                                                       ->find($OfficialComments['classteachersignatureid']);
                                    $SignatureOwner = !is_null($SignatureRecord)?
                                    $SignatureRecord->toArray()['user_belong']['surname']." ".
                                    $SignatureRecord->toArray()['user_belong']['firstname']." ".
                                    $SignatureRecord->toArray()['user_belong']['middlename']
                                    :'Name Unavailable';
                                    echo !is_null($SignatureRecord)?
//                                        HTML::image("/Images/Signatures/".
//                                        $SignatureRecord->toArray()['signatureimage'],
//                                        $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                        'title' =>  $SignatureOwner
//                                             ))
                                        ' <img src="'. asset("/Images/Signatures/".
                                            $SignatureRecord->toArray()['signatureimage']). '" class="col s12 l3 m12 responsive-img" alt="'. $SignatureOwner . '" />':
                                        'Error! Cannot find signature';
                                  } 
                        ?>
                        <b> {{ ( is_array($OfficialComments) and !empty($OfficialComments['classteacherdate']) )? 
                         date('d/m/Y', strtotime($OfficialComments['classteacherdate'])):'' }}</b>
                       @else
                         &nbsp;
                      @endif
       </p><br />
        <p class="ClassTeacherCommenStatement">Principal's Comments:
         <b  class="ClassTeacherCommentText">{{ ( is_array($OfficialComments) 
        and !empty($OfficialComments['principal']) )? $OfficialComments['principal']:'' }}</b></p> <br /> 
         <b  class="ClassTeacherCommentSecond">&nbsp;</b>
        <span class="ClassTeacherSignatureDate">&nbsp;&nbsp;Signature & Date:
         @if(isset($OfficialComments) and is_array($OfficialComments)  )  
                        <?php if($OfficialComments['principalsignatureid'] === 34)
                                  {
                                    echo "";
                                  }
                              else
                                  {
                                    $SignatureRecord = OfficialSignatures::with('UserBelong')
                                                       ->find($OfficialComments['principalsignatureid']);
                                    $SignatureOwner = !is_null($SignatureRecord)?
                                    $SignatureRecord->toArray()['user_belong']['surname']." ".
                                    $SignatureRecord->toArray()['user_belong']['firstname']." ".
                                    $SignatureRecord->toArray()['user_belong']['middlename']
                                    :'Name Unavailable';
                                    echo !is_null($SignatureRecord)?
//                                        HTML::image("/Images/Signatures/".
//                                        $SignatureRecord->toArray()['signatureimage'],
//                                        $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                        'title' =>  $SignatureOwner
//                                             ))
                                        ' <img src="'. asset("/Images/Signatures/".
                                            $SignatureRecord->toArray()['signatureimage']). '" class="col s12 l3 m12 responsive-img" alt="'. $SignatureOwner . '" />'
                                      :
                                        'Error! Cannot find signature';
                                  } 
                        ?>
                        <b> {{ ( is_array($OfficialComments) and !empty($OfficialComments['principaldate']) )? 
                         date('d/m/Y', strtotime($OfficialComments['principaldate'])):'' }}</b>
                       @else
                         &nbsp;
                      @endif

        </span><br />
         <span class="ParentSignatureStatement">Parent's Signature:
          @if(isset($OfficialComments) and is_array($OfficialComments)  )  
                        <?php if($OfficialComments['parentsignatureid'] === 34)
                                  {
                                    echo "";
                                  }
                              else
                                  {
                                    $SignatureRecord = OfficialSignatures::with('UserBelong')
                                                       ->find($OfficialComments['parentsignatureid']);
                                    $SignatureOwner = !is_null($SignatureRecord)?
                                    $SignatureRecord->toArray()['user_belong']['surname']." ".
                                    $SignatureRecord->toArray()['user_belong']['firstname']." ".
                                    $SignatureRecord->toArray()['user_belong']['middlename']
                                    :'Name Unavailable';
                                    echo !is_null($SignatureRecord)?
//                                        HTML::image("/Images/Signatures/".
//                                        $SignatureRecord->toArray()['signatureimage'],
//                                        $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                        'title' =>  $SignatureOwner
//                                             ))
                                        ' <img src="'. asset("/Images/Signatures/".
                                            $SignatureRecord->toArray()['signatureimage']). '" class="col s12 l3 m12 responsive-img" alt="'. $SignatureOwner . '" />'
                                        :
                                        'Error! Cannot find signature';
                                  } 
                        ?>
                       @else
                         &nbsp;
                      @endif
         </span>
         <span class="ParentDate">Date: <b> {{ ( is_array($OfficialComments) and !empty($OfficialComments['parentdate']) )? 
                         date('d/m/Y', strtotime($OfficialComments['parentdate'])):'' }}</b>  </span>
        <div class="d">
                         School Stamp:        
                        <?php 
                          $SignatureRecord = OfficialSignatures::with('UserBelong')->find(64);
                          $SignatureOwner = !is_null($SignatureRecord)?
                          $SignatureRecord->toArray()['user_belong']['surname'].
                          $SignatureRecord->toArray()['user_belong']['firstname'].
                          $SignatureRecord->toArray()['user_belong']['middlename']
                          :'Name Unavailable';
                          echo !is_null($SignatureRecord)?
//                                HTML::image("/Images/Signatures/".
//                                $SignatureRecord->toArray()['signatureimage'],
//                                $SignatureRecord->toArray()['officialsignatureid'], array('height' => '50',
//                                'title' =>  $SignatureOwner))
                              ' <img src="'. asset("/Images/Signatures/".
                                  $SignatureRecord->toArray()['signatureimage']). '" class="col s12 l3 m12 responsive-img" alt="'. $SignatureOwner . '" />'
            :
                                'Error! Cannot find signature';                                              
                        ?>
                       </div>
    </div>
    
        @else
          <b> 'You do not have any result at the moment'</b>
        @endif

    @else
       Your record cannot be found on the system.
     @endif


     </body>
     </html>