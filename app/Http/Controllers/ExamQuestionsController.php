<?php

namespace App\Http\Controllers;


//use App\RandomColor\RandomColor;
use App\Http\Controllers\Controller;
use App\Models\QuestionResult;
use App\Models\QuestionTable;
use App\Models\Students;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use PDO;

require "RandomColor.php";

class ExamQuestionsController extends Controller
{

    public function __construct(  )
    {
        $this->middleware('auth');
    }


    public function getStudentQuestionPage(Request $request)
    {
        $method = $request->isMethod('post');
        if($method)
        {
        }
        else
        {
            $AllSubjects = Subjects::all();
            return View::make('students.studentquestionpage')->with(array(
                                                                'myBreadCrumb' =>
                                                                "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
                'Title' => 'Student Page',
                'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():''
            ));
        }


    }

    public function getQuestionsFromDatabase()
    {
//        return response()->json( ["Gbenga"]);
        $FormMessages = array(/*
                           	'StudentNumber.required_without' => 'Your <b> Student Admission Number</b>  is required or you can type your name in the other text box on the left.',
                           	'StudentNumber.digits' => 'Your <strong> Student Admission Number</strong> must be exactly 4 digits.',
                           	'StudentNumber.numeric' => 'Your <strong> Student Admission Number</strong> must be a number only.',
                           	'Year.required' => 'The year is required.',
                           	'Year.date_format' => 'Please, choose a year from the year list.',
                           	'TermName.required' => 'The term is required.',
                           	'Class.required' => 'The class is required.',
                           	'Class.max' => 'The class must be :max characters long.', */
        );
        $validator = Validator::make(Input::all(),
            array( 'Year' => 'required|date_format:Y',
                //'TermName' => 'required',
                'Class' => 'required|max:3',
                'Subject' => 'required|integer',
            ), $FormMessages);//end static method make of Validator
        if($validator->fails())
        {
            $ValidatorErr =  $validator->getMessageBag()->toArray();
            $response = array('ReportMessage' => ['There are some errors with the details provided. Please, Check below'],
                'status' => 0, 'ValidationErrorReport' => $ValidatorErr
            );
            return Response::json( $response);
        }//end if statement that complain that validation failled
        else
        {

            try{
                $QuestionTable = QuestionTable::where( 'classname', '=', Input::get('Class'))
                                                ->where(  'year', '=' , Input::get('Year') )
                                                ->where( 'subjectid', '=' , Input::get('Subject') )
                                                ->orderBy('questionnumber')->with(["questionOptionsBelong","questionAnswerBelong"]);
                $ThisSubject = Subjects::find( Input::get('Subject'))->toArray()["subject"];
//                DB::setFetchMode(PDO::FETCH_ASSOC);

                $QuestionTableGroupBy = DB::table('questiontable')
                    ->select('questionsection','sectioninstruction', DB::raw('count(*) as MyCount'))
                    ->groupBy('questionsection')
                    ->where(  'year', '=' , Input::get('Year') )
                    ->where( 'subjectid', '=' , Input::get('Subject') )
                    ->where(  'classname', '=' , Input::get('Class') );

                if( !is_null($QuestionTable) and !is_null($QuestionTableGroupBy) and  $QuestionTable->get()->toArray() )
                {

                    $AnswersArray = [];
                    foreach ($QuestionTable->get()->toArray() as $key => $value)
                    {
                        $QuestionNumber =  $value["questionnumber"] - 1;
                        $AnswersArray[$QuestionNumber] =  $value["question_answer_belong"]["correctanswer"];
                    }
                    $IsAdmin = Input::get('IsAdmin') ;

                    $response = array('ReportMessage' => ["$ThisSubject questions for ". Input::get('Class')." class is available "],
                        'QuestionTableGroupBy' => $QuestionTableGroupBy->get(),
                        'AnswersArray' => $AnswersArray,
                        'status' => 1,
                        'QuestionTable' => $QuestionTable->get()->toArray(),
                        "IsAdmin"  => $IsAdmin  	 );
                    return Response::json($response);
                }
                else
                {
                    $response = array('ReportMessage' => ["$ThisSubject questions for ". Input::get('Class').
                        " class is unavailable at the moment. They will be available later"],
                        'ValidationErrorReport' => [' '], 'status' => 0 );
                    return Response::json($response);
                }


            }//end try block
            catch(\Exception $e)
            {

                $response = array('ReportMessage' => ["There is an error:  $ThisSubject questions cannot be found."],
                    'status' => 2,'ErrorReport' => $e,
                );
                return Response::json($response);
            }//end catch
        }//end else

    }

