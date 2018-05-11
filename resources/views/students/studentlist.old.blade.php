@extends('layouts.main')

@section('division_container')
 
  @if(isset($ChoosenTerm) and is_array($ChoosenTerm))
  
    <h3 layout="row" layout-align="center center"> List of Students in {{ $ChoosenTerm['Class']." ".strtoupper($ChoosenTerm['SubClass'])
         ." ". $ChoosenTerm['Year'] }} 
    </h3>    
    <div class="StudentListHeading">

            @if(isset($StudentListMessage))
                 <b> {{$StudentListMessage}}</b> <br />
            @endif  

            @if(isset($StudentClassList) and is_array($StudentClassList) and !empty($StudentClassList))
                    <b class='StudentListInfo'> Total number of students: <b>
                    	  {{ count($StudentClassList) }} </b>
    </div>
                  <?php $SplitStudentClass = array_chunk($StudentClassList, ceil(count($StudentClassList) / 3));
                // var_dump(count($things[0]));
                 $count = 1;   ?>
          <b> Search for anybody on this list using their surname:
           </b> <input type="text" id="CardSearch" placeholder="Search this list"></input>
            <p class="ShowTheCount"> </p>

               <md-content class="md-padding" layout-xs="column" layout="row">            
                  @foreach($SplitStudentClass as $EachSplitedStudentClass) 
                    <div  flex-xs flex-gt-xs="33" layout="column">
                         @foreach($EachSplitedStudentClass as $EachSplitedStudent)                    
                            <md-card  id="{{$count}}" md-theme="<% showDarkTheme ? 'dark-purple' : 'default' %>" md-theme-watch>                            
                            <md-list-item class="clickCard" ng-click="cardClicked($event, {{$count}})">
                              <md-card-title >                               
                                <md-card-title-text>
                                  <span class="md-headline"><b>{{$EachSplitedStudent['user_belong']['surname']}} </b></span>
                                  <span class="md-subhead">
                                    @if (strlen($EachSplitedStudent['user_belong']['firstname']) > 80)
                                          {{ $str = substr($EachSplitedStudent['user_belong']['firstname'] , 0, 7) . ' ';
                                          }}                                           
                                          @else
                                               {{ $EachSplitedStudent['user_belong']['firstname'] }} 
                                    @endif
                                                            
                                  </span>
                                </md-card-title-text>
                                <md-card-title-media>
                                  <div class="md-media-sm card-media">
                                     <md-card-avatar>
                                       
                                          {{HTML::image("/Images/Icons/avatar.jpg", 
                                                 'avatar', array('class' => 'img-responsive md-user-avatar',) )}}
                                    </md-card-avatar>
                                  </div>
                                </md-card-title-media>

                              </md-card-title>
                              </md-list-item>
                               <md-divider></md-divider>
                            <!--  <md-card-actions layout="row" layout-align="center center">
                                 <strong>  {{$EachSplitedStudent['school_admission_number']}}   
                                  {{ asset("../app/views/details.blade.php") }} </strong>
                              </md-card-actions>-->
                              
                            </md-card>                            
                            <div style="visibility: hidden">
                                <div class="md-dialog-container" id="myStaticDialog{{$count}}">
                                  <md-dialog aria-label="List dialog">
                                  <md-dialog-content>
                                      Full Name  :  {{$EachSplitedStudent['user_belong']['surname']}}  
                                                    {{$EachSplitedStudent['user_belong']['firstname']}} 
                                                    {{$EachSplitedStudent['user_belong']['middlename']}}

                                      
                                  </md-dialog-content>                                   
                                  </md-dialog>
                                </div>
                              </div> 
                              <?php $count++ ?>      
                         @endforeach
                     </div>
                  @endforeach          
              </md-content>
    @else
      <br />There is no student in this class yet
    @endif
    <!--
    <md-content class="md-padding" layout-xs="column" layout="row">
    <div flex-xs flex-gt-xs="33" layout="column">
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
          </md-card-actions> -
      </md-card>     
    </div>
     <div flex-xs flex-gt-xs="33" layout="column">
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
     </div>
     <div flex-xs flex-gt-xs="33" layout="column">
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
     </div>
    
    </md-content> -->

  @endif
@stop