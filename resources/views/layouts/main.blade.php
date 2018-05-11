<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title> {{$Title}} </title>
 <meta name="csrf-token" content="<?= csrf_token() ?>">
 <meta name="viewport" content="width=device-width, initial-scale=1">

<meta charset="utf-8">
<meta name="author" content="Akinbami Akinsola Oluwagbenga">
<link rel="shortcut icon" href="{{asset('/Images/Logos/FavIjayeSchool.ico')}}">
<style>
@font-face {
  font-family: 'Poppins';
  font-style: normal;

  src: url({{ asset('/fonts/Poppins/Poppins_Thin.ttf') }}) format('truetype');
}

@font-face {
  font-family: 'Poppins_Light';
  font-style: normal;
  font-weight: 400;
  src: url({{ asset('/fonts/Poppins/Poppins_Light.ttf') }}) format('truetype');
}
@font-face {
  font-family: 'Roboto_Black';
  font-style: normal;
  font-weight: 400;
  src: url({{ asset('/fonts/Roboto/Roboto-Regular.ttf') }}) format('truetype');
}

@font-face {
  font-family: 'Roboto_Light';
  font-style: normal;
  font-weight: 400;
  src: url({{ asset('/fonts/Roboto/Roboto_Light.ttf') }}) format('truetype');
}
@font-face {
  font-family: 'Titillium';
  font-style: normal;
  font-weight: 400;
  src: url({{ asset('/fonts/Titillium/Titillium-Semibold.otf') }});
}
</style>

<link href="{{asset('CSS/SiteStyle.css')}}" rel="stylesheet">
<link href="{{asset('CSS/MaterialDesign/iconfont/material-icons.css')}}" rel="stylesheet">
<link href="{{asset('CSS/materialize.min.css')}}" rel="stylesheet">
<link href="{{asset('CSS/jquery-ui-style.css')}}" rel="stylesheet">

    <link href="{{asset('assets-new/img/prime_favicon.png')}}" rel="shortcut icon">


