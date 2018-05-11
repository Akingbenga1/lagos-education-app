<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OfficialComments;
use App\Models\Students;
use App\Models\SubjectScore;
use App\Models\Subjects;
use App\Models\TermDuration;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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


}//end PageController
