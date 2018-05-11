$(document).ready(function()
	{  
//
//
// bootcards.init({
//   offCanvasBackdrop: true,
//   offCanvasHideOnMainClick: true,
//   enableTabletPortraitMode: true,
//   disableRubberBanding: true
// }); //  Bootscard Features on
//
        //'use strict';

 function toggleChevron(e) {
                        // alert("Going to open");
                        /// console.log(e.target.children().last());
                         console.log(e);
    $(e.target)

        .prev('.panel-heading')
        .find("i.indicator")//.addClass('glyphicon-chevron-up').removeClass('glyphicon-chevron-down');
        .toggleClass('glyphicon-chevron-down glyphicon-chevron-right');
}

$('.panel').on('hidden.bs.collapse', toggleChevron);
$('.panel').on('shown.bs.collapse',  toggleChevron);

$(function() { 
    // for bootstrap 3 use 'shown.bs.tab', data bootstrap 2 use 'shown' in the next line
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
        doo = $(this).attr('href');
         console.log(doo);
    });

    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
    console.log(lastTab);
    if (lastTab) {
        $('a[href="' + lastTab + '"]').tab('show');
    }
});

/*$('.panel').on('hidden.bs.collapse',function() {
  alert("Going to open");
    $("i.indicator").addClass('glyphicon-chevron-up').removeClass('glyphicon-chevron-down');
  });

$('.panel').on('shown.bs.collapse', function() {
  alert("Going to open");
    $("i.indicator").addClass('glyphicon-chevron-down').removeClass('glyphicon-chevron-up');
  });*/


