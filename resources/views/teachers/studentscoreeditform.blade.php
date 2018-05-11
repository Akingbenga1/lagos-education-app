@extends('layouts.main')

@section('adminlinks')
  @include('includes.adminlinks')
@stop

@section('division_container')
  @if(Auth::check())  <!--   if you are authenticated-->
       
  @else
    <a href = '{{URL::route("login-form")}}'> Teacher Login</a>
  @endif

  @if(isset($ThisSubjectScore) and is_array($ThisSubjectScore)  and is_array($ThisStudent) 
            and isset($ThisStudent) and is_array($ThisSubject) and isset($ThisSubject))
   <!-- {{var_dump($ThisSubjectScore)}} -->
    <!--{{var_dump($ThisStudent)}} -->
    <!--{{var_dump($ThisSubject)}} -->
    <h1> Edit "{{$ThisSubject['subject']}}" score  for  @if( is_array($ThisStudent['user_belong']) )
    {{$ThisStudent['user_belong']['surname'] . ' '.$ThisStudent['user_belong']['middlename'] 
        . ' '.$ThisStudent['user_belong']['firstname'] . ' '}}  @endif 
        ({{$ThisSubjectScore['classname']. strtoupper ($ThisSubjectScore['class_subdivision'] ) . 
        " ".$ThisSubjectScore['termname']. " ".$ThisSubjectScore['year']
          }}) </h1> 

        @if(isset($EditMessage))
            <b> {{ $EditMessage}}</b>
        @endif   

  {{ Form::open(array( 'route' => 'edit-this-score' ,'files' => true, 'method'=> 'post')) }}
         @if($errors->has('CAScore'))
            <b>  {{$errors->first('CAScore')}} </b>
        @endif
        <br />
        Continous assesment score: <input type="text" name="CAScore" value="{{$ThisSubjectScore['cont_assess_40']}}" />     
        <br />
         @if($errors->has('ExamScore'))
            <b>   {{$errors->first('ExamScore')}}</b>
        @endif
        <br />
        Exam Score: <input type="text" name="ExamScore" value="{{$ThisSubjectScore['exam_score_60']}}"  />         
        <br />
        <input type = "submit"  value="Edit this score" />
    {{Form::token()}}
     
      {{Form::close()}}
      @else
    <p class="well">   Subject not yet recorded. Nothing to Edit. </p>
      @endif
@stop