</head>
<body>
<header>

    <ul   id="slide-out" class="side-nav">
        <li><div class="user-view">
                <div class="background">
                @if(Auth::check())
                    <!-- <img src="images/office.jpg">  -->
                @else
                    <!-- <img src="images/office.jpg"> -->
                    @endif

                </div>
                <a href="{{route('home')}}">
                    <img src="{{asset('Images/Logos/IjayeSchool.jpg')}}" alt="Ijaye 2 Senior Grammar School Logo" class="left" height="60px" width="60px"   />
                    @if(Auth::check()) &nbsp;&nbsp; &nbsp; @endif<!-- Ijaye Housing Estate Senior Grammar School -->
                </a>
                @if(!Auth::check())
                    <a href="{{route('login')}}" class="right ">
                        <img src="{{asset('/Images/Icons/login-mobile.ico')}}" alt="Login Icon" class="right" height="60px" width="60px"   /> Login</a>
                @else
                    <div class="red circle tooltipped pulse right valign-wrapper" data-position="right" data-delay="50" data-tooltip="View/Edit Your Profile" height ='60' width = '60' >
                        <a href="{{ route('user-profile')}}">  <i class="small white-text material-icons center-align " style="margin:20px;margin-left:15px;">edit</i> </a>
                    </div>
                @endif
                <div style="clear:both"></div>
                @if(Auth::check())
                    <?php
                    //var_dump(Auth::user());
                    $UserName =  Auth::user()->firstname . " ". Auth::user()->surname . " ".  Auth::user()->middlename;
                    $UserEmail =  Auth::user()->useremail;
                    ?>
                    <span><span class="black-text tooltipped name" data-position="right" data-delay="50" data-tooltip="{{$UserName}}">
                            {{ substr($UserName , 0, 30) . '...'  }}</span></span>
                    <span href="#!email"><span class="black-text tooltipped email" data-position="right" data-delay="50" data-tooltip="{{$UserEmail}}">
                        {{  substr($UserEmail , 0, 30) . '...'}}</span></span>
                @endif
            </div></li>

        @if(!Auth::check())
            <li><div class="divider"></div></li>
            &nbsp;<li class=" lighten-5 red-text center-align">You are not logged in!</li>
        @endif
        <li><div class="divider"></div></li>
        <li><a href="{{ route('home')}}" class="waves-effect"><i class="red-text material-icons">home</i>Home</a></li>
        <li><a href="{{route('student-page')}}" class="waves-effect"><i class="red-text material-icons">library_books</i>Students @if(true)<span class=" deep-orange white-text badge">new report!</span>@endif</a></li>
        <li><a href="{{route('teachers-home-page')}}" class="waves-effect"><i class="red-text material-icons">school</i>Teachers</a></li>
        <li><a href="{{route('student-question-page')}}" class="waves-effect"><i class="red-text material-icons">cloud</i>Practise Questions</a></li>
        {{--<li class="green white-text"><a href="{{URL::action('excel-upload-page')}}" class="waves-effect white-text"><i class=" white-text material-icons">list</i>Excel Bulk Upload</a></li>--}}
        <li><div class="divider"></div></li>
        <li><a class="subheader">Classes</a></li>
        <li><a class="waves-effect" href="{{route('public-class-list-page', array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015, 'Class' => 'SS1'))}}">
                SS1 Classes<i class="material-icons red-text right">chevron_right</i></a></li>
        <li><a class="waves-effect" href="{{route('public-class-list-page',
                                            array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015, 'Class' => 'SS2'))}}">
                SS2 Classes<i class="material-icons red-text right">chevron_right</i></a></li>
        <li><a class="waves-effect" href="{{route('public-class-list-page',
                                            array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015, 'Class' => 'SS3'))}}">
                SS3 Classes<i class="material-icons red-text right">chevron_right</i></a></li>
        @if(Auth::check())
            <li><div class="divider"></div></li>
            <li><a class="subheader">Account</a></li>
            <li><a href="{{route('signout')}}" > <b> Sign out</b></a></li>
        @endif
    </ul>
    <nav>
        <div class="nav-wrapper">
            <!--  <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>  -->
            <div class="left">
                <a href="#!" class="brand-logo SlideOutLogo left" style="margin-left:0.2em;" data-activates="slide-out" ><i class=" large material-icons">menu</i></a>
                <a href="{{ route('home')}}" style="margin-left:2.7em;">
                    <img src="{{asset('Images/Logos/IjayeSchool.jpg')}}" alt="Ijaye Senior Grammar School Logo" class="left" height="60px" width="60px"   /></a>
            </div>


            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="{{ route('home')}}" class="waves-effect">Home</a></li>
                <li><a href="{{route('student-page')}}" class="waves-effect">Students</a></li>
                <li><a href="{{route('teachers-home-page')}}" class="waves-effect">Teachers</a></li>
                <li><a href="{{route('student-question-page')}}" class="waves-effect">Practise Questions</a></li>
                <!-- Dropdown Trigger -->
                @if(Auth::check())
                    <li><a class='dropdown-button btn red' href='#' data-activates='dropdown1'><i class="large  material-icons">account_circle</i></a></li>
                @else
                    <li><a href="{{route("login")}}" class="btn light-blue darken-4">Login</a></li>
                @endif

            </ul>
        </div>
    </nav>

    <!-- Dropdown Structure -->
    <ul id='dropdown1' class='dropdown-content red-text'>
        @if(Auth::check())
            <li><a href="{{route('user-profile')}}" class="red-text"><i class="material-icons">portrait</i>Your Profile</a></li>
            <li class="divider"></li>
            <li><a href="{{route('signout')}}" class="red-text"><i class="material-icons">exit_to_app</i>Sign Out</a></li>
            <li class="divider"></li>
        @else
            <li></li>
            <li class="divider"></li>

        @endif

    </ul>
</header>

<main>
    @yield('ShoutOut')
    @yield('adminlinks')
    @yield('division_container')
</main>

<footer class="page-footer example ">
    <div class="container hide hide-on-med-and-down">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Ijaiye Housing Estate Senior Grammar School </h5>
                <!-- <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p> -->
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Navigation Links</h5>
                <ul>
                    <li><a class="grey-text text-lighten-3" href="#!">Students</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Teachers</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Practice Questions and Answers</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Excel Bulk Uploads</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright .show-on-medium-and-down">
        <div class="row container">
           <div class="col s12 m12 l6">
               <i class="material-icons prefix"> copyright</i>  2017-2019 Copyright
           </div>
            <div class="col s12 m12 l6">
                <a href="{{ route('home')}}" class="grey-text text-lighten-4">Powered by Webuserstools</a>
            </div>

        </div>
    </div>
</footer>


<script src="{{ asset('JS/jquery.js') }}"></script>
<script src="{{ asset('JS/jquery-ui.min.js') }}"></script>
<script src="{{ asset('JS/ajaxeditscore.js') }}"></script>
<script src="{{ asset('JS/randomColor.js') }}"></script>
<script src="{{ asset('JS/parsley.min.js') }}"></script>
<script src="{{ asset('JS/materialize.min.js') }}"></script>
<script src="{{ asset('JS/Chart.min.js') }}"></script>
<script src="{{ asset('JS/Chart.PieceLabel.js') }}"></script>
<script src="{{ asset('JS/knob/jquery.knob.min.js') }}"></script>

<script src="{{ asset('JS/DefaultJS.js') }}"></script>
<script src="{{ asset('JS/AllCharts.js') }}"></script>

@yield('script')
</body>
</html>
