@extends('layouts.main')

@section('division_container')
<div class="DivisionContainer">
<h3> Student Questions Input Page </h3>

  @if(Session::has('ReportEditPage'))
      {{ Session::get('ReportEditPage')}}
  @endif
    {{ Form::open(array( 'route' => 'submit-question-to-database' ,'files' => true, 'class'=> 'QuestionInputform', 'method'=> 'post')) }}

<input type="hidden" value="{{URL::action('get-questions')}}" class="GetQuestionInputURL" /> 
<input type="hidden" value="{{URL::action('get-questions')}}" class="GetQuestionEditURL" /> 
<input type="hidden" value="{{URL::action('get-questions')}}" class="GetQuestionURL" /> 
<input type="hidden" value="{{URL::action('save-this-score')}}" class="SaveScoreURL" /> 
<input type="hidden" value="{{URL::to('/')}}" class="BaseURL" /> 
<input type="hidden" value="1" class="IsAdmin" /> 
 <span class="ReportStudentClass"></span>

 <div class="ShowPieChart">
    <h2>Result Pie Chart </h2>
    <canvas id="month-area" width="300" height="300"></canvas>
    <div id="legend"></div>
    </div>

  <span class="ReportMessage" ></span>
  <span class="ValidationErrorReport" ></span>
  <span class="ErrorReport" ></span>


<div>
     <div class="YearDiv">
        <span class="YearLabel"> Year </span>
        <select name = "Year" class="Year" >
          <option> -- Choose year -- </option>
          <option value="2015"> 2015/2016 </option>
        </select>
    </div>

    <div class="ClassDiv">
        <span class="ClassLabel"> Class </span>
        <select name = "Class" class="Class" >
            <option> -- Choose class -- </option>
            <option value="SS1">SS1</option>
            <option value="SS2">SS2</option>
            <option value="SS3">SS3</option>
        </select>
    </div>

    <div class="SubjectDiv">
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

<button  class="GetQuestionButton"> Get Questions </button>

</div>

<button  class="ResultButton">View Results</button>
<ol class="QuestionsPanel"></ol>

<button  class="ResultButton">View Results</button>


<div> 
    <h3> Insert New Questions Here </h3>

    

    <div class="TermDiv">
        <span class="TermLabel"> Term </span>
        <select name = "TermName" class="TermName" >
            <option> -- Choose term -- </option>
            <option value="first term"> First term</option>
            <option value="second term"> Second term</option>
            <option value="third term"> Third term</option>
        </select>
    </div>

   
    <span class=""> Which Section of the Question is this? </span> <b> Section :</b> <input type="text" name="SectionNumber" class="SectionNumber" > <br />
    Section Instruction: </b> 
    <textarea name="SectionInstruction" class="SectionInstruction" id="SectionInstruction" rows="5" cols="80" >Type the instruction associated to each section of the question here
    </textarea>

     <div class="">
      Name of class Teacher: <input type="text" class="ClassTeacher" /> 
    </div> 
    Question Number: <input type="text"  name="QuestionNumber" class="QuestionNumber" />

    <textarea name="editor1" class="editor1" id="editor1" rows="5" cols="80">
                Type each question in a section here! 
    </textarea>
    <input type="file" name="QuestionImage" class="QuestionImage" /> 

    Type the options here: 
    A=>  <input type="text" name="optionA" class="optionA" />, B => <input type="text"  name="optionB" class="optionB" />,  
     C => <input type="text"  name="optionC" class="optionC" /> ,D => <input type="text"  name="optionD" class="optionD" />

     What is the correct answer??  
    <select name = "CorrectAnswer" class="CorrectAnswer" >
            <option> -- Choose the correct answer -- </option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
    </select>
    <span class="ReportMessage" ></span>
    <span class="ValidationErrorReport" ></span>
    <span class="ErrorReport" ></span>

    <button  class="SubmitQuestion"> Save Question to database  </button>
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />



</div>
       
     {{Form::token()}}
     {{Form::close()}}
</div>

<script>
// Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace( 'editor1' );
    CKEDITOR.replace( 'SectionInstruction' );
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

@stop