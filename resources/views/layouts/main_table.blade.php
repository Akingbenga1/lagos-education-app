<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title> {{$Title}} </title>
 <meta name="csrf-token" content="<?= csrf_token() ?>">
 <meta name="viewport" content="width=device-width, initial-scale=1">

<meta charset="utf-8">
<meta name="author" content="Akinbami Akinsola Oluwagbenga">


{{HTML::style('CSS/bootstrap.min.css', array('media' => 'screen'))}}
{{HTML::style('CSS/font-awesome/css/font-awesome.min.css', array('media' => 'screen'))}}
{{HTML::style('CSS/SiteStyle.css', array('media' => 'screen'))}}
{{HTML::style('CSS/jquery-ui-style.css')}}
{{HTML::style('CSS/w3.css', array('media' => 'screen'))}}
{{HTML::style('CSS/bootcards-desktop.min.css', array('media' => 'screen'))}}

 <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">

<!-- Bootcards CSS for desktop: -->

 <!-- Angular Material requires Angular.js Libraries -->
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-route.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-resource.js"></script>
  
  

  <!-- Angular Material Library -->
  <script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.js">  </script>

 {{HTML::script('JS/jquery.js')}}
 {{HTML::script('JS/jquery-ui.min.js')}}
 {{HTML::script('JS/jquery.popupoverlay.js')}}
 {{HTML::script('JS/bootstrap.min.js')}}
 {{HTML::script('JS/jquery.autocomplete.min.js')}}
 

 {{HTML::script('JS/common.js')}}
 {{HTML::script('JS/jquery.backstretch.min.js')}}

 {{HTML::script('JS/ajaxeditscore.js')}}
 
 {{HTML::script('JS/min.js')}}
 {{HTML::script('JS/SlideShow.js')}}
 <!-- <script src="//cdn.ckeditor.com/4.5.9/standard/ckditor.js"></script> -->
 {{HTML::script('JS/ckeditor/ckeditor.js')}}
 {{HTML::script('JS/Chart.min.js')}}
 {{HTML::script('JS/ajaxinfograph.js')}}
  
 {{HTML::script('JS/bootcards.js')}}
 <script src=""></script>



</head>
<body ng-app="BlankApp" ng-controller="AppCtrl" ng-cloak>
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
    <div class="col-md-12">
        <div class="row SiteTitle">
          <div class="col-md-8"> 
              <h4 class="SchoolHeader"> Ijaiye Housing Estate Senior Grammar School </h4>
          </div>

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
   // angular.module('BlankApp', ['ngMaterial']);
    angular.module('BlankApp', ['ngMaterial', 'ngResource'], function( $interpolateProvider, $httpProvider){
  $interpolateProvider.startSymbol('<%');
  $interpolateProvider.endSymbol('%>');
  //$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
})

.controller('AppCtrl', function($scope, $mdDialog) 
{
  $scope.imagePath =  "/Images/Logos/FavIjayeSchool.jpg";//'img/washedout.png';
   var alert;
    $scope.cardClicked = function($event, StudentListID) {
    // Appending dialog to document.body to cover sidenav in docs app
    // Modal dialogs should fully cover application
    // to prevent interaction outside of dialog
    console.log($('#myStaticDialog'+StudentListID).find("md-dialog-content").text());
    alert = $mdDialog.alert({
      //$mdDialog.alert()
      // .parent(angular.element(document.querySelector('#popupContainer')))
        //clickOutsideToClose(true)
        title : 'Student Detail',
       // .contentElement('Welcome to TutorialsPoint.com')
        content : $('#myStaticDialog'+StudentListID).find("md-dialog-content").text(),
        //.ariaLabel('Alert Dialog Demo')
        ok : 'Got it!'
        //.targetEvent(ev)
    });

    $mdDialog
      .show( alert )
      .finally(function() {
        alert = undefined;
      });
  };
}).config(['$resourceProvider', function($resourceProvider) {
  // Don't strip trailing slashes from calculated URLs
  $resourceProvider.defaults.stripTrailingSlashes = false;
}]);
//.config(function($resourceProvider) {
  //$mdThemingProvider.theme('dark-grey').backgroundPalette('grey').dark();
  //$mdThemingProvider.theme('dark-orange').backgroundPalette('orange').dark();
  //$mdThemingProvider.theme('dark-purple').backgroundPalette('deep-purple').dark();
  //$mdThemingProvider.theme('dark-blue').backgroundPalette('blue').dark();
   //$resourceProvider.defaults.stripTrailingSlashes = false;
//});
  </script>
</body>
</html>