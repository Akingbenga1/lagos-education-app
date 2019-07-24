@extends('layouts.main')

@section('division_container')
<div class="DivisionContainer">
                            <h3 class="center-align"> Edit Student Question </h3>
    {{ Form::open(array( 'route' => 'edit-this-question' ,'files' => true, 'class'=> 'QuestionEditform', 'method'=> 'post')) }}
            <input type="hidden" class="EditQuestionId" value="{{$ThisQuestion['questiontableid']}}" />
            <input type="hidden" class="EditQuestionOptionsId" value="{{$ThisQuestion['questionoptionsid']}}" />
            <input type="hidden" class="EditCorrectAnswerId" value="{{$ThisQuestion['questionanswerid']}}" />
            <input type="hidden" value="{{URL::to('/')}}" class="BaseURL" />
            {{--<span class="ReportStudentClass"></span>--}}
                <div class="ReportStudentClass center-align"></div>
                <div class="row">
                    <h6 class="ReportMessage center-align red-text  "></h6>
                    <div class="ValidationErrorReport center-align LoginError"></div>
                </div>
            @if( is_array($ThisQuestion) )
                <div class="row">
                    <div class="YearDiv col  m6 l3 s12">
                        <span class="YearLabel"> Year </span>

                        <select name = "Year" class="Year" >
                            <option> -- Choose year -- </option>
                            <option value="2015" {{($ThisQuestion["year"] == "2015") ? "selected":'' }}> 2015/2016 </option>
                        </select>
                    </div>

                    <div class="TermDiv col m6 l3 s12">
                        <span class="TermLabel"> Term </span>
                        <select name = "TermName" class="TermName" >
                            <option> -- Choose term -- </option>
                            <option value="first term" {{($ThisQuestion["termname"] == "first term") ? "selected":'' }}> First term</option>
                            <option value="second term" {{($ThisQuestion['termname'] == "second term") ? "selected":'' }} > Second term</option>
                            <option value="third term" {{($ThisQuestion['termname'] == "third term") ? "selected":'' }}> Third term</option>
                        </select>
                    </div>

                    <div class="ClassDiv  col m6 l3 s12">
                        <span class="ClassLabel"> Class </span>
                        <select name = "Class" class="Class" >
                            <option> -- Choose class -- </option>
                            <option value="SS1" {{($ThisQuestion["classname"] == "SS1") ? "selected":'' }}>SS1</option>
                            <option value="SS2" {{($ThisQuestion["classname"] == "SS2") ? "selected":'' }}>SS2</option>
                            <option value="SS3" {{($ThisQuestion["classname"] == "SS3") ? "selected":'' }}>SS3</option>
                        </select>
                    </div>

                    <div class="SubjectDiv col m6 l3 s12">
                        <span class="SubjectLabel"> Subjects </span>
                        <select name = "Subject" class="Subject" >
                            <option> -- Choose Subject -- </option>
                            @if(isset($AllSubjects) and is_array($AllSubjects))
                                @foreach($AllSubjects as $EverySubject)
                                    <option value="{{$EverySubject['subjectid']}}" {{($ThisQuestion["subjectid"] == $EverySubject['subjectid']) ? "selected":'' }}>
                                        {{$EverySubject['subject']}}
                                    </option>
                                @endforeach
                            @else
                                <option> No Subject Available  </option>
                            @endif
                        </select>
                    </div>
                </div>


           <div class="row">

               <div class="input-field col s12 m6 l4">
                       <input type="text" name="SectionNumber" class="SectionNumber" id="SectionNumber" value="{{$ThisQuestion['questionsection']}}" />
                       <label for="SectionNumber" class="center-align"> Which <b>Section</b> of the Question ? </label>
               </div>

                <div class="input-field col s12 m6 l4">
                  <input type="text" class="ClassTeacher" id="ClassTeacher"  value="{{$ThisQuestion['classteacher']}}" />
                  <label for="ClassTeacher" class="center-align">  Name of class Teacher  </label>
                </div>

                <div class="input-field col s12 m12 l4">
                    <input type="text"  name="QuestionNumber" class="QuestionNumber" id="QuestionNumber" value="{{$ThisQuestion['questionnumber']}}" />
                    <label for="QuestionNumber" class="center-align">   Question Number:  </label>
                </div>
           </div>

           <div class="row">
               <div class="col s12 m12 l6">
                   <textarea name="SectionInstruction" class="SectionInstruction" id="SectionInstruction" rows="5" cols="80" >
                            {{$ThisQuestion['sectioninstruction']}}
                   </textarea>
               </div>

               <div class="col s12 m12 l6">
                 <textarea name="editor1" class="editor1" id="editor1" rows="5" cols="80">
                    {{$ThisQuestion['questionstatement']}}
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
               </div>
           </div>

          <div class="row">

                <div class="input-field col s12 m6 l6">
                    <input type="text" name="optionA" class="optionA" value="{{$ThisQuestion['question_options_belong']['optionA']}}" />
                    <label for="optionA" class="center-align">  Type option A <i class="material-icons">keyboard_arrow_right</i>   </label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <input type="text"  name="optionB" class="optionB" value="{{$ThisQuestion['question_options_belong']['optionB']}}" />
                    <label for="optionA" class="center-align">  Type option B <i class="material-icons">keyboard_arrow_right</i>   </label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <input type="text"  name="optionC" class="optionC" value="{{$ThisQuestion['question_options_belong']['optionC']}}" />
                    <label for="optionA" class="center-align">  Type option C <i class="material-icons">keyboard_arrow_right</i>   </label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <input type="text"  name="optionD" class="optionD" value="{{$ThisQuestion['question_options_belong']['optionD']}}" />
                    <label for="optionA" class="center-align">  Type option D <i class="material-icons">keyboard_arrow_right</i>   </label>
                </div>
          </div>

          <div class="row">
               <div class="input-field col s8 m8 l8 offset-s2 offset-m2 offset-l2">
                        <select name = "CorrectAnswer" class="CorrectAnswer" id="CorrectAnswer" >
                            <option> -- Choose the correct answer -- </option>
                            <option value="A" {{($ThisQuestion['question_answer_belong']['correctanswer'] == "A") ? "selected":'' }}>A</option>
                            <option value="B" {{($ThisQuestion['question_answer_belong']['correctanswer'] == "B") ? "selected":'' }}>B</option>
                            <option value="C" {{($ThisQuestion['question_answer_belong']['correctanswer'] == "C") ? "selected":'' }}>C</option>
                            <option value="D" {{($ThisQuestion['question_answer_belong']['correctanswer'] == "D") ? "selected":'' }}>D</option>
                        </select>
                        <label for="CorrectAnswer" class="center-align">  What is the correct answer??   </label>
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
    <div class="row">
        <button  class="EditQuestion btn btn-large gradient-45deg-deep-orange-orange col s8 m8 l8 offset-s2 offset-m2 offset-l2"> Edit This Question </button>
    </div>



@endif

</div>
       
     {{Form::token()}}
     {{Form::close()}}
</div>

@section('script')
        {{HTML::script('JS/ckeditor/ckeditor.js')}}
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'editor1' );
        CKEDITOR.replace( 'SectionInstruction' );
        $("#QuestionSubmitProgress").hide();
        $(".ShowPieChart").hide();
        $(".ResultButton").hide();
        var ActionUrl  =  $(".GetQuestionInputURL").val();

    </script>
@endsection

@stop