    public function storeQuestionResultToDatabase()
    {
//        return Response::json(RandomColor::one());
        $AllStudents = Students::with('UserBelong')->get();
        $FormMessages = array(/*
                           	'StudentNumber.required_without' => 'Your <b> Student Admission Number</b>  is required or you can type your name in the other text box on the left.',
                           	'StudentNumber.digits' => 'Your <strong> Student Admission Number</strong> must be exactly 4 digits.',
                           	'StudentNumber.numeric' => 'Your <strong> Student Admission Number</strong> must be a number only.',
                           	'Year.required' => 'The year is required.',
                           	'Year.date_format' => 'Please, choose a year from the year list.',
                           	'TermName.required' => 'The term is required.',
                           	'Class.required' => 'The class is required.',
                           	'Class.max' => 'The class must be :max characters long.', */
        );
        $validator = Validator::make(Input::all(),
            array('MyScore' => 'required|integer',
                'Total' => 'required|integer',
                //'TermName' => 'required',
            ), $FormMessages);//end static method make of Validator
        if($validator->fails())
        {
            $ValidatorErr =  $validator->getMessageBag()->toArray();
            $response = array('ReportMessage' => ['There are some errors with the details provided. Please, Check below'],
                'status' => 0, 'ValidationErrorReport' => $ValidatorErr
            );
            return Response::json( $response);
        }//end if statement that complain that validation failled
        else
        {
//            return Response::json([Auth::user()]);
            $QuestionResult = QuestionResult::where('classname', '=', 'SS1')
                ->where('termname' , '=', "first term")
                ->where('year' , '=', "2015")
                ->where('subjectid' , '=', 1)
                ->where('candidate' , '=', Auth::user()->userid)->get()->first();
            $ChartJSMonth = array();
            if(is_null($QuestionResult))
            {
                try{
                    $QuestionResult = QuestionResult::create( array('classname' => "SS1",
                        'termname' => "first term",
                        'year' =>"2015",
                        'score' => Input::get('MyScore'),
                        'total' => Input::get('Total'),
                        'subjectid' => 1,
                        'candidate' => Auth::user()->userid,
                    )     );
                    $ChartJSMonth[0]['value'] = (int) Input::get('MyScore');
                    $ChartJSMonth[1]['value'] = (int) ( Input::get('Total') - Input::get('MyScore') );
                    $ChartJSMonth[0]['label'] = "You correct answer";
                    $ChartJSMonth[1]['label'] = "Your incorrect answer";
                    /*$input = array("#F7464A","#FF5A5E", "#C48793", "#FDD7E4", "#FFDB58", "#FFEBCD", "#98FF98", "#4E9258","#46C7C7", "#438D80",
                    "#F0FFFF", "#F9966B", "#8A4117", "#C48189", "#C12869","#E4287C" );
                    //$rand_keys1 = array_rand($input, 1);
                    //$rand_keys2 = array_rand($input, 1);
                    //$name1 = $input[$rand_keys];
                    //$name2 = $input[$rand_keys2];
                    //unset($input[$rand_keys]); */
                    $ChartJSMonth[0]['color'] = RandomColor::one();
                    $ChartJSMonth[1]['color'] = RandomColor::one();// $name2;
                    //$ChartJSMonth[0]['highlight'] = $name1;
                    //$ChartJSMonth[1]['highlight'] = $name2;
                    // return Response::json($ChartJSMonth);
                    $response = array('ReportMessage' => ["Your score have been successfully saved to the database"],
                        'status' => 1 , 'ChartJSMonth' => $ChartJSMonth);
                    return Response::json($response);
                }
                catch(\Exception $e)
                {
                    //var_dump($e);die();
                    //$thiscore->delete();
                    $response = array('msg' => 'Student score not successfully created', 'status' => 0);
                    return Response::json($e);
                }//end catch
            }
            else
            {
                try{
                    $QuestionResult->score = Input::get('MyScore');
                    $QuestionResult->total = Input::get('Total');
                    $QuestionResult->save();

//								$ChartJSMonth[0]['value'] = (int) Input::get('MyScore');
//								$ChartJSMonth[1]['value'] = (int) ( Input::get('Total') - Input::get('MyScore') );
//								$ChartJSMonth[0]['label'] = "You correct answer";
//								$ChartJSMonth[1]['label'] = "Your incorrect answer";
//								$ChartJSMonth[0]['labelColor'] = "white";
//								$ChartJSMonth[1]['labelColor'] = 'white';
//								$ChartJSMonth[1]['labelFontSize'] = '16';
//								$ChartJSMonth[0]['labelFontSize'] = "16";
//								$input = array("#F7464A","#FF5A5E", "#C48793", "#FDD7E4", "#FFDB58", "#FFEBCD", "#98FF98", "#4E9258","#46C7C7", "#438D80",
//								"#F0FFFF", "#F9966B", "#8A4117", "#C48189", "#C12869","#E4287C" );
//								/*$rand_keys1 = array_rand($input, 1);
//								$rand_keys2 = array_rand($input, 1);
//								$name1 = $input[$rand_keys1];
//								$name2 = $input[$rand_keys2];*/
//								//unset($input[$rand_keys]);
//								$ChartJSMonth[0]['color'] = RandomColor::one(); //$name1;
//								$ChartJSMonth[1]['color'] = RandomColor::one();// $name2;
//								//$ChartJSMonth[0]['highlight'] = RandomColor::one();//$name1;
//								//$ChartJSMonth[1]['highlight'] = RandomColor::one();//$name2;
//								//$ChartJSMonth = json_encode($ChartJSMonth);
//								 //return Response::json($ChartJSMonth);

                    $ChartJSMonth['QuestionResponse'][] = (int) Input::get('MyScore');
                    $ChartJSMonth['QuestionResponse'][] = (int) ( Input::get('Total') - Input::get('MyScore') );

                    $ChartJSMonth['QuestionResponseLabel'][] = "You correct answer";
                    $ChartJSMonth['QuestionResponseLabel'][] = "Your incorrect answer";

                    $response = array('ReportMessage' => ["Your score have been successfully editted on the database"],
                        'status' => 1, 'ChartJSMonth' => $ChartJSMonth );

                    return Response::json($response);
                }

                catch(\Exception $e)
                {
                    $response = array('ReportMessage' => ["Error! Your score was not successfully editted on the database "],
                        'status' => 2,'ErrorReport' => $e,
                    );
                    return Response::json($response);
                }//end catch
            }//end else
        }//end outer else

    }

