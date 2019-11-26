<div id="login-page" class="row">
    <h3 class="center-align">  Login </h3>
    <div class="col s12 m12  l6 offset-l3 z-depth-4 card-panel">
        <form class="login-form" action="{{URL::route('login')}}" method="post" data-parsley-validate>
            {{ csrf_field() }}
            <div class="row">
                <div class="input-field col s12 center">
                    {{--{{HTML::image("/Images/Icons/login-mobile.ico",'Login Icon',--}}
                 {{--array('class' => 'responsive-img valign z-depth-3 ', 'height' => '60', 'width' => '60') )}}--}}
                    <img src="{{asset('/Images/Icons/login-mobile.ico')}}" class="responsive-img valign z-depth-3" alt="Login Icon" height="60"  width = '60' />
                    <h4 class="center login-form-text">I.H.E.S.G.S</h4>
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
                    <input id="email" type="text" name="Email"  required data-parsley-type="email" value="{{ old('Email') }}">
                    <label for="email" class="center-align">Email</label>

                    @if($errors->has('Email'))
                        <span class="center-align LoginError" >{{$errors->first('Email')}}</span>
                    @endif
                </div>
            </div>
            <div class="row margin">
                <div class="input-field  col s12">
                    <i class="material-icons prefix"> vpn_key</i>
                    <input id="password" type="password" name="Password"  required data-parsley-minlength = 6 >
                    <label for="password" class="">Password</label>
                    @if($errors->has('Password'))
                        <span class="center-align LoginError">{{$errors->first('Password')}}</span>
                    @endif
                </div>
            </div>
            <!-- <div class="row">
                <div class="input-field col s12 m12 l12  login-text">
                    <input type="checkbox" id="remember-me">
                    <label for="remember-me">Remember me</label>
                </div>
            </div> -->
            <div class="row">
                <div class="input-field col s12">
                    <button type="submit" class="btn waves-effect waves-light col s12" >Login</button>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6 l6">
                    <p class="margin medium-small tooltipped" data-position="bottom" data-delay="20" data-tooltip="You must be logged in as an Admin!"><a href="{{ URL::route('register')}}">Register</a></p>
                </div>
                <div class="input-field col s6 m6 l6">
                    <p class="margin right-align medium-small"><a href="{{URL::route('password-reminder')}}">Forgot password ?</a></p>
                </div>
            </div>

        </form>
    </div>
</div>