@extends('layouts.adminmain')
<!--  extend the particular  layout that you want. so, you can have as much layouts as you like  -->

<!--  Extends  adminmain. This is the front page of admin side.  -->
@section('adminhome')
Please Login to the admin Page:

<form action="{{URL::route('login-details')}}" method="post">
 @if($errors->has('Email'))
      {{$errors->first('Email')}}
    @endif
    <br />
    Admin email: <input type="text" name="Email" 
    {{ (Input::old('Email')) ? ' value="'. e(Input::old('Email')) . '"' : '' }}  />
    <br />

    @if($errors->has('Password'))
    {{$errors->first('Password')}}
    @endif
    <br />
    Admin password: <input type="password" name="Password"   /><br />

    {{Form::token()}}
    <button type="submit" class=""> Sign in </button>
    </form>

 @stop


