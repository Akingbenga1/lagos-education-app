@extends('layouts.main')

@section('division_container')

<div class="DivisionContainer">
<h3 class="center-align"> Practice Question Page </h3>

    <div class="row">
        <div class="ReportMessage center-align "></div>
        <div class="ValidationErrorReport center-align LoginError"></div>
    </div>



    {{--<div class="progress">--}}
        {{--<div class="determinate" style="width: 70%"></div>--}}
    {{--</div>--}}


    <div class="ShowPieChart row  center-align" >
        <div class="col s6" >
            <div style="display:block;margin-top:150px;" ><h5> Result Pie Chart </h5> </div>
        </div>


    </div>


    <div>
        <form class="QuestionInputform" action="{{URL::route('submit-question-to-database')}}" method="post">

        <div class="ReportStudentClass center-align ">

        <div class="row">
                <div class="YearDiv col l4 m12 s12">
                    <select name = "Year" class="Year" >
                        <option> -- Choose year -- </option>
                        <option value="2015"> 2015/2016 </option>
                    </select>
                </div>

                <div class="ClassDiv col l4 m12 s12">
                    <select name = "Class" class="Class" >
                        <option> -- Choose class -- </option>
                        <option value="SS1">SS1</option>
                        <option value="SS2">SS2</option>
                        <option value="SS3">SS3</option>
                    </select>
                </div>

                <div class="SubjectDiv col l4 m12 s12">

                    <select name = "Subject" class="Subject " >
                        <option> -- Choose Subject -- </option>
                        @if(isset($AllSubjects) and is_array($AllSubjects))
                            @foreach($AllSubjects as $EverySubject)
                                <option value="{{$EverySubject['subjectid']}}"> {{$EverySubject['subject']}} </option>
                            @endforeach
                        @else
                            <option> No Category Available  </option>
                        @endif
                    </select>
                </div>
        </div>

        <div class="row">
            <button  class="GetQuestionButton col 12 waves-effect waves-light btn-large offset-s5 z-depth-4 light-blue darken-4 pulse"> Get Questions </button>
        </div>

        {{--<div class="row">--}}
            {{--<button  class='ResultButton'>View Results</button>--}}
        </div>

        <div class="row">
            <div id="QuestionPanelProgess" class="col l4 m12 s12 offset-14" >
                <br />
                <br />
                <br />
                <div class="progress light-blue  lighten-4">
                    <div class="indeterminate light-blue darken-4"></div>
                </div>
            </div>
            <ul class="QuestionsPanel collapsible popout col l10 m12 s12 offset-l1"  data-collapsible="accordion">
            </ul>
        </div>

        <div class="row">
            <button  class='ResultButton btn btn-large z-depth-4 light-blue darken-4 col s6 l6 m6' style="margin-left: 25%!important;">View Results</button>
        </div>

    <div id="modal1" class="modal ResultModal">
        <div class="modal-content">
            <h4 class="center-align">Question Response</h4>
            <div class="QuestionResponseModal row" style="height:150px!important;">

            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Ok</a>
        </div>
    </div>


        <input type="hidden" value="{{URL::route('get-questions')}}" class="GetQuestionURL" />
        <input type="hidden" value="{{URL::route('save-this-score')}}" class="SaveScoreURL" />
        <input type="hidden" value="{{URL::to('/')}}" class="BaseURL" />

            {{ csrf_field() }}
        </form>

    </div>

</div>



<script>

</script>
@stop

