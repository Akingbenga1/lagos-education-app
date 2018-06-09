<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OfficialComments;
use App\Models\Students;
use App\Models\StudentTerm;
use App\Models\SubjectScore;
use App\Models\Subjects;
use App\Models\TermDuration;
use App\Models\ThisTerm;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{

    public function __construct(  )
    {
        $this->middleware('web');
    }


    public function studentPage(Request $request)
    {
        $method = $request->isMethod('post');
        if($method)
        {
            $AllStudents = Students::with('UserBelong')->get();
            $AllSubjects = Subjects::all();
            $response=[];

            $FormMessages = array(
                'StudentNumber.required_without' => 'Your <b> Student Admission Number</b>  is required or you can type your name in the other text box on the left.',
                'TypeStudentName.required_without' => 'You must type your <b>Student Admission Number</b> or your <b> Name</b>.',
                'StudentNumber.digits' => 'Your <strong> Student Admission Number</strong> must be exactly 4 digits.',
                'StudentNumber.numeric' => 'Your <strong> Student Admission Number</strong> must be a number only.',
                'Year.required' => 'The year is required.',
                'Year.date_format' => 'Choose a year from the year list.',
                'TermName.required' => 'The term is required.',
                'Class.required' => 'Class is required.',
                'Class.max' => 'Class must be :max characters long.',
            );
            $validator = Validator::make($request->all(),
                array('StudentNumber' => 'required_without:TypeStudentName',
                    'Year' => 'required|date_format:Y',
                    'TermName' => 'required',
                    'Class' => 'required|max:3',
                    'TypeStudentName' => 'required_without:StudentNumber',
                    'SubClass' => ''), $FormMessages);//end static method make of Validator
            if($validator->fails())
            {
                return Redirect::route('student-page')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='".route('home')."' id='BreadNav'>Home</a> => Student Page",
                        'Title' => 'Student Page',
                        'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() )     )
                    ->withErrors($validator)->withInput();
                //end  static method make for making a page;->withErrors($validator)->withInput();
            }//end if statement that complain that validation failled
            else
            {
                try{
                    if(($request->StudentNumber) )
                    {
                        $StudentAdminNumberArray = explode("-", $request->StudentNumber);
                        $StudentAdminNumber  = (count($StudentAdminNumberArray) > 0 ) ?  trim($StudentAdminNumberArray[0]) : "";
                        //var_dump($StudentAdminNumber); die();
                        $ThisStudent = Students::with('StudentTermRelate','UserBelong')
                            ->where('school_admission_number', '=', $StudentAdminNumber   )->get()->first();
                        $studentid = !is_null($ThisStudent)? $ThisStudent->toArray()['studentid']:'';
                    }
                    else
                    {
                        $ThisStudent = Students::with('StudentTermRelate','UserBelong')
                            ->where('studentid', '=', $request->StudentId  )->get()->first();
                        $studentid = !is_null($ThisStudent)? $ThisStudent->toArray()['studentid']:'';

                    }
                    if(isset($ThisStudent) && !is_null($ThisStudent))
                    {

                        $RequestedTerm = $request->TermName;
                        if($RequestedTerm === "first term")
                        {

                            $SubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('classname' , '=', $request->Class)
                                ->with('subjectBelong')->get();
                            $OfficialComments = OfficialComments::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('class_subdivision' , '=', $request->SubClass)
                                ->where('classname' , '=', $request->Class)->get()->first();

                            $Attendance = Attendance::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('class_subdivision' , '=', $request->SubClass)
                                ->where('classname' , '=', $request->Class)->get()->first();
                            $TermDuration = TermDuration::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('class_subdivision' , '=', $request->SubClass)
                                ->where('classname' , '=', $request->Class)->get()->first();
                            $SubjectScoreArray = [];
                            if(!empty($SubjectScore) and is_array($SubjectScore->toArray()))
                            {
                                foreach ($SubjectScore->toArray() as $EachSubjectScore)
                                {
                                    $EachTotalScore = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                        + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                    $SubjectScoreArray["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                    $SubjectScoreArray["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                    $SubjectScoreArray["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                    $SubjectScoreArray["EachTotalScore"][] = $EachTotalScore;
                                }

                            }

                            $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                                "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray() :'',
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                "SubjectScoreJson" => !empty($SubjectScoreArray) ? json_encode($SubjectScoreArray):array(),
                                "RequestedTerm" => $request->TermName
                            );
                            Session::put('MassiveDetails', $MassiveDetails);

                            return View::make('students.studentreportpage')->with(array(
                                'myBreadCrumb'
                                =>"<a href='".route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                                'Title' => 'Student Report Page',
                                'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScoreArray):array(),
                                'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                                'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                                'RequestedTerm'   => $request->TermName )     );
                        }//end if

                        if($RequestedTerm === "second term")
                        {
                            $studentid = $ThisStudent->toArray()['studentid'];
                            $SubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('classname' , '=', $request->Class)
                                ->with('subjectBelong')->get();

                            $FirstTermSubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                ->where('termname' , '=', 'first term')
                                ->where('year' , '=', $request->Year)
                                ->where('classname' , '=', $request->Class)
                                ->with('subjectBelong')->get();

                            $OfficialComments = OfficialComments::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('class_subdivision' , '=', $request->SubClass)
                                ->where('classname' , '=', $request->Class)->get()->first();
                            $Attendance = Attendance::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('class_subdivision' , '=', $request->SubClass)
                                ->where('classname' , '=', $request->Class)->get()->first();
                            $TermDuration = TermDuration::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('class_subdivision' , '=', $request->SubClass)
                                ->where('classname' , '=', $request->Class)->get()->first();

                            $SubjectScoreArray = [];
                            if(!empty($SubjectScore) and is_array($SubjectScore->toArray()))
                            {
                                foreach ($SubjectScore->toArray() as $EachSubjectScore)
                                {
                                    $EachTotalScore = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                        + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                    $SubjectScoreArray["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                    $SubjectScoreArray["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                    $SubjectScoreArray["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                    $SubjectScoreArray["EachTotalScore"][] = $EachTotalScore;
                                }

                            }

                            $FirstTermSubjectScoreArray = [];
                            $SubjectScoreArray_Compare = [];
                            $PositionCounter = 0;
                            if(!empty($FirstTermSubjectScore) and is_array($FirstTermSubjectScore->toArray()))
                            {
                                foreach ($SubjectScore->toArray() as  $EachSubjectScore)
                                {
                                    foreach ($FirstTermSubjectScore->toArray() as $EachFirstTermSubjectScore)
                                    {
                                        if($EachSubjectScore["subject_belong"]["subject"] == $EachFirstTermSubjectScore["subject_belong"]["subject"] )
                                        {
                                            $EachTotalScore_First = (is_numeric($EachFirstTermSubjectScore["exam_score_60"]) ? $EachFirstTermSubjectScore["exam_score_60"] : 0)
                                                + (is_numeric($EachFirstTermSubjectScore["cont_assess_40"]) ? $EachFirstTermSubjectScore["cont_assess_40"] : 0) ;
                                            $FirstTermSubjectScoreArray["Subjects"][] = $EachFirstTermSubjectScore["subject_belong"]["subject"];
                                            $FirstTermSubjectScoreArray["cont_assess_40"][] = $EachFirstTermSubjectScore["cont_assess_40"];
                                            $FirstTermSubjectScoreArray["exam_score_60"][] = $EachFirstTermSubjectScore["exam_score_60"];
                                            $FirstTermSubjectScoreArray["EachTotalScore"][] = $EachTotalScore_First;


                                            $EachTotalScore_Sub = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                                + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                            $SubjectScoreArray_Compare["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                            $SubjectScoreArray_Compare["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                            $SubjectScoreArray_Compare["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                            $SubjectScoreArray_Compare["EachTotalScore"][] = $EachTotalScore_Sub;
                                        }

                                    }
                                    $PositionCounter++;
                                }

                            }

                            //dd($SubjectScoreArray_Compare, $FirstTermSubjectScoreArray, $SubjectScoreArray); die();
                            $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                "RequestedTerm" => $request->TermName ,
                                "FirstTermSubjectScore" => !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array(),
                                "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScoreArray ):array(),
                                "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScore) ? json_encode($FirstTermSubjectScoreArray) :array(),
                                "SubjectScoreArray_Compare" => !empty($SubjectScoreArray_Compare) ? json_encode($SubjectScoreArray_Compare) :array(),

                            );
                            //var_dump($MassiveDetails) ; die();
                            Session::put('MassiveDetails', $MassiveDetails);
                            return View::make('students.studentreportpage')
                                ->with(array( 'myBreadCrumb' =>
                                    "<a href='".route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                                    'Title' => 'Student Report Page',
                                    'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                    "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                    "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                    "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                    'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                    'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array() ,
                                    'RequestedTerm'   => $request->TermName,
                                    'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                                    'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                                    "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScoreArray ):array(),
                                    "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScore) ? json_encode($FirstTermSubjectScoreArray) :array(),
                                    "SubjectScoreArray_Compare" => !empty($SubjectScoreArray_Compare) ? json_encode($SubjectScoreArray_Compare) :array(),
                                )  );
                        }//end if that ensures that student_term_relate is empty
                        if($RequestedTerm === "third term")
                        {
                            //dd($request->all()());
                            $studentid = $ThisStudent->toArray()['studentid'];
                            //var_dump($studentid);
                            $SubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('classname' , '=', $request->Class)
                                ->with('subjectBelong')->get();

                            $FirstTermSubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                ->where('termname' , '=', 'first term')
                                ->where('year' , '=', $request->Year)
                                ->where('classname' , '=', $request->Class)
                                ->with('subjectBelong')->get();
                            $SecondTermSubjectScore = SubjectScore::where('studentid', '=', $studentid)
                                ->where('termname' , '=', 'second term')
                                ->where('year' , '=', $request->Year)
                                ->where('classname' , '=', $request->Class)
                                ->with('subjectBelong')->get();
                            $OfficialComments = OfficialComments::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('class_subdivision' , '=', $request->SubClass)
                                ->where('classname' , '=', $request->Class)->get()->first();
                            $Attendance = Attendance::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('class_subdivision' , '=', $request->SubClass)
                                ->where('classname' , '=', $request->Class)->get()->first();
                            $TermDuration = TermDuration::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', $request->Year)
                                ->where('class_subdivision' , '=', $request->SubClass)
                                ->where('classname' , '=', $request->Class)->get()->first();
                            $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                "RequestedTerm" => $request->TermName ,
                                "FirstTermSubjectScore" => !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array(),
                                "SecondTermSubjectScore" => !empty($SecondTermSubjectScore) ? $SecondTermSubjectScore->toArray() :array(),
                                "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScore->toArray() ):array(),
                                "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScoreArray) ? json_encode($FirstTermSubjectScoreArray) :array(),
                                "SecondTermSubjectScoreJson" => !empty($SecondTermSubjectScoreArray) ? json_encode($SecondTermSubjectScoreArray) :array(),
                                "ThirdSubjectScoreArray_Compare" => !empty($ThirdSubjectScoreArray_Compare) ? json_encode($ThirdSubjectScoreArray_Compare) :array(),
                            );
                            //var_dump($MassiveDetails) ; die();

                            $SubjectScoreArray = [];
                            if(!empty($SubjectScore) and is_array($SubjectScore->toArray()))
                            {
                                foreach ($SubjectScore->toArray() as $EachSubjectScore)
                                {
                                    $EachTotalScore = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                        + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                    $SubjectScoreArray["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                    $SubjectScoreArray["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                    $SubjectScoreArray["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                    $SubjectScoreArray["EachTotalScore"][] = $EachTotalScore;
                                }

                            }

                            $FirstTermSubjectScoreArray = [];
                            $SecondTermSubjectScoreArray = [];
                            $ThirdSubjectScoreArray_Compare = [];
                            $PositionCounter = 0;
                            if( ( !empty($FirstTermSubjectScore) and !empty($SecondTermSubjectScore) ) and ( is_array($FirstTermSubjectScore->toArray())  and  is_array($FirstTermSubjectScore->toArray() )) )
                            {
                                foreach ($SubjectScore->toArray() as  $EachSubjectScore)
                                {
                                    foreach ($FirstTermSubjectScore->toArray() as $EachFirstTermSubjectScore)
                                    {
                                        foreach ($SecondTermSubjectScore->toArray() as $EachSecondTermSubjectScore)
                                        {
                                            if( ($EachSubjectScore["subject_belong"]["subject"] == $EachFirstTermSubjectScore["subject_belong"]["subject"]) and
                                                ($EachSubjectScore["subject_belong"]["subject"] ==  $EachSecondTermSubjectScore["subject_belong"]["subject"])  )
                                            {
                                                $EachTotalScore_First = (is_numeric($EachFirstTermSubjectScore["exam_score_60"]) ? $EachFirstTermSubjectScore["exam_score_60"] : 0)
                                                    + (is_numeric($EachFirstTermSubjectScore["cont_assess_40"]) ? $EachFirstTermSubjectScore["cont_assess_40"] : 0) ;
                                                $FirstTermSubjectScoreArray["Subjects"][] = $EachFirstTermSubjectScore["subject_belong"]["subject"];
                                                $FirstTermSubjectScoreArray["cont_assess_40"][] = $EachFirstTermSubjectScore["cont_assess_40"];
                                                $FirstTermSubjectScoreArray["exam_score_60"][] = $EachFirstTermSubjectScore["exam_score_60"];
                                                $FirstTermSubjectScoreArray["EachTotalScore"][] = $EachTotalScore_First;


                                                $EachTotalScore_Second = (is_numeric($EachSecondTermSubjectScore["exam_score_60"]) ? $EachSecondTermSubjectScore["exam_score_60"] : 0)
                                                    + (is_numeric($EachSecondTermSubjectScore["cont_assess_40"]) ? $EachSecondTermSubjectScore["cont_assess_40"] : 0) ;
                                                $SecondTermSubjectScoreArray["Subjects"][] = $EachSecondTermSubjectScore["subject_belong"]["subject"];
                                                $SecondTermSubjectScoreArray["cont_assess_40"][] = $EachSecondTermSubjectScore["cont_assess_40"];
                                                $SecondTermSubjectScoreArray["exam_score_60"][] = $EachSecondTermSubjectScore["exam_score_60"];
                                                $SecondTermSubjectScoreArray["EachTotalScore"][] = $EachTotalScore_Second;


                                                $EachTotalScore_Sub = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                                    + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                                $ThirdSubjectScoreArray_Compare["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                                $ThirdSubjectScoreArray_Compare["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                                $ThirdSubjectScoreArray_Compare["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                                $ThirdSubjectScoreArray_Compare["EachTotalScore"][] = $EachTotalScore_Sub;
                                            }
                                        }

                                    }
                                    $PositionCounter++;
                                }

                            }

                            //dd($ThirdSubjectScoreArray_Compare, $SecondTermSubjectScoreArray,$FirstTermSubjectScoreArray  ); die();
                            Session::put('MassiveDetails', $MassiveDetails);
                            return View::make('students.studentreportpage')
                                ->with(array( 'myBreadCrumb' =>
                                    "<a href='".route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                                    'Title' => 'Student Report Page',
                                    'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                    "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                    "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                    "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                    'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                    'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array() ,
                                    'SecondTermSubjectScore' =>  !empty($SecondTermSubjectScore) ? $SecondTermSubjectScore->toArray() :array() ,
                                    "SubjectScoreJson" => !empty($SubjectScoreArray) ? json_encode($SubjectScoreArray):array(),
                                    "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScoreArray) ? json_encode($FirstTermSubjectScoreArray) :array(),
                                    "SecondTermSubjectScoreJson" => !empty($SecondTermSubjectScoreArray) ? json_encode($SecondTermSubjectScoreArray) :array(),
                                    "ThirdSubjectScoreArray_Compare" => !empty($ThirdSubjectScoreArray_Compare) ? json_encode($ThirdSubjectScoreArray_Compare) :array(),
                                    'RequestedTerm'   => $request->TermName,
                                    'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                                    'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                                )  );
                        }//end if that ensures that student_term_relate is empty
                        else
                        {
                            //dd("Redirect due to unknown");
                            return Redirect::route('student-page')
                                ->with(array( 'myBreadCrumb' =>
                                    "<a href='".route('home')."' id='BreadNav'>Home</a> => Student Page",
                                    'Title' => 'Student Page',
                                    'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                                ) );
                        }//end else that compalains if student_term_relate is empty
                    }//end if statement that ensures  This student is set
                    else
                    {
                        return Redirect::route('student-page')
                            ->with(array( 'myBreadCrumb' =>
                                "<a href='".route('home')."' id='BreadNav'>Home</a> => Student Page",
                                'Title' => 'Student Page',
                                'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',));
                    }//end else that complains if ThisStudent is not a model
                }
                catch(Exception $e)
                {

                    return  Redirect::route('student-page')
                        ->with(array( 'myBreadCrumb' =>
                            "<a href='". route('home')."' id='BreadNav'>Home</a> => Student Page",
                            'Title' => 'Student Page',
                            'ScoreInput' => '',
                            'error' => "Something Went Wrong! Please contact the web administrator",
                            'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                        ));  //end  static method make for making a page;
                }//end catch
            }//end else
        }

        else
        {
            $Title =  'Student Page';

            $AllStudents = Students::with('UserBelong')->get();
            $AllStudents =  !is_null($AllStudents) ? $AllStudents->toArray(): [];
            $AllSubjects = Subjects::all();
            $AllSubjects = !is_null($AllSubjects)?$AllSubjects->toArray():[];

            return view('students.studentpage', compact('Title','AllStudents', 'AllSubjects' ));
        }


    }
    //Old Methods and Function

    public function getStudentPage()
    {
        $AllStudents = Students::with('UserBelong')->get();
        $AllSubjects = Subjects::all();

        return View::make('students.studentpage')->
        with(array(
            'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
            'Title' => 'Student Page',
            'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
            'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'' ));  //end  static method make for making a page;
    }//end method getStudentPage

    public function getSubjectPage()
    {
        //var_dump(Input::all());
        $AllYear = [];
        $AllSubjectSaff = [];
        $CountYear = 0;
        $CountStaff = 0;

        $AllSubjects = Subjects::all();
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $SubjectTeachersGroupBy = DB::table('stafftable')->select('*', DB::raw('count(*) as MyCount'))
            ->groupBy('year')->where( 'subjectid', '=' ,  Input::get('SubjectId')  );
        //var_dump($SubjectTeachersGroupBy->get()/*->toArray()*/);

        $SubjectTeachers = StaffTable::orderBy('year')->with('assignedrolesBelong')->where( 'subjectid', '=' ,
            Input::get('SubjectId')  );
        // var_dump($SubjectTeachers->get()->toArray());

        if( !is_null($SubjectTeachers) and !is_null($SubjectTeachersGroupBy) and !empty($SubjectTeachers->get()->toArray())
            and !empty($SubjectTeachersGroupBy->get())   )
        {
            //var_dump("Not empty");
            foreach ($SubjectTeachersGroupBy->get() as $EachYear )
            {

                $AllYear[$CountYear]["Year"] = $EachYear['year'];
                $AllYear[$CountYear]["YearCount"] = $EachYear['MyCount'];
                $CountYear++;
            }
            foreach($SubjectTeachers->get()->toArray() as $EachSubjectTeacher )
            {
                $ThisStaffRoleId = $EachSubjectTeacher['assignedroleid'];
                $ThisAssignedRoles =  AssignedRoles::where('id', '=', $ThisStaffRoleId)
                    ->with(['userBelong', 'roleBelong'])->get()->first()->toArray();
                $AllSubjectSaff[$CountStaff]['ThisStaff'] = $EachSubjectTeacher;
                $AllSubjectSaff[$CountStaff]['ThisStaffDetails'] = $ThisAssignedRoles;
                $ThisAssignedRoles ='';
                $CountStaff++;
            }
            //var_dump($AllSubjectSaff[0]['ThisStaffDetails']);
            //var_dump($AllSubjectSaff[0]['ThisStaff']);
        }
        return View::make('students.subjectpage')
            ->with(array( 'myBreadCrumb' =>
                "<a href='". URL::route('home')."' id='BreadNav'>Home</a> =>".  Input::get('SubjectName'),
                'Title' => Input::get('SubjectName') . ' - Subject Page',
                'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                "SubjectName" => Input::has('SubjectName') ? Input::get('SubjectName') : "Subject Unavailable",
                'SubjectTeachers' => !is_null($SubjectTeachers->get()) ? $SubjectTeachers->get()->toArray():'',
                'AllYear' => !empty($AllYear) ? $AllYear : '',
                'AllSubjectSaff' => !empty($AllSubjectSaff) ? $AllSubjectSaff : '',
            ));
    }//end method getStudentReportPage

    public function getStudentReportPage()
    {
        $AllStudents = Students::with('UserBelong')->get();
        $AllSubjects = Subjects::all();
        if( Session::has('MassiveDetails') )
        {
            $MassiveDetails = Session::get('MassiveDetails');
            //destry session
            //var_dump($MassiveDetails);
            Session::forget('MassiveDetails'); //destry session  why?
            return View::make('students.studentreportpage')
                ->with(array( 'myBreadCrumb' =>
                    "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                    'Title' => 'Student Report Page',
                    'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                    'ThisStudent' =>  $MassiveDetails['ThisStudent']  ,
                    'SubjectScore' => $MassiveDetails['SubjectScore'] ,
                    'RequestedTerm' =>$MassiveDetails['RequestedTerm'],
                    'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                    'FirstTermSubjectScore'  => array_key_exists('FirstTermSubjectScore', $MassiveDetails)
                        ?$MassiveDetails['FirstTermSubjectScore'] : '',
                    'SecondTermSubjectScore'  => array_key_exists('SecondTermSubjectScore', $MassiveDetails)
                        ?$MassiveDetails['SecondTermSubjectScore'] : ''));
        }
        else
        {
            return View::make('students.studentreportpage')
                ->with(array( 'myBreadCrumb' =>
                    "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                    'Title' => 'Student Report Page',
                    'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                    'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():''));
        }//end else
    }//end method getStudentReportPage

    public function getStudentReport()
    {
        //var_dump(Input::all()); die();
        Session::put('SourceUrl', "ResultAvailable");
        $AllStudents = Students::with('UserBelong')->get();
        $AllSubjects = Subjects::all();
        $FormMessages = array(
            'StudentId.required' => 'Your <b> Student ID</b>  is required.',
            'StudentId.numeric' => 'Your <strong> Student ID</strong> must be numeric.',
            'Year.required' => 'The year is required.',
            'Year.date_format' => 'Choose a year from the year list.',
            'TermName.required' => 'The term is required.',
            'Class.required' => 'Class is required.',
            'SubClass.required' => 'SubClass is required.',
            'Class.max' => 'Class must be :max characters long.',
        );
        $validator = Validator::make(Input::all(),
            array( 'StudentId' => 'required|integer',
                'Year' => 'required|date_format:Y',
                'TermName' => 'required',
                'Class' => 'required|max:3',
                'SubClass' => 'required'), $FormMessages);//end static method make of Validator
        if($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
            //end  static method make for making a page;->withErrors($validator)->withInput();
        }//end if statement that complain that validation failled
        else
        {
            try
            {
                $ThisStudent = Students::with('StudentTermRelate','UserBelong')->where('studentid', '=', Input::get('StudentId')  )->get()->first();
                $studentid = !is_null($ThisStudent)? $ThisStudent->toArray()['studentid']:'';
                if(isset($ThisStudent) && !is_null($ThisStudent))
                {
                    $RequestedTerm = Input::get('TermName');
                    if($RequestedTerm === "first term")
                    {

                        $SubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', $RequestedTerm)
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();
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
                        $SubjectScoreArray = [];
                        if(!empty($SubjectScore) and is_array($SubjectScore->toArray()))
                        {
                            foreach ($SubjectScore->toArray() as $EachSubjectScore)
                            {
                                $EachTotalScore = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                    + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                $SubjectScoreArray["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                $SubjectScoreArray["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                $SubjectScoreArray["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                $SubjectScoreArray["EachTotalScore"][] = $EachTotalScore;
                            }

                        }

                        $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                            "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                            "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray() :'',
                            "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                            "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                            "SubjectScoreJson" => !empty($SubjectScoreArray) ? json_encode($SubjectScoreArray):array(),
                            "RequestedTerm" => Input::get('TermName')
                        );
                        Session::put('MassiveDetails', $MassiveDetails);

                        return View::make('students.studentreportpage')->with(array(
                            'myBreadCrumb'
                            =>"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                            'Title' => 'Student Report Page',
                            'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                            "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                            "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                            "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                            'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                            "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScoreArray):json_encode(array()) ,
                            'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                            'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                            'RequestedTerm'   => Input::get('TermName') )     );
                    }//end if
                    if($RequestedTerm === "second term")
                    {
                        $studentid = $ThisStudent->toArray()['studentid'];
                        $SubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', $RequestedTerm)
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();

                        $FirstTermSubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', 'first term')
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();

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

                        $SubjectScoreArray = [];
                        if(!empty($SubjectScore) and is_array($SubjectScore->toArray()))
                        {
                            foreach ($SubjectScore->toArray() as $EachSubjectScore)
                            {
                                $EachTotalScore = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                    + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                $SubjectScoreArray["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                $SubjectScoreArray["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                $SubjectScoreArray["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                $SubjectScoreArray["EachTotalScore"][] = $EachTotalScore;
                            }

                        }

                        $FirstTermSubjectScoreArray = [];
                        $SubjectScoreArray_Compare = [];
                        $PositionCounter = 0;
                        if(!empty($FirstTermSubjectScore) and is_array($FirstTermSubjectScore->toArray()))
                        {
                            foreach ($SubjectScore->toArray() as  $EachSubjectScore)
                            {
                                foreach ($FirstTermSubjectScore->toArray() as $EachFirstTermSubjectScore)
                                {
                                    if($EachSubjectScore["subject_belong"]["subject"] == $EachFirstTermSubjectScore["subject_belong"]["subject"] )
                                    {
                                        $EachTotalScore_First = (is_numeric($EachFirstTermSubjectScore["exam_score_60"]) ? $EachFirstTermSubjectScore["exam_score_60"] : 0)
                                            + (is_numeric($EachFirstTermSubjectScore["cont_assess_40"]) ? $EachFirstTermSubjectScore["cont_assess_40"] : 0) ;
                                        $FirstTermSubjectScoreArray["Subjects"][] = $EachFirstTermSubjectScore["subject_belong"]["subject"];
                                        $FirstTermSubjectScoreArray["cont_assess_40"][] = $EachFirstTermSubjectScore["cont_assess_40"];
                                        $FirstTermSubjectScoreArray["exam_score_60"][] = $EachFirstTermSubjectScore["exam_score_60"];
                                        $FirstTermSubjectScoreArray["EachTotalScore"][] = $EachTotalScore_First;


                                        $EachTotalScore_Sub = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                            + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                        $SubjectScoreArray_Compare["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                        $SubjectScoreArray_Compare["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                        $SubjectScoreArray_Compare["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                        $SubjectScoreArray_Compare["EachTotalScore"][] = $EachTotalScore_Sub;
                                    }

                                }
                                $PositionCounter++;
                            }

                        }

                        //dd($SubjectScoreArray_Compare, $FirstTermSubjectScoreArray, $SubjectScoreArray); die();
                        $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                            "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                            "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                            "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                            "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() : array(),
                            "RequestedTerm" => Input::get('TermName') ,
                            "FirstTermSubjectScore" => !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array(),
                            "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScoreArray ):json_encode(array()) ,
                            "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScore) ? json_encode($FirstTermSubjectScoreArray) :json_encode(array()) ,
                            "SubjectScoreArray_Compare" => !empty($SubjectScoreArray_Compare) ? json_encode($SubjectScoreArray_Compare) :json_encode(array()) ,

                        );
                        //var_dump($MassiveDetails) ; die();
                        Session::put('MassiveDetails', $MassiveDetails);
                        return View::make('students.studentreportpage')
                            ->with(array( 'myBreadCrumb' =>
                                "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                                'Title' => 'Student Report Page',
                                'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array() ,
                                'RequestedTerm'   => Input::get('TermName'),
                                'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                                'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                                "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScoreArray ):array(),
                                "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScore) ? json_encode($FirstTermSubjectScoreArray) :array(),
                                "SubjectScoreArray_Compare" => !empty($SubjectScoreArray_Compare) ? json_encode($SubjectScoreArray_Compare) :array(),
                            )  );
                    }//end if that ensures that student_term_relate is empty
                    if($RequestedTerm === "third term")
                    {
                        //dd(Input::all());
                        $studentid = $ThisStudent->toArray()['studentid'];
                        //var_dump($studentid);
                        $SubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', $RequestedTerm)
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();
                        //var_dump($SubjectScore->toArray()); die();
                        $FirstTermSubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', 'first term')
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();
                        // var_dump($FirstTermSubjectScore); die();
                        $SecondTermSubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', 'second term')
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();
                        // var_dump($SecondTermSubjectScore); die();
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
                        $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                            "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                            "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                            "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                            "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                            "RequestedTerm" => Input::get('TermName') ,
                            "FirstTermSubjectScore" => !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array(),
                            "SecondTermSubjectScore" => !empty($SecondTermSubjectScore) ? $SecondTermSubjectScore->toArray() :array(),
                            "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScore->toArray() ):json_encode(array()) ,
                            "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScoreArray) ? json_encode($FirstTermSubjectScoreArray) :json_encode(array()) ,
                            "SecondTermSubjectScoreJson" => !empty($SecondTermSubjectScoreArray) ? json_encode($SecondTermSubjectScoreArray) :json_encode(array()) ,
                            "ThirdSubjectScoreArray_Compare" => !empty($ThirdSubjectScoreArray_Compare) ? json_encode($ThirdSubjectScoreArray_Compare) :json_encode(array()) ,
                        );
                        //var_dump($MassiveDetails) ; die();

                        $SubjectScoreArray = [];
                        if(!empty($SubjectScore) and is_array($SubjectScore->toArray()))
                        {
                            foreach ($SubjectScore->toArray() as $EachSubjectScore)
                            {
                                $EachTotalScore = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                    + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                $SubjectScoreArray["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                $SubjectScoreArray["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                $SubjectScoreArray["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                $SubjectScoreArray["EachTotalScore"][] = $EachTotalScore;
                            }

                        }

                        $FirstTermSubjectScoreArray = [];
                        $SecondTermSubjectScoreArray = [];
                        $ThirdSubjectScoreArray_Compare = [];
                        $PositionCounter = 0;
                        if( ( !empty($FirstTermSubjectScore) and !empty($SecondTermSubjectScore) ) and ( is_array($FirstTermSubjectScore->toArray())  and  is_array($FirstTermSubjectScore->toArray() )) )
                        {
                            foreach ($SubjectScore->toArray() as  $EachSubjectScore)
                            {
                                foreach ($FirstTermSubjectScore->toArray() as $EachFirstTermSubjectScore)
                                {
                                    foreach ($SecondTermSubjectScore->toArray() as $EachSecondTermSubjectScore)
                                    {
                                        if( ($EachSubjectScore["subject_belong"]["subject"] == $EachFirstTermSubjectScore["subject_belong"]["subject"]) and
                                            ($EachSubjectScore["subject_belong"]["subject"] ==  $EachSecondTermSubjectScore["subject_belong"]["subject"])  )
                                        {
                                            $EachTotalScore_First = (is_numeric($EachFirstTermSubjectScore["exam_score_60"]) ? $EachFirstTermSubjectScore["exam_score_60"] : 0)
                                                + (is_numeric($EachFirstTermSubjectScore["cont_assess_40"]) ? $EachFirstTermSubjectScore["cont_assess_40"] : 0) ;
                                            $FirstTermSubjectScoreArray["Subjects"][] = $EachFirstTermSubjectScore["subject_belong"]["subject"];
                                            $FirstTermSubjectScoreArray["cont_assess_40"][] = $EachFirstTermSubjectScore["cont_assess_40"];
                                            $FirstTermSubjectScoreArray["exam_score_60"][] = $EachFirstTermSubjectScore["exam_score_60"];
                                            $FirstTermSubjectScoreArray["EachTotalScore"][] = $EachTotalScore_First;


                                            $EachTotalScore_Second = (is_numeric($EachSecondTermSubjectScore["exam_score_60"]) ? $EachSecondTermSubjectScore["exam_score_60"] : 0)
                                                + (is_numeric($EachSecondTermSubjectScore["cont_assess_40"]) ? $EachSecondTermSubjectScore["cont_assess_40"] : 0) ;
                                            $SecondTermSubjectScoreArray["Subjects"][] = $EachSecondTermSubjectScore["subject_belong"]["subject"];
                                            $SecondTermSubjectScoreArray["cont_assess_40"][] = $EachSecondTermSubjectScore["cont_assess_40"];
                                            $SecondTermSubjectScoreArray["exam_score_60"][] = $EachSecondTermSubjectScore["exam_score_60"];
                                            $SecondTermSubjectScoreArray["EachTotalScore"][] = $EachTotalScore_Second;


                                            $EachTotalScore_Sub = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                                + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                            $ThirdSubjectScoreArray_Compare["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                            $ThirdSubjectScoreArray_Compare["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                            $ThirdSubjectScoreArray_Compare["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                            $ThirdSubjectScoreArray_Compare["EachTotalScore"][] = $EachTotalScore_Sub;
                                        }
                                    }

                                }
                                $PositionCounter++;
                            }

                        }

                        //dd($ThirdSubjectScoreArray_Compare, $SecondTermSubjectScoreArray,$FirstTermSubjectScoreArray  ); die();
                        Session::put('MassiveDetails', $MassiveDetails);
                        return View::make('students.studentreportpage')
                            ->with(array( 'myBreadCrumb' =>
                                "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                                'Title' => 'Student Report Page',
                                'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array() ,
                                'SecondTermSubjectScore' =>  !empty($SecondTermSubjectScore) ? $SecondTermSubjectScore->toArray() :array() ,
                                "SubjectScoreJson" => !empty($SubjectScoreArray) ? json_encode($SubjectScoreArray): json_encode(array())  ,
                                "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScoreArray) ? json_encode($FirstTermSubjectScoreArray) : json_encode(array()) ,
                                "SecondTermSubjectScoreJson" => !empty($SecondTermSubjectScoreArray) ? json_encode($SecondTermSubjectScoreArray) : json_encode(array()) ,
                                "ThirdSubjectScoreArray_Compare" => !empty($ThirdSubjectScoreArray_Compare) ? json_encode($ThirdSubjectScoreArray_Compare) : json_encode(array()) ,
                                'RequestedTerm'   => Input::get('TermName'),
                                'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                                'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                            )  );
                    }//end if that ensures that student_term_relate is empty
                }//end if that ensures student
            } // end try
            catch(Exception $e)
            {
                var_dump($e);die();
                return Redirect::back()->with("error","The result cannot be shown at the moment");
            }//end catch block
        } //end else

    }

    public function postStudentReportPage()
    {
        $AllStudents = Students::with('UserBelong')->get();
        $AllSubjects = Subjects::all();
        $response=[];
        if(Request::header("appId") == "MYAPP_ID_HERE" || Request::ajax() )
        {
            // return Response::json(Input::all());
            $FormMessages = array(
                'StudentNumber.required_without' => 'Your <b> Student Admission Number</b>  is required or you can type your name in the other text box on the left.',
                'StudentNumber.digits' => 'Your <strong> Student Admission Number</strong> must be exactly 4 digits.',
                'StudentNumber.numeric' => 'Your <strong> Student Admission Number</strong> must be a number only.',
                'Year.required' => 'The year is required.',
                'Year.date_format' => 'Please, choose a year from the year list.',
                'TermName.required' => 'The term is required.',
                'Class.required' => 'The class is required.',
                'Class.max' => 'The class must be :max characters long.',
            );
            $validator = Validator::make(Input::all(),
                array('StudentNumber' => 'required|digits:4|numeric',
                    'Year' => 'required|date_format:Y',
                    'TermName' => 'required',
                    'Class' => 'required|max:3',
                    'SubClass' => ''), $FormMessages);//end static method make of Validator
            if($validator->fails())
            {
                $ValidatorErr =  $validator->getMessageBag()->toArray();
                $response = array('msg' => 'There are some errors with the details provided. <br /> Check below:',
                    'status' => 0, 'LoginInfo' => $ValidatorErr
                );
                return Response::json($response);
            }//end validator if
            else
            {
                try{
                    if((Input::get('StudentNumber')) )
                    {
                        $ThisStudent = Students::with('StudentTermRelate','UserBelong')
                            ->where('school_admission_number', '=', Input::get('StudentNumber') )->get()->first();
                        //var_dump($ThisStudent->toArray());  die();
                        $studentid = !is_null($ThisStudent)? $ThisStudent->toArray()['studentid']:'';
                    }
                    if(isset($ThisStudent) && !is_null($ThisStudent))
                    {
                        //echo  $studentid; die();
                        $RequestedTerm = Input::get('TermName');
                        if($RequestedTerm === "first term")
                        {
                            //var_dump($ThisStudent->toArray()['student_term_relate']); die();
                            $SubjectScore = subject_score::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', Input::get('Year'))
                                ->where('classname' , '=', Input::get('Class'))
                                ->with("subjectBelong")->get();
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

                            //var_dump($SubjectScore->toArray()); die();
                            $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                                "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray() :'',
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                "RequestedTerm" => Input::get('TermName')
                            );
                            Session::put('MassiveDetails', $MassiveDetails);
                            //var_dump($TermDuration);die();
                            //var_dump(Session::get('MassiveDetails'));die();

                            $response = array(   'status' => 1,
                                "ResultFound" => ["Your Result has being found in the database"],
                                'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                //'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                                'RequestedTerm'   => Input::get('TermName')
                            );
                            return Response::json($response);


                        }//end if



                        if($RequestedTerm === "second term")
                        {
                            $studentid = $ThisStudent->toArray()['studentid'];
                            $SubjectScore = subject_score::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', Input::get('Year'))
                                ->where('classname' , '=', Input::get('Class'))
                                ->with("subjectBelong")->get();

                            $FirstTermSubjectScore = subject_score::where('studentid', '=', $studentid)
                                ->where('termname' , '=', 'first term')
                                ->where('year' , '=', Input::get('Year'))
                                ->where('classname' , '=', Input::get('Class'))
                                ->with("subjectBelong")->get();

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

                            //var_dump($FirstTermSubjectScore->toArray()); exit();die();
                            $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                "RequestedTerm" => Input::get('TermName') ,
                                "FirstTermSubjectScore" => !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore
                                    ->toArray() :array(),
                            );
                            //var_dump($MassiveDetails) ; die();
                            Session::put('MassiveDetails', $MassiveDetails);

                            $response = array( 'status' => 1,
                                "ResultFound" => ["Your result has being found in the database"],
                                'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array() ,
                                'RequestedTerm'   => Input::get('TermName'),
                                //'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():''
                            );
                            return Response::json($response);
                        }//end if that ensures that student_term_relate is empty
                        if($RequestedTerm === "third term")
                        {
                            $studentid = $ThisStudent->toArray()['studentid'];
                            //var_dump($studentid);
                            $SubjectScore = subject_score::where('studentid', '=', $studentid)
                                ->where('termname' , '=', $RequestedTerm)
                                ->where('year' , '=', Input::get('Year'))
                                ->where('classname' , '=', Input::get('Class'))
                                ->with("subjectBelong")->get();

                            $FirstTermSubjectScore = subject_score::where('studentid', '=', $studentid)
                                ->where('termname' , '=', 'first term')
                                ->where('year' , '=', Input::get('Year'))
                                ->where('classname' , '=', Input::get('Class'))
                                ->with("subjectBelong")->get();

                            $SecondTermSubjectScore = subject_score::where('studentid', '=', $studentid)
                                ->where('termname' , '=', 'second term')
                                ->where('year' , '=', Input::get('Year'))
                                ->where('classname' , '=', Input::get('Class'))
                                ->with("subjectBelong")->get();
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
                            $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                "RequestedTerm" => Input::get('TermName') ,
                                "FirstTermSubjectScore" => !empty($FirstTermSubjectScore)
                                    ? $FirstTermSubjectScore->toArray() :array(),
                                "SecondTermSubjectScore" => !empty($SecondTermSubjectScore)
                                    ? $SecondTermSubjectScore->toArray() :array(),
                            );
                            //var_dump($MassiveDetails) ; die();
                            Session::put('MassiveDetails', $MassiveDetails);

                            $response = array(  'status' => 1,
                                "ResultFound" => ["Your result has being found in the database"],
                                'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array() ,
                                'SecondTermSubjectScore' =>  !empty($SecondTermSubjectScore) ? $SecondTermSubjectScore->toArray() :array() ,
                                'RequestedTerm'   => Input::get('TermName'),
                                //'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():''
                            );
                            return Response::json($response);
                        }//end if that ensures that student_term_relate is empty
                        else
                        {

                            $response = array(	);
                            return Response::json($response);
                        }//end else that compalains if student_term_relate is empty
                    }//end if statement that ensures  This student is set
                    else
                    {

                        $response = array(	);
                        return Response::json($response);

                    }//end else that complains if ThisStudent is not a model
                }//end try block
                catch(Exception $e)
                {
                    $response = array(	);
                    return Response::json($response);
                }//end catch
            }//end else
        } //end Request::ajax()


        $FormMessages = array(
            'StudentNumber.required_without' => 'Your <b> Student Admission Number</b>  is required or you can type your name in the other text box on the left.',
            'TypeStudentName.required_without' => 'You must type your <b>Student Admission Number</b> or your <b> Name</b>.',
            'StudentNumber.digits' => 'Your <strong> Student Admission Number</strong> must be exactly 4 digits.',
            'StudentNumber.numeric' => 'Your <strong> Student Admission Number</strong> must be a number only.',
            'Year.required' => 'The year is required.',
            'Year.date_format' => 'Choose a year from the year list.',
            'TermName.required' => 'The term is required.',
            'Class.required' => 'Class is required.',
            'Class.max' => 'Class must be :max characters long.',
        );
        $validator = Validator::make(Input::all(),
            array('StudentNumber' => 'required_without:TypeStudentName',
                'Year' => 'required|date_format:Y',
                'TermName' => 'required',
                'Class' => 'required|max:3',
                'TypeStudentName' => 'required_without:StudentNumber',
                'SubClass' => ''), $FormMessages);//end static method make of Validator
        if($validator->fails())
        {
            return Redirect::route('student-page')
                ->with(array( 'myBreadCrumb' =>
                    "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
                    'Title' => 'Student Page',
                    'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() )     )
                ->withErrors($validator)->withInput();
            //end  static method make for making a page;->withErrors($validator)->withInput();
        }//end if statement that complain that validation failled
        else
        {
            try{
                if((Input::get('StudentNumber')) )
                {
                    $StudentAdminNumberArray = explode("-", Input::get('StudentNumber' ));
                    $StudentAdminNumber  = (count($StudentAdminNumberArray) > 0 ) ?  trim($StudentAdminNumberArray[0]) : "";
                    //var_dump($StudentAdminNumber); die();
                    $ThisStudent = Students::with('StudentTermRelate','UserBelong')
                        ->where('school_admission_number', '=', $StudentAdminNumber   )->get()->first();
                    $studentid = !is_null($ThisStudent)? $ThisStudent->toArray()['studentid']:'';
                }
                else
                {
                    $ThisStudent = Students::with('StudentTermRelate','UserBelong')
                        ->where('studentid', '=', Input::get('StudentId')  )->get()->first();
                    $studentid = !is_null($ThisStudent)? $ThisStudent->toArray()['studentid']:'';

                }
                if(isset($ThisStudent) && !is_null($ThisStudent))
                {

                    $RequestedTerm = Input::get('TermName');
                    if($RequestedTerm === "first term")
                    {

                        $SubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', $RequestedTerm)
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();
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
                        $SubjectScoreArray = [];
                        if(!empty($SubjectScore) and is_array($SubjectScore->toArray()))
                        {
                            foreach ($SubjectScore->toArray() as $EachSubjectScore)
                            {
                                $EachTotalScore = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                    + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                $SubjectScoreArray["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                $SubjectScoreArray["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                $SubjectScoreArray["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                $SubjectScoreArray["EachTotalScore"][] = $EachTotalScore;
                            }

                        }

                        $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                            "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                            "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray() :'',
                            "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                            "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                            "SubjectScoreJson" => !empty($SubjectScoreArray) ? json_encode($SubjectScoreArray):array(),
                            "RequestedTerm" => Input::get('TermName')
                        );
                        Session::put('MassiveDetails', $MassiveDetails);

                        return View::make('students.studentreportpage')->with(array(
                            'myBreadCrumb'
                            =>"<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                            'Title' => 'Student Report Page',
                            'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                            "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                            "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                            "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                            'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                            "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScoreArray):array(),
                            'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                            'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                            'RequestedTerm'   => Input::get('TermName') )     );
                    }//end if

                    if($RequestedTerm === "second term")
                    {
                        $studentid = $ThisStudent->toArray()['studentid'];
                        $SubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', $RequestedTerm)
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();

                        $FirstTermSubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', 'first term')
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();

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

                        $SubjectScoreArray = [];
                        if(!empty($SubjectScore) and is_array($SubjectScore->toArray()))
                        {
                            foreach ($SubjectScore->toArray() as $EachSubjectScore)
                            {
                                $EachTotalScore = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                    + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                $SubjectScoreArray["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                $SubjectScoreArray["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                $SubjectScoreArray["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                $SubjectScoreArray["EachTotalScore"][] = $EachTotalScore;
                            }

                        }

                        $FirstTermSubjectScoreArray = [];
                        $SubjectScoreArray_Compare = [];
                        $PositionCounter = 0;
                        if(!empty($FirstTermSubjectScore) and is_array($FirstTermSubjectScore->toArray()))
                        {
                            foreach ($SubjectScore->toArray() as  $EachSubjectScore)
                            {
                                foreach ($FirstTermSubjectScore->toArray() as $EachFirstTermSubjectScore)
                                {
                                    if($EachSubjectScore["subject_belong"]["subject"] == $EachFirstTermSubjectScore["subject_belong"]["subject"] )
                                    {
                                        $EachTotalScore_First = (is_numeric($EachFirstTermSubjectScore["exam_score_60"]) ? $EachFirstTermSubjectScore["exam_score_60"] : 0)
                                            + (is_numeric($EachFirstTermSubjectScore["cont_assess_40"]) ? $EachFirstTermSubjectScore["cont_assess_40"] : 0) ;
                                        $FirstTermSubjectScoreArray["Subjects"][] = $EachFirstTermSubjectScore["subject_belong"]["subject"];
                                        $FirstTermSubjectScoreArray["cont_assess_40"][] = $EachFirstTermSubjectScore["cont_assess_40"];
                                        $FirstTermSubjectScoreArray["exam_score_60"][] = $EachFirstTermSubjectScore["exam_score_60"];
                                        $FirstTermSubjectScoreArray["EachTotalScore"][] = $EachTotalScore_First;


                                        $EachTotalScore_Sub = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                            + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                        $SubjectScoreArray_Compare["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                        $SubjectScoreArray_Compare["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                        $SubjectScoreArray_Compare["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                        $SubjectScoreArray_Compare["EachTotalScore"][] = $EachTotalScore_Sub;
                                    }

                                }
                                $PositionCounter++;
                            }

                        }

                        //dd($SubjectScoreArray_Compare, $FirstTermSubjectScoreArray, $SubjectScoreArray); die();
                        $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                            "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                            "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                            "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                            "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                            "RequestedTerm" => Input::get('TermName') ,
                            "FirstTermSubjectScore" => !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array(),
                            "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScoreArray ):array(),
                            "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScore) ? json_encode($FirstTermSubjectScoreArray) :array(),
                            "SubjectScoreArray_Compare" => !empty($SubjectScoreArray_Compare) ? json_encode($SubjectScoreArray_Compare) :array(),

                        );
                        //var_dump($MassiveDetails) ; die();
                        Session::put('MassiveDetails', $MassiveDetails);
                        return View::make('students.studentreportpage')
                            ->with(array( 'myBreadCrumb' =>
                                "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                                'Title' => 'Student Report Page',
                                'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array() ,
                                'RequestedTerm'   => Input::get('TermName'),
                                'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                                'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                                "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScoreArray ):array(),
                                "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScore) ? json_encode($FirstTermSubjectScoreArray) :array(),
                                "SubjectScoreArray_Compare" => !empty($SubjectScoreArray_Compare) ? json_encode($SubjectScoreArray_Compare) :array(),
                            )  );
                    }//end if that ensures that student_term_relate is empty
                    if($RequestedTerm === "third term")
                    {
                        //dd(Input::all());
                        $studentid = $ThisStudent->toArray()['studentid'];
                        //var_dump($studentid);
                        $SubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', $RequestedTerm)
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();

                        $FirstTermSubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', 'first term')
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();
                        $SecondTermSubjectScore = subject_score::where('studentid', '=', $studentid)
                            ->where('termname' , '=', 'second term')
                            ->where('year' , '=', Input::get('Year'))
                            ->where('classname' , '=', Input::get('Class'))
                            ->with('subjectBelong')->get();
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
                        $MassiveDetails = array("ThisStudent" =>  !empty($ThisStudent) ? $ThisStudent->toArray() :array(),
                            "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                            "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                            "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                            "SubjectScore" => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                            "RequestedTerm" => Input::get('TermName') ,
                            "FirstTermSubjectScore" => !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array(),
                            "SecondTermSubjectScore" => !empty($SecondTermSubjectScore) ? $SecondTermSubjectScore->toArray() :array(),
                            "SubjectScoreJson" => !empty($SubjectScore) ? json_encode($SubjectScore->toArray() ):array(),
                            "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScoreArray) ? json_encode($FirstTermSubjectScoreArray) :array(),
                            "SecondTermSubjectScoreJson" => !empty($SecondTermSubjectScoreArray) ? json_encode($SecondTermSubjectScoreArray) :array(),
                            "ThirdSubjectScoreArray_Compare" => !empty($ThirdSubjectScoreArray_Compare) ? json_encode($ThirdSubjectScoreArray_Compare) :array(),
                        );
                        //var_dump($MassiveDetails) ; die();

                        $SubjectScoreArray = [];
                        if(!empty($SubjectScore) and is_array($SubjectScore->toArray()))
                        {
                            foreach ($SubjectScore->toArray() as $EachSubjectScore)
                            {
                                $EachTotalScore = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                    + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                $SubjectScoreArray["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                $SubjectScoreArray["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                $SubjectScoreArray["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                $SubjectScoreArray["EachTotalScore"][] = $EachTotalScore;
                            }

                        }

                        $FirstTermSubjectScoreArray = [];
                        $SecondTermSubjectScoreArray = [];
                        $ThirdSubjectScoreArray_Compare = [];
                        $PositionCounter = 0;
                        if( ( !empty($FirstTermSubjectScore) and !empty($SecondTermSubjectScore) ) and ( is_array($FirstTermSubjectScore->toArray())  and  is_array($FirstTermSubjectScore->toArray() )) )
                        {
                            foreach ($SubjectScore->toArray() as  $EachSubjectScore)
                            {
                                foreach ($FirstTermSubjectScore->toArray() as $EachFirstTermSubjectScore)
                                {
                                    foreach ($SecondTermSubjectScore->toArray() as $EachSecondTermSubjectScore)
                                    {
                                        if( ($EachSubjectScore["subject_belong"]["subject"] == $EachFirstTermSubjectScore["subject_belong"]["subject"]) and
                                            ($EachSubjectScore["subject_belong"]["subject"] ==  $EachSecondTermSubjectScore["subject_belong"]["subject"])  )
                                        {
                                            $EachTotalScore_First = (is_numeric($EachFirstTermSubjectScore["exam_score_60"]) ? $EachFirstTermSubjectScore["exam_score_60"] : 0)
                                                + (is_numeric($EachFirstTermSubjectScore["cont_assess_40"]) ? $EachFirstTermSubjectScore["cont_assess_40"] : 0) ;
                                            $FirstTermSubjectScoreArray["Subjects"][] = $EachFirstTermSubjectScore["subject_belong"]["subject"];
                                            $FirstTermSubjectScoreArray["cont_assess_40"][] = $EachFirstTermSubjectScore["cont_assess_40"];
                                            $FirstTermSubjectScoreArray["exam_score_60"][] = $EachFirstTermSubjectScore["exam_score_60"];
                                            $FirstTermSubjectScoreArray["EachTotalScore"][] = $EachTotalScore_First;


                                            $EachTotalScore_Second = (is_numeric($EachSecondTermSubjectScore["exam_score_60"]) ? $EachSecondTermSubjectScore["exam_score_60"] : 0)
                                                + (is_numeric($EachSecondTermSubjectScore["cont_assess_40"]) ? $EachSecondTermSubjectScore["cont_assess_40"] : 0) ;
                                            $SecondTermSubjectScoreArray["Subjects"][] = $EachSecondTermSubjectScore["subject_belong"]["subject"];
                                            $SecondTermSubjectScoreArray["cont_assess_40"][] = $EachSecondTermSubjectScore["cont_assess_40"];
                                            $SecondTermSubjectScoreArray["exam_score_60"][] = $EachSecondTermSubjectScore["exam_score_60"];
                                            $SecondTermSubjectScoreArray["EachTotalScore"][] = $EachTotalScore_Second;


                                            $EachTotalScore_Sub = (is_numeric($EachSubjectScore["exam_score_60"]) ? $EachSubjectScore["exam_score_60"] : 0)
                                                + (is_numeric($EachSubjectScore["cont_assess_40"]) ? $EachSubjectScore["cont_assess_40"] : 0) ;
                                            $ThirdSubjectScoreArray_Compare["Subjects"][] = $EachSubjectScore["subject_belong"]["subject"];
                                            $ThirdSubjectScoreArray_Compare["cont_assess_40"][] = $EachSubjectScore["cont_assess_40"];
                                            $ThirdSubjectScoreArray_Compare["exam_score_60"][] = $EachSubjectScore["exam_score_60"];
                                            $ThirdSubjectScoreArray_Compare["EachTotalScore"][] = $EachTotalScore_Sub;
                                        }
                                    }

                                }
                                $PositionCounter++;
                            }

                        }

                        //dd($ThirdSubjectScoreArray_Compare, $SecondTermSubjectScoreArray,$FirstTermSubjectScoreArray  ); die();
                        Session::put('MassiveDetails', $MassiveDetails);
                        return View::make('students.studentreportpage')
                            ->with(array( 'myBreadCrumb' =>
                                "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Report Page",
                                'Title' => 'Student Report Page',
                                'ThisStudent' => !empty($ThisStudent) ? $ThisStudent->toArray() :array() ,
                                "OfficialComments" => !is_null($OfficialComments) ? $OfficialComments->toArray():"",
                                "Attendance" => !is_null($Attendance) ? $Attendance->toArray() :'',
                                "TermDuration" => !is_null($TermDuration) ? $TermDuration->toArray() :'',
                                'SubjectScore' => !empty($SubjectScore) ? $SubjectScore->toArray() :array(),
                                'FirstTermSubjectScore' =>  !empty($FirstTermSubjectScore) ? $FirstTermSubjectScore->toArray() :array() ,
                                'SecondTermSubjectScore' =>  !empty($SecondTermSubjectScore) ? $SecondTermSubjectScore->toArray() :array() ,
                                "SubjectScoreJson" => !empty($SubjectScoreArray) ? json_encode($SubjectScoreArray):array(),
                                "FirstTermSubjectScoreJson" => !empty($FirstTermSubjectScoreArray) ? json_encode($FirstTermSubjectScoreArray) :array(),
                                "SecondTermSubjectScoreJson" => !empty($SecondTermSubjectScoreArray) ? json_encode($SecondTermSubjectScoreArray) :array(),
                                "ThirdSubjectScoreArray_Compare" => !empty($ThirdSubjectScoreArray_Compare) ? json_encode($ThirdSubjectScoreArray_Compare) :array(),
                                'RequestedTerm'   => Input::get('TermName'),
                                'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                                'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                            )  );
                    }//end if that ensures that student_term_relate is empty
                    else
                    {
                        //dd("Redirect due to unknown");
                        return Redirect::route('student-page')
                            ->with(array( 'myBreadCrumb' =>
                                "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
                                'Title' => 'Student Page',
                                'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                            ) );
                    }//end else that compalains if student_term_relate is empty
                }//end if statement that ensures  This student is set
                else
                {
                    return Redirect::route('student-page')
                        ->with(array( 'myBreadCrumb' =>
                            "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
                            'Title' => 'Student Page',
                            'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',));
                }//end else that complains if ThisStudent is not a model
            }
            catch(Exception $e)
            {

                return  Redirect::route('student-page')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Page",
                        'Title' => 'Student Page',
                        'ScoreInput' => '',
                        'error' => "Something Went Wrong! Please contact the web administrator",
                        'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                    ));  //end  static method make for making a page;
            }//end catch
        }//end else
    }//end method postStudentReportPage

    public function loadAdminHome()
    {
        return View::make('admin.adminhome')->with(
            array('Title' => 'Admin Home - IjayeHousing Estate'
            ) );  //end  static method make for making a page
    }//end method loadAdminHome

    public function getPrivacyPolicyPage()
    {
        return View::make('legal.privacypolicy')->with(
            array('Title' => 'Privacy Policy - IjayeHousing Estate'
            ) );  //end  static method make for making a page
    }//getPrivacyPolicyPage

    public function getTermOFUsePage()
    {
        return View::make('legal.termsofuse')->with(
            array('Title' => 'Terms of Use - IjayeHousing Estate'
            ) );  //end  static method make for making a page
    }//getPrivacyPolicyPage

    public function teacherSignature()
    {
        $AllAssignedRoles =  AssignedRoles::with('UserBelong')->get()->take(6);
        $OfficialSignatures =  OfficialSignatures::with('UserBelong')->get()->take(6);
        return View::make('admin.teachersignature')
            ->with(array('Title' => 'Teachers Signature',
                'AllAssignedRoles' => !is_null($AllAssignedRoles)?$AllAssignedRoles->toArray():'',
                'OfficialSignatures' => !is_null($OfficialSignatures)?$OfficialSignatures->toArray():''
            ));  //end  static method make for making a page
    }//end method teacherSignature

    public function showAllOfficialSignature()
    {
        $OfficialSignatures =  OfficialSignatures::with('UserBelong')->get();
        return View::make('teachers.teacherssignaturelist')
            ->with(array('Title' => 'Teachers Signature List',
                'OfficialSignatures' => !is_null($OfficialSignatures)?$OfficialSignatures->toArray():''
            ));  //end  static method make for making a page
    }

    public function postTeacherSignature()
    {
        //var_dump(Input::all()); die();
        $AllAssignedRoles =  AssignedRoles::with('UserBelong')->get();
        $FormMessages = array(
            'SignatureImage.mimes' => 'The file must be in jpeg or bmp or png format',
            'SignatureImage.size' => 'The file must not be more than <b> :size kilobyte</b> in size',
            'SignatureImage.required' => 'An image file of the signature is required',
            'AssignedTeacher.required' => 'Please choose the owner of the signature fom the list',
            'AssignedTeacher.integer' => 'The owner of the signature must be choosen from the list',
        );
        $validator = Validator::make(Input::all(),
            array( 'SignatureImage' =>'required|mimes:jpeg,bmp,png|max:1024',
                'AssignedTeacher' => 'required|integer',
            ),$FormMessages);//end static method make of Validator
        if($validator->fails())
        {
            if(Request::ajax())
            {
                $ValidatorErr =  $validator->getMessageBag()->toArray();
                $response = array('msg' => 'Student detail not touched validator failed', 'status' => 0);
                $response = array_merge($response,(array)$ValidatorErr);
                //return Response::json($response);
            }//end ajax request block

            return Redirect::route('upload-teachers-signature')
                ->with(array(
                    'TeacherSignatureResponse' =>
                        "Please correct the validation errors encountered below"))
                ->withErrors($validator);  //end  static method make for making a page

        }//end if statement that complain that validation failled
        else
        {
            if(Input::hasFile('SignatureImage'))
            {	 $AssignedTeacher = Input::get("AssignedTeacher");
                $DestinationPath =  public_path().'/Images/Signatures';
                //upload the file///////////////////////////////////////
                $File = Input::file('SignatureImage');
                //var_dump(($File)); die();
                $FileName =  (Str::random(6)) .'_'.Input::get("AssignedTeacher"). '.'.
                    (Input::file('SignatureImage')->getClientOriginalExtension());
                //  var_dump(($FileName)); die();
                //get file path and save it in the category model
                $echo = Input::file('SignatureImage')->move($DestinationPath, $FileName);
                // create instance
                //$img = Image::make($DestinationPath."/".$FileName);
                // var_dump($img);die();
                //$img->resize(50, 50);
                // resize image to fixed size
                //$img
                if($echo)
                {
                    try
                    {
                        //Image::make($DestinationPath."/".$FileName)->resize(56, 19)->save();
                        DB::transaction(function() use ($FileName)
                        {
                            $ThisSignature =  new OfficialSignatures;
                            $ThisSignature->signatureimage = $FileName;
                            $ThisSignature->userid = Input::get("AssignedTeacher");
                            $ThisSignature->createdby = Auth::user()->userid;
                            $ThisSignature->modifiedby = Auth::user()->userid;
                            $ThisSignature->save();
                        });	//end DB::transaction
                        return Redirect::route('upload-teachers-signature')
                            ->with(array(
                                'TeacherSignatureResponse' =>
                                    "This Signature is succesfully saved in database"));
                    }//end try block
                    catch(Exception $e)
                    {
                        $UpFile  = $DestinationPath.'/'. $FileName;
                        File::delete($UpFile);
                        return Redirect::route('upload-teachers-signature')
                            ->with(array(
                                'TeacherSignatureResponse' =>
                                    "Error!Unable to successfully upload this signature"));
                    }//end catch block
                }
                else
                {
                    $UpFile  = $DestinationPath.'/'. $FileName;
                    File::delete($UpFile);
                    return Redirect::route('upload-teachers-signature')
                        ->with(array(
                            'TeacherSignatureResponse' =>
                                "Error! Unable to upload this signature"));
                } //end else tha complains if signature image is ot updated
            }//end if that ensures file is uploded
            else
            {
                return Redirect::route('upload-teachers-signature')
                    ->with(array( 'TeacherSignatureResponse' => "No File Uploaded!!"));
            }//end else that complains if
        }//end else that ensure validator is fine
    }//end method postTeacherSignature

    public function updateTeacherSignature()
    {
        //var_dump(Input::all()); die();
        $AllAssignedRoles =  AssignedRoles::with('UserBelong')->get();
        $FormMessages = array(
            'SignatureImage.mimes' => 'The file must be jpeg or bmp or png',
            'SignatureImage.required' => 'An image file of the signature is required',
            'AssignedTeacher.required' => 'Please choose the owner of the signature fom the list',
            'AssignedTeacher.integer' => 'The owner of the signature must be choosen from the list',
        );
        $validator = Validator::make(Input::all(),
            array( 'SignatureImage' =>'required|mimes:jpeg,bmp,png',
                'AssignedTeacher' => 'required|integer',
            ),$FormMessages);//end static method make of Validator
        if($validator->fails())
        {
            if(Request::ajax())
            {
                $ValidatorErr =  $validator->getMessageBag()->toArray();
                $response = array('msg' => 'Student detail not touched validator failed', 'status' => 0);
                $response = array_merge($response,(array)$ValidatorErr);
                //return Response::json($response);
            }//end ajax request block

            return Redirect::route('upload-teachers-signature')
                ->with(array(
                    'TeacherSignatureResponse' =>
                        "Please correct the validation errors encountered below"))
                ->withErrors($validator);  //end  static method make for making a page

        }//end if statement that complain that validation failled
        else
        {
            $ThisSignatures= OfficialSignatures::where('userid', '=',Input::get('AssignedTeacher'))->get()->first();
            //var_dump($ThisSignatures); die();
            if(!is_null($ThisSignatures))
            {
                //delete previous file first
                $DestinationPath =  public_path().'/Images/Signatures';
                $OldFileName = $ThisSignatures->toArray()['signatureimage']; //die();
                $UpFile  = $DestinationPath.'/'. $OldFileName;
                File::delete($UpFile);
                if(Input::hasFile('SignatureImage'))
                {
                    $AssignedTeacher = Input::get("AssignedTeacher");

                    //upload the file///////////////////////////////////////
                    $File = Input::file('SignatureImage');
                    //var_dump(($File)); die();
                    $FileName =  (Str::random(6)) .'_'.Input::get("AssignedTeacher"). '.'.
                        (Input::file('SignatureImage')->getClientOriginalExtension());
                    //  var_dump(($FileName)); die();
                    //get file path and save it in the category model
                    $echo = Input::file('SignatureImage')->move($DestinationPath, $FileName);
                    //and save the file name appropriately
                    if($echo)
                    {
                        try
                        {
                            DB::transaction(function() use ($FileName,$ThisSignatures)
                            {
                                $ThisSignatures->signatureimage = $FileName;
                                $ThisSignatures->modifiedby = Auth::user()->userid;
                                $ThisSignatures->save();
                            });	//end DB::transaction
                            return Redirect::route('upload-teachers-signature')
                                ->with(array(
                                    'TeacherSignatureResponse' =>
                                        "This Signature is succesfully update in database
									  						   and a new signature file assigned"));
                        }//end try block
                        catch(Exception $e)
                        {
                            $UpFile  = $DestinationPath.'/'. $FileName;
                            File::delete($UpFile);
                            return Redirect::route('upload-teachers-signature')
                                ->with(array(
                                    'TeacherSignatureResponse' =>
                                        "Error!Unable to successfully upload this signature"));
                        }//end catch block
                    }
                    else
                    {
                        $UpFile  = $DestinationPath.'/'. $FileName;
                        File::delete($UpFile);
                        return Redirect::route('upload-teachers-signature')
                            ->with(array(
                                'TeacherSignatureResponse' =>
                                    "Error! Unable to upload this signature"));
                    } //end else tha complains if signature image is ot updated
                }//end if that ensures file is uploded
                else
                {
                    return Redirect::route('upload-teachers-signature')
                        ->with(array( 'TeacherSignatureResponse' => "No File Uploaded!!"));
                }//end else that complains if
            }//end if statement
            else
            {
                return Redirect::route('upload-teachers-signature')
                    ->with(array('TeacherSignatureResponse' =>
                        "The Teacher profile cannot be retreived.
									  			   Please upload a new signature for this particular teacher"
                    ));
            }
        }//end else that ensure validator is fine
    }//end method updateTeacherSignature

    public function deleteTeacherSignature()
    {
        //var_dump(Input::all()); die();
        $AllAssignedRoles =  AssignedRoles::with('UserBelong')->get();
        $FormMessages = array(
            'AssignedTeacher.required' => 'Please choose the owner of the signature fom the list',
            'AssignedTeacher.integer' => 'A user must be choosen from the list',
        );
        $validator = Validator::make(Input::all(),
            array('AssignedTeacher' => 'required|integer',
            ),$FormMessages);//end static method make of Validator
        if($validator->fails())
        {
            if(Request::ajax())
            {
                $ValidatorErr =  $validator->getMessageBag()->toArray();
                $response = array('msg' => 'Student detail not touched validator failed', 'status' => 0);
                $response = array_merge($response,(array)$ValidatorErr);
                //return Response::json($response);
            }//end ajax request block
            return Redirect::route('upload-teachers-signature')
                ->with(array(
                    'TeacherSignatureResponse' =>
                        "Please correct the validation errors encountered below"))
                ->withErrors($validator);  //end  static method make for making a page

        }//end if statement that complain that validation failled
        else
        {
            $ThisSignatures= OfficialSignatures::where('userid', '=',Input::get('AssignedTeacher'))->get()->first();
            //var_dump($ThisSignatures->toArray()); die();
            if(!is_null($ThisSignatures))
            {
                try
                {
                    DB::transaction(function() use ($ThisSignatures)
                    {
                        $ThisSignatures->delete();
                    });	//end DB::transaction
                    //delete previous file first
                    $DestinationPath =  public_path().'/Images/Signatures';
                    $OldFileName = $ThisSignatures->toArray()['signatureimage']; //die();
                    $UpFile  = $DestinationPath.'/'. $OldFileName;
                    File::delete($UpFile);
                    return Redirect::route('upload-teachers-signature')
                        ->with(array(
                            'TeacherSignatureResponse' =>
                                "This Signature has being deleted from database and
									  				   the signature file deleted also"));
                }//end try block
                catch(Exception $e)
                {
                    //var_dump($e); die();
                    return Redirect::route('upload-teachers-signature')
                        ->with(array('TeacherSignatureResponse' =>
                            "Error!Unable to successfully delete this signature"));
                }//end catch block
            }//end if statement
            else
            {
                return Redirect::route('upload-teachers-signature')
                    ->with(array('TeacherSignatureResponse' =>
                        "The Teacher profile cannot be retreived.
									  			  Please upload a new signature for this teacher"
                    ));
            }
        }//end else that ensure validator is fine
    }//end method deleteTeacherSignature

    public function getScoreInputForm()
    {
        $AllStudents =  Students::with('UserBelong')->get();
        $Subjects =  Subjects::all();
        return View::make('teachers.inputstudentscore')
            ->with(array( 'myBreadCrumb' => "<a href='"
                . URL::route('home')."' id='BreadNav'>Home</a> => Student Score Input Form",
                'Title' => 'Student Score Input Form',
                'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                'Subjects' => !is_null($Subjects)?$Subjects->toArray():'',
            ));
    }//end method getScoreInputForm

    public function inputStudentScores()
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
                'Subjects' => 'required|integer',
                'CAScore' => 'required|integer|max:40|min:0',
                'ExamScore' => 'required|integer|max:60|min:0'
            ));//end  static method make for making a page;->withErrors($validator)->withInput();
        if($validator->fails())
        {
            return Redirect::route('score-input-form')
                ->with(array( 'myBreadCrumb' => "<a href='". URL::route('home')
                    ."' id='BreadNav'>Home</a> => Student Report Page",
                    'Title' => 'Student Score Input Form',
                    'ScoreInput' => "Please review errors below.",
                    'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                    'Subjects' => !is_null($Subjects)?$Subjects->toArray():'',
                ))->withErrors($validator);

        }//end if statement that complain that validation failled
        else
        {
            try{
                $thiscore =  new subject_score;
                $thiscore->cont_assess_40 =  Input::get('CAScore');
                $thiscore->exam_score_60 = Input::get('ExamScore');
                $thiscore->subjectid = Input::get('Subjects');
                $thiscore->termname =  Input::get('TermName');
                $thiscore->classname =  Input::get('Class');
                $thiscore->class_subdivision =  Input::get('SubClass');
                $thiscore->year =  Input::get('Year');
                $thiscore->studentid =  Input::get('StudentId');
                $thiscore->createdby = Auth::user()->userid;
                $thiscore->modifiedby =  Auth::user()->userid;

                $thiscore->save();
                return Redirect::route('score-input-form')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Score Input Form",
                        'Title' => 'Student Score Input Form',
                        'ScoreInput' => "Student Score Saved!",
                        'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                        'Subjects' => !is_null($Subjects)?$Subjects->toArray():'',
                    ));  //end  static method make for making a page;
            }
            catch(Exception $e)
            {
                //var_dump($e);die();
                $thiscore->delete();
                return Redirect::route('score-input-form')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student Score Input Form",
                        'Title' => 'Student Score Input Form',
                        'ScoreInput' => "Unable to upload student score",
                        //'AllClasses' => is_array($AllClasses)?$AllClasses:'',
                        'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                        'Subjects' => !is_null($Subjects)?$Subjects->toArray():'',
                    ));  //end  static method make for making a page;
            }//end catch
        }//end else that ensures that validator is ok
    }//end method inputStudentScores

    public function getStudentList()
    {
        $AllStudents =  Students::with('UserBelong')->get();
        $Subjects =  Subjects::all();
        return View::make('teachers.studentlist')->
        with(array(
            'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student List Page",
            'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
            'Subjects' => !is_null($Subjects)?$Subjects->toArray():'',
            'Title' => 'Student List' ));  //end  static method make for making a page;
    }//end method getStudentList

    public function getScoreEditForm()
    { 	//var_dump(Input::all() ); die();
        $validator = Validator::make(Input::all(),
            array('SubjectId' => 'required|integer'	,
                'StudentId' => 'required|integer'	,
                'Year' => 'required|date_format:Y',
                'TermName' => 'required',
                'Class' => 'required|max:3',
            ));//end static method make of Validator
        if($validator->fails())
        {
            //var_dump($validator->messages()->toArray()); die();
            $AllStudents =  Students::with('UserBelong')->get();
            $Subjects =  Subjects::all();
            return Redirect::route('get-students-list')
                ->with(array( 'myBreadCrumb' => "<a href='". URL::route('home')
                    ."' id='BreadNav'>Home</a> => Student List Page",
                    'Title' => 'Student List Page',
                    'ListMessage' => "Please review the validation errors below.",
                    'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                    'Subjects' => !is_null($Subjects)?$Subjects->toArray():'',
                ))->withErrors($validator); //end  static method make for making a page;
        }//end if statement that complain that validation failled
        else
        {
            try
            {
                $Subjects =  Subjects::all()->toArray();
                $ThisSubject =  Subjects::find(Input::get('SubjectId'));
                !is_null($ThisSubject)?Session::put('ThisSubject', $ThisSubject->toArray()):" ";
                //var_dump($ThisSubject->toArray() ); die();
                $ThisSubjectScore = subject_score::where('studentid', '=', Input::get('StudentId'))
                    ->where('termname' , '=', Input::get('TermName'))
                    ->where('year' , '=', Input::get('Year'))
                    ->where('subjectid' , '=', Input::get('SubjectId'))
                    ->where('classname' , '=', Input::get('Class'))->get()->first();
                !is_null($ThisSubjectScore)?Session::put('ThisSubjectScore', $ThisSubjectScore->toArray()):" ";
                //var_dump($ThisSubjectScore->toArray());die();
                $ThisTerm = ThisTerm::with('StudentTermRelate')
                    ->where('classname', '=', Input::get('Class') )
                    ->where('termname' , '=', Input::get('TermName') )
                    ->where('year' , '=', Input::get('Year') )->get()->first();
                !is_null($ThisTerm)?Session::put('ThisTerm', $ThisTerm->toArray()):" ";
                //var_dump($ThisTerm->toArray()); die();
                $ThisStudent = Students::with('UserBelong')
                    ->where('studentid', '=', Input::get('StudentId') )
                    ->get()
                    ->first();
                !is_null($ThisStudent)?Session::put('ThisStudent', $ThisStudent->toArray()):" ";
                //var_dump($ThisStudent->toArray());die();
                return  View::make('teachers.studentscoreeditform')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Edit student score",
                        'Title' => 'Edit student score',
                        'Subjects' => $Subjects,
                        'ThisSubject' => !is_null($ThisSubject)?$ThisSubject->toArray():'',
                        'ThisStudent' => !is_null($ThisStudent)?$ThisStudent->toArray():'',
                        'ThisSubjectScore' => !is_null($ThisSubjectScore)?$ThisSubjectScore->toArray():'',
                        'ThisTerm' => !is_null($ThisTerm)?$ThisTerm->toArray():''));
                //end  static method route for redirecting a page
            }
            catch(Exception $e)
            {
                //var_dump($e); die();
                $AllStudents =  Students::with('UserBelong')->get();
                $Subjects =  Subjects::all();
                return  Redirect::route('get-students-list')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Student List Page",
                        'Title' => 'Student List Page',
                        'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'',
                        'Subjects' => !is_null($Subjects)?$Subjects->toArray():'',
                        'ListMessage' => "There is an error with getting the student score edit form"));
                //end  static method make for making a page;
            }//end catch
        }//end else that ensures that validator is ok
    }//end method getScoreEditForm

    public function editThisScore()
    { 	//var_dump(Input::all() );
        //die();
        $ThisSubject =  Session::get('ThisSubject');
        $ThisStudent = Session::get('ThisStudent');
        $ThisSubjectScore = Session::get('ThisSubjectScore');
        //var_dump($ThisSubjectScore ); die();

        $validator = Validator::make(Input::all(),
            array( 'CAScore' => 'required|integer|max:40|min:0',
                'ExamScore' => 'required|integer|max:60|min:0'
            ));//end static method make of Validator
        if($validator->fails())
        {
            return View::make('teachers.studentscoreeditform')
                ->with(array( 'myBreadCrumb' => "<a href='". URL::route('home')
                    ."' id='BreadNav'>Home</a> => Edit Subject Score",
                    'Title' => 'Edit Subject Score',
                    'EditMessage' => "Please review the validation errors below.",
                    'ThisSubject' => !is_null($ThisSubject)?$ThisSubject:'',
                    'ThisStudent' => !is_null($ThisStudent)?$ThisStudent:'',
                    'ThisSubjectScore' => !is_null($ThisSubjectScore)?$ThisSubjectScore:''

                ))->withErrors($validator); //end  static method make for making a page;
        }//end if statement that complain that validation failled
        else
        {
            try
            {
                $EditThisSubjectScore = subject_score::find($ThisSubjectScore['scoreid']);
                //var_dump($EditThisSubjectScore); die();
                $EditThisSubjectScore->cont_assess_40 = Input::get('CAScore');
                $EditThisSubjectScore->exam_score_60 = Input::get('ExamScore');
                $EditThisSubjectScore->save();

                $NewSubjectScore = subject_score::where('studentid', '=', $ThisStudent['studentid'])
                    ->where('termname' , '=', $ThisSubjectScore['termname'])
                    ->where('year' , '=',  $ThisSubjectScore['year'])
                    ->where('subjectid' , '=', $ThisSubjectScore['subjectid'])
                    ->where('classname' , '=', $ThisSubjectScore['classname'])->get()->first();
                //var_dump($NewSubjectScore->toArray()); die();
                !is_null($NewSubjectScore)?Session::put('ThisSubjectScore', $NewSubjectScore->toArray()):" ";
                return  View::make('teachers.studentscoreeditform')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Edit Subject Score",
                        'Title' => 'Edit Subject Score',
                        'EditMessage' => 'Score Sucessfully Editted',
                        'ThisSubject' => !is_null($ThisSubject)?$ThisSubject:'',
                        'ThisStudent' => !is_null($ThisStudent)?$ThisStudent:'',
                        'ThisSubjectScore' => !is_null($NewSubjectScore)?$NewSubjectScore->toArray():''));
                //end  static method route for redirecting a page
            }
            catch(Exception $e)
            {
                var_dump($e); die();
                //$EditThisSubjectScore->save();
                return  View::make('teachers.studentscoreeditform')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Edit Subject Score",
                        'Title' => 'Edit Subject Score',
                        'EditMessage' => "There is an error with getting the student score edit form",
                        'ThisSubject' => !is_null($ThisSubject)?$ThisSubject:'',
                        'ThisStudent' => !is_null($ThisStudent)?$ThisStudent:'',
                        'ThisSubjectScore' => !is_null($ThisSubjectScore)?$ThisSubjectScore:''));
                //end  static method make for making a page;
            }//end catch
        }//end else that ensures that validator is ok
    }//end method editThisScore

    public function getStudentTermForm()
    {
        $AllStudents = Students::with('UserBelong')->get();
        //var_dump($AllStudents->toArray()); die();
        return View::make('teachers.studenttermform')->
        with(array(
            'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Add Student Term",
            'Title' => 'Add Student Term',
            'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'' ));  //end  static method make for making a page;
    }//end method getStudentTermForm

    public function postStudentTerm()
    {//var_dump(Input::all()); die();
        $SuccessArray = [];
        $FailureArray = [];
        $AllStudents = Students::with('UserBelong')->get();
        $AllSubjects = Subjects::all();
        $validator = Validator::make(Input::all(),
            array('Year' => 'required|date_format:Y',
                'TermName' => 'required',
                'Class' => 'required|max:3',
                'SubClass' => 'required|max:1',
                'MegaList' => 'required'
            ));//end static method make of Validator
        if($validator->fails())
        {
            //var_dump($validator->messages()->toArray() ); die();
            return Redirect::route('teachers-home-page')
                ->with(array( 'myBreadCrumb' => "<a href='". URL::route('home')
                    ."' id='BreadNav'>Home</a> => Add Student Term",
                    'Title' => 'Add Student Term',
                    'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'' ,
                    'AddStudentTermInfo' => 'Please review the validation Error(s)'

                ))->withErrors($validator); //end  static method make for making a page;
        }//end if statement that complain that validation failled
        else
        {
            try
            {
                $ThisTerm = ThisTerm::where('classname', '=', Input::get('Class') )
                    ->where('termname' , '=', 'first term' )
                    ->where('year' , '=', Input::get('Year') )->get()->first();
                $StudentMegaList =  Input::get('MegaList');

                if(!is_null($ThisTerm) )
                {	//var_dump($ThisTerm->toArray(), Input::get('MegaList')); die();
                    $ThisTermAndSubClass['ThisTerm'] =  $ThisTerm->toArray();// get term information
                    $ThisTermAndSubClass['SubClass'] =   Input::get('SubClass');// get term information
                    foreach ($StudentMegaList as  $EachStudent)
                    {
                        $StudentAdminNumberArray = explode("-", $EachStudent );
                        $StudentAdminNumber  = (count($StudentAdminNumberArray) > 0 ) ?  trim($StudentAdminNumberArray[0]) : "";
                        $ThisStudent = Students::select('studentid')->where('school_admission_number', '=', $StudentAdminNumber   )->get();
                        $EachStudentId = (count($ThisStudent) ) > 0 ? $ThisStudent->first()->toArray()['studentid'] : 0;
                        try{

                            //var_dump($EachStudentId); die();
                            $STudentTerm = new StudentTerm;
                            $STudentTerm->thistermid = $ThisTerm['id'];
                            $STudentTerm->studentid =  $EachStudentId;
                            $STudentTerm->class_subdivision =  Input::get('SubClass');
                            $STudentTerm->save();
                            $SuccessArray['studentid'][] = $EachStudentId;// catch good list here
                        }//end second try block
                        catch(\Exception $e)
                        {
                            //var_dump($e); die();
                            $FailureArray['studentid'][] = $EachStudentId;//catch bad list here
                        }//end catch if we vcant save into stuedentterm
                    }//end for loop

                    return Redirect::route('teachers-home-page')
                        ->with(array( 'myBreadCrumb' =>
                            "<a href='". URL::route('home').
                            "' id='BreadNav'>Home</a> => Add Student Term",
                            'Title' => 'Add Student Term',
                            'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'' ,
                            'ThisTermAndSubClass' => !is_null($ThisTermAndSubClass)?$ThisTermAndSubClass:'' ,
                            'AddStudentTermInfo' => 'Student Allocation Operation Performed!',
                            'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                            'SuccessArray' => $SuccessArray,
                            'FailureArray' => $FailureArray,));

                }//ensures that Thisterm is found
                else
                {
                    return  Redirect::route('teachers-home-page')
                        ->with(array( 'myBreadCrumb' =>
                            "<a href='". URL::route('home').
                            "' id='BreadNav'>Home</a> => Add Student Term",
                            'Title' => 'Add Student Term',
                            'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'' ,
                            'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                            'AddStudentTermInfo' => 'Sorry, unable to find the approprite term'));
                }//else there is a problem with getting thisterm
            }
            catch(Exception $e)
            {
                //var_dump($e); die();
                Redirect::route('teachers-home-page')
                    ->with(array( 'myBreadCrumb' =>
                        "<a href='". URL::route('home').
                        "' id='BreadNav'>Home</a> => Add Student Term",
                        'Title' => 'Add Student Term',
                        'AllStudents' => !is_null($AllStudents)?$AllStudents->toArray():'' ,
                        'AllSubjects' => !is_null($AllSubjects)?$AllSubjects->toArray():'',
                        'AddTermError' => 'Sorry, cannot unable to find the approprite term'));
            }//end catch
        }//end else that ensures that validator is ok
    }//end method postStudentTerm

    public function teachersDay()
    {
        return View::make('teachers.teachersday')->with(array(
            'myBreadCrumb' => "<a href='". URL::route('home')."' id='BreadNav'>Home</a> => Add Student Term",
            'Title' => 'Teachers Day!!!!',
        ));  //end  static method make for making a page;
    }//end method postStudentTerm


}//end PageController
