@extends('layouts.main')
@section('division_container')
@if(isset($ThisStudent) and is_array($ThisStudent) and !empty($ThisStudent))
     <?php
     //if(isset($SubjectScoreJson))  var_dump($SubjectScoreJson);
     $StudentName = array_key_exists("user_belong", $ThisStudent ) ?
         $ThisStudent["user_belong"]["firstname"] . " ".
         $ThisStudent["user_belong"]["surname"]. " "  :
         "No Name";

     ?>

     <h3 class="center-align">Your Report Card </h3>

        @if(isset($SubjectScore) and isset($RequestedTerm) and !empty($RequestedTerm) and  is_array($SubjectScore) and !empty($SubjectScore))
            <script type='text/javascript'>
                <?php
                if(isset($SubjectScoreJson))
                    {
                         echo "var SubjectScoreJson = ". $SubjectScoreJson . ";\n";
                    }
                if(isset($RequestedTerm))
                    {
                        echo "var RequestedTerm = ". json_encode(ucwords($RequestedTerm))  . ";\n";
                    }
                if(isset($FirstTermSubjectScoreJson))
                    {
                         echo "var FirstTermSubjectScoreJson = ". $FirstTermSubjectScoreJson . ";\n";
                    }
                 if(isset($SecondTermSubjectScoreJson))
                    {
                         echo "var SecondTermSubjectScoreJson = ". $SecondTermSubjectScoreJson . ";\n";
                    }
                if(isset($SubjectScoreArray_Compare))
                    {
                        echo "var SubjectScoreArray_Compare = ". $SubjectScoreArray_Compare . ";\n";
                    }
                if(isset($ThirdSubjectScoreArray_Compare))
                    {
                        echo "var ThirdSubjectScoreArray_Compare = ". $ThirdSubjectScoreArray_Compare . ";\n";
                    }


                ?>
                if(typeof SubjectScoreJson !== "undefined")
                {
                    //console.log(SubjectScoreJson);
                }
                if(typeof FirstTermSubjectScoreJson !== "undefined")
                {
                    //console.log(FirstTermSubjectScoreJson)
                }
                if(typeof SecondTermSubjectScoreJson !== "undefined")
                {
                    //console.log(SecondTermSubjectScoreJson );
                }
                if(typeof SubjectScoreArray_Compare !== "undefined")
                {
                    //console.log(SubjectScoreArray_Compare );
                }

                if(typeof ThirdSubjectScoreArray_Compare !== "undefined")
                {
                    console.log(ThirdSubjectScoreArray_Compare );
                }

                //console.log(SubjectScoreJson, FirstTermSubjectScoreJson,SecondTermSubjectScoreJson );
            </script>

            <div class="row">
                <div class="col s12">
                    <ul class="tabs tabs tab-demo z-depth-1">
                        <li class="tab col s4  light-blue lighten-1 "><a href="#ReportCard" class="active white-text"  > {{$StudentName}} - Report Card</a></li>
                        <li class="tab col s4  light-blue lighten-2"><a  href="#TermAnanlysis" class="white-text"  >  Score Analysis - {{$RequestedTerm}} </a></li>
                        <li class="tab col s4  light-blue darken-3"><a href="#SessionAnalysis" class="white-text" > Score Comparison - Academic Session </a></li>
                    </ul>
                </div>
                <div id="ReportCard" class="col s12">


                        <div class="row">
                            <div class="StudentDossier">
                                @include('includes.reportview')
                            </div>
                        </div>
                </div>
                <div id="TermAnanlysis" class="col s12">

                </div>
                <div id="SessionAnalysis" class="col s12">

                </div>
            </div>
            <div class="fixed-action-btn"  style="top: 55px; right: 54px;">
                <a class="btn-floating btn-large light-blue darken-4 tooltipped pulse"  data-position="left" data-delay="50" data-tooltip="Go Back to Student Page"
                   href="{{route('student-page')}}" >
                    <i class="material-icons">keyboard_backspace</i>
                </a>
            </div>
            <div class="fixed-action-btn" style="top: 250px; right: 54px;">
                {{--<a class="btn-floating btn-large red tooltipped pulse"  data-position="left" data-delay="50" data-tooltip="{{$StudentName}} | Download Report Sheet | PDF"--}}
                   {{--href="{{URL::route('get-report-pdf')}}" >--}}
                    {{--<i class="material-icons">file_download</i>--}}
                {{--</a>--}}

            </div>

        @else
            <div class="fixed-action-btn"  style="top: 55px; right: 54px;">
                <a class="btn-floating btn-large light-blue darken-4 tooltipped pulse"  data-position="left" data-delay="50" data-tooltip="Go Back to Student Page"
                   href="{{URL::route('student-page')}}" >
                    <i class="material-icons">keyboard_backspace</i>
                </a>
            </div>
            <div class="card"> <b> You do not have any result at the moment</b> </div>
        @endif

@else
    <h3 class="center-align">Your Report Card </h3>
    <div class="fixed-action-btn"  style="top: 55px; right: 54px;">
        <a class="btn-floating btn-large light-blue darken-4 tooltipped pulse"  data-position="left" data-delay="50" data-tooltip="Go Back to Student Page"
           href="{{URL::route('student-page')}}" >
            <i class="material-icons">keyboard_backspace</i>
        </a>
    </div>
    <div class="card"> Your record cannot be found on the system.</div>
@endif

<div class="row">
    <div class="col l8 offset-l2 m12 s12 z-depth-4 card-panel">
        <form class="form-horizontal" action="{{URL::route('student-page')}}" method="post">
            <div class="row">
                <h5 class="center-align"> Check and Download Report Sheet</h5>
            </div>
            <div class="row">
                @if(!empty($AllStudents) and is_array($AllStudents))
                    <div class="">
                        @if($errors->has('TypeStudentName'))
                            <span class="text-danger StudentError">{{$errors->first('TypeStudentName')}}</span>
                        @endif
                        @include('includes.choosestudentasarray')
                    </div>
                @else
                    No Student Available
                @endif
            </div>

            <div class="row">
                <div class="StudentPageChooseTerm"> @include('includes.chooseterm')</div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
            </div>
            <div class="row">
                <button type = "submit"  class="center-align btn red col l8 offset-l2 m12 s12 ">
                    Get Report Sheet
                </button>
            </div>
        </form>
    </div>
</div>

<div class="tester">
    <canvas height="30"  width="50" >

    </canvas>
</div>
@stop
