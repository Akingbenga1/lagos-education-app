@extends('layouts.main')
@section('division_container')
<div class="DivisionContainer">
              <h5 class="center-align"> Student Questions Input Page </h5>

              @if(Session::has('ReportEditPage'))
                  {{ Session::get('ReportEditPage')}}
              @endif
    {{--{{ Form::open(array( 'route' => 'submit-question-to-database' ,'files' => true, 'class'=> 'QuestionInputform', 'method'=> 'post')) }}--}}

    <form class="col s12 QuestionInputform" action="{{URL::route('submit-question-to-database')}}" method="post" data-parsley-validate data-parsley-ui-enabled="true" enctype="multipart/form-data">
            <div class="ReportStudentClass center-align"></div>
            <div class="row">
                <h6 class="ReportMessage center-align red-text  "></h6>
                <div class="ValidationErrorReport center-align LoginError"></div>
            </div>
                <div class="row">
                    <input type="hidden" value="{{URL::route('get-questions')}}" class="GetQuestionInputURL" />
                    <input type="hidden" value="{{URL::route('get-questions')}}" class="GetQuestionEditURL" />
                    <input type="hidden" value="{{URL::route('get-questions')}}" class="GetQuestionURL" />
                    <input type="hidden" value="{{URL::route('save-this-score')}}" class="SaveScoreURL" />
                    <input type="hidden" value="{{url('/')}}" class="BaseURL" />
                    <input type="hidden" value="1" class="IsAdmin" />


                    <div class="ShowPieChart">
                        <h5>Result Pie Chart </h5>
                        <canvas id="month-area" width="300" height="300"></canvas>
                        <div id="legend"></div>
                    </div>
                </div>

                {{--<div class="row">--}}
                    {{--<span class ="ReportMessage"></span>--}}
                    {{--<span class="ValidationErrorReport" ></span>--}}
                    {{--<span class="ErrorReport" ></span>--}}
                {{--</div>--}}

                <div class="row">
                       <div class="YearDiv col m4 l4 s12">
                          <span class="YearLabel"> Year </span>
                          <select name = "Year" class="Year" >
                            <option> -- Choose year -- </option>
                            <option value="2015"> 2015/2016 </option>
                          </select>
                       </div>

                      <div class="ClassDiv col m4 l4 s12">
                          <span class="ClassLabel"> Class </span>
                          <select name = "Class" class="Class" >
                              <option> -- Choose class -- </option>
                              <option value="SS1">SS1</option>
                              <option value="SS2">SS2</option>
                              <option value="SS3">SS3</option>
                          </select>
                      </div>

                      <div class="SubjectDiv col m4 l4 s12">
                          <span class="SubjectLabel"> Subjects </span>
                          <select name = "Subject" class="Subject" >
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

                      <div class="row">
                        <button  class="GetQuestionButton row col m4 l2 s10 waves-effect waves-light btn-large offset-s1 offset-m4  offset-l5 z-depth-4 gradient-45deg-deep-orange-orange "> Get Questions </button>
                    </div>

                    <div class="row">
                        <div id="QuestionPanelProgess" class="col s4 offset-s4" >
                            <br />
                            <br />
                            <br />
                            <div class="progress light-blue  lighten-4">
                                <div class="indeterminate orange darken-4"></div>
                            </div>
                        </div>
                        <ul class="QuestionsPanel collapsible popout col s10 offset-s1" data-collapsible="accordion"></ul>
                    </div>
                </div>

                <div class="row">
                    <button  class="ResultButton gradient-45deg-deep-orange-orange btn btn-large z-depth-4 col s6 l6 m6" style="margin-left: 25%!important;">View Results</button>

                </div>


    <div class="row">
        <h5 class="center-align"> Insert New Questions Here </h5>

        <div class="row">
            <div class="input-field TermDiv col s12 m6 l3">
                <select name = "TermName" class="TermName" id="TermName"  >
                    <option> -- Choose term -- </option>
                    <option value="first term"> First term</option>
                    <option value="second term"> Second term</option>
                    <option value="third term"> Third term</option>
                </select>
                <label for="TermName" class="center-align">  Choose  Term </label>
            </div>
            <div class="input-field  col s12 m6 l3">
                <input type="text" class="ClassTeacher" id="ClassTeacher" />
                <label for="ClassTeacher" class="center-align"> Name of Class Teacher </label>
            </div>
            <div class="input-field col s12 m6 l3">
                <input type="text" name="SectionNumber" class="SectionNumber"   id="SectionNumber"  />
                <label for="SectionNumber" class="center-align"> Which <b>Section</b> of the Question ? </label>
            </div>
            <div class="col s12 m6 l3 input-field">
               <input type="text"  name="QuestionNumber" class="QuestionNumber" id="QuestionNumber" />
                <label for="QuestionNumber" class="center-align">  Question Number:  </label>
            </div>

        </div>


        <div class="row">
            <div class="col s12 m12 l6">
                <textarea name="SectionInstruction" class="SectionInstruction" id="SectionInstruction" rows="5" cols="80" >Type the instruction associated to each section of the question here
                    </textarea>
            </div>
            <div class="col s12 m12 l6">
               <textarea name="editor1" class="editor1" id="editor1" rows="5" cols="80">
                Type each question in a section here!
                </textarea>
            </div>
            <div class="col s12 m12 l12">
                <div class="file-field input-field">
                    <div class="btn gradient-45deg-deep-orange-orange">
                        <span><i class="material-icons"> file_upload</i> Upload File</span>
                        <input type="file" name="QuestionImage" class="QuestionImage" />
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>
                {{--<input type="file" name="QuestionImage" class="QuestionImage" />--}}
            </div>

        </div>

        <div class="row">

            <div class="input-field col s12 m6 l6">
                <input type="text" name="optionA" class="optionA"  id="optionA" />
                <label for="optionA" class="center-align">  Type option A <i class="material-icons">keyboard_arrow_right</i>   </label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input type="text"  name="optionB" class="optionB"  id="optionB"  />
                <label for="optionB" class="center-align">  Type option B  <i class="material-icons">keyboard_arrow_right</i>   </label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input type="text"  name="optionC" class="optionC"  id="optionC"/>
                <label for="optionC" class="center-align">  Type option C  <i class="material-icons">keyboard_arrow_right</i>   </label>
            </div>
            <div class="input-field col s12 m6 l6">
                <input type="text"  name="optionD" class="optionD"  id="optionD" />
                <label for="optionD" class="center-align">  Type option D  <i class="material-icons">keyboard_arrow_right</i>   </label>
            </div>

        </div>

        <div class="row ">
            <div class="input-field col s8 m8 l8 offset-s2 offset-m2 offset-l2">
                <select name = "CorrectAnswer" class="CorrectAnswer" id="CorrectAnswer" >
                    <option> -- Choose the correct answer -- </option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
                <label for="CorrectAnswer" class="center-align">    What is the correct answer??    </label>
            </div>
        </div>

        <div class="row">
            <div id="QuestionSubmitProgress" class=" col s4 offset-s4" >
                <br />
                <br />
                <br />
                <div class="progress light-blue  lighten-4">
                    <div class="indeterminate orange darken-4"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <h6 class="ReportMessage center-align red-text "></h6>
            <div class="ValidationErrorReport center-align LoginError"></div>
        </div>

        {{csrf_token()}}


        <button  class="SubmitQuestion btn btn-large gradient-45deg-deep-orange-orange col s8 m8 l8 offset-s2 offset-m2 offset-l2 "> Save Question to database  </button>
    </div>

    </form>
