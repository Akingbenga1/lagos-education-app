@extends('layouts.main')

@section('division_container')
    <h3 class="center-align"> Edit Student Details</h3>

    <div class="modal" id="myModal">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="centre-align" > Instruction for finding student </h4>
          </div>
          <div class="">
              <b>Step 1</b> Choose a Student by typing their names.<br />
              <b>Step 2</b> Click on the "Get Student Details" Button.<br />
              <b>Step 3</b> Student Details will be shown.<br />
          </div>
          <div class="modal-footer">
          <a href="#!" class="btn modal-action modal-close waves-effect gradient-45deg-semi-dark gradient-shadow  waves-green white-text ">Ok, Got it</a>
        </div>
        </div>
    </div>

    <div class="row">

      <a class="col s3 offset-s1  btn modal-trigger gradient-45deg-semi-dark gradient-sadow "   href="#myModal"><i class="material-icons">list</i> Instructions</a>
    </div>

  <div class="row">
      <form class="" action="{{URL::route('get-student-details')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data" >
          {{-- <h4 class="center-align"> <b class=""> Find Student </b></h4> --}}

          <div class="row">
            <div class="col s8 offset-s2 ">

              <br />
              <br />
              <br />
                 @if(!empty($AllStudents) and is_array($AllStudents))
                    @include('includes.choosestudentasarray')
                 @endif
                 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            </div>
          </div>
              <div class="row">
                 <button  type="submit" id="AutoButton" class="btn btn-large col s7 m7 l7 offset-s3 offset-m3 offset-l3 "  disabled="true" > <i class="material-icons">search</i>  Get student details  </button>

                  {{-- </button> --}}
              </div>
      </form>
  </div>
  <div class="fixed-action-btn"  style="top: 150px; right: 54px;">
      <a class="btn-floating btn-large gradient-45deg-semi-dark gradient-shadow tooltipped pulse"  data-position="left" data-delay="50" data-tooltip="Back to Teachers Home"
         href="{{URL::route('teachers-home-page')}}" >
          <i class="material-icons">keyboard_backspace</i>
      </a>
  </div>
@stop
