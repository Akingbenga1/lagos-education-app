@extends('layouts.main')

  @section('division_container')
    @if(!empty($ThisStudent) and is_array($ThisStudent))
        <h3 class="center-align"> Account Details of {{is_array($ThisStudent['user_belong'])?
          $ThisStudent['user_belong']['firstname']." ".  $ThisStudent['user_belong']['surname'] :" " }} </h3>

           <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel"> Instructions for editing student details</h4>
                    </div>
                    <div class="modal-body">
                            <div class="StudentEditFormInstruction">
                              <b> Student Admission Number can be edited </b><br />
                              Student school email <b> cannot be editted</b> but it would be updated by the system.<br />
                              <b> Any other details can be editted </b><br />
                            </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
           </div>

        <div class="row">
                <form class="" action="{{URL::route('edit-student-details')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data">
                    <div class="row style="min-height:20px!important;margin-top:20px!important;">
                         @if(Session::has('EditStudentMessage') and Session::has('GoodResponse') and Session::get('GoodResponse') === 1 )
                            <div class="col s12 light-green lighten-3 white-text center-align">
                                <b> <i class=""></i>{{Session::get('EditStudentMessage')}}</b>
                            </div>
                          @elseif(Session::has('EditStudentMessage') and Session::has('GoodResponse') and Session::get('GoodResponse') === 0 )
                            <div class="col s12  red accent-1 white-text center-align">
                                <b><i class=""></i> {{Session::get('EditStudentMessage')}} </b>
                            </div>
                         @endif
                    </div>

                      <div class="row">
                            <div class="input-field col s3 m3 l3">
                                 <input type="text" class="form-control" required name="StudentAdmissionNumber" value="{{!is_null(old('StudentAdmissionNumber'))? old('StudentAdmissionNumber') : $ThisStudent['school_admission_number']}}" />
                                 <label for="email" class="center-align"> Student Admisssion Number</label>
                                 @if($errors->has('StudentAdmissionNumber'))
                                      <span class="center-align LoginError">  {{$errors->first('StudentAdmissionNumber')}}</span>
                                @else
                                @endif
                            </div>
                            <div class="input-field col s3 m3 l3">
                                  <input type="text" class="form-control" required name="Surname" value="{{!is_null(old('Surname'))? old('Surname') : $ThisStudent['user_belong']['surname']}}" />
                                  <label for="email" class="center-align"> Student Surname </label>
                                  @if($errors->has('Surname'))
                                     <span class="center-align LoginError"> {{$errors->first('Surname')}}</span>
                                  @endif
                            </div>
                            <div class="input-field col s3 m3 l3">
                                  <input type="text" class="form-control" name="Middlename" value="{{!is_null(old('Middlename'))? old('Middlename') : $ThisStudent['user_belong']['middlename']}}" />
                                  <label for="email" class="center-align"> Student Middle Name </label>
                                  @if($errors->has('Middlename'))
                                      <span class="center-align LoginError"> >>{{$errors->first('Middlename')}}</span>
                                  @endif
                            </div>
                            <div class="input-field col s3 m3 l3">
                                  <input type="text" class="form-control" required name="Firstname" value="{{!is_null(old('Firstname'))? old('Firstname') : $ThisStudent['user_belong']['firstname']}}" >
                                  <label for="email" class="center-align"> Student Firstname </label>
                                  @if($errors->has('Firstname'))
                                      <span class="center-align LoginError"> {{$errors->first('Firstname')}}</span>
                                  @endif
                            </div>
                      </div>
                      <div class="row">

                        <div class="input-field col s4 m4 l4">
                             <input type="text" class="form-control" required  name="StudentEmail"
                             value="{{!is_null(old('StudentEmail'))? old('StudentEmail') : $ThisStudent['user_belong']['useremail']}}"  disabled="diabled">
                             <label for="email" class="center-align">  Student School Email</label>
                             @if($errors->has('StudentEmail'))
                                         <span class="center-align LoginError"> {{$errors->first('StudentEmail')}}</span>
                             @endif
                        </div>

                        <div class="input-field col s4 m4 l4">
                             <input type="text" class="form-control" data-parsley-type="email"   name="SecondStudentEmail"  value="{{!is_null(old('SecondStudentEmail'))? old('SecondStudentEmail') : $ThisStudent['user_belong']['second_email']}}" />
                             <label for="email" class="center-align"> Student Secondary Email</label>
                             @if($errors->has('StudentEmail'))
                              <span class="center-align LoginError">  {{$errors->first('StudentEmail')}}</span>
                             @endif
                        </div>
                        <div class="input-field col s4 m4 l4">
                             <input type="text" class="form-control" name="DateOfBirth"
                             value="{{!is_null(old('DateOfBirth'))? old('DateOfBirth') : $ThisStudent['user_belong']['date_of_birth']}}"  />
                             <label for="email" class="center-align"> Student date of birth ( DD/MM/YYYY)</label>
                             @if($errors->has('DateOfBirth'))
                              <span class="center-align LoginError"> {{$errors->first('DateOfBirth')}}</span>
                             @endif
                        </div>
                      </div>
                      <div class="row">
                        <div class="input-field col s4 m4 l4">
                              <select class="form-control" name="Sex" >
                                @if($ThisStudent['user_belong']['sex'] === 'Male')
                                    <option value="Male" selected="selected"> Male</option>
                                    <option value="Female">Female </option>
                                  @else
                                    <option value="Male" > Male</option>
                                    <option value="Female" selected="selected">Female </option>
                                  @endif
                              </select>
                               <label for="email" class="center-align">Sex</label>
                               @if($errors->has('Sex'))
                                           <span class="center-align LoginError"> {{$errors->first('Sex')}}</span>
                               @endif
                        </div>
                        <div class="col s8 m8 l8">
                             <input type = "submit" class="col l6  m6 s6 btn btn-large gradient-45deg-semi-dark gradient-shadow  "  value="Save" id="RegistrationButton"  />
                             <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        </div>

                      </div>


                 </form>
        </div>


            <div id="ShowStudentEditMessage" > </div>
      @endif

      <div class="fixed-action-btn"  style="top: 150px; right: 54px;">
          <a class="btn-floating btn-large gradient-45deg-semi-dark gradient-shadow  tooltipped pulse"  data-position="left" data-delay="50" data-tooltip="Back to Find Student Page"
             href="{{URL::route('edit-student-details-form')}}" >
              <i class="material-icons">keyboard_backspace</i>
          </a>
      </div>
      @stop