    //Old Methods

	public function getStudentQuestionInput() 
	{	
		$AllStudents = Students::with('UserBelong')->get();
		$AllSubjects = Subjects::all();
		return View::make('admin.studentquestioninput')->with(array( 
															  			'myBreadCrumb' => 
															  			"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page", 
															  			'Title' => 'Student Page',
															  			'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'' ,
															  			'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'' 
															  			)); 
														      			//end  static method make for making a page; 		
	}//end method getStudentQuestionInput

	public function getStudentQuestionEditPage($QuestionId) 
	{	//var_dump($QuestionId); die();
		//$AllStudents = Students::with('UserBelong')->get()
		$QuestionIdArray['QuestionIdNumber'] =  $QuestionId;
		$AllSubjects = Subjects::all();
		$FormMessages = array(/*
                           	'StudentNumber.required_without' => 'Your <b> Student Admission Number</b>  is required or you can type your name in the other text box on the left.',
                           	'StudentNumber.digits' => 'Your <strong> Student Admission Number</strong> must be exactly 4 digits.',
                           	'StudentNumber.numeric' => 'Your <strong> Student Admission Number</strong> must be a number only.',
                           	'Year.required' => 'The year is required.',
                           	'Year.date_format' => 'Please, choose a year from the year list.',
                           	'TermName.required' => 'The term is required.',
                           	'Class.required' => 'The class is required.',
                           	'Class.max' => 'The class must be :max characters long.', */
						 );
		$validator = Validator::make($QuestionIdArray,array('QuestionIdNumber' => 'required|integer'), $FormMessages);//end static method make of Validator
		if($validator->fails())
		{
			//var_dump( $validator->getMessageBag()->toArray() ); die();
			return Redirect::route('student-question-input-page')
					->with(array( 'ReportEditPage' => 'There is a problem with the validator',
								  // 'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'' ,
								  'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'' 
					))->withErrors($validator); 
		}//end if statement that complain that validation failled
		else
		{
			$ThisQuestion = QuestionTable::where('questiontableid', '=', $QuestionId )->with(["questionOptionsBelong","questionAnswerBelong"])->get()->first();	
			if(!is_null($ThisQuestion))
			{
				//var_dump($ThisQuestion->toArray() ); die();
				return View::make('admin.studentquestionedit')->with(array( 
															  				'myBreadCrumb' => 
															  				"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page", 
															  				'Title' => 'Question Edit Page',
																  			'ThisQuestion' =>$ThisQuestion->toArray() ,
																  			'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'' 
															  			)); 
														      			//end  static method make for making a page; 	
			}
			else
			{		
				return Redirect::route('student-question-input-page')
					->with(array( 'myBreadCrumb' =>
						 		  "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page", 
								  'Title' => 'Student Page',
								  'ReportEditPage' => 'we are unable to retrieve the edit page',
								 // 'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'' ,
								  'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'' 
					))->withErrors($validator); 
			}//end else			
		}	//end else			
	}//end method getStudentQuestionEditPage
	public function editQuestionInDatabase()    
	{ 
		//return Response::json(Input::all());
		$FormMessages = array(/*
                           	'StudentNumber.required_without' => 'Your <b> Student Admission Number</b>  is required or you can type your name in the other text box on the left.',
                           	'StudentNumber.digits' => 'Your <strong> Student Admission Number</strong> must be exactly 4 digits.',
                           	'StudentNumber.numeric' => 'Your <strong> Student Admission Number</strong> must be a number only.',
                           	'Year.required' => 'The year is required.',
                           	'Year.date_format' => 'Please, choose a year from the year list.',
                           	'TermName.required' => 'The term is required.',
                           	'Class.required' => 'The class is required.',
                           	'Class.max' => 'The class must be :max characters long.', */
						 );
		$validator = Validator::make(Input::all(),
							array(	//'QuestionImage' => 'image',
									'QuestionStatement' => 'required',
							       'Year' => 'required|date_format:Y',
								   'TermName' => 'required',
								   'Class' => 'required|max:3',								   
								   'Subject' => 'required|integer',
								   'optionA' => 'required',
								   'optionB' => 'required',
								   'optionC' => 'required',
								   'optionD' => 'required',
								   'QuestionNumber' => 'required|integer',
								   'SectionNumber' => 'required|integer',				
								   'Subject' => 'required|integer',				
								   'SectionInstruction' => 'required',				
								   'ClassTeacher' => 'required',				
								   'CorrectAnswer' => 'required',
								   'EditCorrectAnswerId' => 'required|integer',
								   'EditQuestionId' => 'required|integer',
								   'EditQuestionOptionsId' => 'required|integer',
								   ), $FormMessages);//end static method make of Validator
		if($validator->fails())
		{			  
			    $ValidatorErr =  $validator->getMessageBag()->toArray();
			   	   $response = array('ReportMessage' => ['There are some errors with the details provided. Please, Check below'], 
			   	   					 'status' => 0, 'ValidationErrorReport' => $ValidatorErr
			   	   					);
			   	return Response::json( $response);	
		}//end if statement that complain that validation failled
		else
		{
			
			try{
						$QuestionTable = QuestionTable::find( Input::get('EditQuestionId'));
						if(Input::hasFile('QuestionImage') )
							{	
								$DestinationPath =  public_path().'/Images/QuestionImages';
								$OldFileName = $QuestionTable->questionimage; //die();
								$UpFile  = $DestinationPath.'/'. $OldFileName;
					   			File::delete($UpFile);
					   			//return Response::json( $QuestionTable->questionimage);
								 //$AssignedTeacher = Input::get("AssignedTeacher");		   
					   			// $DestinationPath =  public_path().'/Images/QuestionImages';		  
					  			 //upload the file///////////////////////////////////////
					  			 $File = Input::file('QuestionImage');
					  			  //var_dump(($File)); die();
					  			 $FileName =  (Str::random(6)).".".(Input::file('QuestionImage')->getClientOriginalExtension());
								          //  var_dump(($FileName)); die();
					            //get file path and save it in the category model
					   			 $echo = Input::file('QuestionImage')->move($DestinationPath, $FileName); 
					   			 // create instance
					   			 //$img = Image::make($DestinationPath."/".$FileName);
					   			// var_dump($img);die();
								//$img->resize(50, 50);
								 // resize image to fixed size
								 //$img
					  			 if($echo)
					  				 { 
					  				 	$QuestionImg = $FileName;
					  				 	$check = 1;
					  				 }
					   			  else
					   				{
					   					//$UpFile  = $DestinationPath.'/'. $FileName;
					   					//File::delete($UpFile);
					   					$QuestionImg =  $QuestionTable->questionimage;
					   					$check = 0;
					   		        } //end else tha complains if signature image is ot updated
		   		         	}//end if that ensures file is uploded		   		
				   		else
				   	   	   {

					   			//$DestinationPath =  public_path().'/Images/QuestionImages';
								//$OldFileName = $QuestionTable->questionimage; //die();
								//$UpFile  = $DestinationPath.'/'. $OldFileName;
			   				//	File::delete($UpFile);
				   		    	$QuestionImg =  $QuestionTable->questionimage;
				   		    	$check = 0;
				   		    }//end else that complains if 
					//return Response::json("wait");
						$QuestionAnswers = QuestionAnswers::where( 'correctanswer' , '=' , Input::get('CorrectAnswer') )->get()->first();

						$QuestionOptions = QuestionOptions::find( Input::get('EditQuestionOptionsId'));
						$QuestionOptions->optionA =  Input::get('optionA');
						$QuestionOptions->optionB =  Input::get('optionB');
						$QuestionOptions->optionC =  Input::get('optionC');
						$QuestionOptions->optionD =  Input::get('optionD');
						$QuestionOptions->modifiedby =  Auth::user()->userid;
						$QuestionOptions->save();								 

						
						$QuestionTable->classname =  Input::get('Class');
						$QuestionTable->termname =  Input::get('TermName');
						$QuestionTable->subjectid =  Input::get('Subject');
						$QuestionTable->classteacher =  Input::get('ClassTeacher');
						$QuestionTable->questionstatement = Input::get('QuestionStatement');
						$QuestionTable->questionnumber =  Input::get('QuestionNumber');
						$QuestionTable->questionsection =  Input::get('SectionNumber');

						$QuestionTable->questionimage = $check === 0 ? $QuestionTable->questionimage : $QuestionImg;

						$QuestionTable->sectioninstruction =  Input::get('SectionInstruction');
						$QuestionTable->questionanswerid = $QuestionAnswers->questionanswerid;
						$QuestionTable->modifiedby =  Auth::user()->userid;
						$QuestionTable->save();		
			             
			            if( !is_null($QuestionTable) and !is_null($QuestionOptions) and !is_null($QuestionAnswers) )
			            {
					     	$response = array('ReportMessage' => [" This Question" . $QuestionTable->questionnumber . " in Section" . 
					     										  $QuestionTable->questionsection. " has been successfully editted"], 
					     										  'status' => 1,
			   	   					       	  /*'QuestionTable' => $QuestionTable::with(["questionOptionsBelong","questionAnswerBelong"])->get()->toArray(), 
			   	   					       	  'QuestionOptions' => $QuestionOptions->get()->toArray(), 
			   	   					          'QuestionAnswers' => $QuestionAnswers->get()->toArray(),*/
			   	   					          			   	   	
			   	   					         );
					       	return Response::json($response);
			            }
			            else
			            {
			            	$response = array('ReportMessage' => ["This Question was not succesfully editted."], 'status' => 1, );
					       	 return Response::json($response);

			            }
				}//end try block
			catch(Exception $e)
				{
					
					$response = array('ReportMessage' => ["There is an error: please check that you have changed the question number. 
														   Plesae use the question number of the current question that you are typing now."], 
			   	   					  'status' => 2,'ErrorReport' => $e, 
			   	   				   );	
					return Response::json($response);	
				}//end catch
		}//end else
	}//end method editQuestionInDatabase




	public function getQuestionResult() 
	{	return Response::json(Input::all());
		$AllStudents = Students::with('UserBelong')->get();
		$FormMessages = array(/*
                           	'StudentNumber.required_without' => 'Your <b> Student Admission Number</b>  is required or you can type your name in the other text box on the left.',
                           	'StudentNumber.digits' => 'Your <strong> Student Admission Number</strong> must be exactly 4 digits.',
                           	'StudentNumber.numeric' => 'Your <strong> Student Admission Number</strong> must be a number only.',
                           	'Year.required' => 'The year is required.',
                           	'Year.date_format' => 'Please, choose a year from the year list.',
                           	'TermName.required' => 'The term is required.',
                           	'Class.required' => 'The class is required.',
                           	'Class.max' => 'The class must be :max characters long.', */
						 );
		$validator = Validator::make(Input::all(),
							array('QuestionStatement' => 'required',
							       'Year' => 'required|date_format:Y',
								   'TermName' => 'required',
								   'Class' => 'required|max:3',								   
								   'Subject' => 'required|integer',
								   'optionA' => 'required',
								   'optionB' => 'required',
								   'optionC' => 'required',
								   'optionD' => 'required',
								   'QuestionNumber' => 'required|integer',
								   'SectionNumber' => 'required|integer',				
								   'Subject' => 'required|integer',				
								   'SectionInstruction' => 'required',				
								   'ClassTeacher' => 'required',				
								   'CorrectAnswer' => 'required',
								   'EditCorrectAnswerId' => 'required|integer',
								   'EditQuestionId' => 'required|integer',
								   'EditQuestionOptionsId' => 'required|integer',
								   ), $FormMessages);//end static method make of Validator
		if($validator->fails())
		{			  
			    $ValidatorErr =  $validator->getMessageBag()->toArray();
			   	   $response = array('ReportMessage' => ['There are some errors with the details provided. Please, Check below'], 
			   	   					 'status' => 0, 'ValidationErrorReport' => $ValidatorErr
			   	   					);
			   	return Response::json( $response);	
		}//end if statement that complain that validation failled
		else
		{
			try{
					//return Response::json("wait");
						$QuestionAnswers = QuestionAnswers::where( 'correctanswer' , '=' , Input::get('CorrectAnswer') )->get()->first();

						$QuestionOptions = QuestionOptions::find( Input::get('EditQuestionOptionsId'));
						$QuestionOptions->optionA =  Input::get('optionA');
						$QuestionOptions->optionB =  Input::get('optionB');
						$QuestionOptions->optionC =  Input::get('optionC');
						$QuestionOptions->optionD =  Input::get('optionD');
						$QuestionOptions->modifiedby =  Auth::user()->userid;
						$QuestionOptions->save();								 

						$QuestionTable = QuestionTable::find( Input::get('EditQuestionId'));
						$QuestionTable->classname =  Input::get('Class');
						$QuestionTable->termname =  Input::get('TermName');
						$QuestionTable->subjectid =  Input::get('Subject');
						$QuestionTable->classteacher =  Input::get('ClassTeacher');
						$QuestionTable->questionstatement = Input::get('QuestionStatement');
						$QuestionTable->questionnumber =  Input::get('QuestionNumber');
						$QuestionTable->questionsection =  Input::get('SectionNumber');
						$QuestionTable->sectioninstruction =  Input::get('SectionInstruction');
						$QuestionTable->questionanswerid = $QuestionAnswers->questionanswerid;
						$QuestionTable->modifiedby =  Auth::user()->userid;
						$QuestionTable->save();		
			             
			            if( !is_null($QuestionTable) and !is_null($QuestionOptions) and !is_null($QuestionAnswers) )
			            {
					     	$response = array('ReportMessage' => [" This Question" . $QuestionTable->questionnumber . " in Section" . 
					     										  $QuestionTable->questionsection. " has been successfully editted"], 
					     										  'status' => 1,
			   	   					       	  /*'QuestionTable' => $QuestionTable::with(["questionOptionsBelong","questionAnswerBelong"])->get()->toArray(), 
			   	   					       	  'QuestionOptions' => $QuestionOptions->get()->toArray(), 
			   	   					          'QuestionAnswers' => $QuestionAnswers->get()->toArray(),*/
			   	   					          			   	   	
			   	   					         );
					       	return Response::json($response);
			            }
			            else
			            {
			            	$response = array('ReportMessage' => ["This Question was not succesfully editted."], 'status' => 1, );
					       	 return Response::json($response);

			            }
				}//end try block
			catch(Exception $e)
				{
					
					$response = array('ReportMessage' => ["There is an error: please check that you have changed the question number. 
														   Plesae use the question number of the current question that you are typing now."], 
			   	   					  'status' => 2,'ErrorReport' => $e, 
			   	   				   );	
					return Response::json($response);	
				}//end catch
		}//end else
		

	}//end method getQuestionResult

	
}//end PageController