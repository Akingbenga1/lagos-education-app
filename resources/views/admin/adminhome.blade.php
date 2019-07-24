@extends('layouts.main')

@section('division_container')



<h3> Hello, you are welcome to the admin page. </h3>
you can do the following operations in the admin page: <br/ >
 <a href="{{URL::route('admin-roles')}}">  create roles and permissions    </a><br />
 <a href="{{URL::route('upload-teachers-signature')}}">  Upload teachers signature   </a><br /> 
 <!-- <a href="{{URL::route('staff-page')}}">Staff Page</a><br />  -->
 @stop