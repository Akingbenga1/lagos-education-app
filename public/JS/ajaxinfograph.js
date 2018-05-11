$(document).ready(function()
	{ $.ajaxSetup({  headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')  }    });
                    
      var ActionUrl  = $('.InfoGraphicURL').val();       
       $(document).on({
                               
                          ajaxStart: function() {  $(".StudentInfoGraphClass").addClass("AjaxRotatorInfoGraph"); $(".CheckResultLink").hide();    },
                          ajaxStop: function() { $(".StudentInfoGraphClass").removeClass("AjaxRotatorInfoGraph"); $(".CheckResultLink").show(); },
                            // ajaxComplete:function() { $(".ReportStudentClass").removeClass("AjaxRotatorClass"); },
                          });                      
      //console.log(ActionUrl);
      $.ajax({
                type: 'GET',
                url:  ActionUrl,
                dataType: 'json',
                //data: {"SubjectScore" : SubjectScore },//$(this).parent().serialize(),
                success: function(response, textStatus)
                        { 
                          console.log(response);
                          var StudentsCountHtml  =  response.StudentsCount;
                          var ScoreCountHtml     =  response.ScoreCount;
                          var ResultCountHtml    =  Math.floor(((response.ScoreCount/10)/response.StudentsCount)*100) + "%";
                            $('.StudentCount').html(StudentsCountHtml);
                            $('.ScoreCount').html(ScoreCountHtml);
                            $('.ResultCount').html(ResultCountHtml);


                            console.log(response);
                        },
                error: function(xhr, textStatus, errorThrown) 
                       {
                          console.log(xhr);
                          console.log(textStatus);
                          console.log(errorThrown);
                    
                        }
            });//end ajax
	}//end anon function
	);//end ready