/*
    function CheckUpperCase (CheckedString)
    {

          var str = CheckedString;
          var Alert 
          if(str[0].toUpperCase() == str[0])
              {
                 Alert = "First character of " + str +  " is upper-case";
                 window.alert(Alert);  
              }
          else
              {
               Alert = "First character of " + str +  " is not upper-case";
               window.alert(Alert );  
              }

    }

    CheckUpperCase("Gbenga");*/

      /*$(document).on({
                             ajaxStart: function() {  $(".ReportStudentClass").addClass("AjaxRotator");    },
                             //ajaxStop: function() { $(".ReportStudentClass").removeClass("AjaxRotator"); },
                            // ajaxComplete:function() { $(".ReportStudentClass").removeClass("AjaxRotator"); },
                          });          

      jQuery(window).on('error', function (e) {
                    // This tells jQuery no more ajax Requests are active
                    // (this way Global start/stop is triggered again on next Request)
                    jQuery.active = 0;
                    //Do something to handle the error
                     $(".ReportStudentClass .AdminReportStudentClass").removeClass("help");
                    }); */

    //console.log(randomColor());
    //$("body").css("background-color", randomColor() );
    //$(".NavBar A").css("color", randomColor() );
		$.ajaxSetup({  headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')  }    });
		//$( "#datepicker" ).datepicker();
		/*$("#btnLeft").click(function () {
    	var selectedItem = $("#rightValues option:selected");
    	$("#leftValues").append(selectedItem);
		});*/

		/*$("#btnRight").click(function () {
   			 var selectedItem = $("#leftValues option:selected");
    		 $("#rightValues").append(selectedItem);
		});*/

/*    $('#collapseSS3').on('shown.bs.collapse', function () {
      alert("Going to open");
     $(".glyphicon").removeClass("glyphicon-folder-close").addClass("glyphicon-folder-open");
  });

  $('#collapseSS3').on('hidden.bs.collapse', function () {
     alert("Going to open");
     $(".glyphicon").removeClass("glyphicon-folder-open").addClass("glyphicon-folder-close");
  });*/


		$("#btnRight").click(function () {
			 var selectedItem = new Option($('#autocomplete-custom-append2').val(), $('#StuId2').val()); 
   			// var selectedItem = $("").val();
   			 //alert($('#StuId').val());
    		 $("#rightValues").append(selectedItem);
    		 $('#autocomplete-custom-append2').val('');
    		  $('.AutoButtonRight').prop('disabled', true);
		});

		//select all options in the multiple select
		$('#SelectAll').click(function() 
			{
    			$('#rightValues option').prop('selected', true);
			});
		

		/*$("#rightValues").change(function () {
    		var selectedItem = $("#rightValues option:selected");
    		$("#txtRight").val(selectedItem.text());
		});*/

		$(function () {  $('a.DontGo').on("click", function (e) { e.preventDefault();   });       });

		$('#StudentInput').focus(function() { $('#StuId').val('');  $('#autocomplete-custom-append').val(''); });
    $('#autocomplete-custom-append').focus(function() { $('#StudentInput').val(''); });

    	$(function () {  $('#AjaxButton').on("click", function (e) 
    						{ 
    							e.preventDefault();   
    							$('.DetailsEditDiv').css({'display':'block'})
    							$('.DetailsShowDiv').css({'display':'none'})
								 //alert('Am here'); 
							});//end anon function   
					  });

    	$(function () {  $('#AjaxShowButton').on("click", function (e) 
    						{ 
    							e.preventDefault();   
    							$('.DetailsEditDiv').css({'display':'none'})
    							$('.DetailsShowDiv').css({'display':'block'})
								 //alert('Am here'); 
							});//end anon function   
					  });
    	 $(".DateOfBirth").datepicker({dateFormat: "dd/mm/yy", 'showButtonPanel': true, });

    	 $(function () {$('.ProfileUpdateButton').on("click", function (e) 
                    { 
                        e.preventDefault(); 
                        var Surname = $(".Surname").val();
                        var Middlename = $(".Middlename").val();
                        var Firstname = $(".Firstname").val();
                        var SecondEmail = $(".SecondEmail").val();
                        var Sex = $(".Sex").val();
                        var DateOfBirth  =   $(".DateOfBirth").val().split('/')[2]+ '-' + 
                        					 $(".DateOfBirth").val().split('/')[1] + '-' + $(".DateOfBirth").val().split('/')[0];
                          var ActionUrl  =  $(".UserProfileUpdateForm").prop('action');
                           console.log( Surname );
                           console.log(Middlename);
                            console.log(Firstname);
                             console.log(SecondEmail);
                             console.log( DateOfBirth);
                              console.log(Sex);
                        $.ajax({
                                  type: 'POST',
                                  url:  ActionUrl,
                                  dataType: 'json',
                                  data: { 'Surname' : Surname, 'Middlename' : Middlename,
                                          'Firstname': Firstname, 'SecondEmail':SecondEmail,
                                          'DateOfBirth': DateOfBirth,'Sex': Sex},
                                  success: function(response, textStatus)
                                            {  
                                              console.log(response);
                                            //  window.location.reload();                                            
                                            },
                                  error: function(xhr, textStatus, errorThrown) 
                                            {
                                              alert('Bad response. Reloading the page...');
                                              console.log(xhr);
                                              console.log(textStatus);
                                              console.log(errorThrown);
                                            // window.location.reload();
                                            }
                                        });//end ajax
                            });//end anon function   
                      });      





   $(function () {$('.ResultButton').on("click", function (e) 
                    { 
                        e.preventDefault();
                        //AtURL =  $(".GetQuestionURL").val(); //alert(AtURL);
                        SaveScoreURL =  $(".SaveScoreURL").val(); //alert(AtURL);
                         var answers = {}; 
                         var QuestionList = {};
                         var MyScore;
                          var Year = $(".Year").val();
                        var Class = $(".Class").val();
                        var Subject = $(".Subject").val();
                        AtURL =  $(".GetQuestionURL").val(); //alert(AtURL);
                        data =  { 'Year' : Year, 'Class' : Class,'Subject': Subject, "IsAdmin" : 0};
                        console.log(data);
                        $.ajax({
                                  type: 'POST',
                                  url: AtURL,
                                  data : data,
                                  dataType: 'json',
                                  async: false,
                                  success: function(response, textStatus)
                                            {  
                                              console.log(response);
                                               answers = response.AnswersArray;
                                               QuestionList = response.QuestionTable;                                      
                                            },
                                  error: function(xhr, textStatus, errorThrown) 
                                            {
                                              alert('Bad response. Reloading the page...');
                                              console.log(xhr);
                                              console.log(textStatus);
                                              console.log(errorThrown);
                                            // window.location.reload();
                                            }
                                        });//end ajax
                       console.log(answers);
                        Total = Object.keys(answers).length;
                        console.log(Total);
                        function getCheckedValue( radioName ){
                                                                var radios = document.getElementsByName( radioName ); // Get radio group by-name
                                                                for(var y=0; y<radios.length; y++)
                                                                if(radios[y].checked) return radios[y].value; // return the checked value
                                                             }
                        function getScore(){
                                              var score = 0;
                                              //for (var i=0; i<tot; i++)
                                              $.each(QuestionList, function(index1,value1)
                                                      {  
                                                         QuestionNumber = (value1["questionnumber"] - 1).toString();
                                                        // console.log(QuestionNumber);
                                                          if(getCheckedValue("question"+ QuestionNumber)===answers[QuestionNumber]) 
                                                                score += 1; // increment only                                                       
                                                      });
                                               return score;
                                           }
                        //function returnScore(){
                        MyScore = getScore();
                        alert("Your score is "+ getScore() +"/"+ Total);
                        if(Number.isInteger(MyScore) && Number.isInteger(Total))
                        {
                          alert("Your score will be saved to database");
                          data = {'MyScore' : MyScore, "Total" : Total};
                          console.log(data);
                          var errortext =" ";
                          var monthData = {};
                          $.ajax({
                                  type: 'POST',
                                  url: SaveScoreURL,
                                  data : data,
                                  dataType: 'json',
                                  success: function(response, textStatus)
                                            {  
                                              console.log(response);
                                               $.each(response.ReportMessage, function(index,value)
                                                          {                                      
                                                            errortext +=  value + "<br />";
                                                          });
                                                $.each(response.ChartJSMonth, function(index2,value2)
                                                          {                                      
                                                              monthData[index2] =  value2 ;
                                                             
                                                          });
                                                //var monthData = 
                                                console.log(monthData);
                                             
                                                //$("#month-area").text(" ");
                                                //$("#legend").text(" ");
                                                $('#month-area').remove(); // this is my <canvas> element
                                                
                                                $(".ShowPieChart").show();                                               
                                                $('.ShowPieChart').append('<canvas id="month-area" width="300" height="300"></canvas><div id="legend"></div>');
                                                var canvas = document.getElementById("month-area");
                                                var midX = canvas.width/2;
                                                var midY = canvas.height/2
                                                var ctx = canvas.getContext("2d");
                                                var myPie = new Chart(ctx).Pie(monthData, 
                                                  { onComplete:   function ()
                                                          {  alert("Thanks");
                                                              for(var i=0; i<myPie.segments.length; i++) 
                                                              {
                                                                 var radius = myPie.outerRadius;
                                                                  ctx.fillStyle="white";
                                                                  var textSize = canvas.width/10;
                                                                  ctx.font= textSize+"px Verdana";
                                                                  // Get needed variables
                                                                  var value = myPie.segments[i].value;
                                                                  var startAngle = myPie.segments[i].startAngle;
                                                                  var endAngle = myPie.segments[i].endAngle;
                                                                  var middleAngle = startAngle + ((endAngle - startAngle)/2);

                                                                  // Compute text location
                                                                  var posX = (radius/2) * Math.cos(middleAngle) + midX;
                                                                  var posY = (radius/2) * Math.sin(middleAngle) + midY;

                                                                  // Text offside by middle
                                                                  var w_offset = ctx.measureText(value).width/2;
                                                                  var h_offset = textSize/4;
                                                                 

                                                                  ctx.fillText(value, posX - w_offset, posY + h_offset);
                                                                }
                                                          },
                                                              
                                                  legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><b> <%=segments[i].label%> is <%=segments[i].value%></b><%}%></li><%}%></ul>"
                                              //};
                                                 });    
                                              var legend = myPie.generateLegend();
                                              $("#legend").html(legend);
                                            },
                                  error: function(xhr, textStatus, errorThrown) 
                                            {
                                              alert('Bad response. Reloading the page...');
                                              console.log(xhr);
                                              console.log(textStatus);
                                              console.log(errorThrown);
                                            // window.location.reload();
                                            }
                                        });//end ajax
                        }
                        else
                        {
                           alert("Error! Unable to save your score in the database");
                        }
                        }

                    });//end anon function   
                }); 

       $(function () {$('.SubmitQuestion').on("click", function (e) 
                    { 
                        e.preventDefault();

                        var myFormData = new FormData();
                        myFormData.append('QuestionImage', $('.QuestionImage').prop('files')[0]);
                        //console.log(myFormData);
                          var BaseUrl = $(".BaseURL").val();
                          console.log(BaseUrl);

                        var Year = $(".Year").val();
                        var Class = $(".Class").val();
                        var Subject = $(".Subject").val();
                        var QuestionStatement = CKEDITOR.instances.editor1.getData() ;
                        var QuestionNumber = $(".QuestionNumber").val();
                        var SectionNumber = $(".SectionNumber").val();
                        var SectionInstruction = CKEDITOR.instances.SectionInstruction.getData() ;;
                        var TermName = $(".TermName").val();
                        var ClassTeacher = $(".ClassTeacher").val();
                        var optionA = $(".optionA").val();
                        var optionB = $(".optionB").val();
                        var optionC = $(".optionC").val();
                        var optionD = $(".optionD").val();
                        var QuestionImage = myFormData ;
                        var CorrectAnswer = $(".CorrectAnswer").val();
                        var ActionUrl  =  $(".QuestionInputform").prop('action');          
                                   
                                   //console.log(ActionUrl);
                                   data =  { 'Year' : Year, 'Class' : Class,'Subject': Subject, 'QuestionStatement': QuestionStatement, 'optionA': optionA,
                                          'optionB': optionB,'optionC': optionC,'optionD': optionD, 'CorrectAnswer': CorrectAnswer, 
                                          'QuestionNumber': QuestionNumber, 'SectionNumber': SectionNumber, 'ClassTeacher': ClassTeacher,
                                          'TermName': TermName,'SectionInstruction': SectionInstruction };
                                   
                                    $.each(data, function(index,value)
                                                          {                                      
                                                                myFormData.append(index, value);
                                                          });                                 
                                    
                                       console.log(myFormData);

                        $.ajax({
                                  type: 'POST',
                                  url:  ActionUrl,
                                  dataType: 'json',
                                  data: myFormData,
                                  processData: false, // important
                                  contentType: false, // important
                                  success: function(response, textStatus)
                                            {  var errortext ="";
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
                                                                  if(value["questionimage"] != "NoImage")
                                                                  {
                                                                    console.log( value["questionimage"] );
                                                                    BigHTML += "<br/><img src='" + BaseUrl + "/Images/QuestionImages/" + value["questionimage"] + "' />";                                                                                                                                 
                                                                  }
                                                                  BigHTML += "<li>(A) <input type='radio' name='question" +  ( value["questionnumber"] - 1).toString() + "' value='A'>" +  value.question_options_belong["optionA"] + "<br />" +
                                                                             "(B) <input type='radio' name='question" +  ( value["questionnumber"] - 1).toString()  + "' value='B'>" +  value.question_options_belong["optionB"] + "<br />" +
                                                                             "(C) <input type='radio' name='question" +   ( value["questionnumber"] - 1).toString() + "' value='C'>" +  value.question_options_belong["optionC"] + "<br />" +
                                                                             "(D) <input type='radio' name='question" + ( value["questionnumber"] - 1).toString()  + "' value='D'>" +  value.question_options_belong["optionD"] + "<br /> </li>";                                                                      
                                                                  BigHTML +=  "<a href='StudentQuestionEditPage.html/" + value["questiontableid"]  + "' >Edit</a>";
                                                                  BigHTML +=  "<button type='button' class='DeleteThisQuestion' value='" + value["questiontableid"]  +"' > Delete</button></div>";
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
                                                          });*/
                                                    $(".ValidationErrorReport").html(" ");
                                                    $(".ReportMessage").html(errortext); 
                                                    $(".ErrorReport").html(response.ErrorReport.errorInfo[2]);
                                            }
                                            //  window.location.reload();                                            
                                            },
                                  error: function(xhr, textStatus, errorThrown) 
                                            {
                                               alert('Bad response. Please reload the page. if this message continue, please contact the Head of the school. Thank you.');
                                              console.log(xhr);
                                             //// console.log(textStatus);
                                            //  console.log(errorThrown);
                                            // window.location.reload();
                                            }
                                        });//end ajax
                            });//end anon function   
                      });   
       $(function () {$('.EditQuestion').on("click", function (e) 
                    { 
                        e.preventDefault();
                        var myFormData = new FormData();
                        myFormData.append('QuestionImage', $('.QuestionImage').prop('files')[0]);
                        var BaseUrl = $(".BaseURL").val();
                        //console.log( $('.QuestionImage'));
                       
                        var Year = $(".Year").val();
                        var Class = $(".Class").val();
                        var Subject = $(".Subject").val();
                        var QuestionStatement = CKEDITOR.instances.editor1.getData() ;
                        var QuestionNumber = $(".QuestionNumber").val();
                        var SectionNumber = $(".SectionNumber").val();
                        var SectionInstruction = CKEDITOR.instances.SectionInstruction.getData() ;;
                        var TermName = $(".TermName").val();
                        var ClassTeacher = $(".ClassTeacher").val();
                        var optionA = $(".optionA").val();
                        var optionB = $(".optionB").val();
                        var optionC = $(".optionC").val();
                        var optionD = $(".optionD").val();
                        var optionD = $(".optionD").val();
                        var QuestionImage = myFormData ;
                        var EditQuestionId = $(".EditQuestionId").val();
                        var EditCorrectAnswerId = $(".EditCorrectAnswerId").val();
                        var EditQuestionOptionsId = $(".EditQuestionOptionsId").val();
                        var CorrectAnswer = $(".CorrectAnswer").val();
                        var ActionUrl  =  $(".QuestionEditform").prop('action');          
                                   
                                   //console.log(ActionUrl);
                                   data =  { 'Year' : Year, 'Class' : Class,'Subject': Subject, 'QuestionStatement': QuestionStatement, 'optionA': optionA,
                                          'optionB': optionB,'optionC': optionC,'optionD': optionD, 'CorrectAnswer': CorrectAnswer, 
                                          'QuestionNumber': QuestionNumber, 'SectionNumber': SectionNumber, 'ClassTeacher': ClassTeacher,
                                          'TermName': TermName,'SectionInstruction': SectionInstruction, 'EditQuestionId': EditQuestionId ,
                                          'EditCorrectAnswerId': EditCorrectAnswerId , 'EditQuestionOptionsId': EditQuestionOptionsId  };
                                      $.each(data, function(index,value)
                                     {                                      
                                        myFormData.append(index, value);
                                    });
                              console.log(myFormData);                              

                        $.ajax({
                                  type: 'POST',
                                   url:  ActionUrl,
                                  dataType: 'json',
                                data: myFormData,
                                  processData: false, // important
                                  contentType: false, // important
                                  success: function(response, textStatus)
                                            {  var errortext ="";
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
                                                   
                                                    $(".ValidationErrorReport").html(" ");
                                                    $(".ReportMessage").html(errortext); 
                                                    $(".ErrorReport").html(response.ErrorReport.errorInfo[2]);
                                            }
                                            //  window.location.reload();                                            
                                            },
                                  error: function(xhr, textStatus, errorThrown) 
                                            {
                                               alert('Bad response. Please reload the page. if this message continue, please contact the Head of the school. Thank you.');
                                                console.log(xhr);
                                                console.log(textStatus);
                                            //  console.log(errorThrown);
                                            // window.location.reload();
                                            }
                                        });//end ajax
                            });//end anon function   
                      });   
       AtURL =  $(".GetQuestionURL").val(); //alert(AtURL);


 $('.GetQuestionButton').on("click", function (e) 
                    { 
                        $(".QuestionsPanel").text(" ");     
                        $(".ShowPieChart").hide(); 
                        $(".ResultButton").hide(); 
                         $(".ReportMessage").text(" "); 
                        $(".ValidationErrorReport").text(" "); 
                        $(".ErrorReport").text(" "); 
                        var BaseUrl = $(".BaseURL").val();
                        console.log(BaseUrl);
                        e.preventDefault();
                        if ( $(".IsAdmin").val() == "1"){   IsAdmin = $(".IsAdmin").val() }
                        else{ IsAdmin = 0 }
                        var Year = $(".Year").val();
                        var Class = $(".Class").val();
                        var Subject = $(".Subject").val();
                        AtURL =  $(".GetQuestionURL").val(); //alert(AtURL);
                        data =  { 'Year' : Year, 'Class' : Class,'Subject': Subject, "IsAdmin" : IsAdmin};
                       console.log(data);
                        $.ajax({
                                  type: 'POST',
                                  url: AtURL,
                                  data : data,
                                  dataType: 'json',
                                  success: function(response, textStatus)
                                            {  
                                              console.log(response);
                                               var errortext ="";
                                               var validatorerror ="";
                                               var ErrorExerror ="";
                                               var BigHTML = "";
                                               var Count = 0;
                                               if(response.status == 1)
                                                    {
                                                      console.log(response);
                                                        $.each(response.ReportMessage, function(index,value)
                                                          {                                      
                                                              errortext +=  value + "<br />";
                                                          });

                                                                $(".ResultButton").show(); 
                                                        BigHTML += "<div class='w3-accordion w3-light-grey'>" ;
                                                        $.each(response.QuestionTableGroupBy, function(index1,value1)
                                                            {  
                                                                Count++
                                                                 BigHTML +=  "<button type='button' onclick=\"myAccordionFunction('Demo" + (Count) + "')\" class='w3-btn-block w3-hover-orange w3-light-blue w3-left-align'> Section: "  + (Count) +
                                                                             "</button > <div id='Demo" + (Count) +"' class='w3-accordion-content w3-container'>" +  value1["sectioninstruction"];

                                                               $.each(response.QuestionTable, function(index,value)
                                                                 {   
                                                                     if( value1["questionsection"] == value["questionsection"]   ) 
                                                                        {
                                                                              BigHTML += "<br />" + value["questionnumber"] + " " +  value["questionstatement"];   
                                                                                //console.log( value["questionimage"] );
                                                                              if(value["questionimage"] != "NoImage")
                                                                              {
                                                                                  //console.log( value["questionimage"] );
                                                                                  BigHTML += "<br/><img src='" + BaseUrl + "/Images/QuestionImages/" + value["questionimage"] + "' />";                                                                                                                                 
                                                                              }
                                                                                 BigHTML += "<li>(A) <input type='radio' name='question" +  ( value["questionnumber"] - 1).toString() + "' value='A'>" +  value.question_options_belong["optionA"] + "<br />" +
                                                                                            "(B) <input type='radio' name='question" +  ( value["questionnumber"] - 1).toString()  + "' value='B'>" +  value.question_options_belong["optionB"] + "<br />" +
                                                                                            "(C) <input type='radio' name='question" +   ( value["questionnumber"] - 1).toString() + "' value='C'>" +  value.question_options_belong["optionC"] + "<br />" +
                                                                                            "(D) <input type='radio' name='question" + ( value["questionnumber"] - 1).toString()  + "' value='D'>" +  value.question_options_belong["optionD"] + "<br /> </li> ";
                                                                                  
                                                                              if(response.IsAdmin == 1){
                                                                                                        BigHTML += "<a href='StudentQuestionEditPage.html/" + value["questiontableid"]  + "' >Edit</a>";
                                                                                                        BigHTML +=  "<button type='button' class='DeleteThisQuestion' value='" + value["questiontableid"]  +"' > Delete</button></div><br />";
                                                                                                      }
                                                                              
                                                                        }  
                                                                });
                                                                 BigHTML += "</div>"; 
                                                            });    
                                                          BigHTML += "</div>";                                                  
                                                        //alert(BigHTML);
                                                         $(".QuestionsPanel").html(BigHTML);    
                                                          $(".ReportMessage").html(errortext).css("font-weight", "bold");                                                 
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
                                                      $(".ReportMessage").html(errortext).css("font-weight", "bold"); 
                                                      $(".ValidationErrorReport").html(validatorerror);
                                                      $(".ErrorReport").html(" ");
                                                   }                                       
                                            },
                                  error: function(xhr, textStatus, errorThrown) 
                                            {
                                              alert('Bad response. Reloading the page...');
                                              console.log(xhr);
                                              console.log(textStatus);
                                              console.log(errorThrown);
                                            // window.location.reload();
                                            }
                                        });//end ajax 
                    });//end anon

 $('.YearSelectAdmin').change( function (e) 
                    { 
                      

                     
                   $(document).on({
                             ajaxStart: function() { 

                                                      $(".ChangeStudentYear").addClass("AjaxRotatorClasses");
                                                      $(".StudentInfoGraphClass").removeClass("AjaxRotatorInfoGraph");
                                                      $(".TopStudentScore").removeClass("AjaxRotatorStudentScoreTop");

                                                    },                                
                            ajaxStop: function() { $(".ChangeStudentYear").removeClass("AjaxRotatorClasses"); },
                            // ajaxComplete:function() { $(".ReportStudentClass").removeClass("AjaxRotatorClass"); },
                          });           

     /* jQuery(window).on('error', function (e) {
                    // This tells jQuery no more ajax Requests are active
                    // (this way Global start/stop is triggered again on next Request)
                    jQuery.active = 0;
                    //Do something to handle the error
                     $(".ReportStudentClass .AdminReportStudentClass").removeClass("help");
                    }); */
                       
                        var ActionUrl  =  $(".ChangeYearAdmin").val();    
                        console.log(ActionUrl);
                        e.preventDefault();   
                        console.log($(this)[0].className)                     ;
                        Year =  $(".YearSelectAdmin").val(); //alert(AtURL);
                        data =  { 'YearSelectAdmin' : Year,};
                       console.log(ActionUrl);
                       console.log(data);
                        $.ajax({
                                  type: 'POST',
                                  url: ActionUrl,
                                  data : data,
                                  dataType: 'json',
                                   ajaxStart: function() { $(".ChangeStudentYear").addClass("AjaxRotatorClasses");   
                                                           $(".TopStudentScore").removeClass("AjaxRotatorStudentScoreTop");},                                
                            ajaxStop: function() { $(".ChangeStudentYear").removeClass("AjaxRotatorClasses"); },
                                  success: function(response, textStatus)
                                            {  
                                              console.log(response);
                                              var errortext =" ";
                                               if(response.status == 1)
                                                  {
                                                      $.each(response.ShowClassInfo, function(index,value)
                                                      {                                      
                                                              errortext +=  value + " ";
                                                       });
                                                     alert(errortext);
                                                     window.location.reload();
                                                  }
                                                else if(response.status == 0)
                                                  {
                                                      $.each(response.ShowClassInfo, function(index,value)
                                                      {                                      
                                                              errortext +=  value + " ";
                                                       });
                                                     alert(errortext);
                                                  }
                                            },
                                  error: function(xhr, textStatus, errorThrown) 
                                            {
                                              alert('Bad response. Reloading the page...');
                                              console.log(xhr);
                                              console.log(textStatus);
                                              console.log(errorThrown);
                                              window.location.reload();
                                            }
                                        });//end ajax 
                    });//end anon

 $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase().trim();

    $("table:not(.AcademicTable,.ScoreAbsenceTable,.ScoreTermDurationTable)  tr").each(function(index) {
        if (index !== 0) {

            $row = $(this);
          //  $(this)
            var id = $row.find("td:nth-child(2)").text().toLowerCase().trim();
            console.log(id.indexOf(value));

            if (!(id.indexOf(value) > -1  ) )
            {//|| id.indexOf(value) !== 0) {
                $row.hide();
                console.log(id);
            }
            else {
                $row.show();
                console.log(id);
            }
        }
    });
});

 $("#CardSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase().trim();
    var ShowCount = 1;
     //console.log(value);

    $("md-content md-card").each(function(index) 
    {
          //console.log(index);
        if (index !== -1) 
        {
            $row = $(this);
           
            var id = $row.find(".md-headline").text().toLowerCase().trim();         

            if (!(id.indexOf(value) > -1 ) )
            {
                $row.hide();
            }
            else 
            {
                $row.show();
                ShowCount++;
                var Message = "Showing<b> " + ShowCount +  " </b> students in list...";
                $(".ShowTheCount").html(Message);
                //console.log(id);
            }
        }
    });
});

  
	}//end anon function
);//end ready


