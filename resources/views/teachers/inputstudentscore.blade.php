@extends('layouts.main')


@section('division_container')
      <h3> Student Score Input Page </h3> 
       @include('includes.adminlinks')
        <p class="ScoreInputProcessInstruct"> 
                 <b>step 1:</b> Choose the appropriate term. ( Year, Term, Class, Subclass)<br />
                 <b>step 2:</b> Start typing student name and choose student <br />
                 <b>step 3:</b> Choose subject from list <br />
                 <b>step 4:</b> Input <b> Continuous Assessmnet Score</b> and <b> Examination score </b> <br />
                 
        </p>  

        <div class="AddScoreNotification">
        &nbsp;

         @if(Session::has('ScoreInput'))
               <b class="AddScoreNotificationGeneral"> {{Session::get('ScoreInput')}}</b>
             @endif
             <br />

             <div class="DecoupledErrors"> 
                @if($errors->has('Year'))
                    <span class="YearError">  {{$errors->first('Year')}} </span>
              @endif 
           <br />
            @if($errors->has('Class'))
                <span class="ClassError">{{$errors->first('Class')}} </span>
             @endif
        <br />

         @if($errors->has('SubClass'))
                 <span class="SubClassError">{{$errors->first('SubClass')}} </span>
            @endif
            <br />


             </div>
            
             
                @if($errors->has('CAScore'))
                    {{$errors->first('CAScore')}}
                @endif

                <br />

                 @if($errors->has('ExamScore'))
                    {{$errors->first('ExamScore')}}
                @endif

                 @if($errors->has('Student'))
                    {{$errors->first('Student')}}
                @endif
                <br />

                 @if($errors->has('Subjects'))
                    {{$errors->first('Subjects')}}
                @endif
                <br />
        </div>
        

        {{ Form::open(array( 'route' => 'input-student-score' ,'files' => true, 'method'=> 'post')) }}

        <div class="ScoreInputTerm">
            <p class="ScoreInputInstruction"> <b> STEP 1</b>:<br />Choose the appropraite term. <br />
            ( Year, Term, Class, Subclass) </p>

            @include('includes.chooseterm')
        </div>


          <div class="ScoreInputProcess">
               
                <p class="ScoreInputInstruction"> <b> STEP 2:</b><br />
                 Start typing student name, their names will show automatically. <br />
                    <b> Only students from the list can be chosen.</b><br/> 
                    <b> Student name must be choosen first before proceeding to edit  </b>

                </p>
                    @if(!empty($AllStudents) and is_array($AllStudents))
                          @include('includes.choosestudentasarray')  
                    @else
                          <option> No Student Available  </option>
                    @endif<br/> 
          </div>


          <div class="ScoreInputProcess">
                <p class="ScoreInputInstruction"> <b> STEP 3:</b><br /> Choose subject from list </p>
                <select name = "Subjects"  class="ScoreInputSubjects">
                    @if(!empty($Subjects) and is_array($Subjects))
                          <option> -- Choose subject -- </option>
                          @foreach($Subjects as $allsubjects)
                                <option value="{{$allsubjects['subjectid']}}"> {{$allsubjects['subject']}}</option>
                          @endforeach
                    @else
                          <option> No Subject Available  </option>
                    @endif
                </select> 
          </div>

          <div class="ScoreInputProcess">
                <p class="ScoreInputInstruction"> 
                <b>STEP 4:</b><br /> Input <strong> Continuous Assessmnet Score</strong> and <strong> examination score </strong> </p>
                Continous assessment score:<br /> <input type="text" name="CAScore" />     
                <br /><br/>

                Exam Score:<br /> <input type="text" name="ExamScore" />
                 <div class="ScoreInputSubmit"> 
              <br /> <input type = "submit"  value="Add score" id="AutoButton"  disabled/>
              <a href="{{URL::action('get-students-list')}}" > Edit Student Score </a>
        </div>
          </div>
       
    {{Form::token()}}
     
      {{Form::close()}}
    
@stop