@extends('layouts.main')

@section('division_container')

  <h3  class="center-align"> Teachers Home Page </h3>

<div class="modal" id="myModal">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="centre-align" > Steps to Register New Student Details </h4>
      </div>
            <div class=" ">
                   <b>Step 1:</b> Register student information here by entering their names and Student admission number.<br />
                   <b>Step 2:</b> Student surname and student first name are compulsory.<br />
                   <b>Step 3:</b> Student Admission Number <b> should be alphanumeric with 4 minimum characters </b>. <br />
                   <b>Step 4:</b> <b> 3-digit is not accepted by the system.</b><br />
            </div>
      <div class="modal-footer">
      <a href="#!" class="btn modal-action modal-close waves-effect waves-green white-text gradient-45deg-semi-dark "><i class="material-icons">check</i> Ok, Got it</a>
    </div>
    </div>
</div>
<div class="modal" id="MySecondModal" >
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="centre-align"> Steps to Add New Students to Term </h4>
      </div>
      <div class="modal-body">
              <div class="ScoreInputProcessInstruct">
                 <b>Step 1:</b> Choose the appropriate term. ( Year, Term, Class, Subclass)<br />
                 <b>Step 2:</b> Start typing student name and choose student <br />
                 <b>Step 3:</b>Click to add student to List <br />
                 <b>Step 4:</b> Click " select all students in list " to add all students in term.
                                Then click ' Add student to term'
          </div>
      </div>
      <div class="modal-footer">
          <a href="#!" class="btn modal-action modal-close waves-effect waves-green white-text gradient-45deg-indigo-purple"><i class="material-icons">check</i> Ok, Got it</a>
      </div>
    </div>
