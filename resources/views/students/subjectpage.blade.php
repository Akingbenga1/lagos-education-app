@extends('layouts.main')

@section('division_container')
 
  @if(isset($SubjectTeachers) and is_array($SubjectTeachers)  and is_array($AllYear) and isset($AllYear) and is_array($AllSubjectSaff ) )


    @foreach( $AllYear as $allYear)
    	We have <b> {{ $allYear['YearCount']}} </b> match<?php if($allYear['YearCount'] > 1 ) { echo 'es';} ?> 
    	 in  <b> {{ $allYear['Year'] }}</b>  <br /> 
    	  @foreach( $AllSubjectSaff as $allSubjectSaff)

    	  	 @if($allYear['Year'] == $allSubjectSaff['ThisStaff']['year'] )
    	  	 	   {{$allSubjectSaff['ThisStaffDetails']['user_belong']['surname'] . " " .
    	  	 	     $allSubjectSaff['ThisStaffDetails']['user_belong']['middlename']. "  ".
    	  	 	     $allSubjectSaff['ThisStaffDetails']['user_belong']['firstname']}} for
    	  	 	      @if(Auth::check() and Auth::user()->ability( array('Super User', 'Administrator', 'SchoolAdministrator','Principal', 'Vice Principal', 'Secretary','Teacher'), array()))  <!--   if you are authenticated-->
       						 <a href="{{URL::route('student-term-list-page',
                                            array('Year' => $allSubjectSaff['ThisStaff']['year']  ,
                                             'Class' =>  strtoupper ( $allSubjectSaff['ThisStaff']['classname'] ),
                                             'SubClass' =>  strtoupper ( $allSubjectSaff['ThisStaff']['subclass'] ) ))}}">  
                                             {{ strtoupper ( $allSubjectSaff['ThisStaff']['classname'] ). " " .
    	  	 	      strtoupper ( $allSubjectSaff['ThisStaff']['subclass'] ) }} <b> {{ $allYear['Year'] }}</b> </a>
  						@else
  						 <a href="{{URL::route('public-student-term-list-page',
                                           array('Year' => $allSubjectSaff['ThisStaff']['year']  ,
                                             'Class' =>  strtoupper ( $allSubjectSaff['ThisStaff']['classname'] ),
                                             'SubClass' =>  strtoupper ( $allSubjectSaff['ThisStaff']['subclass'] ) ))}}">  {{ strtoupper ( $allSubjectSaff['ThisStaff']['classname'] ). " " .
    	  	 	      strtoupper ( $allSubjectSaff['ThisStaff']['subclass'] ) }} <b> {{ $allYear['Year'] }}</b> </a>

  						@endif
    	  	 	     class students in  
    	  	 	      {{ $allSubjectSaff['ThisStaff']['termname'] }}
    	  	 	     
    	  	 	    <br />
    	  	  @endif

    	  @endforeach
 <hr />
   @endforeach
  
   @else
         No Subject teacher(s) for {{$SubjectName}}


  @endif
@stop