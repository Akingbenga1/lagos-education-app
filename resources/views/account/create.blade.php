@extends('layouts.main')


@section('division_container')
 <b> {{ str_random(6)}} </b>

 @if(Session::has('AccountCreateInfo'))
	{{Session::get('AccountCreateInfo')}}
@endif
	<form action="{{URL::route('post-new-user')}}" method="post">
	<h1> New User! You are Welcome! </h1>

	@if($errors->has('UserName'))
		{{$errors->first('UserName')}}
	@endif

	<br/>

	Your Name: <input type="text" name="UserName"  {{ (Input::old('UserName')) ? ' value="'. e(Input::old('UserName')) . '"' : '' }} /><br />
	@if($errors->has('UserEmail'))
		{{$errors->first('UserEmail')}}
	@endif

	<br />

	Your Email: <input type="text" name="UserEmail" {{ (Input::old('UserEmail')) ? ' value="'. e(Input::old('UserEmail')) . '"' : ''}} /><br />
	@if($errors->has('UserEmail2'))
		{{$errors->first('UserEmail2')}}
	@endif

	<br/>

	Your Email Again: <input type="text" name="UserEmail2" {{ (Input::old('UserEmail2')) ? ' value="'. e(Input::old('UserEmail2')) . '"' : ''}}  /><br />
	@if($errors->has('Password'))
		{{$errors->first('Password')}}
	@endif

	<br/>

	Your Password: <input type="password" name="Password" /><br />
	{{Form::token()}}
 	<button type="submit" class=""> create account! </button>  
 	</form>

@stop