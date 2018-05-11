@extends('layouts.main')

      @section('division_container')
         <h3> Student Score Sheet</h3> 
         @include('includes.adminlinks') 
         @if(Session::has('ScoreInput'))
            {{Session::get('ScoreInput')}}
         @endif

          <input type = "hidden" value="{{URL::route('get-this-student-class')}}" class="ClassYearURL"> 
            {{ Form::open(array( 'route' => 'post-this-student-details' ,'files' => true, 'method'=> 'post', 
            'class'=>'GetStudentScoreSheetForm')) }}  
                <div class="GetStudentScoreSheet">
                 <p>
                     <b> Type admission number (for faster selection and more accurate) or student name: </b>
                  </p>
                  <p>
                      @if(!empty($AllStudents) and is_array($AllStudents))
                        @include('includes.choosestudentasarray')  
                      @endif
                  </p>

                   <button type="button"  class="GetClassYear"> Get Student Class </button><br />
                   <span class="ReportStudentClass"></span>
                   <span class="TellStudentClass"></span>
                   
                   <div class="moal"></div>
                 
                </div>               
                <div class="GetStudentScoreSheet">
                      <p class=""> <b> Choose term: ( Year, Term, Class, Subclass) </b>
                      </p>
                      <p>
                          <div class="ChooseTermError">
        <div>
            @if($errors->has('Year'))
                <span class="text-danger StudentError">{{$errors->first('Year')}}</span>
            @endif
        </div>
        <div>
             @if($errors->has('TermName'))
                <span class="text-danger StudentError">{{$errors->first('TermName')}}</span>
            @endif
        </div>
        <div>
             @if($errors->has('Class'))
                <span class="text-danger StudentError">{{$errors->first('Class')}}</span>
            @endif
        </div>
        <div>
             @if($errors->has('SubClass'))
                <span class="text-danger StudentError">{{$errors->first('SubClass')}}</span>
            @endif
        </div>
    </div>
<div class="ChooseTermBox">

    <div class="YearDiv">
        <span class="YearLabel"> Year </span>

        <select name = "Year" class="YearSelect" >
          <option> -- Year selected automatically-- </option> 
        </select>
    </div>

    <div class="TermDiv">
        <span class="TermLabel"> Term </span>
        <select name = "TermName" class="TermSelect" >
            <option> -- Choose term -- </option>
            <option value="first term"> First term</option>
            <option value="second term"> Second term</option>
            <option value="third term"> Third term</option>
        </select>
    </div>

    <div class="ClassDiv">
        <span class="ClassLabel"> Class </span>
        <select name = "Class" class="ClassSelect" >
          <option> -- Class selected automatically-- </option>        
        </select>
