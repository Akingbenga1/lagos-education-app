 <div class="row">
     <div class=" col l10 offset-l1 s12 m12">
     {{--{{HTML::image("/Images/Logos/LagosSeal.jpg", 'Lagos State Logo', array('class' => 'col s12 l3 m12 responsive-img','height' => '30', 'width' => '30') )}}--}}
         <img src="{{asset('/Images/Logos/LagosSeal.jpg')}}" class="col s12 l3 m12 responsive-img" alt="Lagos State Logo" height="30"  width = '30' />
     <div class="col s12 l6 m12">
       <p class=""> LAGOS STATE MINISTRY OF EDUCATION </p>
       <p class="">CONTINUOUS ASSESMENT REPORT
             FOR SENIOR SECONDARY SCHOOLS<br />
            <b> IJAIYE HOUSING ESTATE SENIOR GRAMMAR SCHOOL , ALAKASHI</b>
        </p>
     </div>
     {{--{{HTML::image("/Images/Logos/IjayeSchool.jpg", 'Ijaye Senior Grammar School Logo',--}}
      {{--array('class' => 'col l3 s12 m12 responsive-img', 'height' => '30', 'width' => '30')  )}}--}}
         <img src="{{asset('/Images/Logos/IjayeSchool.jpg')}}" class="col l3 s12 m12 responsive-img" alt="Ijaye Senior Grammar School Logo" height="30"  width = '30' />
     </div>
 </div>

 <div class="row">
     <div class="card-panel col l10 offset-l1 s12 m12 ">
        <table class="table">
            <tr>
                <td colspan="11!important" bgcolor="grey">
                <p style="text-align:center!important; height:18px!important;color:white!important;">
                <b> ACADEMIC PERFORMANCE </b> </p> </td>
            </tr>
        </table>
        <table border= 1  class="striped bordered centered responsive-table" >
            {{--<col width="30%" >--}}
           
          <tr class="">
            <th class="center-align"> Subjects </th>
            <th>Cont.<br />Assess.<br/> (40%)</th>
            <th>Exam Score (60%)</th>
            @if($RequestedTerm === 'second term')
            
                <th>2nd term scores (100%)</th>
                <th>1st term scores (100%)</th>
                <th>Cumm.<br />scores (200%)</th>
                <th>Weight Average (100%)</th>
            
            @elseif($RequestedTerm === 'third term')
            
                <th>3rd term scores (100%)</th>
                <th>2nd term scores (100%)</th>
                <th>1st term scores (100%)</th>
                <th>Cummu.<br /> scores (300%)</th>
                <th>Weight Average (100%)</th>
            
            @else
            
                <th>Weight Average (100%)</th>
            
            @endif
            <th>Grade </th>
            <th>Teacher Comment </th>
            <th>Teacher Sign </th>
          </tr>
            <?php use App\Http\Controllers\GradeController;use App\Models\OfficialSignatures;use App\Models\Subjects;$AllSubjects = Subjects::all()->toArray();   ?>
            @foreach( $AllSubjects as $allsubjects)
              <tr class="">
                 <td class=""> <b>{{$allsubjects['subject']}}</b> </td>

                 <td>  @foreach($SubjectScore as $subjectscore)  
                        <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                        { echo $subjectscore['cont_assess_40'];}
                              else{ echo'';} ?>  @endforeach</td>

                 <td>  @foreach($SubjectScore as $subjectscore)
                        <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                        { echo $subjectscore['exam_score_60'];}
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
                                                                    $CheckValue = array($ThirdTermWeight,
                                                                        $SecondTermWeight,$FirstTermWeight);
                                                                    $count = 0;
                                                                    foreach ($CheckValue as $key => $value) {
                                                                        if($value !== '' or isset($value)){
                                                                            $count++;
                                                                        }
                                                                        $count = ($count > 1) ? $count:1;
                                                                        # code...
                                                                    }
                                                                    echo (int)$TermWeight =
                                                                     (int)ceil ( ( (int)$ThirdTermWeight + 
                                                                    (int)$SecondTermWeight+ 
                                                                    (int)$FirstTermWeight ) / $count );
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

                 <td class="EveryGradeCommentColumn">@foreach($SubjectScore as $subjectscore)
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
//                                            $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',)).
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

        <table border= 1 class="striped bordered centered responsive-table">
             <tr>
                 <td colspan="3" bgcolor="grey" style="padding:0; margin:0;">
                 <p style="text-align:center;color:white;">
                 <b> GRADE </b> </p> </td>
            </tr>

            <tr>
                <td> A1 75 - 100 (EXCELLENT)</td>
                <td> C4 60 - 64 (CREDIT)</td>
                <td> D7 45 - 49 (PASS)</td>
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

        <div class="row">
            <div class="col l6 s6 m12" >
                <p class=""><b> Class Teacher's Comment:</b></p>

                <b> <input type="text" class="" readonly maxlength="193"
                           value="{{ ( isset($OfficialComments) and  is_array($OfficialComments)
            and !empty($OfficialComments['classteacher']) )? $OfficialComments['classteacher']:'' }}" /> </b>

                <input type="text" class="" readonly maxlength="177" />
                <p class="">Signature & Date:
                    @if(isset($OfficialComments) and is_array($OfficialComments)  )
                        <?php
                        if($OfficialComments['classteachersignatureid'] === 34)
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
//                                HTML::image("/Images/Signatures/".
//                                    $SignatureRecord->toArray()['signatureimage'],
//                                    $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                        'title' =>  $SignatureOwner
//                                    ))
                                ' <img src="'. asset("/Images/Signatures/".
                                    $SignatureRecord->toArray()['signatureimage']). '" class="col s12 l3 m12 responsive-img" alt="'. $SignatureOwner . '" />':
                                'Error! Cannot find signature';
                        }
                        ?>
                        <b> {{ ( isset($OfficialComments) and is_array($OfficialComments) and !empty($OfficialComments['classteacherdate']) )?
                    date('d/m/Y', strtotime($OfficialComments['classteacherdate'])):'' }}</b>
                    @else
                        &nbsp;
                    @endif
                </p>

            </div>
            <div class="col l6 s6 m12" >
                <p class=""><b>Principal's Comments:</b></p>

                <b> <input type="text" class="" readonly maxlength="193"
                value="{{ ( isset($OfficialComments) and is_array($OfficialComments)
                and !empty($OfficialComments['principal']) )? $OfficialComments['principal']:'' }}" /></b>

                <input type="text" class="" readonly maxlength="177" />
                <p class="">Signature & Date:
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
                                            $SignatureRecord->toArray()['signatureimage']). '" class="col s12 l3 m12 responsive-img" alt="'. $SignatureOwner . '" />':
                                        'Error! Cannot find signature';
                                  }
                        ?>
                        <b> {{ ( isset($OfficialComments) and is_array($OfficialComments) and !empty($OfficialComments['principaldate']) )?
                         date('d/m/Y', strtotime($OfficialComments['principaldate'])):'' }}</b>
                     @else&nbsp;
                     @endif
                </p>
            </div>
            <div class="col l6 s6 m12">
                <p class=""><b>Parent's Signature:</b>
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
//                                HTML::image("/Images/Signatures/".
//                                    $SignatureRecord->toArray()['signatureimage'],
//                                    $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                        'title' =>  $SignatureOwner
//                                    ))
                                ' <img src="'. asset("/Images/Signatures/".
                                    $SignatureRecord->toArray()['signatureimage']). '" class="col s12 l3 m12 responsive-img" alt="'. $SignatureOwner . '"/>':
                                'Error! Cannot find signature';
                        }
                        ?>
                    @else
                        &nbsp;
                    @endif
                </p>

                <p class="">
                    Date: <b> {{ ( isset($OfficialComments) and is_array($OfficialComments) and !empty($OfficialComments['parentdate']) )?
                                 date('d/m/Y', strtotime($OfficialComments['parentdate'])):'' }}</b>
                </p>
            </div>
            <div class="col l6 s6 m12">
                    <b>School Stamp:</b>
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
//                                $SignatureRecord->toArray()['officialsignatureid'], array('class' => '80',
//                                'title' =>  $SignatureOwner, 'width' =>  120, 'height' =>  100))
                              ' <img src="'. asset("/Images/Signatures/".
                                  $SignatureRecord->toArray()['signatureimage']). '" class="col s12 l3 m12 responsive-img" alt="'. $SignatureOwner . '" width = "120"  height = "100"/>':
                                'Error! Cannot find signature';
                        ?>
            </div>
        </div>
     </div>

 </div>