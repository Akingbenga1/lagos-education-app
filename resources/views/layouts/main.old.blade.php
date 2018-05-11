<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title> {{$Title}} </title>
 <meta name="csrf-token" content="<?= csrf_token() ?>">
 <meta name="viewport" content="width=device-width, initial-scale=1">

<meta charset="utf-8">
<meta name="author" content="Akinbami Akinsola Oluwagbenga">

{{HTML::style('CSS/materialize.min.css')}}
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    {{--{{HTML::style('CSS/bootstrap.min.css', array('media' => 'screen'))}}--}}
{{--{{HTML::style('CSS/font-awesome/css/font-awesome.min.css', array('media' => 'screen'))}}--}}
{{--{{HTML::style('CSS/SiteStyle.css', array('media' => 'screen'))}}--}}
{{--{{HTML::style('CSS/SiteStyle.css', array('media' => 'screen'))}}--}}
{{--{{HTML::style('CSS/jquery-ui-style.css')}}--}}
{{--{{HTML::style('CSS/w3.css', array('media' => 'screen'))}}--}}
{{--{{HTML::style('CSS/bootcards-desktop.min.css', array('media' => 'screen'))}}--}}
{{--<link rel="stylesheet" href= "{{ asset('CSS/bootstrap-theme.min.css') }}">--}}
<link rel="shortcut icon" href="{{ asset('/Images/Logos/FavIjayeSchool.jpg') }}}">


 {{----}}
 {{--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">--}}

<!-- Bootcards CSS for desktop: -->

 <!-- Angular Material requires Angular.js Libraries -->
  <!--<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-route.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-resource.js"></script>
  -->
  
  

  <!-- Angular Material Library -->
  <!-- <script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.js">  </script> -->

{{HTML::script('JS/jquery.js')}}
{{HTML::script('JS/jquery-ui.min.js')}}
{{HTML::script('JS/DefaultJS.js')}}
{{HTML::script('JS/jquery.autocomplete.min.js')}}
{{HTML::script('JS/common.js')}}
{{HTML::script('JS/materialize.min.js')}}
{{HTML::script('JS/ckeditor/ckeditor.js')}}
{{HTML::script('JS/Chart.min.js')}}
{{HTML::script('JS/ajaxinfograph.js')}}
{{HTML::script('JS/ajaxeditscore.js')}}



 {{--{{HTML::script('JS/jquery.popupoverlay.js')}}--}}
 {{--{{HTML::script('JS/bootstrap.min.js')}}--}}

 {{----}}

 {{----}}
 {{--{{HTML::script('JS/jquery.backstretch.min.js')}}--}}

 {{----}}
 {{----}}
 {{--{{HTML::script('JS/min.js')}}--}}
 {{--{{HTML::script('JS/SlideShow.js')}}--}}
 {{--<!-- <script src="//cdn.ckeditor.com/4.5.9/standard/ckditor.js"></script> -->--}}
 {{----}}
  {{----}}
 {{--{{HTML::script('JS/bootcards.js')}}--}}
 {{----}}

</head>
<!--   ng-app="BlankApp" ng-controller="AppCtrl" ng-cloak  * -->
<body>

{{--<nav>--}}
    {{--<div class="nav-wrapper red darken-3">--}}
        {{--<a href="#" class="brand-logo">{{HTML::image("/Images/Logos/IjayeSchool.jpg",'Ijaye Senior Grammar School Logo',--}}
         {{--array('class' => ' circle responsive-im', 'height' => '60', 'width' => '60') )}}</a>--}}
        {{--<ul id="nav-mobile" class="right hide-on-med-and-down">--}}
            {{--<li><a href="collapsible.html"><i class="large  material-icons">Account</i></a></li>--}}
        {{--</ul>--}}
    {{--</div>--}}
{{--</nav>--}}


{{--<nav>--}}
    {{--<div class="nav-wrapper">--}}
        {{--<a href="#" ><i class="material-icons">menu</i> </a>--}}
        {{--<ul id="nav-mobile" class="right hide-on-med-and-down">--}}
            {{--<li><a href="sass.html">Sass</a></li>--}}
            {{--<li><a href="badges.html">Components</a></li>--}}
            {{--<li><a href="collapsible.html">JavaScript</a></li>--}}
        {{--</ul>--}}
    {{--</div>--}}
{{--</nav>--}}



<nav>
    <div class="nav-wrapper">
        <a href="#" class="brand-logo">Logo</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="sass.html">Sass</a></li>
            <li><a href="badges.html">Components</a></li>
            <li><a href="collapsible.html">JavaScript</a></li>
        </ul>
    </div>
</nav>

<ul id="slide-out" class="side-nav">
<li><div class="user-view">
        <div class="background">
            <img src="images/office.jpg">
        </div>
        <a href="#!user"><img class="circle" src="images/yuna.jpg"></a>
        <a href="#!name"><span class="white-text name">John Doe</span></a>
        <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
    </div></li>
<li><a href="#!"><i class="material-icons">cloud</i>First Link With Icon</a></li>
<li><a href="#!">Second Link</a></li>
<li><div class="divider"></div></li>
<li><a class="subheader">Subheader</a></li>
<li><a class="waves-effect" href="#!">Third Link With Waves</a></li>
</ul>

<!--<div ng-cloak >
  <md-content class="md-padding" layout-xs="column" layout="row">
    <div flex-xs flex-gt-xs="50" layout="column">
      <md-card md-theme="<% showDarkTheme ? 'dark-grey' : 'default' %>" md-theme-watch>
        <md-card-title>
          <md-card-title-text>
            <span class="md-headline">Card with image</span>
            <span class="md-subhead">Large</span>
          </md-card-title-text>
          <md-card-title-media>
            <div class="md-media-lg card-media">
               <md-card-avatar>

                    {{HTML::image("/Images/Logos/IjayeSchool.jpg",
                           'Ijaye Senior Grammar School Logo', array('class' => 'img-responsive md-user-avatar',) )}}
              </md-card-avatar>
            </div>
          </md-card-title-media>
        </md-card-title>
        <md-card-actions layout="row" layout-align="end center">
          <md-button>Action 1</md-button>
          <md-button>Action 2</md-button>
        </md-card-actions>
      </md-card>
      <md-card md-theme="<% showDarkTheme ? 'dark-orange' : 'default' %>" md-theme-watch>
        <md-card-title>
          <md-card-title-text>
            <span class="md-headline">Card with image</span>
            <span class="md-subhead">Extra Large</span>
          </md-card-title-text>
        </md-card-title>
        <md-card-content layout="row" layout-align="space-between">
          <div class="md-media-xl card-media">
             <md-card-avatar>

                    {{HTML::image("/Images/Logos/IjayeSchool.jpg",
                           'Ijaye Senior Grammar School Logo', array('class' => 'img-responsive md-user-avatar',) )}}
              </md-card-avatar>
          </div>

          <md-card-actions layout="column">
            <md-button class="md-icon-button" aria-label="Favorite">
              <md-icon md-svg-icon="img/icons/favorite.svg"></md-icon>
            </md-button>
            <md-button class="md-icon-button" aria-label="Settings">
              <md-icon md-svg-icon="img/icons/menu.svg"></md-icon>
            </md-button>
            <md-button class="md-icon-button" aria-label="Share">
              <md-icon md-svg-icon="img/icons/share-arrow.svg"></md-icon>
            </md-button>
          </md-card-actions>
        </md-card-content>
      </md-card>
    </div>
    <div flex-xs flex-gt-xs="50" layout="column">
      <md-card md-theme="<% showDarkTheme ? 'dark-purple' : 'default' %>" md-theme-watch>
        <md-card-title>
          <md-card-title-text>
            <span class="md-headline">Card with image</span>
            <span class="md-subhead">Small</span>
          </md-card-title-text>
          <md-card-title-media>
            <div class="md-media-sm card-media">
               <md-card-avatar>

                    {{HTML::image("/Images/Logos/IjayeSchool.jpg",
                           'Ijaye Senior Grammar School Logo', array('class' => 'img-responsive md-user-avatar',) )}}
              </md-card-avatar>
            </div>
          </md-card-title-media>
        </md-card-title>
        <md-card-actions layout="row" layout-align="end center">
          <md-button>Action 1</md-button>
          <md-button>Action 2</md-button>
        </md-card-actions>
      </md-card>
      <md-card md-theme="<% showDarkTheme ? 'dark-blue' : 'default' %>" md-theme-watch>
        <md-card-title>
          <md-card-title-text>
            <span class="md-headline">Card with image</span>
            <span class="md-subhead">Medium</span>
          </md-card-title-text>
          <md-card-title-media>
            <div class="md-media-md card-media">
               <md-card-avatar>

                    {{HTML::image("/Images/Logos/IjayeSchool.jpg",
                           'Ijaye Senior Grammar School Logo', array('class' => 'img-responsive md-user-avatar',) )}}
              </md-card-avatar>
            </div>
          </md-card-title-media>
        </md-card-title>
        <md-card-actions layout="row" layout-align="end center">
          <md-button>Action 1</md-button>
          <md-button>Action 2</md-button>
        </md-card-actions>
      </md-card>
      <div layout layout-padding layout-align="center end" style="height:200px">
        <md-checkbox ng-model="showDarkTheme">Use 'Dark' Themed Cards</md-checkbox>
      </div>
    </div>
  </md-content>
</div> -->

<div class="BigWrapper">

  <div class="MainHeader row">

    <div class="MainHeaderLogo LeftSideBar col-md-3 container-fluid clearfix">
      <div class="LogoDiv row">
        <div class="col-md-2">
                <div class="MenuDropDown dropdown">
                 <i class="MenuIcon glyphicon glyphicon-menu-hamburger"  id = "dLabel" type = "button",
                 data-toggle = "dropdown",  aria-haspopup = "true", aria-expanded = "false"></i>
                   <!-- {{HTML::image("/Images/Icons/menuicon.png",
                         '', array('class' => 'img-responsive',
                         'id' => "dLabel", 'type' => "button", 'data-toggle' => "dropdown",  'aria-haspopup' => "true", 'aria-expanded' => "false") )}} -->
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                      <li>  <a href="{{ URL::route('home')}}"> <i class="glyphicon glyphicon-home"></i>  Home</a> </li>
                      <li> <a href="{{URL::action('student-page')}}">  <i class="glyphicon glyphicon-book"></i>  Students </a></li>
                      <li> <a href="{{URL::action('teachers-home-page')}}" class=""> <i class="glyphicon glyphicon-education"></i>  Teachers </a> </li>
                      <li> <a href="{{URL::action('student-question-page')}}" class=""> <i class="glyphicon glyphicon-tasks"></i>  Practise Questions </a> </li>
                      @if(Auth::check())  <!--   if you are authenticated-->
                      <?php $User = Auth::user(); ?>
                        @if( $User->ability( array('Super User', 'Administrator'),  array()) )
                            <li><a href="{{URL::route('excel-upload-page')}}" class="btn btn-success"> Excel Upload Page</a></li>
                        @endif
                      @endif
                    </ul>
                </div>
        </div>
        <div class="col-md-6  col-md-offset-2"><a href="{{URL::action('home')}}" class="LogoLink">
                {{HTML::image("/Images/Logos/IjayeSchool2.jpg",
                           'Ijaye Senior Grammar School Logo', array('class' => 'img-responsive',) )}}</a>
                                   <!--'height' => '180', 'width' => '180' )-->
        </div>
      </div>

      <div class="YearSelectDiv row">
        <select name = "Year" class="YearSelectAdmin" >
          <option> -- Select Academic Year -- </option>
          <option value="2015"> 2015/2016 </option>
          <option value="2016" > 2016/2017 </option>
        </select>
        <input type="hidden" class="ChangeYearAdmin" value="{{URL::route('class-list-ajax')}}" />
      </div>

      <div class="AccordionMenu row clearfix">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne" data-toggle="collapse"  data-parent="#accordion" href="#collapseSS1" aria-expanded="true" aria-controls="collapseOne">
              <h4 class="panel-title" >
                <div class="row" role="button"  >
                    <span class="col-md-7"> SS1 Classes  </span>
                    <i class="indicator col-md-2 glyphicon glyphicon-large glyphicon-chevron-right"></i>
                     <span class="col-md-3 badge"> {{ Session::has('MyYear') ? " " . Session::get('MyYear') . "/" . ( Session::get('MyYear') + 1 ): "2015/2016" }}</span>
                </div>
              </h4>
            </div>
            <div id="collapseSS1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="list-group">
                    <?php

                      $WhichRoute = 'public-student-term-list-page';
                      if(Auth::check() and Auth::user()->ability( array('Super User', 'Administrator', 'SchoolAdministrator','Principal', 'Vice Principal', 'Secretary','Teacher'), array()))
                         {
                             $WhichRoute = 'student-term-list-page';
                         }
                         else
                         {
                           $WhichRoute = 'public-student-term-list-page';
                         }
                     ?>
                          <li class="list-group-item">   <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'A'))}}">SS1 A
                                                     </a> </li>
                          <li class="list-group-item">   <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'B'))}}"> SS1 B </a> </li>
                          <li class="list-group-item">  <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'C'))}}"> SS1 C </a> </li>
                          <li class="list-group-item">  <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'D'))}}">  SS1 D </a> </li>
                          <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'E'))}}"> SS1 E </a> </li>
                          <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'F'))}}"> SS1 F </a> </li>
                          <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'G'))}}">  SS1 G </a> </li>
                          <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'H'))}}">  SS1 H </a> </li>
                          <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'J'))}}">  SS1 J </a> </li>
                          <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                                      array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                                       'Class' => 'SS1', 'SubClass' => 'K'))}}">   SS1 K </a> </li>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title" data-toggle="collapse"  data-parent="#accordion" href="#collapseSS2" aria-expanded="true" aria-controls="collapseOne">
               <div class="row" role="button"  >
                    <span class="col-md-7"> SS2 Classes  </span>
                    <i class="indicator col-md-2 glyphicon glyphicon-large glyphicon-chevron-right"></i>
                     <span class="col-md-3 badge"> {{ Session::has('MyYear') ? " " . Session::get('MyYear') . "/" . ( Session::get('MyYear') + 1 ): "2015/2016" }}</span>
               </div>
              </h4>
            </div>
            <div id="collapseSS2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="list-group">
                  <li class="list-group-item"><a href="{{URL::route($WhichRoute,
                                              array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                               'Class' => 'SS2', 'SubClass' => 'A'))}}">  SS2 A </a>
                  </li>
                  <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                              array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                               'Class' => 'SS2', 'SubClass' => 'B'))}}">  SS2 B </a>
                  </li>
                  <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                              array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                               'Class' => 'SS2', 'SubClass' => 'C'))}}">  SS2 C </a>
                  </li>
                  <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                              array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                               'Class' => 'SS2', 'SubClass' => 'D'))}}">  SS2 D </a>
                  </li>
                  <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                              array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                               'Class' => 'SS2', 'SubClass' => 'E'))}}"> SS2 E </a>
                  </li>
                  <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                              array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                               'Class' => 'SS2', 'SubClass' => 'F'))}}"> SS2 F  </a>
                  </li>
                  <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                              array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                               'Class' => 'SS2', 'SubClass' => 'G'))}}"> SS2 G  </a>
                  </li>
                   <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                              array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                               'Class' => 'SS2', 'SubClass' => 'H'))}}"> SS2 H  </a>
                  </li>
                  <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                              array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                               'Class' => 'SS2', 'SubClass' => 'J'))}}"> SS2 J  </a>
                  </li>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne" data-toggle="collapse"  data-parent="#accordion" data-target="#collapseSS3" aria-expanded="true" aria-controls="collapseOne">
              <h4 class="panel-title" >
                <div class="row" role="button"  >
                 <span class="col-md-7"> SS3 Classes  </span>
                <i class="indicator col-md-2 glyphicon glyphicon-large glyphicon-chevron-right"></i>
                 <span class="col-md-3 badge"> {{ Session::has('MyYear') ? " " . Session::get('MyYear') . "/" . ( Session::get('MyYear') + 1 ): "2015/2016" }}</span>
                </div>
              </h4>
            </div>
            <div id="collapseSS3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="list-group">
                <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                            array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                             'Class' => 'SS3', 'SubClass' => 'A'))}}">  SS3 A </a> </li>
                <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                            array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                             'Class' => 'SS3', 'SubClass' => 'B'))}}">  SS3 B </a> </li>
                <li class="list-group-item"> <a href="{{URL::route($WhichRoute,
                                            array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
                                             'Class' => 'SS3', 'SubClass' => 'C'))}}">  SS3 C </a> </li>
              </div>
            </div>
          </div>
          <!--<div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              <h4 class="panel-title" >
                <div class="collapsed form-inline" role="button" >
                  <span for="search">Subject</span>
                  <input type="text" id="search" class="form-control"  placeholder="Find subject" />
                </div>
              </h4>
            </div>
          <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
               <div class="panel-body">
                    @if(isset($AllSubjects) and is_array($AllSubjects))
                     <table  class="ChangeRolesTable table table-striped table-responsive">
                     <tr> </tr>
                            @foreach($AllSubjects as $EverySubject)
                            <tr>
                               <td> </td>
                                <td>  <a href="{{URL::route('subject-page', array('SubjectId' => $EverySubject['subjectid'],
                                                                                  'SubjectName' => $EverySubject['subject'] ))}}">
                                                  {{$EverySubject['subject']}}
                                      </a>
                                </td>
                            </tr>
                            @endforeach
                       </table>

                  @else
                      Subjects UnAvailable
                  @endif
              </div>
            </div>
          </div> -->
        </div>
      </div>
      <div class="ChangeStudentYear"></div>
      <div class="row">
        <div class="bootcards-cards">
          <div class="panel panel-default bootcards-summary">
            <div class="panel-heading">
              <div  class="row">
                  <div class="panel-title pull-left PanelHeadingStatistics"> <b> Results 2017 (2nd Term)</b></div>
                  <div class="pull-right StudentInfoGraphClass">
                   <a class="btn CheckResultLink" href="{{URL::action('student-page')}}">
                  <i class="glyphicon glyphicon-check"></i>Check Result</a>
                  </div>
              </div>
            </div>
            <div class="panel-body">

              <div class="row">
                <div class="col-xs-6 col-sm-4">
                  <a class="bootcards-summary-item" href="#">
                    <i class="fa fa-3x fa-users"></i>
                    <h5> Number of Students
                    <span class="label label-danger StudentCount">0</span></h5>
                  </a>
                </div>
                <div class="col-xs-6 col-sm-4">
                  <a class="bootcards-summary-item" href="#">
                    <i class="fa fa-3x fa-building-o"></i>
                    <h5> Numbers of Scores
                    <span class="label label-danger ScoreCount">0</span></h5>
                  </a>
                </div>
                <div class="col-xs-6 col-sm-4">
                  <a class="bootcards-summary-item" href="#">
                    <i class="fa fa-3x fa-clipboard"></i>

                    <h5> Percentage of Scores
                     <span class="label label-danger ResultCount ">0%</span></h5>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
        <!-- </div>-->
       <!--  <div class="NavBar col-md-7">
              <a href="{{ URL::route('home')}}" class="Offset2">Home</a>
              <a href="{{URL::action('student-page')}}" class=""> Students </a>
              <a href="{{URL::action('student-registration-form')}}" class=""> Teachers </a>
              <a href="{{URL::action('student-question-page')}}" class=""> Practise Questions </a>


          </div> -->
    <div class="col-md-9">
        <div class="row SiteTitle">
          <div class="col-md-8">
              <h4 class="SchoolHeader"> Ijaiye Housing Estate Senior Grammar School </h4>
          </div>
          <div class="col-md-4">
            <div class="row">
              <div class="col-md-9 pull-right">
                  @if(Auth::check())
                        <div class="dropdown">
                            <button id="dLabel" type="button" class="btn btn-danger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              {{Auth::user()->surname}}
                              <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dLabel">
                              <li>  <a href="{{URL::route('user-profile')}}" > <b> Your Profile</b></a></li>
                              <li class="divider" role="separator"></li>
                              <li> <a href="{{URL::route('signout')}}" > <b> Sign out</b></a> </li>
                            </ul>
                        </div>
              </div>
              <div class="col-md-3">
               @else
                  <a href = '{{URL::route("login-form")}}' class="btn LoginButton"> Login </a>

                  @endif
              </div>
            </div>
          </div>
        </div>

      <!-- <div class="row CentralContent">      -->
         @yield('ShoutOut')
         @yield('adminlinks')
         @yield('division_container')
      <!-- </div> -->
    </div>
  </div>
    @include('includes.footer')
    <input type="hidden" class="InfoGraphicURL" value= "{{URL::route('inforgrah')}}" />
