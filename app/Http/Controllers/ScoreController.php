<?php
namespace App\Http\Controllers;


use App\Models\Attendance;
use App\Models\OfficialComments;
use App\Models\OfficialSignatures;
use App\Models\Students;
use App\Models\StudentTerm;
use App\Models\Subjects;
use App\Models\SubjectScore;
use App\Models\TermDuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ScoreController extends Controller {

    public function __construct(  )
    {
        $this->middleware('auth');
        $this->middleware('web');
    }

    public function getThisStudentClass(\Illuminate\Http\Request $request)
    {

        $method = $request->isMethod('post');
        if($method)
        {
            if(Request::ajax())
            {
                $FormMessages = array('StudentId.required' => 'The name of the student is required.',
                    'StudentNumber.required' => 'The student admission number  is required.'
                );
                $validator = Validator::make(Input::all(),array('StudentId' => 'integer', 'StudentNumber' => 'required',), $FormMessages);//end static method make
                if($validator->fails())
                {
                    $ValidatorErr =  $validator->getMessageBag()->toArray();
                    $response = array('msg' => 'Validator fails', 'status' => 0);
                    $response = array_merge($response,(array)$ValidatorErr);
                    return Response::json(array());
                    //return Response::json($response);
                }//end if statement that checks that validator did not fail
                $StudentAdminNumberArray = explode("-", Input::get('StudentNumber' ));
                $StudentAdminNumber  = (count($StudentAdminNumberArray) > 0 ) ?  trim($StudentAdminNumberArray[0]) : "";

                $ThisStudent = Students::with('StudentTermRelate','UserBelong')->where('school_admission_number', '=', $StudentAdminNumber   )->get();
                if(count($ThisStudent) > 0 )
                {

                    $ThisStudent = $ThisStudent->first();
                    $ThisStudentId =  !is_null($ThisStudent)?$ThisStudent->toArray()['studentid']:'';
                    $ThisStudentTerm = StudentTerm::with('ThistermBelong')->where('studentid', '=', $ThisStudentId )->get();
                    if(count($ThisStudentTerm) > 0)
                    {
                        return Response::json($ThisStudentTerm->toArray());
                    }
                    else
                    {
                        return Response::json(array());
                    }



                    return Response::json($ThisStudentTerm->toArray());
                }
                else
                {
                    return Response::json(array());
                }

            }
        }
        else
        {


        }
    }

    public function postThisStudentDetails(\Illuminate\Http\Request $request)
    {
        $method = $request->isMethod('post');
        if($method)
        {
            $AllSubjects = Subjects::all();
            $OfficialSignatures =  OfficialSignatures::with('UserBelong')->get();
            $FormMessages = array(
                'StudentId.required' => 'The student has not being choose. Please choose a student.',
                'StudentId.integer' => 'The student has not being choose. Please choose a student',
                'SubClass.required' => 'The subclass has not being choose, Please choose it',
                'Year.required' => 'The year is required.',
                'TermName.required' => 'The term is required.',
                'Class.required' => 'The class is required.',
                'Class.max' => 'The class should be :attribute letters only.',
            );
            $validator = Validator::make(Input::all(),
                array('StudentId' => 'required|integer',
                    'Year' => 'required|date_format:Y',
                    'TermName' => 'required',
                    'Class' => 'required|max:3',
                    'SubClass' => 'required|max:1|alpha'), $FormMessages);//end static method make of Validator
            if($validator->fails())
            {
                return Redirect::route('teachers-home-page')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
                        'Title' => 'Student Score Sheet',
                        'ScoreInput' => 'Errors below! Please correct them'
                    ))->withErrors($validator);
            }
            else
            {
                $User = Auth::user();
                $ConstructTeacher = strtoupper(Input::get('Class')).strtoupper(Input::get('SubClass'));
                //var_dump($ConstructTeacher);die();
                if(true) // $User->ability( array('Super User', 'Administrator', 'SchoolAdministrator','Principal',
//                    'Vice Principal', 'Secretary'/*'Teacher'*/, $ConstructTeacher), array()))
                {
                    try{
                        $ThisStudent = Students::with('StudentTermRelate','UserBelong')
                            ->where('studentid', '=', Input::get('StudentId')  )->get()->first();
                        if(isset($ThisStudent) && !is_null($ThisStudent))
                        {
                            $studentid = !is_null($ThisStudent)? $ThisStudent->toArray()['studentid']:'';
                            $RequestedTerm = Input::get('TermName');
                            $RequestedDetails['Term'] =  Input::get('TermName');
                            $RequestedDetails['Year'] =  Input::get('Year');
                            $RequestedDetails['Class'] =  Input::get('Class');
                            $RequestedDetails['SubClass'] =  Input::get('SubClass');

                            if($RequestedTerm === "first term")
                            {
                                $SubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get();

                                $OfficialComments = OfficialComments::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get()->first();
                                $TermDuration = TermDuration::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get()->first();

                                $Attendance = Attendance::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get()->first();
                                $MassiveDetails = array(
                                    "ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() : '',
                                    "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() : '' ,
                                    "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray() : '' ,
                                    "Attendance" => !is_null($Attendance) ? $Attendance->toArray() : '' ,
                                    "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() : '' ,
                                    "RequestedTerm" => Input::get('TermName')
                                );
                                Session::put('MassiveDetails', $MassiveDetails);
                                return View::make('teachers.ajaxinputstudentscore')->with(array(
                                    'myBreadCrumb'
                                    =>"<a href='". URL::route('home')
                                        ."' id='BreadNav'>Home</a> => Student Report Page",
                                    'Title' => 'Student Score Sheet Page',
                                    'OfficialSignatures'=>!is_null($OfficialSignatures) ? $OfficialSignatures->toArray() :'',
                                    'OfficialComments'=>!is_null($OfficialComments) ? $OfficialComments->toArray() :'',
                                    "Attendance" => !is_null($Attendance) ? $Attendance->toArray() : '' ,
                                    "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() : '' ,
                                    'AllSubjects' => !empty($AllSubjects) ? $AllSubjects->toArray() :'' ,
                                    'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :'' ,
                                    'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :'',
                                    'RequestedDetails' => is_array($RequestedDetails) ? $RequestedDetails :'',
                                    'RequestedTerm'   => Input::get('TermName') )   );
                            }
                            elseif($RequestedTerm === "second term")
                            {
                                $SubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get();

                                $FirstTermSubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', 'first term')
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get();

                                $OfficialComments = OfficialComments::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get()->first();
                                $Attendance = Attendance::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get()->first();
                                $TermDuration = TermDuration::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get()->first();

                                $MassiveDetails = array(
                                    "ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                                    "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                    "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray() : '' ,
                                    "Attendance" => !is_null($Attendance) ? $Attendance->toArray() : '' ,
                                    "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() : '' ,
                                    "RequestedTerm" => Input::get('TermName') ,
                                    "FirstTermSubjectScore" => !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore
                                        ->toArray() :array(),
                                );
                                Session::put('MassiveDetails', $MassiveDetails);
                                return View::make('teachers.ajaxinputstudentscore')
                                    ->with(array( 'myBreadCrumb' =>
                                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                                        'Title' => 'Student Score Sheet Page',
                                        'OfficialSignatures'=>!is_null($OfficialSignatures) ? $OfficialSignatures->toArray() :'',
                                        "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray() : '' ,
                                        "Attendance" => !is_null($Attendance) ? $Attendance->toArray() : '' ,
                                        "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() : '' ,
                                        'AllSubjects' => !empty($AllSubjects) ? $AllSubjects->toArray() :'' ,
                                        'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :'' ,
                                        'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :'',
                                        'RequestedDetails' => is_array($RequestedDetails) ? $RequestedDetails :'',
                                        'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore)
                                            ? $FirstTermSubjectScore->toArray() :'' ,
                                        'RequestedTerm'   => Input::get('TermName'),
                                        //'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():''
                                    ));
                            }//end if that request term is second term
                            elseif($RequestedTerm === "third term")
                            {
                                $SubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get();

                                $FirstTermSubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', 'first term')
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get();
                                $SecondTermSubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', 'second term')
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get();
                                $OfficialComments = OfficialComments::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get()->first();
                                $Attendance = Attendance::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get()->first();
                                $TermDuration = TermDuration::where('studentid', '=', $studentid)
                                    ->where('termname' , '=', $RequestedTerm)
                                    ->where('year' , '=', Input::get('Year'))
                                    ->where('class_subdivision' , '=', Input::get('SubClass'))
                                    ->where('classname' , '=', Input::get('Class'))->get()->first();
                                $MassiveDetails = array(
                                    "ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                                    "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray() : '' ,
                                    "Attendance" => !is_null($Attendance) ? $Attendance->toArray() : '' ,
                                    "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() : '' ,
                                    "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                    "RequestedTerm" => Input::get('TermName') ,
                                    "FirstTermSubjectScore" => !empty($FirstTermSubjectScore)
                                        ? $FirstTermSubjectScore->toArray()
                                        :array(),
                                    "SecondTermSubjectScore" => !empty($SecondTermSubjectScore)
                                        ? $SecondTermSubjectScore->toArray()
                                        :array(),
                                );
                                //var_dump($MassiveDetails) ; die();
                                Session::put('MassiveDetails', $MassiveDetails);
                                return View::make('teachers.ajaxinputstudentscore')
                                    ->with(array( 'myBreadCrumb' =>
                                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                                        'Title' => 'Student Score Sheet Page',
                                        'OfficialSignatures'=>!is_null($OfficialSignatures) ? $OfficialSignatures->toArray() :'',
                                        "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray() : '' ,
                                        "Attendance" => !is_null($Attendance) ? $Attendance->toArray() : '' ,
                                        "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() : '' ,
                                        'AllSubjects' => !empty($AllSubjects) ? $AllSubjects->toArray() :'',
                                        'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :'' ,
                                        'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :'',
                                        'RequestedDetails' => is_array($RequestedDetails) ? $RequestedDetails :'',
                                        'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore) ?
                                            $FirstTermSubjectScore->toArray() : '' ,
                                        'SecondTermSubjectScore' =>  !empty($SecondTermSubjectScore)
                                            ? $SecondTermSubjectScore->toArray() :'' ,
                                        'RequestedTerm'   => Input::get('TermName'),));
                            }//end if that ensures requested term is third term
                            else
                            {
                                return Redirect::route('teachers-home-page')
                                    ->with(array( 'myBreadCrumb' =>
                                        "<a href='". URL::route('home')
                                        ."' id='BreadNav'>Home</a> => Student Page",
                                        'Title' => 'Get Student Score Sheet',
                                        'ScoreInput' => 'Errors! Please choose an appropriate term'
                                    ) );
                            }//end else that compalains if student_term_relate is empty
                        }//end if statement that ensures  This student is set
                        else
                        {
                            return Redirect::route('teachers-home-page')
                                ->with(array( 'myBreadCrumb' =>
                                    "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
                                    'Title' => 'Get Student Score Sheet',
                                    'ScoreInput' => 'Errors! Please choose an appropriate term'));
                        }//end else that complains if ThisStudent is not a model
                    }//end try block
                    catch(\Exception $e)
                    {
                        //var_dump($e);//die();
                        return  Redirect::route('teachers-home-page')
                            ->with(array( 'myBreadCrumb' =>
                                "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
                                'Title' => 'Get Student Score Sheet',
                                'ScoreInput' => ''
                            ));  //end  static method make for making a page;
                    }//end catch
                }
                else
                {
                    return  Redirect::route('teachers-home-page')
                        ->with(array( 'myBreadCrumb' =>
                            "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
                            'Title' => 'Get Student Score Sheet',
                            'ScoreInput' => 'You are not authorized to edit the score for '. $ConstructTeacher ." students.
							Please contact the <b> Class Teacher for ". $ConstructTeacher ." students </b> Thank you."
                        ));  //end  static method make for making a page;

                }//end else that redirectd when you are unauthorized
            }//end else that shows that validator didnt cause issue
        }
        else
        {


        }

    }

    public function saveEachStudentScores(\Illuminate\Http\Request $request)
    {
        $method = $request->isMethod('post');
        if($method)
        {
            $GoodArray = [];
            $BadArray = [];
            $allSubjectScoreInput = Input::get("SubjectScore");
            $FormMessages = array(
                'CAScore.required' => 'The CA Score is required.',
                'ExamScore.required' => 'The Exam Score is required.',
                'CAScore.max' => 'CA Score  cannot be more than :max.',
                'ExamScore.max' => 'Exam Score cannot be more than :max.',
                'ExamScore.min' => 'Exam Score cannot be lower than :min.',
                'CAScore.min' => 'CA Score cannot be lower than :min.'
            );
            $validator = Validator::make($allSubjectScoreInput,
                array('Year' => 'required|date_format:Y',
                    'TermName' => 'required',
                    'Class' => 'required|max:3',
                    'SignatureImageID' => 'integer',
                    'SubClass' => 'required|max:1|alpha',
                    'StudentId' => 'required|integer',
                    'SubjectId' => 'required|integer',
                    'CAScore' => 'required|integer|max:40|min:0',
                    'ExamScore' => 'required|integer|max:60|min:0'
                ), $FormMessages);
            if($validator->fails())
            {
                $ValidatorErr = (array) $validator->getMessageBag()->toArray();
                $response = array('msg' => 'Student detail not touched. Validation failed', 'status' => 2, "ValidatorErr" => $ValidatorErr );
                return Response::json($response);
                $BadArray[] = 	$response;
            }
            else
            {
                $SubjectScore = SubjectScore::where('studentid', '=', $allSubjectScoreInput['StudentId'])
                    ->where('termname' , '=', $allSubjectScoreInput['TermName'])
                    ->where('year' , '=', $allSubjectScoreInput['Year'])
                    ->where('subjectid' , '=', $allSubjectScoreInput['SubjectId'])
                    ->where('classname' , '=', $allSubjectScoreInput['Class'])
                    ->get()->first();
                if(is_null($SubjectScore))
                {
                    try{
                        DB::transaction(function() use ($allSubjectScoreInput)
                        {
                            $thiscore =  new SubjectScore();
                            $thiscore->cont_assess_40 =  $allSubjectScoreInput['CAScore'];
                            $thiscore->exam_score_60 = $allSubjectScoreInput['ExamScore'];
                            $thiscore->subjectid = $allSubjectScoreInput['SubjectId'];
                            $thiscore->termname =  $allSubjectScoreInput['TermName'];
                            $thiscore->classname =  $allSubjectScoreInput['Class'];
                            $thiscore->class_subdivision =  $allSubjectScoreInput['SubClass'];
                            $thiscore->year =  $allSubjectScoreInput['Year'];
                            $thiscore->studentid =  $allSubjectScoreInput['StudentId'];
                            $thiscore->teachersignatureid =  array_key_exists('SignatureImageID', $allSubjectScoreInput) ?
                                $allSubjectScoreInput['SignatureImageID']:34;
                            $thiscore->createdby = Auth::user()->userid;
                            $thiscore->modifiedby =  Auth::user()->userid;
                            $thiscore->save();
                        });	//end DB::transaction
                        $response = array('msg' => 'Student score was successfully created.', 'status' => 1);
                        return Response::json($response);
                        $GoodArray[]  =  $response;

                    }
                    catch(\Exception $e)
                    {

                        $response = array('msg' => 'Student score not was successfully created. Please contact the administrator.', 'status' => 0);
                        return Response::json($e);
                        $BadArray[] = 	$response;

                    }
                }
                else{
                    try{
                        DB::transaction(function() use ($allSubjectScoreInput)
                        {
                            //var_dump($EditThisSubjectScore); die();
                            $SubjectScore = SubjectScore::where('studentid', '=', $allSubjectScoreInput['StudentId'])
                                ->where('termname' , '=', $allSubjectScoreInput['TermName'])
                                ->where('year' , '=', $allSubjectScoreInput['Year'])
                                ->where('subjectid' , '=', $allSubjectScoreInput['SubjectId'])
                                ->where('classname' , '=', $allSubjectScoreInput['Class'])
                                ->get()->first();
                            $SubjectScore->cont_assess_40 = $allSubjectScoreInput['CAScore'];
                            $SubjectScore->exam_score_60 = $allSubjectScoreInput['ExamScore'];
                            $SubjectScore->modifiedby =  Auth::user()->userid;
                            $SubjectScore->teachersignatureid = array_key_exists('SignatureImageID', $allSubjectScoreInput) ?
                                $allSubjectScoreInput['SignatureImageID']:34;
                            $SubjectScore->save();
                        });	//end DB::transaction
                        $response = array('msg' => 'Student score was successfully edited.', 'status' => 1);
                        return Response::json($response);
                        $GoodArray[]  =  $response;
                    }

                    catch(\Exception $e)
                    {
                        //return  var_dump($e);//die();
                        $response = array('msg' => 'Student score not successfully editted. Please contact the administrator.', 'status' => 0 );
                        return Response::json($response);
                        $BadArray[] = 	$response;

                    }
                }
            }
            $Allarr = array_merge($GoodArray,(array)$BadArray);
            return Response::json($Allarr);
        }
        else
        {


        }

    }