</div>


@stop

@section('script')
  {{--{{HTML::script('JS/ckeditor/ckeditor.js')}}--}}
<script src="{{asset('JS/ckeditor/ckeditor.js')}}"></script>
{{--  {{HTML::script('JS/ckeditor/ckeditor.js')}}--}}
  <script>
  // Replace the <textarea id="editor1"> with a CKEditor
      // instance, using default configuration.
      CKEDITOR.replace( 'editor1' );
      CKEDITOR.replace( 'SectionInstruction' );
      $("#QuestionSubmitProgress").hide();
      $(".ShowPieChart").hide();
      $(".ResultButton").hide();
       var ActionUrl  =  $(".GetQuestionInputURL").val();

                         /*  $.ajax({
                                    type: 'GET',
                                    url:  ActionUrl,
                                    dataType: 'json',
                                   // data: data,
                                    success: function(response, textStatus)
                                              {
                                                var errortext ="";
                                                 var validatorerror ="";
                                                 var ErrorExerror ="";
                                                 var BigHTML = "";
                                                console.log(response);
                                                if(response.status == 1)
                                                    {

                                                      $.each(response.ReportMessage, function(index,value)
                                                            {
                                                                errortext +=  value + "<br />";
                                                            });
                                                       $(".ReportMessage").html(errortext);
                                                      $.each(response.QuestionTableGroupBy, function(index1,value1)
                                                        {
                                                           BigHTML += value1["sectioninstruction"];

                                                           $.each(response.QuestionTable, function(index,value)
                                                            {
                                                                if( value1["questionsection"] == value["questionsection"]   )
                                                                {
                                                                    BigHTML += "<div><input type='hidden' value='" + value["questiontableid"]  +"' />";
                                                                    BigHTML += value["questionnumber"] + " " +  value["questionstatement"];
                                                                    BigHTML += "<li>(A) <input type='radio' name='question" +  ( value["questionnumber"] - 1).toString() + "' value='A'>" +  value.question_options_belong["optionA"] + "<br />" +
                                                                               "(B) <input type='radio' name='question" +  ( value["questionnumber"] - 1).toString()  + "' value='B'>" +  value.question_options_belong["optionB"] + "<br />" +
                                                                               "(C) <input type='radio' name='question" +   ( value["questionnumber"] - 1).toString() + "' value='C'>" +  value.question_options_belong["optionC"] + "<br />" +
                                                                               "(D) <input type='radio' name='question" + ( value["questionnumber"] - 1).toString()  + "' value='D'>" +  value.question_options_belong["optionD"] + "<br /> </li>";

                                                                }
                                                            });
                                                        });
                                                      $(".QuestionsPanel").html(BigHTML);
                                                      alert(errortext);
                                                    }
                                                else if(response.status == 0)
                                                    {
                                                      $.each(response.ReportMessage, function(index,value)
                                                            {
                                                              errortext +=  value + "<br />";
                                                            });
                                                       $.each(response.ValidationErrorReport, function(index,value)
                                                            {
                                                              validatorerror +=  value + "<br />";
                                                            });
                                                      $(".ReportMessage").html(errortext);
                                                      $(".ValidationErrorReport").html(validatorerror);
                                                      $(".ErrorReport").html(" ");
                                              }
                                               else if(response.status == 2)
                                                    {
                                                      $.each(response.ReportMessage, function(index,value)
                                                            {
                                                              errortext +=  value + "<br />";
                                                            });
                                                      /* $.each(response.ErrorReport, function(index,value)
                                                            {
                                                              errortext +=  value + "<br />";
                                                            });
                                                      $(".ValidationErrorReport").html(" ");
                                                      $(".ReportMessage").html(errortext);
                                                      $(".ErrorReport").html(response.ErrorReport.errorInfo[2]);
                                              }
                                              //  window.location.reload();
                                              },
                                    error: function(xhr, textStatus, errorThrown)
                                              {
                                                 alert('Bad response. Please reload the page. if this message continue, please contact the Head of the school. Thank you.');
                                               // console.log(xhr);
                                               //// console.log(textStatus);
                                              //  console.log(errorThrown);
                                              // window.location.reload();
                                              }
                                          });//end ajax}); */

  </script>
@endsection
