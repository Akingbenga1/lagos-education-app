@extends('layouts.main')
@section('division_container')
    <h3>  Registration</h3>

    @if(Session::has('AccountCreateInfo'))
        <div class="center-align">
            <span> {{Session::get('AccountCreateInfo')}}</span>
        </div>
    @endif

    <form class="" action="{{URL::route('register')}}" method="post" data-parsley-validate>
        {{ csrf_field() }}
        <div class="row margin">
            @if(Session::has('LoginInfo'))
                <div class="center-align">
                    <span  class="LoginError"> {{Session::get('LoginInfo')}}</span>
                </div>
            @endif
            <div class="input-field col s12">
                <i class="material-icons prefix"> account_circle</i>
                <input id="name" type="text" name="name"  required  value="{{ old('name') }}">
                <label for="name" class="center-align">Name</label>
                @if($errors->has('name'))
                    <span class="center-align LoginError" >{{$errors->first('name')}}</span>
                @endif
            </div>
        </div>
        <div class="row margin">
            @if(Session::has('LoginInfo'))
                <div class="center-align">
                    <span  class="LoginError"> {{Session::get('LoginInfo')}}</span>
                </div>
            @endif
            <div class="input-field col s12">
                <i class="material-icons prefix"> account_circle</i>
                <input id="username" type="text" name="username"  required  value="{{ old('username') }}" autocomplete="off" >
                <label for="username" class="center-align">Username( </label>
                @if($errors->has('username'))
                    <span class="center-align LoginError" >{{$errors->first('username')}}</span>
                @endif
            </div>
        </div>
        <div class="row margin">
            <div class="input-field col s12 m6 l12 black-text">
                <i class="material-icons prefix"> account_circle</i>
                <select name = "YourClass" class="black-red" required>
                    <option> -- Choose Your Class --</option>
                    <option value="JSS1"  {{old('Class') == "JSS1" ? "selected" : ""}}>JSS1</option>
                    <option value="JSS2" {{old('Class') == "JSS2" ? "selected" : ""}}>JSS2</option>
                    <option value="JSS3" {{old('Class') == "JSS3" ? "selected" : ""}}>JSS3</option>
                    <option value="SS1"  {{old('Class') == "SS1" ? "selected" : ""}}>SS1</option>
                    <option value="SS2" {{old('Class') == "SS2" ? "selected" : ""}}>SS2</option>
                    <option value="SS3" {{old('Class') == "SS3" ? "selected" : ""}}>SS3</option>
                </select>
                <label> Choose Your Class </label>
                @if($errors->has('Year'))
                    <span class="center-align LoginError">{{$errors->first('Year')}}</span>
                @endif
            </div>
        </div>
        <div class="row margin">
            <div class="input-field  col s12">
                <i class="material-icons prefix"> vpn_key</i>
                <input id="password" type="password" name="Password"  required data-parsley-minlength = 6 >
                <label for="password" class="">Password ( minimum 6 characters ) </label>
                @if($errors->has('Password'))
                    <span class="center-align LoginError">{{$errors->first('Password')}}</span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <button type="submit" class="btn waves-effect waves-light col s12" >Join</button>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s6 m6 l6">
                <p class="margin medium-small tooltipped" data-position="bottom" data-delay="20" data-tooltip="You must be logged in as an Admin!"><a href="{{ URL::route('teachers-home-page')}}">Register</a></p>
            </div>
            <div class="input-field col s6 m6 l6">
                <p class="margin right-align medium-small"><a href="{{URL::route('password-reminder')}}">Forgot password ?</a></p>
            </div>
        </div>
    </form>
@stop