</div>


  <div class="row">
      <ul class="tabs tab-demo z-depth-1">
          <li class="tab col s2 gradient-45deg-semi-dark "><a href="#RegisterStudent" class="active white-text"  > Register Student </a></li>
          <li class="tab col s4 gradient-45deg-red-pink "><a  href="#StudentScoreTable" class="white-text"  > Student Score Table  </a></li>
          <li class="tab col s3 gradient-45deg-indigo-purple"><a href="#AddStudentTerm" class="white-text" >Add Student to Term </a></li>
          <li class="tab col s2 m2 l2 gradient-45deg-deep-orange-orange  "><a href="#QuestionInputPage" class="white-text" > Question Input Page </a></li>
      </ul>
  </div>
  <div class="row">
      <div id="RegisterStudent" class="row col s12">
          <form class="" action="{{URL::route('register-student-details')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data">

                  <h4 class="center-align">  Student Registration</h4>
                  <div class="row">
                    <a href="{{URL::route('edit-student-details-form')}}" class="EditStudentLink btn gradient-45deg-semi-dark gradient-shadow">
                      <i class="material-icons">mode_edit</i>  &nbsp; Edit student details
                    </a>
                   <a type = "button"  class="btn modal-trigger gradient-45deg-semi-dark" href="#myModal">
                         <i class="material-icons">list</i> Instructions
                   </a>
                </div>

               <div class="row">
                  @if(Session::has('AccountCreateInfo') and Session::has('GoodResponse'))
                        @if(Session::get('GoodResponse') == 1 )
                            <div class="RegistrationGoodResponse  s9 offset-s1 light-green lighten-3 white-text center-align"> <h5> {{Session::get('AccountCreateInfo')}}</h5> </div>
                        @elseif(Session::get('GoodResponse') == 0 )
                            <div class="RegistrationBadResponse col s9 offset-s1   red accent-1 white-text center-align"><span> {{Session::get('AccountCreateInfo')}} </span></div>
                        @endif
                  @endif
               </div>

            <div class="row">
                      <div class="col s6 m6 l6">
                        <div class="input-field">
                              <input type="text" name="StudentSurname" required class="" value="{{ old('StudentSurname') }}"  id="StudentSurname"  />
                               @if($errors->has('StudentSurname'))
                                  <span class="LoginError">
                                      {{$errors->first('StudentSurname')}}</span>
                              @endif
                              <label for="StudentSurname" class="center-align">Student Surname:(<b> compulsory </b>)</label>
                        </div>
                    </div>
                    <div class="col s6 m6 l6">
                        <div class="input-field">
                           <input type="text" name="StudentFirstname" value="{{ old('StudentFirstname') }}" required class="" id="StudentFirstname" />
                            @if($errors->has('StudentFirstname'))
                                 <span class="LoginError">{{$errors->first('StudentFirstname')}}</span>
                           @endif
                           <label for="StudentFirstname" class="center-align">Student firstname: (<b> compulsory </b>)</label>
                        </div>
                           <input type="hidden" name="_token" value="<?php use App\Models\Students;echo csrf_token(); ?>">
                    </div>
                    <div class=" col s6 m6 l6">
                        <div class="input-field">
                          <input type="text" name="StudentAdmissionNumber" required class="" id="StudentAdmissionNumber" minlength="4" data-parsley-type="alphanum" value="{{ old('StudentAdmissionNumber') }}"   />
                              @if($errors->has('StudentAdmissionNumber'))
                                  <span class="LoginError">{{$errors->first('StudentAdmissionNumber')}}</span>
                              @endif
                            <label for="StudentAdmissionNumber" class="center-align">Student Admission Number (<b> compulsory </b>)</label>
                        </div>
                    </div>
                    <div class="col s6 m6 l6">
                      <div class="input-field">
                          <input type="text" name="StudentMiddlename"  value="{{ old('StudentMiddlename') }}" class="" id="StudentMiddlename" />
                           @if($errors->has('StudentMiddlename'))
                              <span class="LoginError">{{$errors->first('StudentMiddlename')}}</span>
                           @endif
                          <label for="StudentMiddlename" class="center-align">Student middlename: ( optional )</label>
                      </div>
                    </div>
                    <div class="row">
                      <button  type = "submit"  class="btn btn-large col s12 gradient-45deg-semi-dark gradient-shadow m12 l12">
                         <i class="material-icons">save</i> Register Student
                     </button>
                  </div>
            </div>
            </form>
      </div>
      <div id="StudentScoreTable" class="col s12">
        <div class="" id="profile">
              <div class="">
                    <form class="" action="{{URL::route('post-this-student-details')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data">
                          <h4 class="center-align"> Student Score Sheet</h4>

                          <div class="">
                              <div class="row">
                                        @if(Session::has('ScoreInput'))
                                           <h5 class="center-align red accent-1 white-text "> {{Session::get('ScoreInput')}}</h5>
                                        @endif
                              </div>
                                      {{-- <span class="TopStudentScore"></span> --}}

                              <div class="row">
                                  <div class="col s6 m6 l6">
                                      @if(!empty($AllStudents) and is_array($AllStudents))
                                        @include('includes.choosestudentasarray')
                                      @endif
                                  </div>
                                  <div class="col s4 m4 l4">
                                      <button type="button" class="GetClassYear btn btn-large gradient-45deg-red-pink gradient-shadow" >   <i class="material-icons prefix">search</i> Get Student Class </button>
                                 </div>
                              </div>

                              <div class="ExamPanelProgess" class="row col s6 offset-s4" >
                                  <br />
                                  <div class="progress light-blue  lighten-4">
                                      <div class="indeterminate pink accent-2"></div>
                                  </div>
                              </div>

                              <div class="">
                                <div class="row">
                                    <div class="HighlitStudentClass">
                                        <p class=""> Select Available Student Class </p>
                                        <div class="ReportStudentClass row">

                                        </div>
                                        <div class="TellStudentClass row">
                                        </div>
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    </div>
                                    <div class="ChooseTermScoreSheet GetStudentScoreSheet ">
                                        <p class="text-muted"> Choose Term: (Year, Class and SubClass will be updated automatically)
                                        </p>

                                        <div class="row">
                                            <div class="col s6 m3 l3">
                                                <span class="YearLabel"> Year </span>
                                                <select name = "Year" required class="YearSelect browser-default " >
                                                  <option> -- Year -- </option>
                                                </select>
                                            </div>
                                            <div class="TermDiv col s6 m3 l3">
                                                <span class="TermLabel"> Term </span>
                                                <select name = "TermName" required class="TermSelect browser-default " >
                                                    <option> -- Term -- </option>
                                                    <option value="first term"> First term</option>
                                                    <option value="second term"> Second term</option>
                                                    <option value="third term"> Third term</option>
                                                </select>
                                            </div>

                                            <div class="ClassDiv col s6 m3 l3">
                                                <span class="ClassLabel"> Class </span>
                                                <select name = "Class" required class="ClassSelect browser-default " >
                                                  <option> -- Class-- </option>
                                                </select>
                                            </div>

                                            <div class="SubClassDiv col s6 m3 l3 ">
                                                <span class="SubClassLabel"> SubClass </span>
                                                <select name = "SubClass" required class="SubClassSelect browser-default " >
                                                     <option> -- Class Arm -- </option>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="ChooseTermError row">
                                              <div class="col s6 m3 l3">
                                                  @if($errors->has('Year'))
                                                      <span class="text-danger LoginError">{{$errors->first('Year')}}</span>
                                                  @endif
                                              </div>
                                              <div class="col s6 m3 l3">
                                                   @if($errors->has('TermName'))
                                                      <span class="text-danger LoginError">{{$errors->first('TermName')}}</span>
                                                  @endif
                                              </div>
                                              <div class="col s6 m3 l3">
                                                   @if($errors->has('Class'))
                                                      <span class="text-danger LoginError">{{$errors->first('Class')}}</span>
                                                  @endif
                                              </div>
                                              <div class="col s6 m3 l3">
                                                   @if($errors->has('SubClass'))
                                                      <span class="text-danger LoginError">{{$errors->first('SubClass')}}</span>
                                                  @endif
                                              </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                          </div>
                          <div class="row">
                              <input type = "submit"  value="Open Score Sheet" id="AutoButton" class="col offset-l3 offset-m3 offset-s3 s6 m6 l6 btn btn-large gradient-45deg-red-pink gradient-shadow"  disabled/>
                              <input type = "hidden" value="{{URL::route('get-this-student-class')}}" class="ClassYearURL">
                              <input type="hidden" name="StudentId" class="StudentId" style="float: left;"  />
                          </div>
                      </form>
              </div>

        </div>
      </div>
      <div id="AddStudentTerm" class="col s12">
              {{-- <div class="" id="messages">
              </div> --}}
              <h4 class="center-align"> Add Student to Term </h4>
              <div class="row">
                 <a href="#MySecondModal"  class="btn btn-large  modal-trigger right gradient-45deg-indigo-purple gradient-shadow "  >
                       <i class="material-icons"> list </i>
                      Instructions
                 </a>
               </div>
              <form class="row" action="{{URL::route('post-student-term')}}" method="post" data-parsley-validate   data-parsley-ui-enabled="true" enctype="multipart/form-data" >

                <div class="col s8 m8 l8">
                          <div class="row">
                                <div class="ScoreInputInstruction col s12 m2 l2">
                                     <b> STEP 1</b>:<br /> Choose the appropriate term ( Year, Term, Class, Subclass)
                               </div>
                                <div class="StudentPageChooseTerm col s12 m10 l10">
                                        @include('includes.chooseterm')
                                </div>
                          </div>
                          <div class="StudentTermProcessDiv StudentTermSelect row">
                                <div class="ScoreInputInstruction col s12 m2 l2"> <b> STEP 2:<br /></b>Type Student Name First
                                </div>
                                <div class="col s12 m10 l10">
                                     @if(!empty($AllStudents) and is_array($AllStudents))
                                        @include('includes.choosestudentasarray')
                                     @endif
                                     <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
                                </div>
                          </div>
                          <div class="row">
                              <div  class="MoveRightButton StudentTermProcessDiv StudentTermSelect row">
                                  <div class="MoveRightLabel col s12 m2 l2"> <b> STEP 3:<br /></b> Add student to List
                                  </div>
                                  <div class="col s12 m2 l2">
                                      <input type="button" id="btnRight" value="&gt;&gt;&gt;"  class="AutoButtonRight btn-large btn gradient-45deg-indigo-purple " disabled />
                                  </div>
                                  <div class="col s12 m8 l8 ">
                                      <select id="rightValues" class="browser-default" name="MegaList[]" size="5" multiple ="multiple"></select>
                                 </div>
                             </div>
                          </div>
                          <div class="TermAddMultipleSelect StudentTermSelect row StudentTermProcessDiv">
                              <div class="MegaListLabel col s12 m2 l2">
                                    <b>STEP 4:</b>
                              </div>
                              <div class="col s6 m5 l5">
                                    <input type = "button"  value="select all students in list"  class="AutoButtonSelectAll  btn gradient-45deg-indigo-purple " id="SelectAll" disabled />
                              </div>
                              <div class="col s6 m5 l5">
                                    <input type = "submit"  value="add student to term" class="AutoButtonSubmit  btn gradient-45deg-indigo-purple"  disabled/>
                               </div>
                          </div>
                </div>
                <div class="AddScoreNotification col s4 m4 l4 row ">
                     @if(Session::has('AddStudentTermInfo'))
                            <h5 class="center-align"> {{Session::get('AddStudentTermInfo')}}</h5>
                     @endif
                      <?php
                            if(Session::has('SuccessArray'))
                            {
                              $SuccessArray  = Session::get('SuccessArray');
                            }

                             if(Session::has('FailureArray'))
                            {
                              $FailureArray  = Session::get('FailureArray');
                            }
                            if(Session::has('ThisTermAndSubClass'))
                            {
                              $ThisTermAndSubClass  = Session::get('ThisTermAndSubClass');
                            }
                      ?>
                      <div class="TermAddSuccess  green lighten-2">
                          <?php
                             if( isset($SuccessArray) and is_array($SuccessArray)  and array_key_exists('studentid', $SuccessArray)
                                  and isset($ThisTermAndSubClass) and is_array($ThisTermAndSubClass))
                                {
                                   echo "The following students were successfully attached to <b>".
                                        $ThisTermAndSubClass['ThisTerm']['classname'] . " ".
                                        strtoupper($ThisTermAndSubClass['SubClass'])." ".
                                        $ThisTermAndSubClass['ThisTerm']['year']. "</b><br />";
                                        //var_dump($ThisTermAndSubClass); var_dump($SuccessArray) ;
                                    foreach( $SuccessArray['studentid'] as $EachStudentInserted)
                                      {
                                            $ThisStudentInserted = Students::with('UserBelong')
                                                                 ->where('studentid', '=', $EachStudentInserted )
                                                                 ->get();
                                            //var_dump($ThisStudentInserted);
                                            $ThisStudentInsertedArray =   count($ThisStudentInserted) > 0 ?   $ThisStudentInserted->first()->toArray() : [] ;
                                            // var_dump($ThisStudentInsertedArray);
                                            if(count($ThisStudentInsertedArray) > 0)
                                            {
                                                echo "".$ThisStudentInsertedArray ['user_belong']['surname']. " "
                                                . $ThisStudentInsertedArray['user_belong']['firstname']." "
                                                . $ThisStudentInsertedArray['user_belong']['middlename'] .""." <b class='Demarcation'> | </b> " ;
                                            }
                                          else
                                            {
                                              echo "No Name <b class='Demarcation'> | </b>";
                                            }
                                        }

                                  }
                          ?>
                      </div>
                      <div class="row">
                        <br />
                        <br />
                      </div>
                      <div class="TermAddFail  red lighten-3">
                    @if(isset($FailureArray) and is_array($FailureArray) and array_key_exists('studentid', $FailureArray)
                         and isset($ThisTermAndSubClass) and is_array($ThisTermAndSubClass) )
                          The following students  were not successfully attached to
                          <b> {{  $ThisTermAndSubClass['ThisTerm']['classname'] .
                               strtoupper($ThisTermAndSubClass['SubClass'])." ".
                               $ThisTermAndSubClass['ThisTerm']['year']
                           }}</b><br />
                           <?php
                              foreach( $FailureArray['studentid'] as $EachStudentInserted)
                              {
                                 $ThisStudentInserted = Students::with('UserBelong')
                                                     ->where('studentid', '=', $EachStudentInserted )
                                                     ->get();
                                 $ThisStudentInsertedArray =   count($ThisStudentInserted) > 0 ?   $ThisStudentInserted->first()->toArray() : [] ;
                                 // var_dump($ThisStudentInsertedArray);
                                 if(count($ThisStudentInsertedArray) > 0)
                                 {
                                     echo "".$ThisStudentInsertedArray ['user_belong']['surname']. " "
                                     . $ThisStudentInsertedArray['user_belong']['firstname']." "
                                     . $ThisStudentInsertedArray['user_belong']['middlename'] .""." <b class='Demarcation'> | </b> " ;
                                 }
                               else
                                 {
                                   echo "No Name <b class='Demarcation'> | </b>";
                                 }
                            }
                             //var_dump($FailureArray, $SuccessArray );
                          ?>
                    @endif
              </div>
                </div>
              </form>
      </div>
      <div id="QuestionInputPage" class="">
              <div class="center-align" style="margin-top:50px;">
                  <a href="{{URL::route('student-question-input-page')}}" class="StudentQuestionInputPage btn btn-large gradient-45deg-deep-orange-orange pulse">
                         Go to  Questions Input Page
                  </a>  <i class="material-icons grey-text">fast_forward</i>
              </div>
      </div>
  </div>


@stop
