@extends('layouts.main')

  @section('division_container')
     @if( isset($ThisUser) and is_array($ThisUser)    )
         {{--<h3 class="center-align"> {{$ThisUser['surname']}}  {{$ThisUser['firstname']}} {{$ThisUser['middlename']}}'s Profile </h3>--}}
     @endif


  <div class="row">
      <div class="col s12 l4 m12 z-depth-4 " style="border:0px solid black;">
          <div id="profile-page-header" class="card gradient-shadow">
              <figure class="card-profile-image center-align">
                  {{--{{ HTML::image("/Images/Icons/avatar.jpg",'avatar', array('class' => 'circle',  'height' => '250', 'width' => '30', 'style' => "width:40%!important; height:100%!important;mrgin-left:30%;") )}}--}}
              </figure>
              <div class="card-content">
                  <div class="row">
                     {{--<h4 class="center-align"> {{$ThisUser['surname']}} {{$ThisUser['firstname']}} {{$ThisUser['middlename']}}   </h4>--}}
                     <h6 class="center-align">
                         {{--{{$ThisUser['useremail']}}  --}}
                     </h6>
                  </div>
                  <div class="row">
                      <?php
                    //  var_dump($ThisStudentTerm);die();
                          $ThisStudentClass = ( is_array($ThisStudentTerm) and count($ThisStudentTerm) > 0 and array_key_exists('thisterm_belong', $ThisStudentTerm ) and  array_key_exists('classname', $ThisStudentTerm['thisterm_belong'] ) ) ?   $ThisStudentTerm['thisterm_belong']['classname'] : '' ;

                      $ThisStudentYear = ( is_array($ThisStudentTerm) and count($ThisStudentTerm) > 0 and array_key_exists('thisterm_belong', $ThisStudentTerm ) and  array_key_exists('year', $ThisStudentTerm['thisterm_belong'] ) ) ?   $ThisStudentTerm['thisterm_belong']['year'] : '' ;

                      $ThisStudentTermName = ( is_array($ThisStudentTerm) and count($ThisStudentTerm) > 0 and array_key_exists('thisterm_belong', $ThisStudentTerm ) and  array_key_exists('termname', $ThisStudentTerm['thisterm_belong'] ) ) ?   $ThisStudentTerm['thisterm_belong']['termname'] : '' ;

                      $ThisStudentSubClass = ( is_array($ThisStudentTerm) and count($ThisStudentTerm) > 0 and array_key_exists('class_subdivision', $ThisStudentTerm )  ) ?   strtoupper( $ThisStudentTerm['class_subdivision']) : '';
                          $CurrentClass = $ThisStudentClass . " " . $ThisStudentSubClass . " " .$ThisStudentTermName;
                          $CurrentYear = $ThisStudentYear ;
                          $AvailableResultTerms = '';
                          $StudentID =   array_key_exists('studentid', $ThisStudentTerm ) ? $ThisStudentTerm['studentid']  : 0;
                          $ResultClass = $ThisStudentClass;
                        if ( is_array($ThisStudentScores) and count($ThisStudentScores) > 0)
                            {
                                echo "<div class='row'>";

                                foreach($ThisStudentScores as $EachThisStudentScores)
                                {
                                    //var_dump($EachThisStudentScores['termname']);
                                    $AvailableResultTerms .=  array_key_exists('termname', $EachThisStudentScores) ?
                                    '<div class="col s4 m4 l4"> <a href="'. URL::route('get-student-report',
                                                                        array('Year' => $CurrentYear, 'StudentId' => $StudentID,  'Class' => $ResultClass,
                                                                        'TermName' => strtolower($EachThisStudentScores['termname']), 'SubClass' => $ThisStudentSubClass  )) .'" class="">' . ucfirst($EachThisStudentScores['termname']) . "</a> </div> " : '';
                                }
                                echo "</div>";
                            }
                      ?>
                      @if( count($ThisStudentTerm) > 0 )
                          <div class="row">
                              <div class="col s12 m6 l6 center-align">
                                  <h5 class="card-title grey-text text-darken-4">Current Class </h5>
                                  <p class="medium-small grey-text"> {{ $CurrentClass  }}</p>
                              </div>
                              <div class="col s12 m6 l6 center-align">
                                  <h5 class="card-title grey-text text-darken-4"> Current Year </h5>
                                  <p class="medium-small grey-text"> {{ $CurrentYear  }}</p>
                              </div>
                          </div>
                          <div class="row" style="boder: 1px solid black">
                              <h5 class="col s12 m12 l12  center-align  card-title grey-text text-darken-4">Result Availability </h5><br />
                              <p class="medium-small grey-text">{{$AvailableResultTerms}}</p>
                          </div>
                          <div class="row" style="min-height: 20px!important;margin-top: 20px!important;">
                            @if( Session::get('SourceUrl')  == "ResultAvailable" )
                              @if(Session::has('success'))
                                  <div class="col s12 light-green lighten-3 white-text center-align"  style="min-height: 20px!important;">
                                          {{ Session::get('success') }}
                                  </div>
                              @endif
                              @if(Session::has('error'))
                                  <div class="col s12  red accent-1 white-text center-align"  style="min-height: 20px!important;">
                                          {{ Session::get('error') }}
                                  </div>
                              @endif
                              @if(count($errors) > 0 )
                                  <div class="col s12 red accent-1 white-text center-align"  style="min-height: 20px!important;" >
                                      @include('includes.errors.allerrors')
                                  </div>
                              @endif
                          @endif
                          </div>
                      @endif
                      <div class="col s12 m12 l12 center-align">
                          <a class="btn-floating activator waves-effect waves-light rec accent-2 right">
                              <i class="material-icons">perm_identity</i>
                          </a>
                      </div>
                  </div>

              </div>
              <div class="card-reveal">

                  <p>
                  <span class="card-title grey-text text-darken-4">
                      <i class="material-icons right">close</i>
                  </span>
                      <span>
                      <i class="material-icons blue-text text-darken-2">comment</i> Your Thoughts</span>
                  </p>
                  @if( $YourThoughts == "You have not posted any Thoughts yet.")
                      <p style="color:#78909c"> {{ $YourThoughts["thoughts"]}} </p>
                  @else
                      <p  style="color:#78909c"> {{  $YourThoughts["thoughts"]}} </p>
                  @endif

                  <div class="row">
                      <p>  <b> Surname:
                          {{--</b>{{$ThisUser['surname']}}--}}
                      </p>
                      <p>  <b> Firstname:     </b>
                          {{--{{$ThisUser['firstname']}} </p>--}}
                      <p>
                          {{--<b> Middle name:   </b> {{$ThisUser['middlename']}}--}}
                      </p>
                         {{--@if( !is_null( $ThisUser['student_relate']))--}}
                          {{--<p>--}}
                              {{--<b> Student Admission Number:  </b>{{$ThisUser['student_relate']['school_admission_number']}}--}}
                          {{--</p>--}}
                      {{--@endif--}}

                      <p> <b> Primary Email Address:
                          {{--</b> {{$ThisUser['useremail']}}--}}
                      </p>
                      <p> <b> Date of Birth: </b>
                          {{--{{ date('d/m/Y', strtotime($ThisUser['date_of_birth']))  }}--}}
                      </p>
                      <p>
                          {{--<b> Gender:       --}}
                          {{--</b>{{$ThisUser['sex']}}--}}
                      </p>
                  </div>
              </div>
          </div>
      </div>

      <div class="col s12 l8 m12" style="border:0px solid black;">
          <div class="row">
              <div class="col s12">
                  <ul class="tabs tab-demo z-depth-1">
                      <li class="tab col s4  light-blue lighten-1 "><a href="#EditUserDetails" class="active white-text"  >  Edit User Details </a></li>
                      <li class="tab col s4  light-blue lighten-2"><a  href="#YourThoughts" class="white-text"  >  Your Thoughts  </a></li>
                      <li class="tab col s4  light-blue darken-3"><a href="#SchoolNews" class="white-text" > School News / Announcement </a></li>
                  </ul>
              </div>
              <div id="EditUserDetails" class="col s12">
                   {{-- {{ Form::open(array( 'route' => 'post-user-profile' ,'files' => true, 'method'=> 'post','class'=> '')) }} --}}

                          {{--<form class="col s12" action="{{URL::route('post-user-profile')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data">--}}

                            {{--<div class="row" style="min-height: 20px!important;margin-top: 20px!important;">--}}
                              {{--@if( Session::get('SourceUrl')  == "UserProfile" )--}}
                                {{--@if(Session::has('success'))--}}
                                    {{--<div class="col s12 light-green lighten-3 white-text center-align"  style="min-height: 20px!important;">--}}
                                            {{--{{ Session::get('success') }}--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                                {{--@if(Session::has('error'))--}}
                                    {{--<div class="col s12  red accent-1 white-text center-align"  style="min-height: 20px!important;">--}}
                                            {{--{{ Session::get('error') }}--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                                {{--@if(count($errors) > 0 )--}}
                                    {{--<div class="col s12 red accent-1 white-text center-align"  style="min-height: 20px!important;" >--}}
                                        {{--@include('includes.errors.allerrors')--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                            {{--@endif--}}
                            {{--</div>--}}


                              {{--<div class="row">--}}
                                {{--<div class="input-field col  s12 m6 l4 ">--}}
                                    {{--<i class="material-icons prefix"> account_circle</i>--}}
                                    {{--<input type="text" name="Firstname" required  value="{{!is_null(old('Firstname'))? old('Firstname') : $ThisUser['firstname']}}"  />--}}
                                    {{--<label for="email" class="center-align">Firstname</label>--}}
                                    {{--<?php--}}
                                        {{--//dd(Session::get('url.intended'));--}}
                                    {{--?>--}}
                                    {{--@if($errors->has('Firstname'))--}}
                                        {{--<span class="center-align LoginError" >{{$errors->first('Firstname')}}</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                {{--<div class="input-field col s12 m6 l4">--}}
                                    {{--<i class="material-icons prefix"> account_circle</i>--}}
                                    {{--<input type="text" id = "Middlename" name="Middlename"  value="{{!is_null(old('Middlename'))? old('Middlename') : $ThisUser['middlename']}}"    class="Middlename">--}}
                                    {{--<label for="email" class="center-align">Middle Name(optional)</label>--}}
                                    {{--@if($errors->has('Middlename'))--}}
                                        {{--<span class="center-align LoginError" >{{$errors->first('Middlename')}}</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                {{--<div class="input-field col s12 m12 l4 ">--}}
                                    {{--<i class="material-icons prefix"> account_circle</i>--}}
                                    {{--<input type="text" name="Surname" required id="Surname" value="{{!is_null(old('Surname'))? old('Surname') : $ThisUser['surname']}}" class="Surname" />--}}
                                    {{--<label for="Surname" class="center-align">Surname</label>--}}
                                    {{--@if($errors->has('Surname'))--}}
                                        {{--<span class="center-align LoginError" >{{$errors->first('Surname')}}</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                              {{--</div>--}}
                              {{--<div class="row">--}}
                                {{--<div class="input-field col  s12 m6 l4 ">--}}
                                    {{--<i class="material-icons prefix"> account_circle</i>--}}
                                    {{--<input type="text" name="SecondEmail" id="SecondEmail" data-parsley-type="email" value="{{!is_null(old('SecondEmail'))? old('SecondEmail') : $ThisUser['second_email']}}"  />--}}
                                    {{--<label for="SecondEmail" class="center-align">Second Email</label>--}}
                                    {{--@if($errors->has('SecondEmail'))--}}
                                        {{--<span class="center-align LoginError" >{{$errors->first('SecondEmail')}}</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                {{--<div class="input-field col s12 m6 l4" style="position:relative;">--}}
                                    {{--<i class="material-icons prefix"> account_circle</i>--}}
                                    {{--<?php--}}
                                      {{--$Old_DOB = !is_null(old('DateOfBirth'))? old('DateOfBirth') : null;--}}
                                      {{--$Current_DOB = array_key_exists('date_of_birth', $ThisUser) ? date('d/m/Y', strtotime($ThisUser['date_of_birth'])) : '';--}}
                                     {{--?>--}}
                                    {{--<input type="text" id="DateOfBirth" name="DateOfBirth" data-parsley-date name="DateOfBirth" value="{{!is_null($Old_DOB)? $Old_DOB : $Current_DOB  }}"   required />--}}
                                    {{--<label for="DateOfBirth" class="center-align">Date Of Birth ( DD/MM/YYYY )</label>--}}
                                    {{--@if($errors->has('DateOfBirth'))--}}
                                        {{--<span class="center-align LoginError" >{{$errors->first('DateOfBirth')}}</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                                {{--<div class="input-field  black-text col s12 m12 l4 ">--}}
                                  {{--<i class="material-icons prefix"> account_circle</i>--}}
                                  {{--<select name="Sex"  >--}}
                                      {{--@if($ThisUser['sex'] === 'Male')--}}
                                          {{--<option value="Male" selected="selected"> Male</option>--}}
                                          {{--<option value="Female">Female </option>--}}
                                      {{--@else--}}
                                          {{--<option value="Male" > Male</option>--}}
                                          {{--<option value="Female" selected="selected">Female </option>--}}
                                      {{--@endif--}}
                                  {{--</select>--}}
                                    {{--<label> Choose Gender </label>--}}
                                    {{--@if($errors->has('Sex'))--}}
                                        {{--<span class="center-align LoginError">{{$errors->first('Sex')}}</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                              {{--</div>--}}
                              {{--{{ csrf_field() }}--}}
                                   {{--<div class="row">--}}
                                       {{--<div class="input-field col s12">--}}
                                           {{--<button type="submit" class="btn waves-effect waves-light col s12" >Save changes</button>--}}
                                       {{--</div>--}}
                                   {{--</div>--}}
                              {{--</form>--}}
                              {{-- {{Form::close()}} --}}

              </div>
      </div>
              <div id="YourThoughts" class="col s12">
              @if( Session::get('SourceUrl')  == "YourThoughts" )
                <div class="row" style="min-height: 20px!important;margin-top: 5px!important;">
                    @if(Session::has('success'))
                        <div class="col s12 light-green lighten-3 white-text center-align"  style="min-height: 20px!important;">
                                {{ Session::get('success') }}
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="col s12  red accent-1 white-text center-align"  style="min-height: 20px!important;">
                                {{ Session::get('error') }}
                        </div>
                    @endif
                    @if(count($errors) > 0 )
                        <div class="col s12 red accent-1 white-text center-align"  style="min-height: 20px!important;" >
                            @include('includes.errors.allerrors')
                        </div>
                    @endif
                </div>
            @endif
                    <div class="row">
                      <h5 class="col s12 m12 l12 center-align"> Say something about yourself or your class or the school  </h5> </div>
                          <?php
//                  Session::has('SourceUrl') ? var_dump(Session::get('SourceUrl')) :   var_dump(null)
                  ?>
                      <div class="col s12 m12 l12">
                          <form class="col s12" action="{{URL::route('post-your-thoughts')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data">
                        <div class="input-field col s12">
                              <textarea name="Thoughts" id="Thoughts_textarea" class="materialize-textarea " data-length="120" data-parsley-range="[1, 120]" required>{{old('Thoughts')}}</textarea>
                              <label for="textarea1"> Type Something </label>
                        </div>
                        <div class="input-field col s12">
                          <br />
                            {{ csrf_field() }}
                            <button type="submit" class="btn waves-effect waves-light col s12 m4 l4 offset-m4 offset-l4" >Submit</button>
                        </div>
                      </form>
                      </div>
                    </div>
              </div>
              <div id="SchoolNews" class="col s12">

              </div>
          </div>
      </div>
  </div>




  @stop