//New List of Methods
	public function getThisStudentScoreForm()
	{

		$AllStudents = Students::with(['UserBelong'])->get();
		$AllSubjects = Subjects::all();
		if(Request::ajax())
				{
					$ThisStudentTerm = StudentTerm::with('ThistermBelong')
											->where('studentid', '=', Input::get('StudentAdmissionNumber') )
											->get();
					return Response::json($ThisStudentTerm->toArray());
										 //	var_dump($ThisStudentTerm->toArray()); die();
			 	}//end ajax request block
		return View::make('teachers.getstudentforscoreinput')->
		with(array(
					'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Add Student Term",
					'Title' => 'Get Student Score Sheet',
					'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
					//'VeryLargeArray' => is_array($r) ? $r : ''
					 ));  //end  static method make for making a page;
	}//end method getThisStudentScoreForm



	public function changeThisStudentClass()
	{
		//return Response::json(Input::all());
		$FormMessages = array(
                           	   'StudentId.required' => 'The student has not being choosen. Please choose a student.',
                           	   'StudentId.integer' => 'The student has not being choosen. Please choose a student',
                           	   'SubClass.required' => 'The subclass has not being choosen, Please choose it',
                           	   'Year.required' => 'The year is required.',
                               'TermName.required' => 'The term is required.',
                               'Class.required' => 'The class is required.',
                               'Class.max' => 'The class should be :attribute letters only.',
						 );
		$validator = Validator::make(Input::all(),
							array('StudentId' => 'required|integer',
							       'Year' => 'required|date_format:Y',
								   'OldClass' => 'required|max:3',
								   'NewClass' => 'required|max:3',
								    'SubClass' => 'required|max:1'), $FormMessages);//end static method make of Validator
		if($validator->fails())
		{
			//var_dump($validator->messages()); die();
			//return Response::json("Validator failed");
			if(Request::ajax())
			 {
				$ValidatorErr =  $validator->getMessageBag()->toArray();
			   	$response = array('msg' => 'There are some errors with the details provided. Please, Check below',
			   	   					 'status' => 0,'ChangeClassInfo' => $ValidatorErr
			   	   					);
				return Response::json($response);
			}
					 		//end  static method make for making a page;->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{
			if(Request::ajax())
			 {
				try
					{
						$OldThisTerm = ThisTerm::where('classname', '=', Input::get('OldClass') )
											 ->where('termname' , '=', 'first term' )
											 ->where('year' , '=', Input::get('Year') )->get()->first();
											// return Response::json($ThisTerm);
						$NewThisTerm = ThisTerm::where('classname', '=', Input::get('NewClass') )
											 ->where('termname' , '=', 'first term' )
											 ->where('year' , '=', Input::get('Year') )->get()->first();
											// return Response::json($ThisTerm);
						if(!is_null($OldThisTerm) and !is_null($NewThisTerm) )
							{
								$StudentTerm = StudentTerm::where('studentid', '=', Input::get('StudentId') )
														    ->where('thistermid' , '=', $OldThisTerm['id'] )->get()->first();
								if( is_null($StudentTerm) )
					  				{
					  					$BadString = "There is an error. Student not designated to any year or class.
					  								  Please contact the Administrator. Thank you.";
					  					$response = array('msg' => '', 'status' => 0, 'ChangeClassInfo'
														=> ["There is an error. Student not designated to any year or class.
					  								  Please contact the Administrator. Thank you." ]
			   	   					);
			 						return Response::json($response);
					  					//return Response::json($BadString);
					  				}
								$StudentTerm->thistermid = $NewThisTerm['id'];
								// $NewSTudentTerm->studentid =Input::get('StudentId');
								$StudentTerm->class_subdivision =  Input::get('SubClass');
								$StudentTerm->save();
								$response = array('msg' => '', 'status' => 1, 'ChangeClassInfo'
														=> ["Student class has been changed to ". Input::get('NewClass') .
															" ". strtoupper ( Input::get('SubClass') )  ]
			   	   					);
			 					return Response::json($response);
								//return Response::json($StudentTerm);
							}//ensures that Thisterm is found
							else
							{
								$response = array('msg' => '', 'status' => 0, 'ChangeClassInfo'
														=> ["This term does not exist. Please contact the admistrator" ]
			   	   					);
			 					return Response::json($response);
								//return Response::json("This term does not exist. Please contact the admistrator");
							}//else there is a problem with getting thisterm
					}
				catch(Exception $e)
					{
						//var_dump($e); die();
						$response = array('msg' => '', 'status' => 0, 'ChangeClassInfo'
												=> ["There is a problem. Please contact the administrator " ]
			   	   					);
			 			 return Response::json($response);
						// return Response::json($e);//"Error! This Term has a problem associated with it");
					}//end catch
		 	  }//end ajax request block
		}//end else that ensure validator passed
	}//end method getThisStudentClass

	public function promoteThisStudent()
	{
		//return Response::json(Input::all());
		$FormMessages = array(
                           	   'StudentId.required' => 'The student has not being choosen. Please choose a student.',
                           	   'StudentId.integer' => 'The student has not being choosen. Please choose a student',
                           	   'SubClass.required' => 'The subclass has not being choosen, Please choose it',
                           	   'Year.required' => 'The year is required.',
                               'TermName.required' => 'The term is required.',
                               'Class.required' => 'The class is required.',
                               'Class.max' => 'The class should be :attribute letters only.',
						 );
		$validator = Validator::make(Input::all(),
							array('StudentId' => 'required|integer',
							       'Year' => 'required|date_format:Y',
								   'Class' => 'required|max:3',
								    'SubClass' => 'required|max:1'), $FormMessages);//end static method make of Validator
		if($validator->fails())
		{
			//var_dump($validator->messages()); die();
			if(Request::ajax())
			 {
				$ValidatorErr =  $validator->getMessageBag()->toArray();
			   	$response = array('msg' => 'There are some errors with the details provided. Please, Check below',
			   	   					 'status' => 0,'PromotionInfo' => $ValidatorErr
			   	   					);
				return Response::json($response);
			}

					 		//end  static method make for making a page;->withErrors($validator)->withInput();
		}//end if statement that complain that validation failled
		else
		{
			if(Request::ajax())
			 {
				try
					{
						$ThisTerm = ThisTerm::where('classname', '=', Input::get('Class') )
											 ->where('termname' , '=', 'first term' )
											 ->where('year' , '=', Input::get('Year') )->get()->first();
											// return Response::json($ThisTerm);
						$NextTerm = ThisTerm::where('termname', '=', 'first term' )->where('year' , '=', Input::get('Year')  )->get();
						if(!is_null($ThisTerm) and !is_null($NextTerm) )
							{
								foreach($NextTerm->toArray() as $select)
			                      {
			                        $ComputedStudentTerm = StudentTerm::where('studentid' ,'=',Input::get('StudentId') )
			                        									->where('thistermid', '=', $select['id'])
			                        									->with("ThistermBelong")->get()->toArray();
			                         $PromotedStudentTerm[] = !empty($ComputedStudentTerm) ?
			                         						 strtoupper( $ComputedStudentTerm[0]["thisterm_belong"]['classname'] .
			                         						 	" " . $ComputedStudentTerm[0]["class_subdivision"] . ", ".
			                         						 	 $ComputedStudentTerm[0]["thisterm_belong"]['year'] )  : false;
			                      }
			                      //var_dump($PromotedStudentTerm); die();
			                         $NewArrayStudentTerm = array_filter($PromotedStudentTerm );
			                         $WhichString = (count( $NewArrayStudentTerm) >= 1 )  ? true : false;
								/*$StudentTerm = StudentTerm::where('studentid', '=', Input::get('StudentId') )->get()->first();*/
								if( $WhichString  )
					  				{
					  					//$BadString = "";
					  					foreach($NewArrayStudentTerm as $NAST){ $PromotedNewClass = $NAST ." ";  }
					  					$response = array('msg' => '', 'status' => 1,
												   'PromotionInfo' =>
												   ["This student has already being promoted to " . $PromotedNewClass   ."Please change the student to the appropriate year and the appropriate class. Thank you"  ]
						   	   					);
					  					return Response::json($response);
					  				}
					  			$NewSTudentTerm = new StudentTerm;
								$NewSTudentTerm->thistermid = $ThisTerm['id'];
								$NewSTudentTerm->studentid = Input::get('StudentId');
								$NewSTudentTerm->class_subdivision =  Input::get('SubClass');
								$NewSTudentTerm->save();
							   	$response = array('msg' => '', 'status' => 1,'PromotionInfo' => ["Student has been promoted to ". Input::get('Class')
							   	   					 			." ". strtoupper( Input::get('SubClass') ) .", " .  Input::get('Year')  ]
							   	   					);
								return Response::json($response);
								//return Response::json($NewSTudentTerm);
							}//ensures that Thisterm is found
							else
							{
								$response = array('msg' => '', 'status' => 1,
												   'PromotionInfo' => ["This term does not exist. Please contact the admistrator"  ]
						   	   					);
								return Response::json($response);
								//return Response::json("This term does not exist. Please contact the admistrator");
							}//else there is a problem with getting thisterm
					}
				catch(Exception $e)
					{
						//var_dump($e); die();
						$response = array('msg' => '', 'status' => 1,'PromotionInfo' => ["Error! Cannot promote student. This Term has a problem associated with it"  ]
						   	   					);
						return Response::json($e);
						//return Response::json("Error! This Term has a problem associated with it");
					}//end catch
		 	  }//end ajax request block
		}//end else that ensure validator passed
	}//end method getThisStudentClass

	public function graduateThisStudent()
	{
		$FormMessages = array(
                           	   'StudentId.required' => 'The student has not being choosen. Please choose a student.',
                           	   'StudentId.integer' => 'The student has not being choosen. Please choose a student',
						     );
		$validator = Validator::make(Input::all(),
							array('StudentId' => 'required|integer',
							      ), $FormMessages);//end static method make of Validator
		if($validator->fails())
		{
			$ValidatorErr =  $validator->getMessageBag()->toArray();
			$response = array('msg' => 'There are some errors with the details provided. Please, Check below',
			   	   					 'status' => 0,'PromotionInfo' => $ValidatorErr
			   	   					);
			return Response::json($response);
		}//end if statement that complain that validation failled
		else
		{
			if(Request::ajax())
			   {
					try
						{
							$NewGraduate = new GraduateTable;
							$NewGraduate->studentid =  Input::get('StudentId');
							$NewGraduate->createdby =  Auth::user()->userid;
							$NewGraduate->save();

							$response = array('msg' => '', 'status' => 1,'PromotionInfo' => ["This student have been graduated ".
																							 "and designated as a Graduate. If this not intended,".
											  					    						 "please contact the website administrator"
											  					    						]
			   	   								);
							return Response::json($response);
						}//end try loop
					catch(Exception $e)
						{
							$response = array('msg' => '', 'status' => 1,'PromotionInfo' => ["Student has already being graduate, There is no need to do anything again ".
											  					   							  "or else contact the system administrator"
											  					 							]
											);
							return Response::json($response);
						}//end catch
		 	   }//end ajax request block
	 	}//end else that ensures that validor is ok
	}//end method graduateThisStudent



	public function saveStudentScores()
	{
		//var_dump(Input::all()); die();
		$AllStudents =  Students::with('UserBelong')->get();
		$Subjects =  Subjects::all();
		$validator = Validator::make(Input::all(),
								array('Year' => 'required|date_format:Y',
									  'TermName' => 'required',
									  'Class' => 'required|max:3',
									  'SubClass' => 'required|max:1|alpha',
									   'SignatureImageID' => 'integer',
									  'StudentId' => 'required|integer',
									  'SubjectId' => 'required|integer',
									  'CAScore' => 'required|integer|max:40|min:0',
									  'ExamScore' => 'required|integer|max:60|min:0'
									));//end  static method make for making a page;->withErrors($validator)->withInput();
		if($validator->fails())
		{
			$ValidatorErr =  $validator->getMessageBag()->toArray();
			$response = array('msg' => 'Student detail not touched validator failed', 'status' => 0);
			 $response = array_merge($response,(array)$ValidatorErr);
			 					return Response::json($response);

		}//end if statement that complain that validation failled
		else
		{
			$SubjectScore = subject_score::where('studentid', '=', Input::get('StudentId'))
											->where('termname' , '=', Input::get('TermName'))
											->where('year' , '=', Input::get('Year'))
											->where('subjectid' , '=', Input::get('SubjectId'))
											->where('classname' , '=', Input::get('Class'))->get()->first();
			if(is_null($SubjectScore))
				{
					try{
						  DB::transaction(function()
							{
								$thiscore =  new subject_score;
								$thiscore->cont_assess_40 =  Input::get('CAScore');
								$thiscore->exam_score_60 = Input::get('ExamScore');
								$thiscore->subjectid = Input::get('SubjectId');
								$thiscore->termname =  Input::get('TermName');
								$thiscore->classname =  Input::get('Class');
								$thiscore->class_subdivision =  Input::get('SubClass');
								$thiscore->year =  Input::get('Year');
								$thiscore->studentid =  Input::get('StudentId');
								$thiscore->teachersignatureid =  array_key_exists('SignatureImageID', Input::all()) ?
																    Input::get('SignatureImageID'):34;
								$thiscore->createdby = Auth::user()->userid;
								$thiscore->modifiedby =  Auth::user()->userid;
								$thiscore->save();
							});	//end DB::transaction
							$response = array('msg' => 'Student score successfully created', 'status' => 1);
			 				return Response::json($response);
						}
					catch(Exception $e)
						{
							//var_dump($e);die();
							//$thiscore->delete();
							$response = array('msg' => 'Student score not successfully created', 'status' => 0);
			 				return Response::json($e);
						}//end catch
				}//end if statemenbt that commputes that new subject should be saved
			else{
					try{
						  DB::transaction(function() use ($SubjectScore)
							{
								//$EditThisSubjectScore = subject_score::find($ThisSubjectScore['scoreid']);
								//var_dump($EditThisSubjectScore); die();
								$SubjectScore->cont_assess_40 = Input::get('CAScore');
								$SubjectScore->exam_score_60 = Input::get('ExamScore');
								$SubjectScore->teachersignatureid = array_key_exists('SignatureImageID', Input::all()) ?
																    Input::get('SignatureImageID'):34;
								$SubjectScore->modifiedby =  Auth::user()->userid;
								$SubjectScore->save();
							});	//end DB::transaction
							$response = array('msg' => 'Student score is  successfully edited', 'status' => 1);
			 				return Response::json($response);
					}

				catch(Exception $e)
					{
						//return  var_dump($e);//die();
						//$thiscore->delete();
						$response = array('msg' => 'Student score not successfully editted', 'status' => 0 );

			 			return Response::json($response);
					}//end catch
				}//end else statement

		}//end else that ensures that validator is ok
	}//end method saveStudentScores




	public function saveAllStudentScores()
	{
		//return Response::json(Input::all()); die();
		//return Response::json(Input::get("SubjectScore"));
		$GoodArray = [];
		$BadArray = [];
		$ALLSubjectScoreInput = Input::get("SubjectScore");
		foreach($ALLSubjectScoreInput as $allSubjectScoreInput )
		{
			$validator = Validator::make($allSubjectScoreInput,
								array('Year' => 'required|date_format:Y',
									  'TermName' => 'required',
									  'Class' => 'required|max:3',
									  'SignatureImageID' => 'integer',
									  'SubClass' => 'required|max:1|alpha',
									  'StudentId' => 'required|integer',
									  'SubjectId' => 'required|integer',
									  'CAScore' => 'required|integer|max:40|min:0',
									  'ExamScore' => 'required|integer|max:60|min:0'
									));//end  static method make for making a page;->withErrors($validator)->withInput();
		if($validator->fails())
		{
			$ValidatorErr =  $validator->getMessageBag()->toArray();
			$response = array('msg' => 'Student detail not touched validator failed', 'status' => 0);
			$response = array_merge($response,(array)$ValidatorErr);
			$BadArray[] = 	$response;
		}//end if statement that complain that validation failled
		else
		{
			$SubjectScore = subject_score::where('studentid', '=', $allSubjectScoreInput['StudentId'])
											->where('termname' , '=', $allSubjectScoreInput['TermName'])
											->where('year' , '=', $allSubjectScoreInput['Year'])
											->where('subjectid' , '=', $allSubjectScoreInput['SubjectId'])
											->where('classname' , '=', $allSubjectScoreInput['Class'])
											->get()->first();
			if(is_null($SubjectScore))
				{
					try{
						  DB::transaction(function() use ($allSubjectScoreInput)
							{
								$thiscore =  new subject_score;
								$thiscore->cont_assess_40 =  $allSubjectScoreInput['CAScore'];
								$thiscore->exam_score_60 = $allSubjectScoreInput['ExamScore'];
								$thiscore->subjectid = $allSubjectScoreInput['SubjectId'];
								$thiscore->termname =  $allSubjectScoreInput['TermName'];
								$thiscore->classname =  $allSubjectScoreInput['Class'];
								$thiscore->class_subdivision =  $allSubjectScoreInput['SubClass'];
								$thiscore->year =  $allSubjectScoreInput['Year'];
								$thiscore->studentid =  $allSubjectScoreInput['StudentId'];
								$thiscore->teachersignatureid =  array_key_exists('SignatureImageID', $allSubjectScoreInput) ?
																 $allSubjectScoreInput['SignatureImageID']:34;
								$thiscore->createdby = Auth::user()->userid;
								$thiscore->modifiedby =  Auth::user()->userid;
								$thiscore->save();
							});	//end DB::transaction
							$response = array('msg' => 'Student score was successfully created', 'status' => 1);
			 				//return Response::json($response);
			 				$GoodArray[]  =  $response;

						}
					catch(Exception $e)
						{
							//var_dump($e);die();
							$response = array('msg' => 'Student score not was successfully created', 'status' => 0);
			 				//return Response::json($e);
			 				$BadArray[] = 	$response;

						}//end catch
				}//end if statemenbt that commputes that new subject should be saved
			else{
					try{
						  DB::transaction(function() use ($allSubjectScoreInput)
							{
								//var_dump($EditThisSubjectScore); die();
								$SubjectScore = subject_score::where('studentid', '=', $allSubjectScoreInput['StudentId'])
											->where('termname' , '=', $allSubjectScoreInput['TermName'])
											->where('year' , '=', $allSubjectScoreInput['Year'])
											->where('subjectid' , '=', $allSubjectScoreInput['SubjectId'])
											->where('classname' , '=', $allSubjectScoreInput['Class'])
											->get()->first();
								$SubjectScore->cont_assess_40 = $allSubjectScoreInput['CAScore'];
								$SubjectScore->exam_score_60 = $allSubjectScoreInput['ExamScore'];
								$SubjectScore->modifiedby =  Auth::user()->userid;
								$SubjectScore->teachersignatureid = array_key_exists('SignatureImageID', $allSubjectScoreInput) ?
																 $allSubjectScoreInput['SignatureImageID']:34;
								$SubjectScore->save();
							});	//end DB::transaction
							$response = array('msg' => 'Student score was successfully edited', 'status' => 1);
			 				//return Response::json($response);
			 				$GoodArray[]  =  $response;
					}

				catch(Exception $e)
					{
						//return  var_dump($e);//die();
						$response = array('msg' => 'Student score not successfully editted', 'status' => 0 );
			 			//return Response::json($response);
			 			$BadArray[] = 	$response;

					}//end catch
				}//end else statement
		   }//end else that ensures that validator is ok
	    } //end for loop
	   //return $GoodArray;
	   //return $BadArray;
	   $Allarr = array_merge($GoodArray,(array)$BadArray);
	   return Response::json($Allarr);
	}//end method saveAllStudentScores

	public function deleteStudentScores()
	{
		//var_dump(Input::all()); die();
		$AllStudents =  Students::with('UserBelong')->get();
		$Subjects =  Subjects::all();
		$validator = Validator::make(Input::all(),
								array('Year' => 'required|date_format:Y',
									  'TermName' => 'required',
									  'Class' => 'required|max:3',
									  'SubClass' => 'required|max:1|alpha',
									  'StudentId' => 'required|integer',
									  'SubjectId' => 'required|integer',
									));//end  static method make for making a page;->withErrors($validator)->withInput();
		if($validator->fails())
		{
			$ValidatorErr =  $validator->getMessageBag()->toArray();
			$response = array('msg' => 'Student detail not touched validator failed', 'status' => 0);
			 $response = array_merge($response,(array)$ValidatorErr);
			 					return Response::json($response);

		}//end if statement that complain that validation failled
		else
		{
			$SubjectScore = subject_score::where('studentid', '=', Input::get('StudentId'))
											->where('termname' , '=', Input::get('TermName'))
											->where('year' , '=', Input::get('Year'))
											->where('subjectid' , '=', Input::get('SubjectId'))
											->where('classname' , '=', Input::get('Class'))->get()->first();
			if(is_null($SubjectScore))
				{

							$response = array('msg' => 'Student score cannot be found in database', 'status' => 2);
			 				return Response::json($response);
				}//end if statemenbt that commputes that new subject should be saved
			else{
					try{
						  DB::transaction(function()
							{
								//var_dump($EditThisSubjectScore); die();
								$SubjectScore = subject_score::where('studentid', '=', Input::get('StudentId'))
											->where('termname' , '=', Input::get('TermName'))
											->where('year' , '=', Input::get('Year'))
											->where('subjectid' , '=', Input::get('SubjectId'))
											->where('class_subdivision' , '=', Input::get('SubClass'))
											->where('classname' , '=', Input::get('Class'))->get()->first();
								$SubjectScore->delete();
							});	//end DB::transaction
							$response = array('msg' => 'Student score is  successfully deleted', 'status' => 1);
			 				return Response::json($response);
					}

				catch(Exception $e)
					{
						//return  var_dump($e);//die();
						$response = array('msg' => 'Student score cannot be deleted', 'status' => 0 );

			 			return Response::json($response);
					}//end catch
				}//end else statement

		}//end else that ensures that validator is ok
	}//end method deleteStudentScores

	public function saveOfficialComments()
	{
		//return Response::json(Input::all());
	  /* $FormMessages = array(
                           	   'SignatureImage.mimes' => 'The file must be in jpeg or bmp or png format',
                           	   'SignatureImage.size' => 'The file must not be more than <b> :size kilobyte</b> in size',
                           	   'SignatureImage.required' => 'An image file of the signature is required',
                           	   'AssignedTeacher.required' => 'Please choose the owner of the signature fom the list',
                           	   'AssignedTeacher.integer' => 'The owner of the signature must be choosen from the list',
						   );*/
	   $validator = Validator::make(Input::all(),
								array('Year' => 'required|date_format:Y',
									  'TermName' => 'required',
									  'Class' => 'required|max:3',
									  'SubClass' => 'required|max:1|alpha',
									  'StudentId' => 'required|integer',
									  'ClassTeacherSignature' =>'integer',
									  'PrincipalSignature' =>'integer',
									  'ParentSignature' =>'integer',
									  'ClassTeacherComment' =>'sometimes',
									  'ClassTeacherDate' =>'sometimes|',
									  'PrincipalCommentText' =>'sometimes',
									  'PrincipalDate' =>'sometimes|',
									  'ParentDate' =>'sometimes|',
									));//end  static method make for making a page;->withErrors($validator)->withInput();
		if($validator->fails())
		{
			if(Request::ajax())
				{
			   	   $ValidatorErr =  $validator->getMessageBag()->toArray();
			   	   $response = array('msg' => 'Please review the validation below', 'status' => 0);
			       $response = array_merge($response,(array)$ValidatorErr);
			 	   return Response::json($response);
			 	}//end ajax request block
		}//end if statement that complain that validation failled
		else
		{
			$OfficialComments = OfficialComments::where('studentid', '=', Input::get('StudentId'))
											->where('termname' , '=', Input::get('TermName'))
											->where('year' , '=', Input::get('Year'))
											->where('class_subdivision' , '=', Input::get('SubClass'))
											->where('classname' , '=', Input::get('Class'))->get()->first();

			if(is_null($OfficialComments))
				{	try{
						  DB::transaction(function()
							{
								$ThisOfficialComments =  new OfficialComments;
								$ThisOfficialComments->classteachersignatureid =  array_key_exists('ClassTeacherSignature', Input::all()) ?
																 				  Input::get('ClassTeacherSignature') :34;
								$ThisOfficialComments->classteacher = Input::get('ClassTeacherComment');
								$ThisOfficialComments->classteacherdate = ( date_parse( Input::get('ClassTeacherDate'))['error_count'] === 0 )  ?
																		  Input::get('ClassTeacherDate') : date('Y-m-d');
								$ThisOfficialComments->principal = Input::get('PrincipalCommentText');
								$ThisOfficialComments->principalsignatureid = array_key_exists('PrincipalSignature', Input::all()) ?
																 				  Input::get('PrincipalSignature') :34;
								$ThisOfficialComments->principaldate =  ( date_parse( Input::get('PrincipalDate'))['error_count'] === 0 ) ?
																		  Input::get('PrincipalDate') : date('Y-m-d');
								$ThisOfficialComments->parentsignatureid = array_key_exists('ParentSignature', Input::all()) ?
																 				  Input::get('ParentSignature') :34;
								$ThisOfficialComments->parentdate = ( date_parse( Input::get('ParentDate'))['error_count'] === 0 )  ?
																		  Input::get('ParentDate') : date('Y-m-d');
								$ThisOfficialComments->termname =  Input::get('TermName');
								$ThisOfficialComments->classname =  Input::get('Class');
								$ThisOfficialComments->class_subdivision =  Input::get('SubClass');
								$ThisOfficialComments->year =  Input::get('Year');
								$ThisOfficialComments->studentid =  Input::get('StudentId');
								$ThisOfficialComments->createdby = Auth::user()->userid;
								$ThisOfficialComments->modifiedby =  Auth::user()->userid;
								$ThisOfficialComments->save();
							});	//end DB::transaction
							$response = array('msg' => 'Official result comments was successfully created', 'status' => 1);
			 				return Response::json($response);
						}
					catch(Exception $e)
						{
							//var_dump($e);die();
							$response = array('msg' => 'Official result comments not successfully created', 'status' => 0);
			 				return Response::json($response);
						}//end catch
				}//end if statemenbt that commputes that new subject should be saved
			else{
					try{
						  DB::transaction(function() use ($OfficialComments)
							{
								$OfficialComments->classteachersignatureid = array_key_exists('ClassTeacherSignature', Input::all()) ?
																 				  Input::get('ClassTeacherSignature') :34;

								$OfficialComments->classteacher = Input::get('ClassTeacherComment');
								$OfficialComments->classteacherdate = Input::get('ClassTeacherDate');
								$OfficialComments->principal = Input::get('PrincipalCommentText');
								$OfficialComments->principalsignatureid = array_key_exists('PrincipalSignature', Input::all()) ?
																 				  Input::get('PrincipalSignature') :34;
								$OfficialComments->principaldate = Input::get('PrincipalDate');
								$OfficialComments->parentsignatureid = array_key_exists('ParentSignature', Input::all()) ?
																 				  Input::get('ParentSignature') :34;
								$OfficialComments->parentdate = Input::get('ParentDate');
								$OfficialComments->modifiedby =  Auth::user()->userid;
								$OfficialComments->save();
							});	//end DB::transaction
							$response = array('msg' => 'Official result comments was successfully edited', 'status' => 1);
			 				return Response::json($response);
					}

				catch(Exception $e)
					{
						//return  var_dump($e);//die();
						//$thiscore->delete();
						$response = array('msg' => 'Official result comments was not successfully editted', 'status' => 0 );
			 			return Response::json($response);
					}//end catch
				}//end else statement

		}//end else that ensures that validator is ok
	}//end method saveOfficialComments
	public function saveStudentAttendance()
	{
		//return Response::json(Input::all());
	  /* $FormMessages = array(
                           	   'SignatureImage.mimes' => 'The file must be in jpeg or bmp or png format',
                           	   'SignatureImage.size' => 'The file must not be more than <b> :size kilobyte</b> in size',
                           	   'SignatureImage.required' => 'An image file of the signature is required',
                           	   'AssignedTeacher.required' => 'Please choose the owner of the signature fom the list',
                           	   'AssignedTeacher.integer' => 'The owner of the signature must be choosen from the list',
						   );*/
	   $validator = Validator::make(Input::all(),
								array('Year' => 'required|date_format:Y',
									  'TermName' => 'required',
									  'Class' => 'required|max:3',
									  'SubClass' => 'required|max:1|alpha',
									  'StudentId' => 'required|integer',
									  'SchoolOpen' =>'integer',
									  'DaysPresent' =>'integer',
									  'DaysAbent' =>'integer'
									));//end  static method make for making a page;->withErrors($validator)->withInput();
		if($validator->fails())
		{
			if(Request::ajax())
				{
			   	   $ValidatorErr =  $validator->getMessageBag()->toArray();
			   	   $response = array('msg' => 'Please review the validation below', 'status' => 0);
			       $response = array_merge($response,(array)$ValidatorErr);
			 	   return Response::json($response);
			 	}//end ajax request block
		}//end if statement that complain that validation failled
		else
		{
			$Attendance =  Attendance::where('studentid', '=', Input::get('StudentId'))
											->where('termname' , '=', Input::get('TermName'))
											->where('year' , '=', Input::get('Year'))
											->where('class_subdivision' , '=', Input::get('SubClass'))
											->where('classname' , '=', Input::get('Class'))->get()->first();


			if(is_null($Attendance))
				{
					try{
						  DB::transaction(function()
							{
								$ThisAttendance =  new Attendance;
								$ThisAttendance->schoolopen = array_key_exists('SchoolOpen', Input::all()) ?
																 				  Input::get('SchoolOpen') :500;

								$ThisAttendance->dayspresent = array_key_exists('DaysPresent', Input::all()) ?
																 				  Input::get('DaysPresent') :500;
								$ThisAttendance->daysabent =  array_key_exists('DaysAbent', Input::all()) ?
																 				  Input::get('DaysAbent') :500;
								$ThisAttendance->termname =  Input::get('TermName');
								$ThisAttendance->classname =  Input::get('Class');
								$ThisAttendance->class_subdivision =  Input::get('SubClass');
								$ThisAttendance->year =  Input::get('Year');
								$ThisAttendance->studentid =  Input::get('StudentId');
								$ThisAttendance->createdby = Auth::user()->userid;
								$ThisAttendance->save();
							});	//end DB::transaction
							$response = array('msg' => 'Attendance record was successfully created', 'status' => 1);
			 				return Response::json($response);
						}
					catch(Exception $e)
						{
							$response = array('msg' => 'Attendance record was not successfully created', 'status' => 0);
			 				return Response::json($response);
						}//end catch
				}//end if statemenbt that commputes that new subject should be saved
			else{
					try{
						  DB::transaction(function() use ($Attendance)
							{
								$Attendance->schoolopen = array_key_exists('SchoolOpen', Input::all()) ?
																 				  Input::get('SchoolOpen') :500;

								$Attendance->dayspresent = array_key_exists('DaysPresent', Input::all()) ?
																 				  Input::get('DaysPresent') :500;
								$Attendance->daysabent =  array_key_exists('DaysAbent', Input::all()) ?
																 				  Input::get('DaysAbent') :500;
								$Attendance->save();
							});	//end DB::transaction
							$response = array('msg' => 'Attendance record was successfully edited', 'status' => 1);
			 				return Response::json($response);
					}

				catch(Exception $e)
					{
						//return  var_dump($e);//die();
						$response = array('msg' => 'Attendance record was not successfully editted', 'status' => 0 );
			 			return Response::json($e);
					}//end catch
				}//end else statement

		}//end else that ensures that validator is ok
	}//end method saveStuendtAttendance

	public function saveTermDuration()
	{
		//return Response::json(Input::all());
	  /* $FormMessages = array(
                           	   'SignatureImage.mimes' => 'The file must be in jpeg or bmp or png format',
                           	   'SignatureImage.size' => 'The file must not be more than <b> :size kilobyte</b> in size',
                           	   'SignatureImage.required' => 'An image file of the signature is required',
                           	   'AssignedTeacher.required' => 'Please choose the owner of the signature fom the list',
                           	   'AssignedTeacher.integer' => 'The owner of the signature must be choosen from the list',
						   );*/
	   $validator = Validator::make(Input::all(),
								array('Year' => 'required|date_format:Y',
									  'TermName' => 'required',
									  'Class' => 'required|max:3',
									  'SubClass' => 'required|max:1|alpha',
									  'StudentId' => 'required|integer',
									  'TermBegins' =>'date',
									  'TermEnd' =>'date',
									  'NextTermBegins' =>'date'
									));//end  static method make for making a page;->withErrors($validator)->withInput();
		if($validator->fails())
		{
			if(Request::ajax())
				{
			   	   $ValidatorErr =  $validator->getMessageBag()->toArray();
			   	   $response = array('msg' => 'Please review the validation below', 'status' => 0);
			       $response = array_merge($response,(array)$ValidatorErr);
			 	   return Response::json($response);
			 	}//end ajax request block
		}//end if statement that complain that validation failled
		else
		{
			$TermDuration =  TermDuration::where('studentid', '=', Input::get('StudentId'))
											->where('termname' , '=', Input::get('TermName'))
											->where('year' , '=', Input::get('Year'))
											->where('class_subdivision' , '=', Input::get('SubClass'))
											->where('classname' , '=', Input::get('Class'))->get()->first();

			if(is_null($TermDuration))
				{	try{
						  DB::transaction(function()
							{
								$ThisTermDuration =  new TermDuration;
								$ThisTermDuration->termbegins = ( date_parse( Input::get('TermBegins'))['error_count']
																  === 0 )  ? Input::get('TermBegins') : date('Y-m-d');
								$ThisTermDuration->termend = ( date_parse( Input::get('TermEnd'))['error_count']
																  === 0 )  ? Input::get('TermEnd') : date('Y-m-d');
								$ThisTermDuration->nexttermbegins = ( date_parse( Input::get('NextTermBegins'))['error_count']
																  === 0 )  ? Input::get('NextTermBegins') : date('Y-m-d');
								$ThisTermDuration->termname =  Input::get('TermName');
								$ThisTermDuration->classname =  Input::get('Class');
								$ThisTermDuration->class_subdivision =  Input::get('SubClass');
								$ThisTermDuration->year =  Input::get('Year');
								$ThisTermDuration->studentid =  Input::get('StudentId');
								$ThisTermDuration->createdby = Auth::user()->userid;
								$ThisTermDuration->save();
							});	//end DB::transaction
							$response = array('msg' => 'Term duration record was successfully created', 'status' => 1);
			 				return Response::json($response);
						}
					catch(Exception $e)
						{
							$response = array('msg' => 'Term duration record was not successfully created', 'status' => 0);
			 				return Response::json($response);
						}//end catch
				}//end if statemenbt that commputes that new subject should be saved
			else{
					try{
						  DB::transaction(function() use ($TermDuration)
							{
								$TermDuration->termbegins = ( date_parse( Input::get('TermBegins'))['error_count']
																  === 0 )  ? Input::get('TermBegins') : date('Y-m-d');
								$TermDuration->termend = ( date_parse( Input::get('TermEnd'))['error_count']
																  === 0 )  ? Input::get('TermEnd') : date('Y-m-d');
								$TermDuration->nexttermbegins = ( date_parse( Input::get('NextTermBegins'))['error_count']
																  === 0 )  ? Input::get('NextTermBegins') : date('Y-m-d');
								$TermDuration->createdby = Auth::user()->userid;
								$TermDuration->save();
							});	//end DB::transaction
							$response = array('msg' => 'Term duration record was successfully edited', 'status' => 1);
			 				return Response::json($response);
					}

				catch(Exception $e)
					{
						//return  var_dump($e);//die();
						$response = array('msg' => 'Term duration record was not successfully editted', 'status' => 0 );
			 			return Response::json($e);
					}//end catch
				}//end else statement

		}//end else that ensures that validator is ok
	}//end method saveTermDuration

	public function getClassScoreProgress()
	{
		//var_dump(Session::get("BigSearchArray")); die();
		//return Response::json(Input::all());
	  /* $FormMessages = array(
                           	   'SignatureImage.mimes' => 'The file must be in jpeg or bmp or png format',
                           	   'SignatureImage.size' => 'The file must not be more than <b> :size kilobyte</b> in size',
                           	   'SignatureImage.required' => 'An image file of the signature is required',
                           	   'AssignedTeacher.required' => 'Please choose the owner of the signature fom the list',
                           	   'AssignedTeacher.integer' => 'The owner of the signature must be choosen from the list',
						   );*/
	  $BigSearchArray =  Session::get("BigSearchArray");

	   $validator = Validator::make(Input::all(), array('TermName' => 'required',));//end  static method make for making a page;->withErrors($validator)->withInput();
		if($validator->fails())
		{
			if(Request::ajax())
				{
			   	   $ValidatorErr =  $validator->getMessageBag()->toArray();
			   	   $response = array('msg' => 'Please review the validation below', 'status' => 0);
			       $response = array_merge($response,(array)$ValidatorErr);
			 	   return Response::json($response);
			 	}//end ajax request block
			 	//var_dump( $BigSearchArray['ChooseTerm'] ); die();
			 	return Redirect::route('student-term-list-page',["Year" => $BigSearchArray['ChooseTerm']["Year"],
			 													 "Class" => $BigSearchArray['ChooseTerm']["Class"],
			 													 "SubClass" =>  $BigSearchArray['ChooseTerm']["SubClass"] ])
			 					->with(array( 'StudentListMessage' =>
			 								 'Plesae check that that the termname is choosen properly'))->withErrors($validator);

		}//end if statement that complain that validation failled
		else
		{
			/*$TermDuration =  TermDuration::where('studentid', '=', Input::get('StudentId'))
											->where('termname' , '=', Input::get('TermName'))
											->where('year' , '=', Input::get('Year'))
											->where('class_subdivision' , '=', Input::get('SubClass'))
											->where('classname' , '=', Input::get('Class'))->get()->first();*/
			//$BigSearchArray =  Session::get("BigSearchArray");

			if(is_array($BigSearchArray['AllThisSTudent']) and is_array($BigSearchArray['ChooseTerm']))
				{
					//var_dump( $BigSearchArray); die();
					$TotalStudentInClass = count($BigSearchArray['AllThisSTudent']);
					$Massive = "";
					$CountofAvailableReport ="";
					try{
							DB::setFetchMode(PDO::FETCH_ASSOC);
							$QuestionTableGroupBy = DB::table('subject_score')
							->select('subjectid','termname', 'classname','class_subdivision','year','studentid',
							 DB::raw('count(*) as MyCount'),  DB::raw(' count(studentid) as MyStudentCount'),
							  DB::raw('count(subjectid) as MySubjectCount') )->groupBy('studentid')
							->where(  'year', '=' , $BigSearchArray['ChooseTerm']['Year'] )
							->where( 'class_subdivision', '=' , $BigSearchArray['ChooseTerm']['SubClass'] )
							->where(  'classname', '=' , $BigSearchArray['ChooseTerm']['Class'] )
							->where(  'termname', '=' , Input::get('TermName') )->get();
							$CountofAvailableReport = count($QuestionTableGroupBy);
							//var_dump($QuestionTableGroupBy); die();
							foreach ($QuestionTableGroupBy as $key => $value)
							{
								# code...
								$Stid =  $value['studentid'];
								$Massive[$Stid]['StudentSubjectCount'] = $value['MyCount'];
								$Massive[$Stid]['StudentId'] = $value['studentid'];
								$Massive[$Stid]['SubjectList'] =  subject_score::where('studentid','=', $Stid)
								->where(  'year', '=' , $BigSearchArray['ChooseTerm']['Year'] )
								->where( 'class_subdivision', '=' , $BigSearchArray['ChooseTerm']['SubClass'] )
								->where(  'classname', '=' , $BigSearchArray['ChooseTerm']['Class'] )
								->where(  'termname', '=' , Input::get('TermName') )->with(['subjectBelong','modifiedByBelong'])
								->select('subjectid','modifiedby')->get()->toArray();//[0]['subject_belong']['subject'];
							}
							//var_dump($Massive); die();
							return View::make('teachers.studentscorereport')->with(array( 'myBreadCrumb' =>
														 					 			 "<a href='". URL::route('home')."' id='BreadNav'>
														 					 			 Home</a> => Student Score Diagnostic Pag",
																						  'Title' => 'Student Score Diagnostic Page',
																						  'Massive' => is_array($Massive) ? $Massive : '',
																						  'BigSearchArray' => $BigSearchArray,
																						  'TotalStudentInClass' => $TotalStudentInClass,
																						  'CountofAvailableReport' => is_integer($CountofAvailableReport) ? $CountofAvailableReport : '',
																						  'TermName' => Input::get('TermName')
																						  ));
					   }
					catch(Exception $e)
						{
							var_dump($e);die();
							return Redirect::route('student-term-list-page',["Year" => $BigSearchArray['ChooseTerm']["Year"],
						 													 "Class" => $BigSearchArray['ChooseTerm']["Class"],
						 													 "SubClass" =>  $BigSearchArray['ChooseTerm']["SubClass"] ])
			 					->with(array( 'StudentListMessage' =>
			 								 'Plesae check that that the termname is choosen properly'));
						}//end catch
				}//end if statemenbt that commputes that new subject should be saved
			else{
					return Redirect::route('student-term-list-page', ["Year" => $BigSearchArray['ChooseTerm']["Year"],
				 													 "Class" => $BigSearchArray['ChooseTerm']["Class"],
				 													 "SubClass" =>  $BigSearchArray['ChooseTerm']["SubClass"] ] )
									->with(array( 'StudentListMessage' =>'Plesae check that that the termname is choosen properly'));
				}//end else statement

		}//end else that ensures that validator is ok
	}//end method saveTermDuration
}//end PageController