</div>




  <script>
    // To attach Backstrech as the body's background
    //$.backstretch("Images/Logos/b.JPG");

    // You may also attach Backstretch to a block-level element
    //$(".foo").backstretch("path/to/image.jpg");

    // Or, to start a slideshow, just pass in an array of images
    /*$(".DivisionContainerInner").backstretch([
      "Images/Logos/b.JPG",
      "Images/Logos/c.JPG",
      "Images/Logos/d.JPG",
      "Images/Logos/g.JPG"
    ], {duration: 5000, fade: 750,  centeredY: true  }); */
  </script>
  <script type="text/javascript">
    /**
     * You must include the dependency on 'ngMaterial'
     */
//   // angular.module('BlankApp', ['ngMaterial']);
//    angular.module('BlankApp', ['ngMaterial', 'ngResource'], function( $interpolateProvider, $httpProvider){
//  $interpolateProvider.startSymbol('<%');
//  $interpolateProvider.endSymbol('%>');
//  //$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
//})
//
//.controller('AppCtrl', function($scope, $mdDialog)
//{
//  $scope.imagePath =  "/Images/Logos/FavIjayeSchool.jpg";//'img/washedout.png';
//   var alert;
//    $scope.cardClicked = function($event, StudentListID) {
//    // Appending dialog to document.body to cover sidenav in docs app
//    // Modal dialogs should fully cover application
//    // to prevent interaction outside of dialog
//    console.log($('#myStaticDialog'+StudentListID).find("md-dialog-content").text());
//    alert = $mdDialog.alert({
//      //$mdDialog.alert()
//      // .parent(angular.element(document.querySelector('#popupContainer')))
//        //clickOutsideToClose(true)
//        title : 'Student Detail',
//       // .contentElement('Welcome to TutorialsPoint.com')
//        content : $('#myStaticDialog'+StudentListID).find("md-dialog-content").text(),
//        //.ariaLabel('Alert Dialog Demo')
//        ok : 'Got it!'
//        //.targetEvent(ev)
//    });
//
//    $mdDialog
//      .show( alert )
//      .finally(function() {
//        alert = undefined;
//      });
//  };
//}).config(['$resourceProvider', function($resourceProvider) {
//  // Don't strip trailing slashes from calculated URLs
//  $resourceProvider.defaults.stripTrailingSlashes = false;
//}]);
////.config(function($resourceProvider) {
//  //$mdThemingProvider.theme('dark-grey').backgroundPalette('grey').dark();
//  //$mdThemingProvider.theme('dark-orange').backgroundPalette('orange').dark();
//  //$mdThemingProvider.theme('dark-purple').backgroundPalette('deep-purple').dark();
//  //$mdThemingProvider.theme('dark-blue').backgroundPalette('blue').dark();
//   //$resourceProvider.defaults.stripTrailingSlashes = false;
////});
  </script>
</body>
</html>