@extends('layouts.main')
@section('division_container')
  @if(Auth::check())  <!--   if you are authenticated-->
       
  @else
    <a href = '{{URL::route("login-form")}}'> Teacher Login</a>
  @endif
  <h3> Edit Student Score </h3>    
  @include('includes.adminlinks')
   @if(isset($ListMessage))
      <b> {{$ListMessage}}</b>
  @endif  

  <p class="well"> 
                 <b>step 1:</b> Choose the appropriate term. ( Year, Term, Class, Subclass)<br />
                 <b>step 2:</b> Start typing student name and choose student <br />
                 <b>step 3:</b> Choose subject from list <br />
                 <b>step 4:</b> Click on "Edit score for this subject" <br />
                 
  </p>  
  {{ Form::open(array( 'route' => 'edit-student-score' ,'files' => true, 'method'=> 'post')) }}
      <p class="ScoreInputInstruction"> <b> STEP 1</b>:Choose the appropraite term. ( Year, Term, Class, Subclass) </p>
        @include('includes.chooseterm')
        <hr />

       <p class="ScoreInputInstruction"> <b> STEP 2:</b>
         @if(!empty($AllStudents) and is_array($AllStudents))
              @include('includes.choosestudentasarray')  
              <br />

          @else
              <option> No Student Available  </option>
          @endif

        <hr />
        <br/>

        <p class="ScoreInputInstruction"> <b> STEP 3:</b> Choose subject from list </p>
         <select name = "SubjectId" >
         @if(!empty($Subjects) and is_array($Subjects))
              <option> -- Choose subject -- </option>
                    @foreach($Subjects as $allsubjects)
                            <option value="{{$allsubjects['subjectid']}}"> {{$allsubjects['subject']}}</option>
                    @endforeach
          @else
              <option> No Subject Available  </option>
          @endif
        </select> 
         <hr />
        <br/>
      <input type = "submit"  value="Edit score for this subject" id="AutoButton"  disabled/>    
      {{Form::token()}}  
      {{Form::close()}}
@stop