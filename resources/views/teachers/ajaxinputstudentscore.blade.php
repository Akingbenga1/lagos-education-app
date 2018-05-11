@extends('layouts.main')

@section('division_container')

    <?php use App\Http\Controllers\GradeController;use App\Models\OfficialSignatures;?>
      @if(is_array($ThisStudent) and  is_array($RequestedDetails))
        <h3 class="center-align"> {{ trim ($ThisStudent['user_belong']['surname']. " ". $ThisStudent['user_belong']['firstname']
        . " ". $ThisStudent['user_belong']['middlename'])}}'s  Score Sheet Page </h3>
            {{-- <div class=" row ScoreInputUpdate">
                 <span class="ReportStudentClass"></span> signature and choose dates.
            </div> --}}
          <div class="row ScoreStudentInfo">
            <div class="col s3">
              Admission Number: <b> {{$ThisStudent['school_admission_number'] }} </b>
               <input type="hidden" name="" value="{{$ThisStudent['studentid']}}" class="ScoreStudentId" />
            </div>
            <div class="col s3">
               Year:  <b> {{$RequestedDetails['Year']}} </b>
               <input type="hidden" name="" value="{{$RequestedDetails['Year']}}" class="ScoreYear" />
            </div>
            <div class="col s3">
              Class: <b>{{$RequestedDetails['Class'] ." ". strtoupper($RequestedDetails['SubClass'])}} </b>
              <input type="hidden" name="" value="{{$RequestedDetails['Class']}}" class="ScoreClass" />
              <input type="hidden" name="" value="{{strtoupper($RequestedDetails['SubClass'])}}" class="ScoreSubClass" />
              </div>
            <div class="col s3">

              Term: <b>{{$RequestedDetails['Term']}}</b>
              <input type="hidden" name="" value="{{ucwords($RequestedDetails['Term'])}}" class="ScoreTerm" />
              </div>

          </div>

          {{-- {{ Form::open(array( 'route' => 'save-student-attendance' ,'files' => true, 'method'=> 'post',
                               'class'=>'AjaxScoreAttendance')) }} --}}
    <div class="row">
            <div class="col s6 m6 l6">
            <form class="AjaxScoreAttendance" action="{{URL::route('save-student-attendance')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data">
              {{-- <p class="ScoreAbsenceTableInfo"> Click on the space with light red background and type the number of days</p> --}}
              <table border= 1  class="ScoreAbsenceTable table table-responsive" >
                <tr>
                  <td colspan="3" bgcolor="grey" style="padding:0; margin:0;">
                         <p style="text-align:center;color:white;"> <b> ATTENDANCE </b>
                         </p>
                  </td>
                </tr>
                {{-- <tr>
                  <td style="text-align:center;"> No. of <br /> Days School Opened</td>
                  <td style="text-align:center;"> No. of <br /> Days  Present</td>
                  <td style="text-align:center;"> No. of <br /> Days Absent </td>
                </tr> --}}
                <tr>
                  <td>
                    <div class="input-field">
                       <input type="text" name="SchoolOpen" value= "{{ ( isset($Attendance) and is_array($Attendance) and
                              ($Attendance['schoolopen'] !== 500)   ) ? $Attendance['schoolopen'] : '' }}" class="SchoolOpen"  />
                      <label for="email" class="center-align"> Days School Opened</label>
                    </div>
                  </td>
                  <td>
                    <div class="input-field">
                       <input type="text" name="DaysPresent" value= "{{( isset($Attendance) and is_array($Attendance)  and
                              ($Attendance['dayspresent'] !== 500)  )? $Attendance['dayspresent']:'' }}" class="DaysPresent"  id="DaysPresent" />
                      <label for="DaysPresent" class="center-align"> Days  Present </label>
                    </div>
                  </td>
                   <td>
                     <div class="input-field">
                       <input type="text" id="DaysAbent" name="DaysAbent" value= "{{(isset($Attendance) and  is_array($Attendance) and
                              ($Attendance['dayspresent'] !== 500) )? $Attendance['daysabent']:'' }}" class="DaysAbent"  />
                      <label for="DaysAbent" class="center-align"> Days  Present </label>
                    </div>
                  </td>
                </tr>
              </table>
              <button  type="submit" class="SaveAttendanceButton btn btn-large col s12 m12 l12 gradient-45deg-red-pink" > Save Attendance Record </button>
                {{ csrf_field() }}
        </form>
      </div>
          {{-- {{ Form::open(array( 'route' => 'save-student-termduration' ,'files' => true, 'method'=> 'post',
                          'class'=>'AjaxScoreTermDuration')) }} --}}
        <div class="col s6 m6 l6">
          <form class="AjaxScoreTermDuration" action="{{URL::route('save-student-termduration')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" class="col s6 m6 l6" enctype="multipart/form-data">
              {{-- <p class="ScoreTermDurationTableInfo"> Click the space with light red background and choose date required</p> --}}
              <table border= 1 class="table ScoreTermDurationTable table-responsive" >
                  <tr>
                    <td colspan="3" bgcolor="grey" style="padding:0; margin:0;">
                          <p style="text-align:center;color:white;">
                            <b> TERMINAL DURATION (..................) WEEKS </b>
                          </p>
                    </td>
                  </tr>

                  {{-- <tr>
                      <td style="text-align:center;"> Term Begins</td>
                      <td style="text-align:center;"> Term Ends</td>
                      <td style="text-align:center;"> Next Term Begins</td>
                  </tr> --}}
                  <tr>
                        <td>
                            <div class="input-field">
                                <input type="text" name="TermBegins" id="TermBegins"  value= "{{ ( isset($TermDuration) and
                                       is_array($TermDuration) and !empty($TermDuration['termbegins']) )?
                                       date('d/m/Y', strtotime($TermDuration['termbegins'])):'' }}" class="TermBegins"  />
                                <label for="TermBegins" class="center-align"> Term Begins </label>
                            </div>
                        </td>
                        <td>
                          <div class="input-field">
                            <input type="text" id="TermEnd" name="TermEnd" value= "{{ ( isset($TermDuration) and
                                   is_array($TermDuration) and !empty($TermDuration['termend']) )?
                                   date('d/m/Y', strtotime($TermDuration['termend'])):'' }}" class="TermEnd"  />
                            <label for="TermEnd" class="center-align"> Term Begins </label>
                          </div>
                        </td>
                        <td>
                          <div class="input-field">
                                <input type="text" id="NextTermBegins"  name="NextTermBegins" value= "{{ ( isset($TermDuration) and
                                       is_array($TermDuration) and !empty($TermDuration['nexttermbegins']) )?
                                       date('d/m/Y', strtotime($TermDuration['nexttermbegins'])):'' }}" class="NextTermBegins" />
                                <label for="NextTermBegins" class="center-align">  Next Term Begins </label>
                            </div>
                        </td>
                  </tr>
              </table>
              <button  type="submit" class="SaveTermDurationButton gradient-45deg-red-pink gradient-shadow  btn btn-large col s12 m12 l12" > Save Term Duration Record </button>
              {{ csrf_field() }}
         </form>
       </div>
    </div>

        {{-- {{ Form::open(array( 'route' => 'save-this-student-score' ,'files' => true, 'method'=> 'post',
        'class'=>'AjaxScoreForm')) }} --}}
          <form class="AjaxScoreForm row" action="{{URL::route('save-this-student-score')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data">
            <div class="ReportScoreInput"> </div>
                  <div class="ScoreInputTable">
                     <table  class="AcademicTable table bordered striped table-responsive col s10 m10 l10 offset-s1 offset-m1 offset-l1" >
                       <col width="30%" >
                       <tr>
                           <td colspan="9" bgcolor="grey">
                           <p style="text-align:center; height:18px;color:white;">
                           <b> Student Score Table </b> </p> </td>
                      </tr>
                        <tr class="">
                          <th class=""> Subjects </th>
                          <th>Continouns<br /> Assesment (40%)</th>
                          <th>Examination<br/> Score (60%)</th>
                          <th> Delete Score </th>
                          <th>Weight Average (100%)</th>
                          <th>Grade </th>
                          <th>Teacher Comment </th>
                          <th>Teacher Signature </th>

                         </tr>
                              @foreach( $AllSubjects as $allsubjects)
                                   <tr class="">
                                       <td class="EveryFirstColumn">
                                          <b>{{$allsubjects['subject']}}</b>
                                          <input type="hidden" class="SubjectIdSave" name="SubjectId"
                                            value= "{{$allsubjects['subjectid']}}" />
                                       </td>
                                       <td>
                                            <?php $ContAssessValue= ''; ?>
                                            @foreach($SubjectScore as $subjectscore)
                                              <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                              { $ContAssessValue =  $subjectscore['cont_assess_40'];} else{ echo'';} ?>
                                            @endforeach
                                            <input type="text" name="CAScore" value= "{{$ContAssessValue}}" class="CAScore input__no-border blue_border" />
                                            <div class="MessageIndicator"></div>
                                            <div class="ExamPanelProgess" class="col s4 offset-s4" >
                                                <br />
                                                <div class="progress light-blue  lighten-4">
                                                    <div class="indeterminate light-blue darken-4"></div>
                                                </div>
                                            </div>
                                        </td>
                                       <td>
                                            <?php $ExamValue= ''; ?>
                                             @foreach($SubjectScore as $subjectscore)
                                                <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                { $ExamValue =  $subjectscore['exam_score_60'];} else{ echo'';} ?>
                                             @endforeach
                                            <input type="text" name="ExamScore" value= "{{$ExamValue}}" class="ExamScore input__no-border blue_border" />
                                              <div class="MessageIndicator"></div>
                                              <div class="ExamPanelProgess" class="col s4 offset-s4" >
                                                  <br />
                                                  <div class="progress light-blue  lighten-4">
                                                      <div class="indeterminate light-blue darken-4"></div>
                                                  </div>
                                              </div>
                                       </td>
                                       <td>
                                           @foreach($SubjectScore as $subjectscore)
                                               @if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                <button  type="button" class="DeleteScoreButton btn btn-small btn-floating gradient-45deg-red-pink  gradient-shadow   tooltipped pulse" data-position="right" data-tooltip="DELETE SCORE"  > <i class="material-icons"> delete </i> </button>
                                              @endif
                                            @endforeach
                                            <input   type="hidden" name="SubjectId" value= "{{$allsubjects['subjectid']}}" />
                                            <input   type="hidden" name="DeleteUrl"
                                                     value= "{{URL::route('delete-this-student-score')}}" />
                                           <div class="DeletePanelProgess" class="col s4 offset-s4" >
                                               <br />
                                               <div class="progress light-blue  lighten-4">
                                                   <div class="indeterminate light-blue darken-4"></div>
                                               </div>
                                           </div>
                                      </td>
                                       <td class="ScoreInputTermWeight">
                                          <?php $TermWeight= ''; ?>
                                               @foreach($SubjectScore as $subjectscore)
                                                <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                { echo $TermWeight = (int)$subjectscore['cont_assess_40']
                                                                  + (int)$subjectscore['exam_score_60'];
                                                }
                                                else{ echo ''; } ?>
                                             @endforeach
                                      </td>

                                         <td>
                                              @foreach($SubjectScore as $subjectscore)
                                                  <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                                { //echo $TermWeight;
                                                   echo is_array(GradeController::getGrade((int)$TermWeight))
                                                     ?GradeController::getGrade((int)$TermWeight)['Grade']:'';
                                                   }
                                                    else{ echo '';} ?>
                                               @endforeach
                                          </td>

                                      <td class="EveryGradeCommentColumn">
                                            @foreach($SubjectScore as $subjectscore)
                                            <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                              {echo is_array(GradeController::getGrade((int)$TermWeight))
                                                     ?GradeController::getGrade((int)$TermWeight)['Comment']:'';
                                              }                 // Grades::find($subjectscore['gradeid'])->toArray()['grade'] ;}
                                                else{ echo'';} ?>
                                              @endforeach
                                        </td>

                                      <td class='TeacherSignature'>
                                      @foreach($SubjectScore as $subjectscore)
                                            <?php if($subjectscore['subjectid'] === $allsubjects['subjectid'])
                                              {
                                                 if($subjectscore['teachersignatureid'] === 34)
                                                  {
                                                      echo "<a class='TeacherSignatureDefault'>add signature</a>";
                                                  }
                                                  else
                                                  {
                                                     $SignatureRecord = OfficialSignatures::with('UserBelong')
                                                                       ->find($subjectscore['teachersignatureid']);
                                                     //var_dump($SignatureRecord); //die();
                                                    $SignatureOwner = !is_null($SignatureRecord)?
                                                    $SignatureRecord->toArray()['user_belong']['surname']." ".
                                                    $SignatureRecord->toArray()['user_belong']['firstname']." ".
                                                    $SignatureRecord->toArray()['user_belong']['middlename']
                                                    :'Name Unavailable';

                                                    echo   !is_null($SignatureRecord)?
//                                                    HTML::image("/Images/Signatures/".
//                                                    $SignatureRecord->toArray()['signatureimage'],
//                                                    $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                                      'title' =>  $SignatureOwner,
//                                                     ))
                                                        '<img src="'. asset("/Images/Signatures/".
                                    $SignatureRecord->toArray()['signatureimage']). '" alt="'. $SignatureRecord->toArray()['officialsignatureid'] . '" />' :
                                                    'Error! Cannot find signature';
                                                  }
                                              }
                                          ?>
                                      @endforeach
                                      </td>
                                    <!-- <td> <button  type="submit" class="AjaxButton" > Add score </button> </td>-->

                                   </tr>
                              @endforeach
                       </table>
                  </div>
                   {{-- <button  type="submit" class="btn-lg " id="SaveAllScoreButtonDown" > Save All </button> --}}

                   <input type="hidden" name="SaveAllUrl" value= "{{URL::route('save-all-this-student-score')}}"
                    class="SaveAllUrl" />
                    <input type="hidden" name="SaveEachScoreUrl" value= "{{URL::route('save-each-student-score')}}"
                     class="SaveEachScoreUrl" />
              {{ csrf_field() }}
          </form>


          <div style="margin-top: 100px;">
          </div>
           <form class="OfficialCommentsForm row" action="{{URL::route('save-official-comments')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data" style="border: 1px solid black;">
                <h5 class="center-align"> Teacher and Parent Consent </h5>
             <div class="row">
                  <div class="col s4 m4 l4 input-field">
                       <textarea  name="ClassTeacherComment" id="ClassTeacherComment" class="ClassTeacherComment">{{ ( is_array($OfficialComments) and !empty($OfficialComments['classteacher']) )? $OfficialComments['classteacher']:'' }}</textarea>
                       <label for="ClassTeacherComment"> Class Teacher's Comment </label>
                  </div>
                  <div class="col s4 m4 l4 input-field">
                        <p> Class Teacher's Signature </p>
                        <p class="TeacherSignature" id="ClassTeacherSignature">
                            @if(isset($OfficialComments) and is_array($OfficialComments) and count($OfficialComments) > 0   )
                               <?php if($OfficialComments['classteachersignatureid'] === 34)
                                      {
                                          echo "<a class='TeacherSignatureDefault'>add signature</a>";
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
//                                          HTML::image("/Images/Signatures/".
//                                          $SignatureRecord->toArray()['signatureimage'],
//                                          $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                          'title' =>  $SignatureOwner))
                                              '<img src="'. asset("/Images/Signatures/".
                                                  $SignatureRecord->toArray()['signatureimage']). '" alt="'. $SignatureOwner . '" />':
                                          'Error! Cannot find signature';
                                      }
                              ?>
                            @else
                              &nbsp;
                              <a class='TeacherSignatureDefault'><i class='material-icons'>add_circle_outline</i> add signature</a>
                            @endif
                        </p>
                  </div>
                  <div class="col s4 m4 l4 input-field">
                          <input type="text" name="ClassTeacherDate" id="ClassTeacherDate" class="ClassTeacherDate"
                                  value="{{ ( is_array($OfficialComments) and !empty($OfficialComments['classteacherdate']) )?
                                  date('d/m/Y', strtotime($OfficialComments['classteacherdate'])):'' }}"  />
                          <label for="ClassTeacherDate"> Date for Class Teacher  </label>
                  </div>

              </div>
              <div class="row">

                    <div class="input-field col s4 l4 m4">
                          <textarea name="PrincipalCommentText" id="PrincipalCommentText"  class="PrincipalCommentText">{{ ( is_array($OfficialComments)
                             and !empty($OfficialComments['principal']) )? $OfficialComments['principal']:'' }}</textarea>
                          <label for="PrincipalCommentText"> Principal's Comment</label>
                    </div>
                    <div class="col s4 m4 l4 input-field">
                      <p> Principal's Signature </p>
                      <p class="TeacherSignature" id="PrincipalSignature">
                        @if(isset($OfficialComments) and is_array($OfficialComments)  )
                          <?php if($OfficialComments['principalsignatureid'] === 34)
                                  {
                                    echo "<a class='TeacherSignatureDefault'>add signature</a>";
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
                                        '<img src="'. asset("/Images/Signatures/".
                                            $SignatureRecord->toArray()['signatureimage']). '" alt="'. $SignatureOwner . '" />':
                                        'Error! Cannot find signature';
                                  }
                          ?>
                        @else
                         &nbsp;
                          <a class='TeacherSignatureDefault'><i class='material-icons'>add_circle_outline</i> add signature</a>
                        @endif
                      </p>
                    </div>
                    <div class="col s4 m4 l4 input-field">
                       <input type="text" name="PrincipalDate" class="PrincipalDate" id="PrincipalDate"
                                 value="{{ ( is_array($OfficialComments) and !empty($OfficialComments['principaldate']) )?
                                 date('d/m/Y', strtotime($OfficialComments['principaldate'])):'' }}" />
                       <label for="PrincipalDate">Date for  Principal  </label>
                    </div>
              </div>
              <div class="row">
                  <div class="col s4 m4 l4">
                      <p> Parent's Signature </p>
                          <p  class="TeacherSignature"  id="ParentSignature">
                            @if(isset($OfficialComments) and is_array($OfficialComments)  )
                              <?php if($OfficialComments['parentsignatureid'] === 34)
                                     {
                                        echo "<a class='TeacherSignatureDefault'><i class='material-icons'>add_circle_outline</i>add signature</a>";
                                     }
                                    else
                                     {
                                        $SignatureRecord = OfficialSignatures::with('UserBelong')
                                                                       ->find($OfficialComments['parentsignatureid']);
                                        $SignatureOwner = !is_null($SignatureRecord)?
                                        $SignatureRecord->toArray()['user_belong']['surname'].
                                        $SignatureRecord->toArray()['user_belong']['firstname'].
                                        $SignatureRecord->toArray()['user_belong']['middlename']
                                          :'Name Unavailable';
                                        echo !is_null($SignatureRecord)?
//                                              HTML::image("/Images/Signatures/".
//                                              $SignatureRecord->toArray()['signatureimage'],
//                                              $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                              'title' =>  $SignatureOwner))
                                            '<img src="'. asset("/Images/Signatures/".
                                                $SignatureRecord->toArray()['signatureimage']). '" alt="'. $SignatureOwner . '" />':
                                              'Error! Cannot find signature';
                                      }
                              ?>
                            @else
                              &nbsp;
                               <a class='TeacherSignatureDefault'><i class='material-icons'>add_circle_outline</i> add signature</a>
                            @endif
                          </p>
                  </div>
                  <div class="input-field col s4 m4 l4">
                       <input type="text" name="ParentDate" class="ParentDate" id="ParentDate"
                        value="{{ ( is_array($OfficialComments) and !empty($OfficialComments['parentdate']) )?
                        date('d/m/Y', strtotime($OfficialComments['parentdate'])):'' }}"  />
                        <label for="ParentDate"> Date for  Parent </label>
                  </div>
                  <div class="col s4 m4 l4">
                      <p> School Stamp : </p>
                      <p class="TeacherSignature">
                                 @if(isset($OfficialComments) and is_array($OfficialComments)  )
                                  <?php if($OfficialComments['classteachersignatureid'] === 34)
                                        {

                                          echo "<a class='TeacherSignatureDefault'><i class='material-icons'>add_circle_outline</i>add signature</a>";
                                        }
                                      else
                                        {

                                            $SignatureRecord = OfficialSignatures::with('UserBelong')
                                                                ->find(64);
                                            $SignatureOwner = !is_null($SignatureRecord)?
                                            $SignatureRecord->toArray()['user_belong']['surname'].
                                            $SignatureRecord->toArray()['user_belong']['firstname'].
                                            $SignatureRecord->toArray()['user_belong']['middlename']
                                            :'Name Unavailable';
                                            echo !is_null($SignatureRecord)?
//                                                  HTML::image("/Images/Signatures/".
//                                                  $SignatureRecord->toArray()['signatureimage'],
//                                                  $SignatureRecord->toArray()['officialsignatureid'], array('class' => '',
//                                                  'title' =>  $SignatureOwner))
                                                '<img src="'. asset("/Images/Signatures/".
                                                    $SignatureRecord->toArray()['signatureimage']). '" alt="'. $SignatureOwner . '" />':
                                                  'Error! Cannot find signature';
                                        }
                                  ?>
                              @else
                                &nbsp;
                                 <a class='TeacherSignatureDefault'><i class='material-icons'>add_circle_outline</i> add signature</a>

                              @endif
                              </p>
                  </div>
              </div>

               {{ csrf_field() }}

  <button  type="submit" class="col s12 m12 l12 btn btn-large gradient-45deg-red-pink  gradient-shadow SaveOfficialComments" > Save All Comments and Signature </button>

    @endif
  </form>




     <div class="SignaturesPool" class="w3-text-black" title="Hello World!">
                  <p  class="CloseSignaturePanel"> X Close this panel </p>
                  @if(isset($OfficialSignatures) and is_array($OfficialSignatures))
                       <b> Search for anybody on this table: </b> <input type="text" id="search" class="w3-text-black" placeholder="Search this table" /> <br /><br />
                      <table border = "1" class="ChangeRolesTable">
                        <tr class="w3-grey w3-text-black">
                          <th> S/N </th>
                          <th> Name and Signature </th>
                        </tr>
                        <?php $Count = 1; ?>
                      @foreach($OfficialSignatures as $officialSignatures)
                      <tr class="w3-text-blue">
                          <td> {{$Count++}} </td>
                          <td>
                              <div class="">
                                {{--{{  HTML::image("/Images/Signatures/".$officialSignatures['signatureimage'],--}}
                                    {{--$officialSignatures['officialsignatureid'],--}}
                                    {{--array('class' => '')) }}--}}
                                  {{----}}
                                  <img src='{{asset("/Images/Signatures/".
                                 $officialSignatures['signatureimage'])}}'  alt="{{$officialSignatures['officialsignatureid']}}"  /><br />
                             <span class="">  Owner of Signature => {{ $officialSignatures['user_belong']['surname'] . " ".
                                   $officialSignatures['user_belong']['firstname'] . " ".
                                   $officialSignatures['user_belong']['middlename'] }}</span>
                                </div>
                            </td>
                        </tr>
                      @endforeach
                      </table>
                  @endif
                   <p  class="CloseSignaturePanel gradient-45deg-red-pink  gradient-shadow"> X Close This Panel </p>
     </div>

     <div class="fixed-action-btn"  style="top: 150px; right: 54px;">
         <a class="btn-floating btn-large gradient-45deg-red-pink  gradient-shadow  tooltipped pulse"  data-position="left" data-delay="50" data-tooltip="Back to Teachers Home"  href="{{URL::route('teachers-home-page')}}" >
             <i class="material-icons">keyboard_backspace</i>
         </a>
     </div>
     <div class="fixed-action-btn"  style="top: 50px; right: 54px;">
         <a class="btn-floating btn-large gradient-45deg-red-pink  gradient-shadow tooltipped pulse FreeReload"  data-position="left" data-delay="50" data-tooltip="Reload ..."  href="{{URL::route('teachers-home-page')}}" >
             <i class="material-icons">refresh</i>
         </a>
     </div>
@stop
