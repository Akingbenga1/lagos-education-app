
$( document ).ready(function(){
    $('.button-collapse').sideNav({'edge': 'left'});
    $('.SlideOutLogo').sideNav({'edge': 'left'});
    $.ajaxSetup({  headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')  }    });
    $('.dropdown-button').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrainWidth: false, // Does not change width of dropdown to that of the activator
            hover: false, // Activate on hover
            gutter: 0, // Spacing from edge
            belowOrigin: true, // Displays dropdown below the button
            alignment: 'left', // Displays dropdown with edge aligned to the left of button
            stopPropagation: false // Stops event propagation
        });

    // $('.carousel, .carousel-slider').carousel({fullWidth: true, indicators : true, duration: 1000});

    $('.slider').slider({height: 550});


    $('select').material_select();
    //StudentList is being populated from the Blade View through a direct PHP to Javascript variable assignment
    //console.log(StudentList);

    $("#btnRight").click(function () {
			 var selectedItem = new Option($('.StudentNumber').val(), $('.StudentNumber').val());
       //console.log(selectedItem);
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


    if (typeof StudentList !== "undefined")
    {
        // console.log(ClassListArray);
        $('input.autocomplete').autocomplete({
            data: StudentList ,
            limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
            onAutocomplete: function(val) {
                //console.log($($(this)[0]).find("img").addClass("Gbenga")); Find Image inside generated li and assign Class Gbenga to it
                // Callback function when value is autcompleted.
                $(".StudentNumber").val(val);
                $('#AutoButton, .AutoButtonRight, .AutoButtonSelectAll, .AutoButtonSubmit').prop('disabled', false);
            //  console.log(  $('#AutoButton'), val);
            },
            minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
        });
    }

    //
    // $('.tab-demo').tabs();

    $('#YearSelectAdmin').on( 'change', function (e)
    {
        // $(document).on({
        //     ajaxStart: function() {
        //
        //         $(".ChangeStudentYear").addClass("AjaxRotatorClasses");
        //         $(".StudentInfoGraphClass").removeClass("AjaxRotatorInfoGraph");
        //         $(".TopStudentScore").removeClass("AjaxRotatorStudentScoreTop");
        //
        //     },
        //     ajaxStop: function() { $(".ChangeStudentYear").removeClass("AjaxRotatorClasses"); },
        //     // ajaxComplete:function() { $(".ReportStudentClass").removeClass("AjaxRotatorClass"); },
        // });

        /* jQuery(window).on('error', function (e) {
         // This tells jQuery no more ajax Requests are active
         // (this way Global start/stop is triggered again on next Request)
         jQuery.active = 0;
         //Do something to handle the error
         $(".ReportStudentClass .AdminReportStudentClass").removeClass("help");
         }); */


        e.preventDefault();

        $('#modal1').modal('close');
        var ActionUrl  =  $(".ChangeYearAdmin").val();
        var CurrentClass  =  $(".CurrentClass").val();
        Year =  this.value; // $(".YearSelectAdmin").val(); //alert(AtURL);
        data =  { 'YearSelectAdmin' : Year, 'CurrentClass': CurrentClass };
        $.ajax({
            type: 'POST',
            url: ActionUrl,
            data : data,
            dataType: 'json',
            // ajaxStart: function() { $(".ChangeStudentYear").addClass("AjaxRotatorClasses");
            //     $(".TopStudentScore").removeClass("AjaxRotatorStudentScoreTop");},
            // ajaxStop: function() { $(".ChangeStudentYear").removeClass("AjaxRotatorClasses"); },
            success: function(response, textStatus)
            {
               // console.log(response);
                var errortext =" ";
                if(response.status == 1)
                {
                    $.each(response.ShowClassInfo, function(index,value)
                    {
                        errortext +=  value + " ";
                    });
                    // alert(errortext);
                    Materialize.toast(errortext , 4000); // 4000 is the duration of the toast
                    setTimeout( function() {
                        Materialize.toast("<span style='color:#eeff41;'>Reloading Page... </span>", 4000); // 4000 is the duration of the toast
                    }, 700 );

                    //window.location.reload();

                    setTimeout(function () {
                        window.location = response.ResponseURl;
                    }, 3000);

                }
                else if(response.status == 0)
                {
                    $.each(response.ShowClassInfo, function(index,value)
                    {
                        errortext +=  value + " ";
                    });
                    // alert(errortext);
                    Materialize.toast(errortext, 4000); // 4000 is the duration of the toast
                }
            },
            error: function(xhr, textStatus, errorThrown)
            {
                // alert('Bad response. Reloading the page...');
                Materialize.toast('Bad response. Reloading the page...', 4000); // 4000 is the duration of the toast
               // console.log(xhr);
               //  console.log(textStatus);
               //  console.log(errorThrown);
                window.location.reload();
            }
        });//end ajax
    });//end anon

    $(".dial").knob();

    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();



    $("#CardSearch").on("keyup", function()
    {
        var value = $(this).val().toLowerCase().trim();
        var ShowCount = 0;
        //console.log(value);

        $(".card").each(function(index)
        {
            //console.log(index);
            if (index !== -1)
            {
                row = $(this);

                var id = row.find(".card-content").text().toLowerCase().trim();

                if (!(id.indexOf(value) > -1 ) )
                {
                    row.hide();
                    // ShowCount--;
                }
                else
                {
                    row.show();
                    ShowCount++;
                    var Message = "Showing<b> " + ShowCount +  " </b> students in list...";
                    $(".ShowTheCount").html(Message);
                    console.log(id);
                }
            }
        });
    });

    $('.tab a').on('click', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
        doo = $(this).attr('href');
         //console.log(doo);
    });

    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
   //console.log(lastTab);
    if (lastTab) {
      var this_tab = lastTab.substr(1);
      //console.log(this_tab);
       $('.tab-demo').tabs('select_tab', this_tab);
    }


    $(function () {$('.SubmitQuestion').on("click", function (e)
    {
        e.preventDefault();

        var myFormData = new FormData();
        myFormData.append('QuestionImage', $('.QuestionImage').prop('files')[0]);
        //console.log(myFormData);
        var BaseUrl = $(".BaseURL").val();
       // console.log(BaseUrl);

        $("#QuestionSubmitProgress").show();
        var Year = $(".Year option:selected").val();
        var Class = $(".Class option:selected").val();
        var Subject = $(".Subject option:selected").val();
        var QuestionStatement = CKEDITOR.instances.editor1.getData() ;
        var QuestionNumber = $(".QuestionNumber").val();
        var SectionNumber = $(".SectionNumber").val();
        var SectionInstruction = CKEDITOR.instances.SectionInstruction.getData() ;
        var TermName = $(".TermName option:selected").val();
        var ClassTeacher = $(".ClassTeacher").val();
        var optionA = $(".optionA").val();
        var optionB = $(".optionB").val();
        var optionC = $(".optionC").val();
        var optionD = $(".optionD").val();
        var QuestionImage = myFormData ;
        var CorrectAnswer = $(".CorrectAnswer option:selected").val();
        var ActionUrl  =  $(".QuestionInputform").prop('action');

        if ( $(".IsAdmin").val() == "1")
        {   IsAdmin = $(".IsAdmin").val(); }
        else{ IsAdmin = 0; }

        //console.log(ActionUrl);
        data =  { 'Year' : Year, 'Class' : Class,'Subject': Subject, 'QuestionStatement': QuestionStatement, 'optionA': optionA,
            'optionB': optionB,'optionC': optionC,'optionD': optionD, 'CorrectAnswer': CorrectAnswer,
            'QuestionNumber': QuestionNumber, 'SectionNumber': SectionNumber, 'ClassTeacher': ClassTeacher,
            'TermName': TermName,'SectionInstruction': SectionInstruction, "IsAdmin" : IsAdmin };

        $.each(data, function(index,value)
        {
            myFormData.append(index, value);
        });

        // console.log(myFormData);

        $.ajax({
            type: 'POST',
            url:  ActionUrl,
            dataType: 'json',
            data: myFormData,
            processData: false, // important
            contentType: false, // important
            success: function(response, textStatus)
            {
                $("#QuestionSubmitProgress").hide();
                var errortext ="";
                var validatorerror ="";
                var ErrorExerror ="";
                var BigHTML = "";
                var Count = 0;
                console.log(response);
                if(response.status == 1)
                {

                    $.each(response.ReportMessage, function(index,value)
                    {
                        errortext +=  value + "<br />";
                    });
                    $(".ReportMessage").html(errortext);
                    // $.each(response.QuestionTableGroupBy, function(index1,value1)
                    // {
                    //     BigHTML += value1["sectioninstruction"];
                    //
                    //     $.each(response.QuestionTable, function(index,value)
                    //     {
                    //         if( value1["questionsection"] == value["questionsection"]   )
                    //         {
                    //             BigHTML += "<div><input type='hidden' value='" + value["questiontableid"]  +"' />";
                    //             BigHTML += value["questionnumber"] + " " +  value["questionstatement"];
                    //             if(value["questionimage"] != "NoImage")
                    //             {
                    //                 console.log( value["questionimage"] );
                    //                 BigHTML += "<br/><img src='" + BaseUrl + "/Images/QuestionImages/" + value["questionimage"] + "' />";
                    //             }
                    //             BigHTML += "<li>(A) <input type='radio' name='question" +  ( value["questionnumber"] - 1).toString() + "' value='A'>" +  value.question_options_belong["optionA"] + "<br />" +
                    //                 "(B) <input type='radio' name='question" +  ( value["questionnumber"] - 1).toString()  + "' value='B'>" +  value.question_options_belong["optionB"] + "<br />" +
                    //                 "(C) <input type='radio' name='question" +   ( value["questionnumber"] - 1).toString() + "' value='C'>" +  value.question_options_belong["optionC"] + "<br />" +
                    //                 "(D) <input type='radio' name='question" + ( value["questionnumber"] - 1).toString()  + "' value='D'>" +  value.question_options_belong["optionD"] + "<br /> </li>";
                    //             BigHTML +=  "<a href='StudentQuestionEditPage.html/" + value["questiontableid"]  + "' >Edit</a>";
                    //             BigHTML +=  "<button type='button' class='DeleteThisQuestion' value='" + value["questiontableid"]  +"' > Delete</button></div>";
                    //         }
                    //     });
                    // });
                    $.each(response.QuestionTableGroupBy, function(index1,value1)
                    {
                        Count++;
                        BigHTML +=  "<li>" ;
                        BigHTML +=  "<div class='collapsible-header'> <i class='material-icons'> filter_drama </i>Section: "  + (Count) + "</div >" +
                            "<div id='Demo" + (Count) +"' class='collapsible-body'>" +  value1["sectioninstruction"];
                        BigHTML += "<div class='row'>";

                        $.each(response.QuestionTable, function(index,value)
                        {

                            if( value1["questionsection"] == value["questionsection"]   )
                            {
                                BigHTML +=   "<div class='col s12 m12 l4'>";
                                BigHTML +=   "<div class='card  light-blue lighten-5 z-depth-3' style='height:350px!important; overflow: auto; '>";

                                //console.log( value["questionimage"] );


                                BigHTML += "<div class='card-content'>";
                                BigHTML += "<span style ='color:#b71c1c;font-weight:400;'>" + value["questionnumber"] + "</span><br /> " +  value["questionstatement"] + "";
                                if(value["questionimage"] != "NoImage")
                                {
                                    //console.log( value["questionimage"] );
                                    BigHTML += "<br/><img src='" + BaseUrl + "/Images/QuestionImages/" + value["questionimage"] + "'  class='responsive-img'/><br />";
                                }
                                BigHTML +=  "<input type='radio' class='redRadio darken-1' id='question" +  ( value["questionnumber"] - 1).toString() + "A' name='question" +  ( value["questionnumber"] - 1).toString() + "' value='A' >" +
                                    "<label class='active' for='question" +  ( value["questionnumber"] - 1).toString() + "A'>(A) "+value.question_options_belong["optionA"] + "&nbsp; &nbsp; &nbsp; </label>   " +
                                    "<input type='radio' class='redRadio darken-1' id='question" +  ( value["questionnumber"] - 1).toString() + "B' name='question" +  ( value["questionnumber"] - 1).toString()  + "' value='B'  />"  +
                                    "<label class='active' for='question" +  ( value["questionnumber"] - 1).toString() + "B'>(B) "+value.question_options_belong["optionB"] + "&nbsp; &nbsp; &nbsp;</label>" +
                                    "<input type='radio' class='redRadio darken-1' id='question" +  ( value["questionnumber"] - 1).toString() + "C' name='question" +   ( value["questionnumber"] - 1).toString() + "' value='C'  />" +
                                    "<label class='active' for='question" +  ( value["questionnumber"] - 1).toString() + "C'>(C) "+value.question_options_belong["optionC"] + "&nbsp; &nbsp; &nbsp;</label>" +
                                    "<input type='radio' class='redRadio darken-1' id='question" +  ( value["questionnumber"] - 1).toString() + "D' name='question" + ( value["questionnumber"] - 1).toString()  + "' value='D'  />"  +
                                    "<label class='active' for='question" +  ( value["questionnumber"] - 1).toString() + "D'>(D) "+value.question_options_belong["optionD"] + "&nbsp; &nbsp; &nbsp;</label>" +
                                    "</div>";
                                if(response.IsAdmin == 1)
                                {
                                    BigHTML += "<div class='card-action'><a href='StudentQuestionEditPage.html/" + value["questiontableid"]  + "' class='btn light-blue darken-5 left tooltipped ' data-position='left' data-delay='20' data-tooltip='EDIT' ><i class='material-icons prefix'>edit</i></a>";
                                    BigHTML +=  "<a class='DeleteThisQuestion btn gradient-45deg-deep-orange-orange right tooltipped' data-position='right' data-delay='20' data-tooltip='DELETE' value='" + value["questiontableid"]  +"' > <i class='material-icons prefix'>delete</i></a><br /> </div>";
                                }
                                BigHTML += "</div></div>";
                            }


                        });
                        BigHTML += "</div>";
                        BigHTML += "</div>";
                        BigHTML +=  "</li>" ;
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
                $("#QuestionSubmitProgress").hide();
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
        // console.log(BaseUrl);
        //console.log( $('.QuestionImage'));

        var Year = $(".Year option:selected").val();
        var Class = $(".Class option:selected").val();
        var Subject = $(".Subject option:selected").val();
        var QuestionStatement = CKEDITOR.instances.editor1.getData() ;
        var QuestionNumber = $(".QuestionNumber").val();
        var SectionNumber = $(".SectionNumber").val();
        var SectionInstruction = CKEDITOR.instances.SectionInstruction.getData() ;;
        var TermName = $(".TermName option:selected").val();
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
        var CorrectAnswer = $(".CorrectAnswer option:selected").val();
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
    $('.GetQuestionButton').on("click", function (e)
    {
        e.preventDefault();

        $(".QuestionsPanel").text(" ");
        $(".ShowPieChart").hide();
        $(".ResultButton").hide();
        $("#QuestionPanelProgess").show();
        $(".ReportMessage").text(" ");
        $(".ValidationErrorReport").text(" ");
        $(".ErrorReport").text(" ");
        var BaseUrl = $(".BaseURL").val();


        if ( $(".IsAdmin").val() == "1"){   IsAdmin = $(".IsAdmin").val(); }
        else{ IsAdmin = 0; }

        var Year = $(".Year option:selected").val();
        var Class = $(".Class option:selected").val();
        var Subject = $(".Subject option:selected").val();

        AtURL =  $(".GetQuestionURL").val(); //alert(AtURL);
        data =  { 'Year' : Year, 'Class' : Class,'Subject': Subject, "IsAdmin" : IsAdmin};

        $.ajax({
            type: 'POST',
            url: AtURL,
            data : data,
            dataType: 'json',
            success: function(response, textStatus)
            {
                // console.log(response);
                $("#QuestionPanelProgess").hide();
                var errortext ="";
                var validatorerror ="";
                var ErrorExerror ="";
                var BigHTML = "";
                var Count = 0;
                if(response.status == 1)
                {
                    //console.log(response);
                    $.each(response.ReportMessage, function(index,value)
                    {
                        errortext +=  " <span class='SuccessBlock'>" +  value + "<br />";
                    });

                    $(".ResultButton").show();
                    // BigHTML += "<div>" ;
                    $.each(response.QuestionTableGroupBy, function(index1,value1)
                    {
                        Count++;
                        BigHTML +=  "<li>" ;
                        BigHTML +=  "<div class='collapsible-header'> <i class='material-icons'> filter_drama </i>Section: "  + (Count) + "</div >" +
                                    "<div id='Demo" + (Count) +"' class='collapsible-body'>" +  value1["sectioninstruction"];
                        BigHTML += "<div class='row'>";

                        $.each(response.QuestionTable, function(index,value)
                        {

                            if( value1["questionsection"] == value["questionsection"]   )
                            {
                                BigHTML +=   "<div class='col s12 m12 l4'>";
                                BigHTML +=   "<div class='card  light-blue lighten-5 z-depth-3' style='height:350px!important; overflow: auto; '>";

                                //console.log( value["questionimage"] );


                                BigHTML += "<div class='card-content'>";
                                BigHTML += "<span style ='color:#b71c1c;font-weight:400;'>" + value["questionnumber"] + "</span><br /> " +  value["questionstatement"] + "";
                                if(value["questionimage"] != "NoImage")
                                {
                                    //console.log( value["questionimage"] );
                                    BigHTML += "<br/><img src='" + BaseUrl + "/Images/QuestionImages/" + value["questionimage"] + "'  class='responsive-img'/><br />";
                                }
                                BigHTML +=  "<input type='radio' class='redRadio darken-1' id='question" +  ( value["questionnumber"] - 1).toString() + "A' name='question" +  ( value["questionnumber"] - 1).toString() + "' value='A' >" +
                                            "<label class='active' for='question" +  ( value["questionnumber"] - 1).toString() + "A'>(A) "+value.question_options_belong["optionA"] + "&nbsp; &nbsp; &nbsp; </label>   " +
                                            "<input type='radio' class='redRadio darken-1' id='question" +  ( value["questionnumber"] - 1).toString() + "B' name='question" +  ( value["questionnumber"] - 1).toString()  + "' value='B'  />"  +
                                            "<label class='active' for='question" +  ( value["questionnumber"] - 1).toString() + "B'>(B) "+value.question_options_belong["optionB"] + "&nbsp; &nbsp; &nbsp;</label>" +
                                            "<input type='radio' class='redRadio darken-1' id='question" +  ( value["questionnumber"] - 1).toString() + "C' name='question" +   ( value["questionnumber"] - 1).toString() + "' value='C'  />" +
                                            "<label class='active' for='question" +  ( value["questionnumber"] - 1).toString() + "C'>(C) "+value.question_options_belong["optionC"] + "&nbsp; &nbsp; &nbsp;</label>" +
                                            "<input type='radio' class='redRadio darken-1' id='question" +  ( value["questionnumber"] - 1).toString() + "D' name='question" + ( value["questionnumber"] - 1).toString()  + "' value='D'  />"  +
                                            "<label class='active' for='question" +  ( value["questionnumber"] - 1).toString() + "D'>(D) "+value.question_options_belong["optionD"] + "&nbsp; &nbsp; &nbsp;</label>" +
                                            "</div>";
                                if(response.IsAdmin == 1)
                                {
                                    BigHTML += "<div class='card-action'><a href='StudentQuestionEditPage.html/" + value["questiontableid"]  + "' class='btn light-blue darken-5 left tooltipped ' data-position='left' data-delay='20' data-tooltip='EDIT' ><i class='material-icons prefix'>edit</i></a>";
                                    BigHTML +=  "<a class='DeleteThisQuestion btn gradient-45deg-deep-orange-orange right tooltipped' data-position='right' data-delay='20' data-tooltip='DELETE' value='" + value["questiontableid"]  +"' > <i class='material-icons prefix'>delete</i></a><br /> </div>";
                                }
                                BigHTML += "</div></div>";
                            }


                        });
                        BigHTML += "</div>";
                        BigHTML += "</div>";
                        BigHTML +=  "</li>" ;
                    });
                    // BigHTML += "</div>";
                    //alert(BigHTML);
                    $(".QuestionsPanel").html(BigHTML);
                    $(".ReportMessage").html(errortext).css("font-weight", "bold");
                }
                else if(response.status == 0)
                {
                    $.each(response.ReportMessage, function(index,value)
                    {
                        errortext +=  "<span class='LoginError'>" + value + "<br />";
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
                $("#QuestionPanelProgess").hide();
                alert('Bad response. Reloading the page...');
                console.log(xhr.responseText);
                console.log(textStatus);
                console.log(errorThrown);
                // window.location.reload();
            }
        });//end ajax
    });//end anon


    /*    Javascript for the StudentQuestionPage   */
    $(".ShowPieChart").hide();
    $(".ResultButton").hide();
    $("#QuestionPanelProgess").hide();
    $("#ResultModal").hide();
    /*    Javascript for the StudentQuestionPage   */

    function myAccordionFunction(id) {
        var x = document.getElementById(id);
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }

    $('.ResultButton').on("click", function (e)
    {
        e.preventDefault();
        $("#ResultModal").show();

        SaveScoreURL =  $(".SaveScoreURL").val(); //alert(AtURL);
        var answers = {};
        var QuestionList = {};
        var MyScore;
        var Year = $(".Year option:selected").val();
        var Class = $(".Class option:selected").val();
        var Subject = $(".Subject option:selected").val();
        AtURL =  $(".GetQuestionURL").val(); //alert(AtURL);
        data =  { 'Year' : Year, 'Class' : Class,'Subject': Subject, "IsAdmin" : 0};
        $.ajax({
            type: 'POST',
            url: AtURL,
            data : data,
            dataType: 'json',
            async: false,
            success: function(response, textStatus)
            {
                answers = response.AnswersArray;
                QuestionList = response.QuestionTable;
            },
            error: function(xhr, textStatus, errorThrown)
            {
                alert('Bad response. Reloading the page...');
                console.log(xhr);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });//end ajax
        Total = Object.keys(answers).length;
        function getCheckedValue( radioName ){
            var radios = document.getElementsByName( radioName ); // Get radio group by-name
            for(var y=0; y<radios.length; y++)
                if(radios[y].checked) return radios[y].value; // return the checked value
        }
        function getScore(){
            var score = 0;
            $.each(QuestionList, function(index1,value1)
            {
                QuestionNumber = (value1["questionnumber"] - 1).toString();
                if(getCheckedValue("question"+ QuestionNumber)===answers[QuestionNumber])
                    score += 1; // increment only
            });
            return score;
        }

        MyScore = getScore();
        var TextResponse = "<div class='green lighten-5 green-text center-align'><h4> Your score is "+ getScore() +"/"+ Total +  "</h4></div>";
        // alert("Your score is "+ getScore() +"/"+ Total);
        if(Number.isInteger(MyScore) && Number.isInteger(Total))
        {
            //alert("Your score will be saved to database");
            TextResponse  += "<div class='center-align lighten-5 blue-text'>Your score will be saved to database!</div>";
            $('.QuestionResponseModal').html(TextResponse);
            data = {'MyScore' : MyScore, "Total" : Total};
            //console.log(data);
            var errortext =" ";
            var monthData = {};
            $.ajax({
                type: 'POST',
                url: SaveScoreURL,
                data : data,
                dataType: 'json',
                async: false,
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

                    $('#month-area').remove(); // this is my <canvas> element

                    $(".ShowPieChart").show();
                    $('.ShowPieChart').append('<div class="col s6"><canvas id="month-area" style=""></canvas></div>');
                    var canvas = document.getElementById("month-area");
                    var midX = canvas.width/2;
                    var midY = canvas.height/2
                    var ctx = canvas.getContext("2d");
                    var  myPie = new Chart(ctx, {
                                                    type: 'pie',
                                                    // data: monthData,
                        data: {
                            labels:  monthData.QuestionResponseLabel,
                            datasets: [{
                                label: '# of Votes',
                                data: monthData.QuestionResponse,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                                                    //options: options
                                                });
                    //console.log(myPie);
                    // var myPie = new Chart(ctx).Pie(monthData,
                    //     { onComplete:   function ()
                    //     {  alert("Thanks");
                    //         for(var i=0; i<myPie.segments.length; i++)
                    //         {
                    //             var radius = myPie.outerRadius;
                    //             ctx.fillStyle="white";
                    //             var textSize = canvas.width/10;
                    //             ctx.font= textSize+"px Verdana";
                    //             // Get needed variables
                    //             var value = myPie.segments[i].value;
                    //             var startAngle = myPie.segments[i].startAngle;
                    //             var endAngle = myPie.segments[i].endAngle;
                    //             var middleAngle = startAngle + ((endAngle - startAngle)/2);
                    //
                    //             // Compute text location
                    //             var posX = (radius/2) * Math.cos(middleAngle) + midX;
                    //             var posY = (radius/2) * Math.sin(middleAngle) + midY;
                    //
                    //             // Text offside by middle
                    //             var w_offset = ctx.measureText(value).width/2;
                    //             var h_offset = textSize/4;
                    //
                    //
                    //             ctx.fillText(value, posX - w_offset, posY + h_offset);
                    //         }
                    //     },
                    //
                    //         legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><b> <%=segments[i].label%> is <%=segments[i].value%></b><%}%></li><%}%></ul>"
                    //         //};
                    //     });
                    var legend = myPie.generateLegend();
                    $("#legend").html(legend);
                },
                error: function(xhr, textStatus, errorThrown)
                {
                    alert('Bad response. Reloading the page...');
                    console.log(xhr.responseText);
                    // console.log(textStatus);
                    // console.log(errorThrown);
                    // window.location.reload();
                }
            });//end ajax
        }
        else
        {
            TextResponse  += "<div class=' lighten-5 red-text center-align'> Error! Unable to save your score in the database!</div>";
            //alert("Error! Unable to save your score in the database");
        }

        $('#modal1').modal('open');
        $('.QuestionResponseModal').html(TextResponse);

    });//end anon function

});

$(document).ready(function ()
{
    $('.tab-demo').tabs();
    $('#Thoughts_textarea').characterCounter();
    $('.datepicker').pickadate({
   selectMonths: true, // Creates a dropdown to control month
   selectYears: 150, // Creates a dropdown of 15 years to control year,
   today: 'Today',
   clear: 'Clear',
   close: 'Ok',
   closeOnSelect: true // Close upon selecting a date,
 });
});

$(function () {$('.ChangeClassYear').on("click", function (e)
             {

                 e.preventDefault();
                 var StudentId =  $(this).parents().eq(2).find('input[type="hidden"][name="PromotionStudentId"]').val() ;
                 //PromotionStudentId
                 var Year = $(".CurrentYear").val(); // From PHP Session
                 var OldClass =  $(".OldClass").val();

                 var NewClass =  $(this).parents().eq(1).find(".ClassSelect").val(); //Trust this code, its not an hack
                 var SubClass = $(this).parents().eq(1).find(".SubClassSelect").val(); //Trust this clode, its not an hack

                 var ActionUrl  =  $(".ChangeClassURL").val().replace(/\/$/, ""); // You must remove trailing slashes // to avoid 404 errors that you cant see;

                    data = { 'Year' : Year,
                             'SubClass': SubClass,
                             'OldClass':OldClass,
                             'NewClass':NewClass,
                             'StudentId': StudentId
                           }

                    console.log( ActionUrl, data );

                 $.ajax({
                           type: 'POST',
                           url:  ActionUrl,
                           dataType: 'json',
                           data: data,
                           success: function(response, textStatus)
                                     {
                                       console.log(response);
                                       var errortext =" ";
                                        if(response.status == 1)
                                           {
                                               $.each(response.ChangeClassInfo, function(index,value)
                                               {
                                                       errortext +=  value + " ";
                                                });
                                              alert(errortext);
                                              window.location.reload();
                                           }
                                         else if(response.status == 0)
                                           {
                                               $.each(response.ChangeClassInfo, function(index,value)
                                               {
                                                       errortext +=  value + " ";
                                                });
                                              alert(errortext);
                                           }
                                       //window.location.reload();
                                     },
                           error: function(xhr, textStatus, errorThrown)
                                     {
                                       alert('Bad response. Reloading the page...');
                                       console.log(xhr);
                                       console.log(textStatus);
                                       console.log(errorThrown);
                                       //window.location.reload();
                                     }
                                 });//end ajax
                     });//end anon function
               });


$(function () {$('.PromoteStudent').on("click", function (e)
                                   {
                                       e.preventDefault();
                                       var StudentId =  $(this).parents().eq(0).find('input[type="hidden"][name="PromotionStudentId"]').val() ;
                                       var Year = 2016; //Year Hard Coded
                                       var Class =  $(this).parents().eq(1).find(".ClassSelect").val(); // Dont trust this code. Its an hack
                                       var SubClass = $(this).parents().eq(1).find(".SubClassSelect").val(); // Dont trust this code. Its an hack


                                       var ActionUrl  =  $(".PromotionURL").val().replace(/\/$/, ""); // You must remove trailing slashes
                                                                                                      // to avoid 404 errors that you cant see

                                          data = { 'Year' : Year,'SubClass': SubClass, 'Class':Class,'StudentId': StudentId }
                                           //site.replace(/\/$/, "");
                                          //console.log( ActionUrl.replace(/\/$/, "") );
                                          //alert(ActionUrl);

                                          console.log(data);


                                       $.ajax({
                                                 type: 'POST',
                                                 url:  ActionUrl , //"http://localhost/PROJECT/IjayeHousingEstate/IjayeHousingEstate/public/PromoteThisStudent/",
                                                 dataType: 'json',
                                                 data: data,
                                                 success: function(response, textStatus)
                                                           {
                                                             console.log(response);
                                                             var errortext =" ";
                                                        if(response.status == 1)
                                                           {
                                                               $.each(response.PromotionInfo, function(index,value)
                                                               {
                                                                       errortext +=  value + " ";
                                                                });
                                                              alert(errortext);
                                                              window.location.reload();
                                                           }
                                                         else if(response.status == 0)
                                                           {
                                                               $.each(response.PromotionInfo, function(index,value)
                                                               {
                                                                       errortext +=  value + " ";
                                                                });
                                                              alert(errortext);
                                                           }
                                                            // window.location.reload();
                                                           },
                                                 error: function(xhr, textStatus, errorThrown)
                                                           {
                                                             alert('Bad response. Reloading the page...');
                                                             console.log(xhr);
                                                             console.log(textStatus);
                                                             console.log(errorThrown);
                                                             //window.location.reload();
                                                           }
                                                       });//end ajax
                                           });//end anon function
                                     });
jQuery(window).on('error', function (e) {
 // This tells jQuery no more ajax Requests are active
 // (this way Global start/stop is triggered again on next Request)
 jQuery.active = 0;
 //Do something to handle the error
 //   $(".ReportStudentClass").removeClass("AjaxRotator");
 });

$('.GetClassYear').on("click", function (e)
{
// console.log(  (javascript:void(0) ) );
   e.preventDefault();
    $(".ExamPanelProgess").show();
   $(".ReportStudentClass").html(" ");
   var StudentID = $('#StuId').val();
   $(".StudentId").val('');
   var StudentNumber = $('.StudentNumber').val();
   var ActionUrl  =  $(".ClassYearURL").val();
   $(document).on({

                 ajaxStart: function() { $(".TopStudentScore").addClass("AjaxRotatorStudentScoreTop");
                                         $(".StudentInfoGraphClass").removeClass("AjaxRotatorInfoGraph");
                                         $(".CheckResultLink").show();     },
                 ajaxStop: function() {  $(".TopStudentScore").removeClass("AjaxRotatorStudentScoreTop");
                                         $(".StudentInfoGraphClass").removeClass("AjaxRotatorInfoGraph");
                                         $(".CheckResultLink").show();  },
                   // ajaxComplete:function() { $(".ReportStudentClass").removeClass("AjaxRotatorClass"); },
                 });

   data =  { 'StudentID' : StudentID, 'StudentNumber': StudentNumber};


   //console.log(ActionUrl, data);
   try {
              $.ajax({
                     type: 'POST',
                     url:  ActionUrl,
                     dataType: 'json',
                     data: data,
                     success: function(response, textStatus)
                               {
                                 	$(".ExamPanelProgess").hide();
                                    var classlist ="";
                                  var validatorerror ="";
                                  var ErrorExerror ="";
                                  var BigHTML = "";

                                 //console.log(response);
                                 //console.log(response[0].thisterm_belong.classname);
                                 len = $.map(response, function(n, i) { return i; }).length;
                                 if( !( len  == 0) )
                                 {
                                     $('.GetStudentScoreSheet .ClassSelect option:last-child').remove();
                                     $('.GetStudentScoreSheet .SubClassSelect option:last-child').remove();
                                     $('.GetStudentScoreSheet .YearSelect option:last-child').remove();
                                     $(".TellStudentClass").html(" ");
                                    // $(".ClassSelect").val(response[0].thisterm_belong.classname);
                                     $.each(response, function(index,value)
                                         {
                                         console.log(value );
                                              $(".StudentId").val(value.studentid);
                                              classlist +=  "<div class='col s3 m3 l3'> <input type='radio' name='PickClass' id='question"+ value.id + "' class='PickClass  red darken-1 ' value='" +
                                                             JSON.stringify(value) + "' />" +
                                                             "<label class='active' for='question"+ value.id + "'> " +
                                                            value.thisterm_belong.classname + " " +
                                                             value.class_subdivision.toUpperCase() + " , "  +
                                                             value.thisterm_belong.year + "/" +
                                                             ( parseInt(value.thisterm_belong.year) + 1)
                                                             + " session  </label> </div>";
                                          });
                          //  alert(classlist);
                             // window.location.reload();
                              $(".TellStudentClass").append(classlist );
                               $(".TelStudentClass").append("Choose the right class of this student"
                                                                 + "<b>" +
                                                                    "</b> . Details of this student class " +
                                                                    "will be  updated automatically on " +
                                                                    " the right hand side of this page." +
                                                                    " ");
                                 $('.HighlitStudentClass').effect("highlight", {}, 5000);
                                 }
                                 else
                                 {
                                     $('.GetStudentScoreSheet .ClassSelect option:last-child').remove();
                                     $('.GetStudentScoreSheet .SubClassSelect option:last-child').remove();
                                     $('.GetStudentScoreSheet .YearSelect option:last-child').remove();
                                    // $(".ReportStudentClass").html(" ");
                                    // $(".ClassSelect").val(response[0].thisterm_belong.classname);
                                     $(".TellStudentClass").html(" ");
                                      $(".StudentId").val('');
                                    $(".ReportStudentClass").html("<b> This student does not have any class</b>");
                                    $('.HighlitStudentClass').effect("highlight", {}, 5000);
                                 }

                               //  window.location.reload();
                               },
                     error: function(xhr, textStatus, errorThrown)
                               {
                                 // alert('Bad response. Please reload the page. if this message continue, please contact the Head of the school. Thank you.');
                                 	$(".ExamPanelProgess").hide();
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
           	$(".ExamPanelProgess").hide();
           console.log(e); // pass exception object to error handler
       }

});//end anon function
$('.TellStudentClass').on("change", ".PickClass", function (e)
{
   e.preventDefault();
   $(".ExamPanelProgess").show();
    //$(this).prop('checked');
  // $(".ReportStudentClass").html(" ");
  var ThisRadioId = $(this).prop('id');
  $("#" + ThisRadioId).attr("checked", true);
   //console.log( $(this), $(this).prop('id') );
   var ThisRadioClassYear = JSON.parse( $(this).val() );

   var ActionUrl  =  $(".ClassYearURL").val();

   //console.log(ActionUrl);
   try {
              $.ajax({
                     type: 'POST',
                     url:  ActionUrl,
                     dataType: 'json',
                     data: {},
                     success: function(response, textStatus)
                               { //console.log(response);
                                    $(".ExamPanelProgess").hide();
                                     $('.GetStudentScoreSheet .ClassSelect option:last-child').remove();
                                     $('.GetStudentScoreSheet .SubClassSelect option:last-child').remove();
                                     $('.GetStudentScoreSheet .YearSelect option:last-child').remove();
                                     //$(".ReportStudentClass").html(" ");
                                    // $(".ClassSelect").val(response[0].thisterm_belong.classname);
                                     $('.GetStudentScoreSheet .ClassSelect').append($('<option/>', {
                                                               value: ThisRadioClassYear.thisterm_belong.classname,
                                                               text : ThisRadioClassYear.thisterm_belong.classname,
                                                               selected : true
                                                           }));
                                      $('.GetStudentScoreSheet .SubClassSelect').append($('<option/>', {
                                                               value: ThisRadioClassYear.class_subdivision,
                                                               text : ThisRadioClassYear.class_subdivision.toUpperCase(),
                                                               selected : true
                                                           }));
                                       $('.GetStudentScoreSheet .YearSelect').append($('<option/>', {
                                                               value: ThisRadioClassYear.thisterm_belong.year,
                                                               text : ThisRadioClassYear.thisterm_belong.year,
                                                               selected : true
                                                           }));

                                  // ChooseTermBox
                                       //$div2blink.toggleClass("backgroundRed"); $( "#toggle" ).toggle( "highlight" );
                                       $('.GetStudentScoreSheet .ClassSelect').effect("highlight", {}, 5000);
                                       $('.GetStudentScoreSheet .SubClassSelect').effect("highlight", {}, 5000);
                                       $('.GetStudentScoreSheet .YearSelect').effect("highlight", {}, 5000);



                               },
                     error: function(xhr, textStatus, errorThrown)
                               {
                                 $(".ExamPanelProgess").hide();
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