</div>

        <div class="SubClassDiv">
            <span class="SubClassLabel"> SubClass </span>
            <select name = "SubClass" class="SubClassSelect" >
                 <option> -- The class arm selected automatically -- </option>
            </select> 
        </div>


    </div>
                      </p>
                </div>
                <input type = "submit"  value="Open score sheet" id="AutoButton"  disabled/>

                {{Form::token()}}
               
            {{Form::close()}}

  <script type="text/javascript">

              jQuery(window).on('error', function (e) {
                    // This tells jQuery no more ajax Requests are active
                    // (this way Global start/stop is triggered again on next Request)
                    jQuery.active = 0;
                    //Do something to handle the error
                  //   $(".ReportStudentClass").removeClass("AjaxRotator");
                    });

           $('.GetClassYear').on("click", function (e) 
                    { 
                          e.preventDefault();
                           $(".ReportStudentClass").html(" ");
                          var StudentID = $('#StuId').val();                         
                          var ActionUrl  =  $(".ClassYearURL").val(); 
                          $(document).on({
                             //ajaxStart: function() {  $(".ReportStudentClass").addClass("AjaxRotator");    },
                          //   ajaxStop: function() { $(".ReportStudentClass").removeClass("AjaxRotator"); },
                             //ajaxComplete:function() { $(".ReportStudentClass").removeClass("help"); },
                          });                                                                    
                          //console.log(ActionUrl);
                          data =  { 'StudentID' : StudentID};     
                          try {
                                     $.ajax({
                                            type: 'POST',
                                            url:  ActionUrl,
                                            dataType: 'json',
                                            data: data,                                           
                                            success: function(response, textStatus)
                                                      {  var classlist ="";
                                                         var validatorerror ="";
                                                         var ErrorExerror ="";
                                                         var BigHTML = "";
                                                        console.log(response);
                                                        //console.log(response[0].thisterm_belong.classname);
                                                        len = $.map(response, function(n, i) { return i; }).length;
                                                        if( !( len  == 0) )
                                                        {
                                                            $('.ClassSelect option:last-child').remove();
                                                            $('.SubClassSelect option:last-child').remove();
                                                            $('.YearSelect option:last-child').remove();
                                                            $(".TellStudentClass").html(" ");
                                                           // $(".ClassSelect").val(response[0].thisterm_belong.classname);
                                                            $.each(response, function(index,value)
                                                                {       
                                                                //console.log(JSON.stringify(value) );
                                                                        classlist +=  "<input type='radio' name='PickClass' class='PickClass' value='" + 
                                                                                    JSON.stringify(value) + "' /> <b> " +
                                                                                   value.thisterm_belong.classname + " " +
                                                                                           value.class_subdivision.toUpperCase() + " , "  +
                                                                                           value.thisterm_belong.year + " class </b><br /> ";
                                                                 });
                                                 //  alert(classlist);
                                                    // window.location.reload();
                                                     $(".TellStudentClass").append(classlist );
                                                      $(".TellStudentClass").append("Choose the right class of this student" 
                                                                                        + "<b>" + 
                                                                                           "</b> . Details of this student class " +
                                                                                           "will be  updated automatically on " +
                                                                                           " the right hand side of this page." +
                                                                                           " ");
                                                           /* $('.ClassSelect').append($('<option/>', { 
                                                                                      value: response[0].thisterm_belong.classname,
                                                                                      text : response[0].thisterm_belong.classname,
                                                                                      selected : true 
                                                                                  }));
                                                             $('.SubClassSelect').append($('<option/>', { 
                                                                                      value: response[0].class_subdivision,
                                                                                      text : response[0].class_subdivision.toUpperCase(),
                                                                                      selected : true 
                                                                                  }));
                                                              $('.YearSelect').append($('<option/>', { 
                                                                                      value: response[0].thisterm_belong.year,
                                                                                      text : response[0].thisterm_belong.year,
                                                                                      selected : true 
                                                                                  }));*/
                                                        }
                                                        else
                                                        {
                                                            $('.ClassSelect option:last-child').remove();
                                                            $('.SubClassSelect option:last-child').remove();
                                                            $('.YearSelect option:last-child').remove();
                                                           // $(".ReportStudentClass").html(" ");
                                                           // $(".ClassSelect").val(response[0].thisterm_belong.classname);
                                                           $(".ReportStudentClass").html("This student does not have any class");
                                                        }
                                                       
                                                      //  window.location.reload();                                            
                                                      },
                                            error: function(xhr, textStatus, errorThrown) 
                                                      {
                                                        // alert('Bad response. Please reload the page. if this message continue, please contact the Head of the school. Thank you.');
                                                        console.log(xhr);
                                                        console.log(textStatus);
                                                      //  console.log(errorThrown);
                                                      // window.location.reload();
                                                      }
                                        });//end ajax
                              }
                          catch(e) 
                              {
                                  // statements to handle any exceptions
                                  console.log(e); // pass exception object to error handler
                              }                            
                         
                            });//end anon function   
           $('.TellStudentClass').on("click", ".PickClass", function (e) 
                    { 
                          e.preventDefault();
                         // $(".ReportStudentClass").html(" ");
                          //console.log(JSON.parse( $(this).val() ) );
                          var ThisRadioClassYear = JSON.parse( $(this).val() );
                         
                          var ActionUrl  =  $(".ClassYearURL").val(); 
                          $(document).on({
                            // ajaxStart: function() {  $(".ReportStudentClass").addClass("AjaxRotator");    },
                           //  ajaxStop: function() { $(".ReportStudentClass ").removeClass("AjaxRotator"); },
                             //ajaxComplete:function() { $(".ReportStudentClass").removeClass("AjaxRotator"); },
                          });                                                                    
                          //console.log(ActionUrl);     
                          try {
                                     $.ajax({
                                            type: 'POST',
                                            url:  ActionUrl,
                                            dataType: 'json',
                                            data: {},                                           
                                            success: function(response, textStatus)
                                                      { //console.log(response);
                                                        
                                                            $('.ClassSelect option:last-child').remove();
                                                            $('.SubClassSelect option:last-child').remove();
                                                            $('.YearSelect option:last-child').remove();
                                                            //$(".ReportStudentClass").html(" ");
                                                           // $(".ClassSelect").val(response[0].thisterm_belong.classname);                                                           
                                                            $('.ClassSelect').append($('<option/>', { 
                                                                                      value: ThisRadioClassYear.thisterm_belong.classname,
                                                                                      text : ThisRadioClassYear.thisterm_belong.classname,
                                                                                      selected : true 
                                                                                  }));
                                                             $('.SubClassSelect').append($('<option/>', { 
                                                                                      value: ThisRadioClassYear.class_subdivision,
                                                                                      text : ThisRadioClassYear.class_subdivision.toUpperCase(),
                                                                                      selected : true 
                                                                                  }));
                                                              $('.YearSelect').append($('<option/>', { 
                                                                                      value: ThisRadioClassYear.thisterm_belong.year,
                                                                                      text : ThisRadioClassYear.thisterm_belong.year,
                                                                                      selected : true 
                                                                                  }));                                           
                                                      },
                                            error: function(xhr, textStatus, errorThrown) 
                                                      {
                                                        // alert('Bad response. Please reload the page. if this message continue, please contact the Head of the school. Thank you.');
                                                        console.log(xhr);
                                                        console.log(textStatus);
                                                      //  console.log(errorThrown);
                                                      // window.location.reload();
                                                      }
                                        });//end ajax
                              }
                          catch(e) 
                              {
                                  // statements to handle any exceptions
                                  console.log(e); // pass exception object to error handler
                              }                            
                         
                            });//end anon function   
           
  </script>
      @stop