@extends('layouts.main')

@section('division_container')
 <h3> Choose student to add to term</h3>
  @include('includes.adminlinks')

 <p class="ScoreInputProcessInstruct"> 
                 <b>step 1:</b> Choose the appropriate term. ( Year, Term, Class, Subclass)<br />
                 <b>step 2:</b> Start typing student name and choose student <br />
                 <b>step 3:</b>Click to add student to List <br />
                 <b>step 4:</b> Click " select all students in list " to add all students in term.
                                Then click ' Add student to term' 
                 
    </p>  

 <div class="AddScoreNotification">
  &nbsp;
   @if(Session::has('AddStudentTermInfo'))
             {{Session::get('AddStudentTermInfo')}}
   @endif<br />

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

    <div class="TermAddSuccess">
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
                                           ->get()->first()->toArray();
                      //var_dump($ThisStudentInserted);
                      echo  "". $ThisStudentInserted['user_belong']['surname']. " "
                      . $ThisStudentInserted['user_belong']['firstname']." "
                      . $ThisStudentInserted['user_belong']['middlename'] .""." <b class='Demarcation'> | </b> ";
                  }      
            }
      ?>
    </div>
    <div class="TermAddFail"> 
          @if(isset($FailureArray) and is_array($FailureArray) and array_key_exists('studentid', $FailureArray)
               and isset($ThisTermAndSubClass) and is_array($ThisTermAndSubClass) )
                Error!<br />
                "The following students <b>  were not </b>successfully attached to 
                 {{  $ThisTermAndSubClass['ThisTerm']['classname'] .
                     strtoupper($ThisTermAndSubClass['SubClass'])." ".
                     $ThisTermAndSubClass['ThisTerm']['year'] 
                 }}<br />
                 <?php
                    foreach( $FailureArray['studentid'] as $EachStudentInserted)
                    {
                       $ThisStudentInserted = Students::with('UserBelong')
                                           ->where('studentid', '=', $EachStudentInserted )
                                           ->get()->first()->toArray();
                       echo  "".$ThisStudentInserted['user_belong']['surname']. " "
                            . $ThisStudentInserted['user_belong']['firstname']." "
                            . $ThisStudentInserted['user_belong']['middlename']." <b> | </b> ";
                  }                
                ?>
          @endif
    </div>
   </div>

<br />
<br />
      {{ Form::open(array( 'route' => 'post-student-term' ,'files' => true, 'method'=> 'post')) }}

      <div class="StudentTermSelect">
            <p class="ScoreInputInstruction"> <b> STEP 1</b>:<br />Choose the appropraite term. <br />
            ( Year, Term, Class, Subclass) </p>
         @include('includes.chooseterm')    
         </div>      
           
          <div class="StudentTermProcess">   
          <div class="StudentTermProcessDiv">            
                <p class="ScoreInputInstruction"> <b> STEP 2:</b><br />
                 Start typing student name, their names will show automatically. <br />
                  <b> Only students from the list can be chosen.</b><br/> 
                  <b> Student name must be choosen first before proceeding to edit  </b>
                </p>
                @if(!empty($AllStudents) and is_array($AllStudents))
                      @include('includes.choosestudentasarray')     
                @endif  
          </div>
           <div  class="MoveRightButton StudentTermProcessDiv">
            <!-- <input type="button" id="btnLeft" value="&lt;&lt;" /> -->
            <p class="MoveRightLabel"> <b> STEP 3:</b><br />Click here to add student to List</p> 
            <input type="button" id="btnRight" value="&gt;&gt;"  class="AutoButtonRight" disabled/>
         </div>
            <!-- <div>
                  <select id="leftValues" size="5" multiple></select>
            </div> -->
        <div class="TermAddMultipleSelect StudentTermProcessDiv">

                <p class="MegaListLabel">
                   <b>STEP 4:</b><br />
                               This is the list of students you are about designated to the term chosen above. <br />
                               Click "<b>Select all students in list</b> " to add all students in term. <br />
                               Then click "<b> Add student to term</b>" 
                </p> 
                 <select id="rightValues" name="MegaList[]" size="5" multiple ="multiple">
                 </select>
                    <!-- <div>
                    <input type="text" id="txtRight" /> 
                   </div> -->
                   <br />
            <input type = "button"  value="select all students in list"  class="AutoButtonSelectAll" id="SelectAll" disabled/>
            <input type = "submit"  value="add student to term" class="AutoButtonSubmit"  disabled/>
        </div>



 
   </div>
            {{Form::token()}}     
      {{Form::close()}}
 @stop