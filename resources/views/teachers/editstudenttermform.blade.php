@extends('layouts.main')


@section('division_container')
      {{ Form::open(array( 'route' => 'post-student-term' ,'files' => true, 'method'=> 'post')) }}
         @include('includes.chooseterm')           
            <h2>Choose student to add to term</h2>
             @include('includes.adminlinks')
            @if(!empty($AllStudents) and is_array($AllStudents))
                @include('includes.choosestudentasarray')     
            @endif  
            <input type = "submit"  value="add student to term"  id="AutoButton"  disabled/>
            {{Form::token()}}     
      {{Form::close()}}
 @stop