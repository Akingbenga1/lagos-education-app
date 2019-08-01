<?php

namespace App\Http\Controllers;

use App\Exports\ExcelExport;
use App\Imports\ExcelImport;
use App\Libraries\Utilities;
use App\Models\AcademicYear;
use App\Models\ClassLevels;
use App\Models\ClassSubdivisions;
use App\Models\School;
use App\Models\StudentRegistration;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\SubjectScore;
use App\Models\Terms;
use App\Models\Users;
use App\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use ZipArchive;


class ScoreReportController extends Controller
{

    public function __construct(  )
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        // Authorization based , Only admin can do CRUD.
        // Switch case to different route based on role or permission assessment
        // Show all schools and include appropriate utility action links
        //  Links to schools and show modal on mode of score upload

        $method = $request->isMethod('get');
        switch ($method) {
            case true:

                $all_schools = School::all();

                $academic_year = AcademicYear::all();

                $terms = Terms::all();

                $classlevels = ClassLevels::all();

                $class_subdivisions = ClassSubdivisions::all();

                $data['all_schools'] = $all_schools->toArray();

                $data['academic_years'] = $academic_year;

                $data['terms'] = $terms;

                $data['classlevels'] = $classlevels;

                $data['class_subdivisions'] = $class_subdivisions;

                $data['Title'] = 'Edu App | School Management Software';

                //        dd($data);
                return view('schools.schools', $data);

                break;
            case false:
                // post value for school modal
                // If academic year, term and class level exists
                // the redirect to new view that show excel to be uploaded for student scores

//                dd($request->all());
                Session::forget("ExcelData");

                $validator = Validator::make($request->all(),
                [
//                    'academic_year_value' => 'required|integer',
                    'term_value' => 'required|integer',
//                    'class_level_value' => 'required|integer',
                    'school_value' => 'required|integer'

                ]);

                if ($validator->fails())
                {
                    return back()->withErrors($validator)->withInput();
                }

                $academic_year = $request->academic_year_value;
                $term_value = $request->term_value;
                $class_level_value = $request->class_level_value;
                $school_value = $request->school_value;

//                $school_value = 869669959;
//                dd($academic_year, $term_value, $class_level_value  );



                $this_school = School::find($school_value);
                if(is_null($this_school)  )
                {
                    $request->session()->flash('error' , "The school chosen is not valid.");
                    return back();
                }
//
//
//                $this_academic_year = AcademicYear::find($academic_year);
//                if(is_null($this_academic_year)  )
//                {
//                    $request->session()->flash('error' , "The academic year chosen is not valid.");
//                    return back();
//                }

                $this_term = Terms::find($term_value);
                if(is_null($this_term)  )
                {
                    $request->session()->flash('error' , "The term chosen is not valid.");
                    return back();
                }

//                $this_class_level = ClassLevels::find($class_level_value);
//                if(is_null($this_class_level)  )
//                {
//                    $request->session()->flash('error' , "The class level chosen is not valid.");
//                    return back();
//                }


                // Save Models in Session Variables and open next page , Excel upload page

//                Session::put("academic_year_object", $this_academic_year);

                Session::put("term_object", $this_term);

//                Session::put("class_level_object", $this_class_level);

                Session::put("school_object", $this_school);


//                Session::has('plate_number_search_array')

//                Session::get('plate_number_search_array');

                $data = [];

                return view('schools.score_excel_upload', $data);

                break;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $plate_number_addition_mode  =  $request->addition_mode;

        switch ($plate_number_addition_mode)
        {

            case "single":
                dd($request->all());
                $validator = Validator::make($request->all(), [

                    'plate_number' => 'required',
                    'addition_mode' => 'required',

                ]);

                if ($validator->fails())
                {
                    return back()->withErrors($validator)->withInput();
                }

                //Get plate number and split in to it component the . Do validation on these split , then check all tables for duplicates ( out of series, standard number plates and fancy plates tables )

                $plate_number = trim( $request->plate_number );
                $response = Utilities::checkNumberPlate( $plate_number);
                if(array_key_exists("status", $response ) and  $response["status"] == 1)
                {
                    // The Plate Number can be saved to database as it doesn't exist beforehand

                    //If response for single is status == 1 , then save , however if not, return negative response
                    //Make attempt to save plate  number in out of series
                    //Build plate number insert array first then insert to database

                    $response_array = array_key_exists("response_array", $response ) ? $response["response_array"] : [];
                    $plate_number_insert_data = [
                        "plate_number" =>  strtoupper($plate_number)    ,
                        "lga_code_id"  => $response_array["lga_code_id"]   ,
                        "letter_code_id"  =>  $response_array["letter_code_id"]  ,
                        "generated"  => 1    , // Yes, its now generated
                        "produced"  =>  0   ,// No, it has not be produced yet
                        "printed"  =>   0   , //No , it has not been procduced yet
                        "supplied"  =>  0  , //No, it has not been supplied yet
                        "vehicle_type_id"  =>  1  , // Vehicle
                        "created_at"  =>  Carbon::now()->toDateTimeString()   ,
                        "updated_at"  =>  null   ,
                        "deleted_at"  =>  null   ,
                        "user_id"  =>   null     ,
                        "created_by"  => 1, //  Auth::user()->id  ,
                        "date_generated"  =>  Carbon::now()->toDateTimeString()  ,
                        "posted"  => 1, // Auth::user()->id    ,
                        'lga_code' =>   $response_array["lga_code"] ,
                        'letter_code' =>  $response_array["letter_code"] ,
                        'number_series' => $response_array["numeric_series"]
                    ];
//                    dd($response_array, $plate_number_insert_data);

                    try
                    {
                        NumberPlateGeneration::insert( $plate_number_insert_data  );
                        $plate_number_insert_data["lga_name"] =  $response_array["lga_description"] ;

                        $request->session()->flash('success' , "Plate Number " .  $plate_number ." successfully saved to the STANDARD table.");
                        return back()->withInput();
                    }
                    catch (\Exception $e)
                    {
                        Log::info($e->getMessage());

                        $request->session()->flash('error' , "There was an error saving this plate number " .  $plate_number ."  in the STANDARD table!");
                        return back()->withInput();
                    }


                }
                elseif(array_key_exists("status", $response ) and  $response["status"] == 0)
                {
                    // The Plate Number can not be saved to database as it already exist somewhere else or its of a wrong format

                    $request->session()->flash('error' , "The plate number $plate_number can not be saved to the STANDARD table because: ". $response["message"]);

                    return back()->withInput();
                }
                else
                {
                    // The Plate Number can not be saved to database as it already exist somewhere else or its of a wrong format

                    $request->session()->flash('error' , "The plate number $plate_number has  a wrong format or the number plate is a fancy number plate.");

                    return back()->withInput();

                }


                break;
            case "bulk":

//                dd($request->all());
                $validator = Validator::make($request->all(), [

                    'plate_number_list' => 'required',
                    'addition_mode' => 'required',

                ]);

                if ($validator->fails())
                {
                    return back()->withErrors($validator)->withInput();
                }
                // Expected Data is a comma separated list of plate number (dashed format)
                $plate_number_response_array = [];

                //Get Data and split based on comma
                $plate_number_list = $request->plate_number_list;
                $plate_number_list_array  = explode(",", $plate_number_list);
                if(is_array($plate_number_list_array) and (count($plate_number_list) > 0))
                {

                    foreach ( $plate_number_list_array as $each_plate_number)
                    {
                        $each_plate_number = trim($each_plate_number);
                        $response = Utilities::checkNumberPlate( $each_plate_number);
                        if(array_key_exists("status", $response ) and  $response["status"] == 1)
                        {
                            // The Plate Number can be saved to database as it doesn't exist beforehand

                            $response_array = array_key_exists("response_array", $response ) ? $response["response_array"] : [];
                            $plate_number_insert_data = [
                                "plate_number" =>  strtoupper($each_plate_number)    ,
                                "lga_code_id"  => $response_array["lga_code_id"]   ,
                                "letter_code_id"  =>  $response_array["letter_code_id"]  ,
                                "generated"  => 1    , // Yes, its now generated
                                "produced"  =>  0   ,// No, it has not be produced yet
                                "printed"  =>   0   , //No , it has not been procduced yet
                                "supplied"  =>  0  , //No, it has not been supplied yet
                                "vehicle_type_id"  =>  1  , // Vehicle
                                "created_at"  =>  Carbon::now()->toDateTimeString()   ,
                                "updated_at"  =>  null   ,
                                "deleted_at"  =>  null   ,
                                "user_id"  =>   null     ,
                                "created_by"  => 1, //  Auth::user()->id  ,
                                "date_generated"  =>  Carbon::now()->toDateTimeString()  ,
                                "posted"  => 1, // Auth::user()->id    ,
                                'lga_code' =>   $response_array["lga_code"] ,
                                'letter_code' =>  $response_array["letter_code"] ,
                                'number_series' => $response_array["numeric_series"]
                            ];
//                    dd($response_array, $plate_number_insert_data);

                            try
                            {
                                NumberPlateGeneration::insert( $plate_number_insert_data  );
                                $plate_number_insert_data["lga_name"] =  $response_array["lga_description"] ;

                                $plate_number_response_array["Success"][$each_plate_number] =  "Plate Number " .  $each_plate_number ." successfully saved to the STANDARD table.";
                            }
                            catch (\Exception $e)
                            {
                                Log::info($e->getMessage());

                                $plate_number_response_array["Failure"][$each_plate_number] =  " There was an error saving this plate number " .  $each_plate_number ."  in the STANDARD table!";
                            }


                        }
                        elseif(array_key_exists("status", $response ) and  $response["status"] == 0)
                        {
                            // The Plate Number can not be saved to database as it already exist somewhere else or its of a wrong format

                            $plate_number_response_array["Failure"][$each_plate_number] =  $response["message"];
                        }
                        else
                        {
                            // The Plate Number can not be saved to database as it already exist somewhere else or its of a wrong format

                            $plate_number_response_array["FancyIncorrect"][$each_plate_number] =  $response["message"];
                        }

                    }

//                        dd($plate_number_response_array );
                    $request->session()->flash('success' , "Bulk storage operation has been completed. View result below.");
                    $request->session()->flash('bulkOperationArray' , $plate_number_response_array );
                    return back()->withInput();

                }
                else
                {
                    // Negative response of Number plate - Redirect back
//                        dd("Please provide a comma separated list of plate numbers");
                    $request->session()->flash('error' , "Please provide a comma separated list of plate numbers");
                    return back()->withInput();
                }

                break;
            case "bulk_excel":

//                dd($request->all());
                $validator = Validator::make($request->all(), [

                    'excel_upload' => 'required|file',
                    'addition_mode' => 'required'

                ]);

                if ($validator->fails())
                {
                    return back()->withErrors($validator)->withInput();
                }

                $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv");

                $result = array($request->file('excel_upload')->getClientOriginalExtension());

//                dd($result);

                if (!in_array($result[0], $extensions))
                {
                    $request->session()->flash('error', 'You must upload an excel file.');
                    return back();
                }

                $data = [];
                if ($request->hasFile('excel_upload'))
                {
                    $path = $request->file('excel_upload')->getRealPath();

                    $data = Excel::load($path, function ($reader)
                    {

                    })->get();
                }

//                dd($data);
                $plate_number_response_array = [];
                $temp_count = 1;
                if(count($data) > 0)
                {
                    foreach ($data as $datum)
                    {

                        $each_unit_lga_code = $datum->has( "lga_code")  ?  trim($datum->lga_code) : null;
                        $each_unit_letter_code = $datum->has( "letter_code")  ?  trim($datum->letter_code) : null;
                        $each_unit_number = $datum->has( "number")  ?  trim($datum->number) : null;
                        $each_plate_number = "temp_plate_number_" . $temp_count;
                        $temp_count++;

//                        dd($each_unit_lga_code,  $each_unit_letter_code, $each_unit_number, $each_plate_number);
                        if(is_null($each_unit_lga_code) or empty($each_unit_lga_code)  )
                        {
                            $plate_number_response_array["Failure"][$each_plate_number] =  "LGA information is missing for $each_unit_lga_code - $each_unit_letter_code - $each_unit_number plate number. Please fill the LGA Code for the appropriate row. ";
                            continue; // stop this iteration and move to the next one

                        }

                        if(is_null($each_unit_letter_code)  or empty($each_unit_letter_code)  )
                        {
                            $plate_number_response_array["Failure"][$each_plate_number] =  "Letter code information is missing for $each_unit_lga_code - $each_unit_letter_code - $each_unit_number plate number. Please fill the letter code for the appropriate row. ";

                            continue; // stop this iteration and move to the next one

                        }
                        if(is_null($each_unit_number)  or empty($each_unit_number) or  $each_unit_number <= 0   )
                        {
                            $plate_number_response_array["Failure"][$each_plate_number] =  "Number series is missing for $each_unit_lga_code - $each_unit_letter_code - $each_unit_number plate number. Please fill the number series for the appropriate row. ";

                            continue; // stop this iteration and move to the next one

                        }

//                        dd($plate_number_response_array);

                        $each_unit_number = (int)$each_unit_number;
                        $each_plate_number = $each_unit_lga_code. "-" . $each_unit_number. "-" . $each_unit_letter_code ;
//                        dd($each_plate_number);
//                        $each_plate_number = $datum->has( "plate")  ?  trim($datum->plate) : null;

                        $response = Utilities::checkNumberPlate( $each_plate_number);
                        if(array_key_exists("status", $response ) and  $response["status"] == 1)
                        {
                            // The Plate Number can be saved to database as it doesn't exist beforehand

                            $response_array = array_key_exists("response_array", $response ) ? $response["response_array"] : [];
                            $plate_number_insert_data = [
                                "plate_number" =>  strtoupper($each_plate_number)    ,
                                "lga_code_id"  => $response_array["lga_code_id"]   ,
                                "letter_code_id"  =>  $response_array["letter_code_id"]  ,
                                "generated"  => 1    , // Yes, its now generated
                                "produced"  =>  0   ,// No, it has not be produced yet
                                "printed"  =>   0   , //No , it has not been procduced yet
                                "supplied"  =>  0  , //No, it has not been supplied yet
                                "vehicle_type_id"  =>  1  , // Vehicle
                                "created_at"  =>  Carbon::now()->toDateTimeString()   ,
                                "updated_at"  =>  null   ,
                                "deleted_at"  =>  null   ,
                                "user_id"  =>   null     ,
                                "created_by"  => 1, //  Auth::user()->id  ,
                                "date_generated"  =>  Carbon::now()->toDateTimeString()  ,
                                "posted"  => 1, // Auth::user()->id    ,
                                'lga_code' =>   $response_array["lga_code"] ,
                                'letter_code' =>  $response_array["letter_code"] ,
                                'number_series' => $response_array["numeric_series"]
                            ];
//                    dd($response_array, $plate_number_insert_data);

                            try
                            {
                                NumberPlateGeneration::insert( $plate_number_insert_data  );
                                $plate_number_insert_data["lga_name"] =  $response_array["lga_description"] ;
                                $plate_number_response_array["Success"][$each_plate_number] =  "Plate Number " .  $each_plate_number ." successfully saved to the STANDARD table.";
                            }
                            catch (\Exception $e)
                            {
                                Log::info($e->getMessage());

                                $plate_number_response_array["Failure"][$each_plate_number] =  " There was an error saving this plate number " .  $each_plate_number ."  in the STANDARD table!";
                            }


                        }
                        elseif(array_key_exists("status", $response ) and  $response["status"] == 0)
                        {
                            // The Plate Number can not be saved to database as it already exist somewhere else or its of a wrong format

                            $plate_number_response_array["Failure"][$each_plate_number] =  $response["message"];
                        }
                        else
                        {
                            // The Plate Number can not be saved to database as it already exist somewhere else or its of a wrong format

                            $plate_number_response_array["FancyIncorrect"][$each_plate_number] =  $response["message"];
                        }


                    }

//                    dd($plate_number_response_array );
                    $request->session()->flash('success' , "Bulk storage operation has been completed. View result below.");
                    $request->session()->flash('bulkOperationArray' , $plate_number_response_array );
                    return back()->withInput();
                }
                else
                {
                    $request->session()->flash('error' , "The excel uploaded is empty. Please insert some bulk plate numbers" );
                    return back()->withInput();
                }

                break;
            default:
                break;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function schools_excel_upload(Request $request)
    {
        $method = $request->isMethod('post');
        if ($method)
        {
            Session::forget("ExcelData" );

            $validator = Validator::make($request->all(), [

                'bulk_excel_upload' => 'required|file',
            ]);

            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }

            $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv");

            $result = array($request->file('bulk_excel_upload')->getClientOriginalExtension());

            if (!in_array($result[0], $extensions))
            {
                $request->session()->flash('error', 'You must upload an excel file.');

                return back()->withErrors($validator)->withInput();
            }


            $file_name = $request->file('bulk_excel_upload')->getClientOriginalName();

            $destinationPath = storage_path('app/uploads');

//            $request->file('bulk_excel_upload')->move($destinationPath, $file_name);

//            dd($request->file('bulk_excel_upload'));

            //File is Uploaded , now send file to csv_to_array

            $FullFilePath = $destinationPath."/".$file_name;

            $data = [];

            if ($request->hasFile('bulk_excel_upload'))
            {
                $path = $request->file('bulk_excel_upload')->getRealPath();

                $data  = Excel::toCollection(new ExcelImport, request()->file('bulk_excel_upload'));
            }

//            dd($data);


            Session::put("ExcelData", $data );

            $data = [];

            return view('schools.score_excel_upload', $data);

        }
        else
        {
            return view('data_upload.excel_upload',
                [
                    'MyBreadCrumb' => '',
                    'Title' => 'EduApp Lagos',
                ]);
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function schools_save_scores(Request $request)
    {
        $method = $request->isMethod('post');
        if ($method)
        {
//            dd($request->all());

            $validator = Validator::make($request->all(), [

                'selected_score_list_index' => 'required|integer',
            ]);

            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }

            // Get Excel Data from Session Varibale
            // Loop through data and use school_id and student admission number to get userid and save score in database
            $FullExcelData = null;

            $selected_score_list_index = $request->selected_score_list_index;


            if(Session::has("ExcelData" ))
            {
                $FullExcelData =  Session::get("ExcelData" );

                // Get The data list based on the selected index choosen
                $selectedScoreList = $FullExcelData->get($selected_score_list_index);

                $school_object = Session::has("school_object") ? Session::get("school_object")  : "";
                $school_id = $school_object->id;


//                $subject_score_collection   =  Subjects::all();
////                dd($subject_score_collection, $selectedScoreList[0]);
//
//                $subject_mapped_collections =   $subject_score_collection->filter(function ($item, $key) use ($selectedScoreList)
//                {
//                    $slugged_subject_name = str_slug($item->subject, '_');
//                    $item->slugged_subject_name = $slugged_subject_name;
//
//                    if($selectedScoreList[0]->has($slugged_subject_name) )
//                    {
//                        $item->sluuged_subject_score = $selectedScoreList[0]->get($slugged_subject_name);
//                     return $item;
//                    }
//                    else
//                    {
//                        return false;
//                    }
//                });

//                dd($selectedScoreList[0]);

                $term_object = Session::has("term_object") ? Session::get("term_object")  : "";
                $term_id = $term_object->id;

                $StudentScoreUploadArray = [];
                $student_score_counter =  0;
                $subject_score_collection   = Subjects::all();

                switch ($school_object->school_type_id)  //  Check school_type_id
                {
                    case 1:/* This is for Junior Secondary School */

                        if( (
                             count($selectedScoreList) > 0 )
                            and $selectedScoreList[0]->has("admn_no")
                            and $selectedScoreList[0]->has("academic_year")
                            and $selectedScoreList[0]->has("class_level")
                            and $selectedScoreList[0]->has("subdivision")
                            and $selectedScoreList[0]->has("english")
                            and $selectedScoreList[0]->has("mathematics")
                            and $selectedScoreList[0]->has("basic_science_and_technology")
                            and $selectedScoreList[0]->has("french")
                            and $selectedScoreList[0]->has("yoruba")
                            and $selectedScoreList[0]->has("rel_val_edu")
                            and $selectedScoreList[0]->has("pre_vocational_studies")
                            and $selectedScoreList[0]->has("business_studies")
                            and $selectedScoreList[0]->has("cca")
                            and $selectedScoreList[0]->has("arabic")
                        )
                        {
                            ini_set('memory_limit', '-1');
                            ini_set('max_execution_time', 30000); //300 seconds = 5 minutes

                            //Loop through list
                            $loop_breaker = 1;
                            foreach( $selectedScoreList as $eachScoreDetail)
                            {
                                if($loop_breaker > 3)
                                {
                                    break;
                                }

                                $loop_breaker++;

//                                dd($eachScoreDetail);

                                $student_admission_no =  $eachScoreDetail->has('admn_no') ? $eachScoreDetail->get('admn_no') : null;
                                $student_name_surname_first =  $eachScoreDetail->has('student_name') ? $eachScoreDetail->get('student_name') : null;
                                $student_academic_year =  $eachScoreDetail->has('academic_year') ? $eachScoreDetail->get('academic_year') : null;
                                $student_school_id =  $school_id;
                                $student_term =  $term_object->id;
                                $student_class_level =  $eachScoreDetail->has('class_level') ? $eachScoreDetail->get('class_level') : null;

                                $classubdivision_array =
                                    [
                                        "a" => 1,
                                        "b" => 2,
                                        "c" => 3,
                                        "d" => 4,
                                        "e" => 5,
                                        "f" => 6,
                                        "g" => 7,
                                        "h" => 8,
                                        "i" => 9,
                                        "j" => 10,
                                        "k" => 11
                                    ];

                                /* Check Values of Class Sub division for correctness */
                                $student_subdivision =  $eachScoreDetail->has('subdivision') ? $eachScoreDetail->get('subdivision') : null;
                                $student_subdivision = strtolower($student_subdivision);
                                $student_division_id = array_key_exists($student_subdivision, $classubdivision_array )  ? $classubdivision_array[$student_subdivision] : null ;
                                if(is_null($student_division_id))
                                {
                                    // continue but dont execute any code further for this iteration
                                    //Record this student name and Admin_id
                                    $failureArray = [
                                        "student_registration_counter" =>  $student_score_counter,
                                        "student_name" => $student_name_surname_first ,
                                        "admn_no" => $student_admission_no,
                                        "school" => $school_object->school_name,
                                        "reason" => "The Class Subdivision of this student is invalid. This student was skipped."
                                    ];
                                    $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;
                                    continue;
                                }
                                /* Check Values of Class Sub division for correctness */

                                /* Check Values of Class Levels for correctness */

                                $class_level_array =
                                    [
                                        "jss1" => 1,
                                        "jss2" => 2,
                                        "jss3" => 3,
                                        "ss1" => 4,
                                        "ss2" => 5,
                                        "ss3" => 6
                                    ];

                                $student_class_level =  preg_replace('/\s+/', '', strtolower($student_class_level)) ;

                                $student_class_level_id = array_key_exists($student_class_level, $class_level_array )  ? $class_level_array[$student_class_level] : null;

                                if(is_null($student_class_level_id))
                                {
                                    // continue but don't execute any code further for this iteration
                                    //Record this student name and Admin_id
                                    $failureArray = [
                                        "student_registration_counter" =>  $student_score_counter,
                                        "student_name" => $student_name_surname_first ,
                                         "admn_no" => $student_admission_no,
                                        "school" => $school_object->school_name,
                                        "reason" => "The Student Class Level is invalid. This student was skipped."
                                    ];
                                    $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;

                                    continue;
                                }

                                /* Check Values of Class Levels for correctness */

                                /* Check Values of Academic Year for correctness */


                                $student_academic_year = (int)$student_academic_year;

                                $student_academic_year =  preg_replace('/\s+/', '', strtolower($student_academic_year)) ;

                                $student_academic_year_object =  AcademicYear::where("academic_year", $student_academic_year )->get();
                                $student_academic_year_id = null;
                                if(count($student_academic_year_object) > 0)
                                {
                                    $student_academic_year_id =  $student_academic_year_object->first()->id;
                                }


                                if(is_null($student_academic_year_id))
                                {
                                    // continue but dont execute any code further for this iteration
                                    //Record this student name and Admin_id
                                    $failureArray = [
                                        "student_registration_counter" =>  $student_score_counter,
                                        "student_name" => $student_name_surname_first ,
                                        "admn_no" => $student_admission_no,
                                        "school" => $school_object->school_name,
                                        "reason" => "The Academic Year is invalid. This student was skipped."
                                    ];
                                    $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;
                                    continue;
                                }

                                /* Check Values of Academic Year for correctness */

                                $student_registration_models =  null;
                                $student_registration_models =  StudentRegistration::where(
                                    [
                                        "admission_number" =>  $student_admission_no,
                                        "school_id" =>  $student_school_id
                                    ])->get();

//                                var_dump($student_registration_models);

                                if(!is_null($student_registration_models) and ( count($student_registration_models) > 0) )
                                {
                                    $student_registration_model =  $student_registration_models->first();

                                    // Build Collection based on filter function that is based on



                                    $subject_filtered_collections =   $subject_score_collection->filter(function ($item, $key) use ($eachScoreDetail)
                                    {
                                        $slugged_subject_name = str_slug($item->subject, '_');
                                        $item->slugged_subject_name = $slugged_subject_name;

                                        if($eachScoreDetail->has($slugged_subject_name) )
                                        {
                                            $item->slugged_subject_score = $eachScoreDetail->get($slugged_subject_name);
                                            return $item;
                                        }
                                        else
                                        {
                                            return false;
                                        }
                                    });

//                                    dd($eachScoreDetail, $subject_filtered_collections->toArray());

                                    foreach ( $subject_filtered_collections as $subject_filtered_collections_keys => $subject_filtered_collections_subject)
                                    {
                                        $student_score_model = null;
                                        try
                                        {
                                            $subject_filtered_collections_subject_array =  $subject_filtered_collections_subject->toArray();
//                                            dd( $r );
                                            // Query and get student_registration model and get student id
                                            // Build subject score using the id  builted and generated

                                            // Create Student Subject Score  Model
                                            $student_score_model =  new SubjectScore();

                                        $student_score_model->exam_score = array_key_exists( "slugged_subject_score" ,  $subject_filtered_collections_subject_array )  ? $subject_filtered_collections_subject_array["slugged_subject_score"]  : null; // Can be null;

                                            $student_score_model->subject_id =   array_key_exists( "subjectid" , $subject_filtered_collections_subject_array )  ? $subject_filtered_collections_subject_array["subjectid"]   :  0; // Cannot be null

                                            $student_score_model->school_id = $student_school_id;
                                            $student_score_model->student_registration_id = $student_registration_model->id;
                                            $student_score_model->class_level_id = $student_registration_model->class_level_id;
                                            $student_score_model->class_type_id = $student_registration_model->class_type_id;
                                            $student_score_model->class_subdivision_id = $student_registration_model->class_subdivision;
                                            $student_score_model->term_id = $term_id;
                                            $student_score_model->academic_year_id = $student_registration_model->academic_year;;
                                            $student_score_model->createdby = Auth::user()->id;

//                                            var_dump($student_score_model);
                                            $student_score_model->save();

                                            $successArray =
                                                [
                                                    "student_registration_counter" =>  $student_score_counter,
                                                    "student_name" => $student_name_surname_first ,
                                                    "admn_no" => $student_admission_no,
                                                    "school" => $school_object->school_name,
                                                    "reason" => "Student Score was successfully saved.",
                                                ];

                                            $StudentScoreUploadArray["Success"][$student_score_counter] = $successArray;
                                        }
                                        catch(\Exception $e)
                                        {
                                            Log::info($e->getMessage());

                                            if(!is_null($student_score_model))
                                            {
                                                $student_score_model->forceDelete();
                                            }

                                            // Record failure situation here and store in recon Array
                                            $failureArray = [
                                                "student_registration_counter" =>  $student_score_counter,
                                                "student_name" => $student_name_surname_first ,
                                                "admn_no" => $student_admission_no,
                                                "school" => $school_object->school_name,
                                                "reason" => "There was an error in saving Student Score for this subject. Please contact administrator.",
                                                "errorText" => $e->getMessage()
                                            ];

                                            $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;
                                        }
                                    }

                                }
                                else
                                {
                                    // Student does not exist in the database
                                    $failureArray =
                                        [
                                            "student_registration_counter" =>  $student_score_counter,
                                            "student_name" => $student_name_surname_first ,
                                            "admn_no" => $student_admission_no,
                                            "school" => $school_object->school_name,
                                            "reason" => "The Student in this school, $school_object->school_name, with admission number, $student_admission_no could not be found. Please register this student appropriately. Please also ensure the correct school is selected before score upload. "
                                        ];
                                    $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;
                                    continue;
                                }
                                $student_score_counter++;


                            }

//                            dd($StudentScoreUploadArray);

                            $request->session()->flash('success' , "Bulk student score upload is successful.");
                            $session_name = "StudentScoreExcelExportData";


                            Session::put($session_name, $StudentScoreUploadArray );
                            Session::put("session_name", $session_name );

                            $request->session()->flash('bulkOperationArray' , $StudentScoreUploadArray );

                            return view('schools.score_excel_upload',
                                [
                                    'MyBreadCrumb' => '',
                                    'Title' => 'EduApp Lagos',
                                ]);

                        }
                        else
                        {
                            // Complain that excel does not have subdivision and many other headings  heading and that it must be added
                            // continue but don't execute any code further, loop out an return error through session

                            $request->session()->flash('error' , "Bulk student score upload  was not done because  one or more headings are  missing. Please ensure the following heading:  
                        Admission Number, Student Name, SEX , 	ENGLISH, MATHEMATICS , BASIC SCIENCE AND TECHNOLOGY , FRENCH, YORUBA , REL & VAL EDU, PRE VOCATIONAL STUDIES, BUSINESS STUDIES, CCA, ARABIC, subdivision, Class Level, Academic Year ");

                            return view('schools.score_excel_upload',
                                [
                                    'MyBreadCrumb' => '',
                                    'Title' => 'EduApp Lagos',
                                ]);
                        }
                        break;
                    case 2: /* This is for Senior Secondary School */

                        dd("Process Score for Senior Secondary School");
                        if( (
                                count($selectedScoreList) > 0 )
                            and $selectedScoreList[0]->has("admn_no")
                            and $selectedScoreList[0]->has("academic_year")
                            and $selectedScoreList[0]->has("class_level")
                            and $selectedScoreList[0]->has("subdivision")
                            and $selectedScoreList[0]->has("english")
                            and $selectedScoreList[0]->has("mathematics")
                            and $selectedScoreList[0]->has("basic_science_and_technology")
                            and $selectedScoreList[0]->has("french")
                            and $selectedScoreList[0]->has("yoruba")
                            and $selectedScoreList[0]->has("rel_val_edu")
                            and $selectedScoreList[0]->has("pre_vocational_studies")
                            and $selectedScoreList[0]->has("business_studies")
                            and $selectedScoreList[0]->has("cca")
                            and $selectedScoreList[0]->has("arabic")
                        )
                        {
                            ini_set('memory_limit', '-1');
                            ini_set('max_execution_time', 30000); //300 seconds = 5 minutes

                            //Loop through list
                            $loop_breaker = 1;
                            foreach( $selectedScoreList as $eachScoreDetail)
                            {
                                if($loop_breaker > 3)
                                {
                                    break;
                                }

                                $loop_breaker++;

//                                dd($eachScoreDetail);

                                $student_admission_no =  $eachScoreDetail->has('admn_no') ? $eachScoreDetail->get('admn_no') : null;
                                $student_name_surname_first =  $eachScoreDetail->has('student_name') ? $eachScoreDetail->get('student_name') : null;
                                $student_academic_year =  $eachScoreDetail->has('academic_year') ? $eachScoreDetail->get('academic_year') : null;
                                $student_school_id =  $school_id;
                                $student_term =  $term_object->id;
                                $student_class_level =  $eachScoreDetail->has('class_level') ? $eachScoreDetail->get('class_level') : null;

                                $classubdivision_array =
                                    [
                                        "a" => 1,
                                        "b" => 2,
                                        "c" => 3,
                                        "d" => 4,
                                        "e" => 5,
                                        "f" => 6,
                                        "g" => 7,
                                        "h" => 8,
                                        "i" => 9,
                                        "j" => 10,
                                        "k" => 11
                                    ];

                                /* Check Values of Class Sub division for correctness */
                                $student_subdivision =  $eachScoreDetail->has('subdivision') ? $eachScoreDetail->get('subdivision') : null;
                                $student_subdivision = strtolower($student_subdivision);
                                $student_division_id = array_key_exists($student_subdivision, $classubdivision_array )  ? $classubdivision_array[$student_subdivision] : null ;
                                if(is_null($student_division_id))
                                {
                                    // continue but dont execute any code further for this iteration
                                    //Record this student name and Admin_id
                                    $failureArray = [
                                        "student_registration_counter" =>  $student_score_counter,
                                        "student_name" => $student_name_surname_first ,
                                        "admn_no" => $student_admission_no,
                                        "school" => $school_object->school_name,
                                        "reason" => "The Class Subdivision of this student is invalid. This student was skipped."
                                    ];
                                    $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;
                                    continue;
                                }
                                /* Check Values of Class Sub division for correctness */

                                /* Check Values of Class Levels for correctness */

                                $class_level_array =
                                    [
                                        "jss1" => 1,
                                        "jss2" => 2,
                                        "jss3" => 3,
                                        "ss1" => 4,
                                        "ss2" => 5,
                                        "ss3" => 6
                                    ];

                                $student_class_level =  preg_replace('/\s+/', '', strtolower($student_class_level)) ;

                                $student_class_level_id = array_key_exists($student_class_level, $class_level_array )  ? $class_level_array[$student_class_level] : null;

                                if(is_null($student_class_level_id))
                                {
                                    // continue but don't execute any code further for this iteration
                                    //Record this student name and Admin_id
                                    $failureArray = [
                                        "student_registration_counter" =>  $student_score_counter,
                                        "student_name" => $student_name_surname_first ,
                                        "admn_no" => $student_admission_no,
                                        "school" => $school_object->school_name,
                                        "reason" => "The Student Class Level is invalid. This student was skipped."
                                    ];
                                    $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;

                                    continue;
                                }

                                /* Check Values of Class Levels for correctness */

                                /* Check Values of Academic Year for correctness */


                                $student_academic_year = (int)$student_academic_year;

                                $student_academic_year =  preg_replace('/\s+/', '', strtolower($student_academic_year)) ;

                                $student_academic_year_object =  AcademicYear::where("academic_year", $student_academic_year )->get();
                                $student_academic_year_id = null;
                                if(count($student_academic_year_object) > 0)
                                {
                                    $student_academic_year_id =  $student_academic_year_object->first()->id;
                                }


                                if(is_null($student_academic_year_id))
                                {
                                    // continue but dont execute any code further for this iteration
                                    //Record this student name and Admin_id
                                    $failureArray = [
                                        "student_registration_counter" =>  $student_score_counter,
                                        "student_name" => $student_name_surname_first ,
                                        "admn_no" => $student_admission_no,
                                        "school" => $school_object->school_name,
                                        "reason" => "The Academic Year is invalid. This student was skipped."
                                    ];
                                    $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;
                                    continue;
                                }

                                /* Check Values of Academic Year for correctness */

                                $student_registration_models =  null;
                                $student_registration_models =  StudentRegistration::where(
                                    [
                                        "admission_number" =>  $student_admission_no,
                                        "school_id" =>  $student_school_id
                                    ])->get();

//                                var_dump($student_registration_models);

                                if(!is_null($student_registration_models) and ( count($student_registration_models) > 0) )
                                {
                                    $student_registration_model =  $student_registration_models->first();

                                    // Build Collection based on filter function that is based on



                                    $subject_filtered_collections =   $subject_score_collection->filter(function ($item, $key) use ($eachScoreDetail)
                                    {
                                        $slugged_subject_name = str_slug($item->subject, '_');
                                        $item->slugged_subject_name = $slugged_subject_name;

                                        if($eachScoreDetail->has($slugged_subject_name) )
                                        {
                                            $item->slugged_subject_score = $eachScoreDetail->get($slugged_subject_name);
                                            return $item;
                                        }
                                        else
                                        {
                                            return false;
                                        }
                                    });

//                                    dd($eachScoreDetail, $subject_filtered_collections->toArray());

                                    foreach ( $subject_filtered_collections as $subject_filtered_collections_keys => $subject_filtered_collections_subject)
                                    {
                                        $student_score_model = null;
                                        try
                                        {
                                            $subject_filtered_collections_subject_array =  $subject_filtered_collections_subject->toArray();
//                                            dd( $r );
                                            // Query and get student_registration model and get student id
                                            // Build subject score using the id  builted and generated

                                            // Create Student Subject Score  Model
                                            $student_score_model =  new SubjectScore();

                                            $student_score_model->exam_score = array_key_exists( "slugged_subject_score" ,  $subject_filtered_collections_subject_array )  ? $subject_filtered_collections_subject_array["slugged_subject_score"]  : null; // Can be null;

                                            $student_score_model->subject_id =   array_key_exists( "subjectid" , $subject_filtered_collections_subject_array )  ? $subject_filtered_collections_subject_array["subjectid"]   :  0; // Cannot be null

                                            $student_score_model->school_id = $student_school_id;
                                            $student_score_model->student_registration_id = $student_registration_model->id;
                                            $student_score_model->class_level_id = $student_registration_model->class_level_id;
                                            $student_score_model->class_type_id = $student_registration_model->class_type_id;
                                            $student_score_model->class_subdivision_id = $student_registration_model->class_subdivision;
                                            $student_score_model->term_id = $term_id;
                                            $student_score_model->academic_year_id = $student_registration_model->academic_year;;
                                            $student_score_model->createdby = Auth::user()->id;

//                                            var_dump($student_score_model);
                                            $student_score_model->save();

                                            $successArray =
                                                [
                                                    "student_registration_counter" =>  $student_score_counter,
                                                    "student_name" => $student_name_surname_first ,
                                                    "admn_no" => $student_admission_no,
                                                    "school" => $school_object->school_name,
                                                    "reason" => "Student Score was successfully saved.",
                                                ];

                                            $StudentScoreUploadArray["Success"][$student_score_counter] = $successArray;
                                        }
                                        catch(\Exception $e)
                                        {
                                            Log::info($e->getMessage());

                                            if(!is_null($student_score_model))
                                            {
                                                $student_score_model->forceDelete();
                                            }

                                            // Record failure situation here and store in recon Array
                                            $failureArray = [
                                                "student_registration_counter" =>  $student_score_counter,
                                                "student_name" => $student_name_surname_first ,
                                                "admn_no" => $student_admission_no,
                                                "school" => $school_object->school_name,
                                                "reason" => "There was an error in saving Student Score for this subject. Please contact administrator.",
                                                "errorText" => $e->getMessage()
                                            ];

                                            $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;
                                        }
                                    }

                                }
                                else
                                {
                                    // Student does not exist in the database
                                    $failureArray =
                                        [
                                            "student_registration_counter" =>  $student_score_counter,
                                            "student_name" => $student_name_surname_first ,
                                            "admn_no" => $student_admission_no,
                                            "school" => $school_object->school_name,
                                            "reason" => "The Student in this school, $school_object->school_name, with admission number, $student_admission_no could not be found. Please register this student appropriately. Please also ensure the correct school is selected before score upload. "
                                        ];
                                    $StudentScoreUploadArray["Failure"][$student_score_counter] = $failureArray;
                                    continue;
                                }
                                $student_score_counter++;


                            }

//                            dd($StudentScoreUploadArray);

                            $request->session()->flash('success' , "Bulk student score upload is successful.");
                            $session_name = "StudentScoreExcelExportData";


                            Session::put($session_name, $StudentScoreUploadArray );
                            Session::put("session_name", $session_name );

                            $request->session()->flash('bulkOperationArray' , $StudentScoreUploadArray );

                            return view('schools.score_excel_upload',
                                [
                                    'MyBreadCrumb' => '',
                                    'Title' => 'EduApp Lagos',
                                ]);

                        }
                        else
                        {
                            // Complain that excel does not have subdivision and many other headings  heading and that it must be added
                            // continue but don't execute any code further, loop out an return error through session

                            $request->session()->flash('error' , "Bulk student score upload  was not done because  one or more headings are  missing. Please ensure the following heading:  
                        Admission Number, Student Name, SEX , 	ENGLISH, MATHEMATICS , BASIC SCIENCE AND TECHNOLOGY , FRENCH, YORUBA , REL & VAL EDU, PRE VOCATIONAL STUDIES, BUSINESS STUDIES, CCA, ARABIC, subdivision, Class Level, Academic Year ");

                            return view('schools.score_excel_upload',
                                [
                                    'MyBreadCrumb' => '',
                                    'Title' => 'EduApp Lagos',
                                ]);
                        }
                        break;
                }

                $data = [];

                return view('schools.score_excel_upload', $data);

            }
            else
            {
                // Return with error that excel data does not exist
                $request->session()->flash('error' , "Student Score Upload  Data is unavailable. Please try again.  ");

                return view('schools.score_excel_upload',
                    [
                        'MyBreadCrumb' => '',
                        'Title' => 'EduApp Lagos',
                    ]);
            }

        }
        else
        {
            return view('data_upload.excel_upload',
                [
                    'MyBreadCrumb' => '',
                    'Title' => 'EduApp Lagos',
                ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function schools_options(Request $request)
    {
        $method = $request->isMethod('post');
        if ($method)
        {
//            dd($request->all());

            $validator = Validator::make($request->all(), [

                'selected_score_list_index' => 'required|integer',
            ]);

            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }

            // Get Excel Data from Session Varibale
            // Loop through data and use school_id and student admission number to get userid and save score in database
            $FullExcelData = null;

            $selected_score_list_index = $request->selected_score_list_index;

            $academic_year_object = Session::get("academic_year_object");

            $term_object = Session::get("term_object");

            $class_level_object = Session::get("class_level_object");

            $school_object = Session::get("school_object");

            Log::info(json_encode($school_object) . " ". json_encode($term_object) );

//            $academic_year_object = Session::get("academic_year_object");
//
//            Session::put("academic_year_object", $this_academic_year);
//
//            Session::put("term_object", $this_term);
//
//            Session::put("class_level_object", $this_class_level);
//
//            Session::put("school_object", $this_school);

            if(Session::has("ExcelData" ))
            {
                $FullExcelData =  Session::get("ExcelData" );

                // Get The data list based on the selected index choosen

                $selectedScoreList = $FullExcelData->get($selected_score_list_index);
//                dd($selectedScoreList);

                //Loop through list

//                "admn_no" => "4496"
//                "name_surname_first" => "Olasunkanmi Waliat Omowunmi"
//                "spin" => null
//                "sex" => "F"
//                "english" => "71"
//                "mathematics" => "99"
//                "basic_sc_tech" => "79"
//                "french" => "83"
//                "yoruba" => "58"
//                "rel_val_edu" => "85"
//                "pre_voc_std" => "87"
//                "business_studies" => "72"
//                "cca" => "81"
//                "arabic" => null
//                "no_of_sub" => "9"
//                "total_marks" => "715"
//                "percentage" => 79.4
//                "position" => "1ST"
//                "remarks" => "PASSED"
//
//                $academic_year_object = Session::get("academic_year_object");
//
//                $term_object = Session::get("term_object");
//
//                $class_level_object = Session::get("class_level_object");
//
//                $school_object = Session::get("school_object");

                $school_id = $school_object->id;
                $academic_year = $academic_year_object->id;


                foreach( $selectedScoreList as $eachScoreDetail)
                {
                    dd($eachScoreDetail);
                    // Check first that collection has district_no
                    //if true, use district_no to get student/user model
                    //if false, use school_id and admn_no to get student /user model

                    if($eachScoreDetail->has('district_no') )
                    {
                        //if true, use district_no to get student/user model
                        // Get student/user model usinf district no
                        $student_district_no =  $eachScoreDetail->get('district_no');

                        // Query to get student/user model or id

                        // Build Student score and save to score table

                    }
                    else
                    {
                        //if false, use school_id and admn_no to get student /user model
                        dd($eachScoreDetail);
                        $student_admission_number = $eachScoreDetail->has('admn_no') ? $eachScoreDetail->get('admn_no') : null;

                        $student_full_name = $eachScoreDetail->has('name_surname_first') ? $eachScoreDetail->get('name_surname_first') : null;

                        // Get Student detail from users, student table

                        // Query to get student/user model or id

                        // Build Student score and save to score table



                    }

                }

            }
            else
            {
                // Return with error that excel data does not exist

            }

            $data = [];

            return view('schools.score_excel_upload', $data);

        }
        else
        {

            return view('schools.schools_options',
                [
                    'MyBreadCrumb' => '',
                    'Title' => 'EduApp Lagos',
                ]);
        }

    }

    public function schools_reports(Request $request)
    {
        $method = $request->isMethod('post');
        if ($method)
        {
            dd($request->all());
            // post value for school modal
            // If academic year, term, class subdivision and class level exists
            // then redirect to new view that show excel to be uploaded for student scores

            Session::forget("ExcelData");

            $validator = Validator::make($request->all(),
                [
                    'academic_year_value' => 'required|integer',
                    'term_value' => 'required|integer',
                    'class_level_value' => 'required|integer',
                    'class_subdivision_value' => 'required|integer',
                    'school_value' => 'required|integer'

                ]);

            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }

            $academic_year = $request->academic_year_value;
            $term_value = $request->term_value;
            $class_level_value = $request->class_level_value;
            $class_subdivision_value = $request->class_subdivision_value;
            $school_value = $request->school_value;

//                $school_value = 869669959;
                dd($academic_year, $term_value, $class_level_value, $class_subdivision_value  );


            $this_class_subdivision = ClassSubdivisions::find($class_subdivision_value);
            if(is_null($this_class_subdivision)  )
            {
                $request->session()->flash('error' , "The class subdivision chosen is not valid.");
                return back();
            }

            $this_school = School::find($school_value);
            if(is_null($this_school)  )
            {
                $request->session()->flash('error' , "The school chosen is not valid.");
                return back();
            }

            $this_academic_year = AcademicYear::find($academic_year);
            if(is_null($this_academic_year)  )
            {
                $request->session()->flash('error' , "The academic year chosen is not valid.");
                return back();
            }

            $this_term = Terms::find($term_value);
            if(is_null($this_term)  )
            {
                $request->session()->flash('error' , "The term chosen is not valid.");
                return back();
            }

            $this_class_level = ClassLevels::find($class_level_value);
            if(is_null($this_class_level)  )
            {
                $request->session()->flash('error' , "The class level chosen is not valid.");
                return back();
            }


            // Save Models in Session Variables and open next page , Excel upload page

            Session::put("academic_year_object", $this_academic_year);

            Session::put("term_object", $this_term);

            Session::put("class_level_object", $this_class_level);

            Session::put("class_subdivision_object", $this_class_subdivision);

            Session::put("school_object", $this_school);

            $data = [];

            return view('schools.school_registration_upload', $data);
        }
        else
        {

            $all_schools = School::all();

            $academic_year = AcademicYear::all();

            $terms = Terms::all();

            $classlevels = ClassLevels::all();

            $class_subdivisions = ClassSubdivisions::all();

            $data['all_schools'] = $all_schools->toArray();

            $data['academic_years'] = $academic_year;

            $data['terms'] = $terms;

            $data['classlevels'] = $classlevels;

            $data['class_subdivisions'] = $class_subdivisions;

            $data['Title'] = 'Edu App | School Management Software';

//            dd($data);

            return view('schools.report_schools', $data);
        }

    }

    public function schools_registration_excel_upload(Request $request, $school_id = 0)
    {
        $method = $request->isMethod('post');
        if ($method)
        {
            Session::forget("ExcelData" );

            $validator = Validator::make($request->all(), [

                'bulk_excel_upload' => 'required|file',
            ]);

            if ($validator->fails())
            {
                return back()->withErrors($validator)->withInput();
            }

            $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv");

            $result = array($request->file('bulk_excel_upload')->getClientOriginalExtension());

            if (!in_array($result[0], $extensions))
            {
                $request->session()->flash('error', 'You must upload an excel file.');

                return back()->withErrors($validator)->withInput();
            }


            $file_name = $request->file('bulk_excel_upload')->getClientOriginalName();

            $destinationPath = storage_path('app/uploads');

//            $request->file('bulk_excel_upload')->move($destinationPath, $file_name);

//            dd($request->file('bulk_excel_upload'));

            //File is Uploaded , now send file to csv_to_array

            $FullFilePath = $destinationPath."/".$file_name;

            $data = [];

            if ($request->hasFile('bulk_excel_upload'))
            {
                $path = $request->file('bulk_excel_upload')->getRealPath();

                $data  = Excel::toCollection(new ExcelImport, request()->file('bulk_excel_upload'));
            }

//            dd($data);


            Session::put("ExcelData", $data );

            $data = [];

            return view('schools.registration_excel_upload', $data);

        }
        else
        {
            Session::forget("ExcelData" );

            $this_school = School::find($school_id);

            if(is_null($this_school)  )
            {
                $request->session()->flash('error' , "The school chosen is not valid.");
                return back();
            }

            Session::put("school_object", $this_school);
            Session::forget("StudentRegistrationExcelExportData" );
            Session::forget("session_name" );

            return view('schools.registration_excel_upload',
                [
                    'MyBreadCrumb' => '',
                    'Title' => 'EduApp Lagos',
                ]);
        }

    }

    public function schools_registration_save_students(Request $request)
    {
        $method = $request->isMethod('post');
        if ($method)
        {
//            dd($request->all());

            $validator = Validator::make($request->all(), [

                'selected_registration_list_index' => 'required|integer',
            ]);

            if ($validator->fails())
            {
//                dd($validator->errors());
                return back()->withErrors($validator)->withInput();
            }

            // Get Excel Data from Session Varibale
            // Loop through data and use school_id and student admission number to get userid and save score in database
            $FullExcelData = null;

            $selected_registration_list_index = $request->selected_registration_list_index;

//            $academic_year_object = Session::get("academic_year_object");

//            $term_object = Session::get("term_object");

//            $class_level_object = Session::get("class_level_object");

//            $school_object = Session::get("school_object");

//            Log::info(json_encode($school_object) . " ". json_encode($term_object) );

//            $academic_year_object = Session::get("academic_year_object");
//
//            Session::put("academic_year_object", $this_academic_year);
//
//            Session::put("term_object", $this_term);
//
//            Session::put("class_level_object", $this_class_level);
//
//            Session::put("school_object", $this_school);

            if(Session::has("ExcelData" ))
            {
                $FullExcelData =  Session::get("ExcelData" );

                // Get The data list based on the selected index choosen

                $selectedRegistrationList = $FullExcelData->get($selected_registration_list_index);
//                dd($selectedRegistrationList);

                //Loop through list

//
//                $academic_year_object = Session::get("academic_year_object");

//                $term_object = Session::get("term_object");

                $class_level_object = Session::get("class_level_object");

                $school_object = Session::has("school_object") ? Session::get("school_object")  : "";

                $school_id = $school_object->id;

//                $academic_year = $academic_year_object->id;

                // Test Run And Check that Excel sheet has Sub division, or else bypass bulk student registration and complain

                //Get first student as a sample
//                dd(count($selectedRegistrationList));

                $StudentRegistrationArray = [];
                $student_registration_counter =  0;

                if( (count($selectedRegistrationList) > 0 )
                    and $selectedRegistrationList[0]->has("subdivision")
                    and $selectedRegistrationList[0]->has("sex")
                    and $selectedRegistrationList[0]->has("class_level")
                    and $selectedRegistrationList[0]->has("academic_year")
                    and $selectedRegistrationList[0]->has("admn_no")
                    and $selectedRegistrationList[0]->has("student_name")
                )
                {
                    ini_set('memory_limit', '-1');
                    ini_set('max_execution_time', 30000); //300 seconds = 5 minutes


                    $loop_breaker = 1;
                    foreach( $selectedRegistrationList as $eachRegisteredStudent)
                    {
//                        dd($eachRegisteredStudent);
                        // Check first that collection has district_no
                        //if true, use district_no to save student details including other details
                        //if false, use school_id and admn_no to save student details. Also, generated an alphanumeric 5 digit code for district number for the students

//                             "admn_no" => "4485"
//                                "student_name" => "Paul-Maduka Faith Obianuju"
//                                "spin" => null
//                                "sex" => "F"
//                                "subdivision" => "A"
//                                "class_level" => "JSS1"
//                                "academic_year" => 2019.0


//                        if($loop_breaker > 10)
//                        {
//                            break;
//                        }

                        $loop_breaker++;

                        if($eachRegisteredStudent->has('district_no') )
                        {
                            //if true, use district_no to get student/user model
                            $student_district_no =  $eachRegisteredStudent->get('district_no');
                        }
                        else
                        {
                            //if false, auto generate student district ID
                            $random_alphanum_array = Utilities::getUniqueFixedCharacters(5, ["district_number" => "students"]);
                            $student_district_no =   $random_alphanum_array["generated_character"];

                        }

                        $student_admission_no =  $eachRegisteredStudent->has('admn_no') ? $eachRegisteredStudent->get('admn_no') : null;
                        $student_spin_no =  $eachRegisteredStudent->has('spin') ? $eachRegisteredStudent->get('spin') : null;
                        $student_sex =  $eachRegisteredStudent->has('sex') ? $eachRegisteredStudent->get('sex') : null;
                        $student_name_surname_first =  $eachRegisteredStudent->has('student_name') ? $eachRegisteredStudent->get('student_name') : null;

//                        $student_academic_year =  $academic_year;
                        $student_academic_year =  $eachRegisteredStudent->has('academic_year') ? $eachRegisteredStudent->get('academic_year') : null;
                        $student_school_id =  $school_id;
//                        $student_term =  $term_object->id;
                        $student_class_level =  $eachRegisteredStudent->has('class_level') ? $eachRegisteredStudent->get('class_level') : null;

                        $classubdivision_array =
                            [
                                "a" => 1,
                                "b" => 2,
                                "c" => 3,
                                "d" => 4,
                                "e" => 5,
                                "f" => 6,
                                "g" => 7,
                                "h" => 8,
                                "i" => 9,
                                "j" => 10,
                                "k" => 11
                            ];

                        /* Check Values of Class Sub division for correctness */
                                $student_subdivision =  $eachRegisteredStudent->has('subdivision') ? $eachRegisteredStudent->get('subdivision') : null;

                                $student_subdivision = strtolower($student_subdivision);

                                $student_division_id = array_key_exists($student_subdivision, $classubdivision_array )  ? $classubdivision_array[$student_subdivision] : null ;

                                if(is_null($student_division_id))
                                {
                                    // continue but dont execute any code further for this iteration
                                    //Record this student name and Admin_id
                                    $failureArray = [
                                                     "student_registration_counter" =>  $student_registration_counter,
                                                     "student_name" => $student_name_surname_first ,
                                                     "admn_no" => $student_admission_no,
                                                     "school" => $school_object->school_name,
                                                     "reason" => "The Class Subdivision is invalid. This student was skipped."
                                                   ];
                                    $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;
                                    continue;
                                }
                        /* Check Values of Class Sub division for correctness */


                        /* Check Values of Class Levels for correctness */

                        $class_level_array =
                                            [
                                                "jss1" => 1,
                                                "jss2" => 2,
                                                "jss3" => 3,
                                                "ss1" => 4,
                                                "ss2" => 5,
                                                "ss3" => 6
                                            ];

                        $student_class_level =  preg_replace('/\s+/', '', strtolower($student_class_level)) ;


                        $student_class_level_id = array_key_exists($student_class_level, $class_level_array )  ? $class_level_array[$student_class_level] : null;

//                        dd($student_class_level, $student_class_level_id);


                        if(is_null($student_class_level_id))
                        {
                            // continue but don't execute any code further for this iteration
                            //Record this student name and Admin_id
                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "The Student Class Level is invalid. This student was skipped."
                            ];
                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;

                            continue;
                        }

                        /* Check Values of Class Levels for correctness */

                        /* Check Values of Academic Year for correctness */


                        $student_academic_year = (int)$student_academic_year;

                        $student_academic_year =  preg_replace('/\s+/', '', strtolower($student_academic_year)) ;

                        $student_academic_year_object =  AcademicYear::where("academic_year", $student_academic_year )->get();
                        $student_academic_year_id = null;
                        if(count($student_academic_year_object) > 0)
                        {
                            $student_academic_year_id =  $student_academic_year_object->first()->id;
                        }
                        else
                        {
                            $student_academic_year_id = null;
                        }

//                        dd($student_academic_year, $student_academic_year_id);


                        if(is_null($student_academic_year_id))
                        {
                            // continue but dont execute any code further for this iteration
                            //Record this student name and Admin_id
                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "The Academic Year is invalid. This student was skipped."
                            ];
                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;
                            continue;
                        }

                        /* Check Values of Academic Year for correctness */


                        // Build Student Model and Users Model and populate Student , Users and Student Registration Tables
                        $user_object = null;
                        $student_model = null;
                        $student_registration = null;
                        try
                        {
                            $studentNameArray = $this->extractName($student_name_surname_first);
                            $builtStudentEmail = $this->buildEmail($studentNameArray, $student_district_no);
                            $raw_password = Utilities::create_random_name(20);
                            $userInsertStudentArrayCount = count($studentNameArray);
                            $studentSex = strtolower($student_sex) == "m" ? "Male" : "Female";

                            $surname =   $userInsertStudentArrayCount > 0 ?  $studentNameArray[0] : " ";
                            $firstname =   $userInsertStudentArrayCount > 1 ?  $studentNameArray[1] : " ";
                            $middlename =   $userInsertStudentArrayCount > 2 ?  $studentNameArray[2] : " ";

                            $user_object = new Users();

                            $user_object->useremail = $builtStudentEmail;
                            $user_object->firstname = $firstname;
                            $user_object->middlename = $middlename;
                            $user_object->surname = $surname;
                            $user_object->activated =  1; // Activated by default
                            $user_object->password =  bcrypt($raw_password);
                            $user_object->sex = $studentSex;

//                            dd($user_object);
                            $user_object->save();

                            // Create Student Model

                            $student_model = new Students();

                            $student_model->userid =   $user_object->id;
                            $student_model->district_number =  $student_district_no;
                            $student_model->spin =  $student_spin_no;

//                            dd($student_model);
                            $student_model->save();

                            $student_registration =  new StudentRegistration();
                            $student_registration->student_id = $student_model->id;
                            $student_registration->academic_year = $student_academic_year_id;
                            $student_registration->school_id = $student_school_id;
                            $student_registration->class_level_id = $student_class_level_id;
                            $student_registration->class_type_id = null;
                            $student_registration->class_subdivision = $student_division_id;
                            $student_registration->admission_number = $student_admission_no;

//                            dd($student_registration);
                            $student_registration->save();

                            $successArray = [
                                                "student_registration_counter" =>  $student_registration_counter,
                                                "student_name" => $student_name_surname_first ,
                                                "admn_no" => $student_admission_no,
                                                "school" => $school_object->school_name,
                                                "reason" => "Student details was successfully saved.",

                            ];

                            $StudentRegistrationArray["Success"][$student_registration_counter] = $successArray;
                        }
                        catch(\Exception $e)
                        {
                            Log::info($e->getMessage());

                            if(!is_null($student_registration))
                            {
                                $student_registration->forceDelete();
                            }

                            if(!is_null($student_model))
                            {
                                $student_model->forceDelete();
                            }

                            if(!is_null($user_object))
                            {
                                $user_object->forceDelete();
                            }

                            // Record failure situation here and store in recon Array

                            $failureArray = [
                                                "student_registration_counter" =>  $student_registration_counter,
                                                "student_name" => $student_name_surname_first ,
                                                "admn_no" => $student_admission_no,
                                                "school" => $school_object->school_name,
                                                "reason" => "There was an error in saving User, Student or Student Registration information. Please contact administrator.",
                                                "errorText" => $e->getMessage()
                            ];

                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;

                            $StudentRegistrationArray["Success"][$student_registration_counter] = $failureArray;
                        }

                        $student_registration_counter++;

                    }

//                     dd("End of loop" );
                    // Build Response Object and return

                    $request->session()->flash('success' , "Bulk student registration is successful.");
                    $session_name = "StudentRegistrationExcelExportData";



                    Session::put("StudentRegistrationExcelExportData", $StudentRegistrationArray );
                    Session::put("session_name", $session_name );

                    $request->session()->flash('bulkOperationArray' , $StudentRegistrationArray );

                    return view('schools.registration_excel_upload',
                        [
                            'MyBreadCrumb' => '',
                            'Title' => 'EduApp Lagos',
                        ]);

//                    return back()->withInput();
                }
                else
                {
                    // Complain that excel does not have subdivision and many other headings  heading and that it must be added
                    // continue but don't execute any code further, loop out an return error through session
//                    dd("An important header is missing " );

                    $request->session()->flash('error' , "Bulk student registration was not done because an important heading was missing. Please ensure the following heading:  Student name, Admission Number, Sex, Class level, Academic Year, Sub Division.");

                    return view('schools.registration_excel_upload',
                        [
                            'MyBreadCrumb' => '',
                            'Title' => 'EduApp Lagos',
                        ]);

//                    return back()->withInput();
                }
                
            }
            else
            {
                // Return with error that excel data does not exist
//                dd("No data choosen or index is wrong" );

                $request->session()->flash('error' , "Student Registration Data is unavailable. Please try again.  ");

                return view('schools.registration_excel_upload',
                    [
                        'MyBreadCrumb' => '',
                        'Title' => 'EduApp Lagos',
                    ]);

//                return back()->withInput();
            }

        }
        else
        {
            return view('school.registration_excel_upload',
                [
                    'MyBreadCrumb' => '',
                    'Title' => 'EduApp Lagos',
                ]);
        }

        }

    private function downloadOperation($student_name_array, $district_number)
    {
        // Check that array count
        // Loop and concatentae first 3 character of each names element
        // append first three letter of district number
        //else generate 7 character alphanum and return

        $extracted_email_name = "";
        $array_count = count($student_name_array);
        if( $array_count >  0)
        {

            foreach ($student_name_array as $key =>  $each_name)
            {
                if($key != ($array_count - 1 ) )
                {
                    $extracted_email_name .=  strtolower(substr($each_name , 0, 3)   . ".") ;
                }
                else
                {
                    $extracted_email_name .=  strtolower(substr($each_name , 0, 3));
                }
            }

            // concatenate  extracted name to district number
            $extracted_email = $extracted_email_name . "." . strtolower($district_number ) ."@eduapp.com";
//            dd($extracted_email);
        }
        else
        {
            // generate 7 character alphanum and concatenate to email ending
            $extracted_email = $district_number  . "@eduapp.com";

        }

//        dd($extracted_email);
        return  $extracted_email;
    }

    private function buildEmail($student_name_array, $district_number)
    {
        // Check that array count
        // Loop and concatentae first 3 character of each names element
        // append first three letter of district number
        //else generate 7 character alphanum and return

        $extracted_email_name = "";
        $array_count = count($student_name_array);
        if( $array_count >  0)
        {

            foreach ($student_name_array as $key =>  $each_name)
            {
                if($key != ($array_count - 1 ) )
                {
                    $extracted_email_name .=  strtolower(substr($each_name , 0, 3)   . ".") ;
                }
                else
                {
                    $extracted_email_name .=  strtolower(substr($each_name , 0, 3));
                }
            }

            // concatenate  extracted name to district number
            $extracted_email = $extracted_email_name . "." . strtolower($district_number ) ."@eduapp.com";
//            dd($extracted_email);
        }
        else
        {
            // generate 7 character alphanum and concatenate to email ending
            $extracted_email = $district_number  . "@eduapp.com";

        }

//        dd($extracted_email);
        return  $extracted_email;
    }

    private function extractName($studentName)
    {
        // Get student name and check that name is not empty
        //separate student name based on space and generate array

         if(!empty($studentName) and strlen($studentName) > 0  )
         {
             $student_name_array = explode(' ', $studentName  );
             //             dd($student_name_array);
             return $student_name_array;

         }
         else
         {
             return  [];

         }

    }

    public function school_report_download(Request $request)
    {
        // Authorization based , Only admin can do CRUD.
        // Switch case to different route based on role or permission assessment
        // Show all schools and include appropriate utility action links
        //  Links to schools and show modal on mode of score upload

        $method = $request->isMethod('get');
        switch ($method) {
            case true:

                $all_schools = School::all();

                $academic_year = AcademicYear::all();

                $terms = Terms::all();

                $classlevels = ClassLevels::all();

                $class_subdivisions = ClassSubdivisions::all();

                $data['all_schools'] = $all_schools->toArray();

                $data['academic_years'] = $academic_year;

                $data['terms'] = $terms;

                $data['classlevels'] = $classlevels;

                $data['class_subdivisions'] = $class_subdivisions;

                $data['Title'] = 'Edu App | School Management Software';

                //        dd($data);
                return view('schools.schools', $data);

                break;
            case false:
                // post value for school modal
                // If academic year, term and class level exists
                // the redirect to new view that show excel to be uploaded for student scores

//                dd($request->all());
//                Session::forget("ExcelData");

                $validator = Validator::make($request->all(),
                    [
                    'academic_year_value' => 'required|integer',
                        'term_value' => 'required|integer',
                    'class_level_value' => 'required|integer',
                        'school_value' => 'required|integer'

                    ]);

                if ($validator->fails())
                {
                    return back()->withErrors($validator)->withInput();
                }

                $academic_year = $request->academic_year_value;
                $term_value = $request->term_value;
                $class_level_value = $request->class_level_value;
                $school_value = $request->school_value;

//                $school_value = 869669959;
//                dd($academic_year, $term_value, $class_level_value , $school_value  );

                $this_school = School::find($school_value);
                if(is_null($this_school)  )
                {
                    $request->session()->flash('error' , "The school chosen is not valid.");
                    return back();
                }


                $this_academic_year = AcademicYear::find($academic_year);
                if(is_null($this_academic_year)  )
                {
                    $request->session()->flash('error' , "The academic year chosen is not valid.");
                    return back();
                }

                $this_term = Terms::find($term_value);
                if(is_null($this_term)  )
                {
                    $request->session()->flash('error' , "The term chosen is not valid.");
                    return back();
                }

                $this_class_level = ClassLevels::find($class_level_value);
                if(is_null($this_class_level)  )
                {
                    $request->session()->flash('error' , "The class level chosen is not valid.");
                    return back();
                }


                // Save Models in Session Variables and open next page , Excel upload page

                Session::put("academic_year_object", $this_academic_year);

                Session::put("term_object", $this_term);

                Session::put("class_level_object", $this_class_level);

                Session::put("school_object", $this_school);

                // Use the Values Sent here to Build Tables

            $whereQueryArray =

                [
                    'ss.academic_year_id' => $this_academic_year->id,
                    'ss.school_id' => $this_school->id,
                    'ss.term_id' => $this_term->id,
                    'ss.class_level_id' => $this_class_level->id
                ];

//            dd($whereQueryArray);
                $score_data =  DB::table("subject_score as ss")
                               ->join('class_subdivisions as csb', 'csb.id', '=', 'ss.class_subdivision_id')
//                               ->join('academic_years as acy', 'acy.id', '=', 'ss.academic_year_id')
                                ->join('class_levels as cls', 'cls.id', '=', 'ss.class_level_id')
                               ->join('subjects as sbj', 'sbj.subjectid', '=', 'ss.subject_id')
                                ->join('student_registration as srg', 'srg.id', '=', 'ss.student_registration_id')
                    ->join('students as std', 'std.id', '=', 'srg.student_id')
                    ->join('users as usr', 'usr.id', '=', 'std.userid')
                      ->groupBy([ 'ss.class_subdivision_id' , 'ss.student_registration_id', 'ss.subject_id'])

                                ->where($whereQueryArray)
                                ->selectRaw('ss.*, ss.student_registration_id as group_student_registration_id, csb.class_subdivision as class_subdivision_name, cls.class_level  as student_class_level, srg.admission_number as student_admission_number, usr.surname as student_surname, usr.middlename as student_middlename, usr.firstname as student_firstname, sbj.subject as student_subject, srg.admission_number as student_admission_number' )
                                ->get();
//                dd($score_data);


                $score_grouped_class_subdivision = collect();
                $score_data_grouped =  collect();
                if(count ($score_data) > 0)
                {
                    $score_data_grouped = $score_data->groupBy([
                        'class_subdivision_name',
                        'group_student_registration_id',
                        function ($item)
                        {
                            return  str_slug($item->student_subject, "_");
                        },
//                        'student_subject',
                    ]);

                    $score_grouped_class_subdivision = $score_data_grouped->keys();

//                    dd($score_grouped_class_subdivision);
//                    dd($score_data_grouped);

                      Session::put("StudentScoreResultData", $score_data_grouped);
                }
                else
                {
                    // The query above return no values

                }



                $data["score_grouped_class_subdivision"] =  $score_grouped_class_subdivision;
                $data["score_data_grouped"] =  $score_data_grouped;

                return view('schools.score_download_upload', $data);

                break;
        }

    }

    public function student_score_download_pdf(Request $request, $student_registration_id, $class_subdivision_loop_key)
    {
        $method = $request->isMethod('get');
        if ($method)
        {
//            dd($request->all(), $student_registration_id ,  $class_subdivision_loop_key);

//            $validator = Validator::make($request->a, [
//
//                'class_subdivision_loop_key' => 'required|integer',
//            ]);
//
//            if ($validator->fails())
//            {
////                dd($validator->errors());
//                return back()->withErrors($validator)->withInput();
//            }

//            dd(Session::get("academic_year_object"));

            $term_object = Session::has("term_object") ? Session::get("term_object") : null;
            $school_object = Session::has("school_object") ? Session::get("school_object") : null;

            $academic_year_object = Session::has("academic_year_object") ? Session::get("academic_year_object") : null;

            // Get Excel Data from Session Varibale
            // Loop through data and use school_id and student admission number to get userid and save score in database
            $FullExcelData =  Session::has("StudentScoreResultData" ) ? Session::get("StudentScoreResultData" ) : null ;

            $selected_registration_list_index = $class_subdivision_loop_key;
            $selected_student_registration_index = $student_registration_id;

            if(!is_null($FullExcelData) and ( count($FullExcelData) > 0) and !is_null($term_object) )
            {
                // Extended checks should be put in place
                $StudentScoreData =   $FullExcelData[$selected_registration_list_index][$selected_student_registration_index];
//                dd($StudentScoreData);

                //Send this variable to reportpdf and make attempt to download it
                $html = '';

                $surname = property_exists($StudentScoreData->first()->first(), "student_surname") ? ucwords(strtolower($StudentScoreData->first()->first()->student_surname))  : " ";

                $middle_name = property_exists($StudentScoreData->first()->first(), "student_middlename") ? ucwords(strtolower($StudentScoreData->first()->first()->student_middlename))  : " ";

                $first_name = property_exists($StudentScoreData->first()->first(), "student_firstname") ? ucwords(strtolower($StudentScoreData->first()->first()->student_firstname))  : " ";

                $class_level = property_exists($StudentScoreData->first()->first(), "student_class_level") ? ucwords(strtolower($StudentScoreData->first()->first()->student_class_level))  : " ";


                $student_admission_number = property_exists($StudentScoreData->first()->first(), "student_admission_number") ? ucwords(strtolower($StudentScoreData->first()->first()->student_admission_number))  : " ";

                $StudentName = $surname . " ". $middle_name . " ".  $first_name;

                $view_string = View::make('includes.reportviewpdf_review')
                    ->with(array(
                        'Title' => 'Student Report Page',
                        'ThisStudent' => $StudentName,
                        'SubjectScore' => $StudentScoreData,
                        'Attendance' => [] ,
                        'TermDuration' => [],
                        'RequestedTerm'   => $term_object,
                        "OfficialComments" => [],
                        'School'   => $school_object,
                        'ClassSubDivision'  => $class_subdivision_loop_key,
                        'ClassLevel'  => $class_level,
                        'AcademicYear'  => $academic_year_object,
                        'StudentAdmissionNumber'  => $student_admission_number,
                        'FirstTermSubjectScore'  => $StudentScoreData,
                        'SecondTermSubjectScore'  => []))
                    ->render();

                $view_string = preg_replace('/>\s+</', "><", $view_string);
//                return $view_string;

                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML($view_string);

//                return $pdf->stream("Download.pdf");
                $pdfStream =  $pdf->stream("Download.pdf");

//                dd ( $pdfStream->getContent() );

//                TUNDE  SHOMEFUN - FIRST TERM SS1 A - Result
            $requested_term = $term_object->term;
                return $pdf->download("$StudentName - $requested_term  term - " .  strtoupper($class_level) . "$class_subdivision_loop_key - Result.pdf");

                $zip_file = storage_path( 'app/test.zip') ;

                $zip = new ZipArchive;
                $res = $zip->open($zip_file , ZipArchive::CREATE);
                if ($res === TRUE)
                {
                    $zip->addFromString("test.pdf",  $pdfStream->getContent());
                    $zip->addFromString("test1.pdf", $pdfStream->getContent());
                    $zip->addFromString("test3.pdf", $pdfStream->getContent());
                    $zip->close();
                    echo 'ok';
                } else {
                    echo 'failed';
                }


//                header('Content-Type: application/zip');
//                header('Content-disposition: attachment; filename='.$zip_file);
//                header('Content-Length: ' . filesize($zip_file));
//                readfile($zip_file);

//                dd($zip_file);

                $response  = response()->download($zip_file);
                ob_end_clean();
                return $response;
            }
            else
            {
                // Return That Data does not exist
            }



//            $academic_year_object = Session::get("academic_year_object");

//            $term_object = Session::get("term_object");

//            $class_level_object = Session::get("class_level_object");

//            $school_object = Session::get("school_object");

//            Log::info(json_encode($school_object) . " ". json_encode($term_object) );

//            $academic_year_object = Session::get("academic_year_object");
//
//            Session::put("academic_year_object", $this_academic_year);
//
//            Session::put("term_object", $this_term);
//
//            Session::put("class_level_object", $this_class_level);
//
//            Session::put("school_object", $this_school);

            if(Session::has("ExcelData" ))
            {
                $FullExcelData =  Session::get("ExcelData" );

                // Get The data list based on the selected index choosen

                $selectedRegistrationList = $FullExcelData->get($selected_registration_list_index);
//                dd($selectedRegistrationList);

                //Loop through list

//
//                $academic_year_object = Session::get("academic_year_object");

//                $term_object = Session::get("term_object");

                $class_level_object = Session::get("class_level_object");

                $school_object = Session::has("school_object") ? Session::get("school_object")  : "";

                $school_id = $school_object->id;

//                $academic_year = $academic_year_object->id;

                // Test Run And Check that Excel sheet has Sub division, or else bypass bulk student registration and complain

                //Get first student as a sample
//                dd(count($selectedRegistrationList));

                $StudentRegistrationArray = [];
                $student_registration_counter =  0;

                if( (count($selectedRegistrationList) > 0 )
                    and $selectedRegistrationList[0]->has("subdivision")
                    and $selectedRegistrationList[0]->has("sex")
                    and $selectedRegistrationList[0]->has("class_level")
                    and $selectedRegistrationList[0]->has("academic_year")
                    and $selectedRegistrationList[0]->has("admn_no")
                    and $selectedRegistrationList[0]->has("student_name")
                )
                {
                    ini_set('memory_limit', '-1');
                    ini_set('max_execution_time', 30000); //300 seconds = 5 minutes


                    $loop_breaker = 1;
                    foreach( $selectedRegistrationList as $eachRegisteredStudent)
                    {
//                        dd($eachRegisteredStudent);
                        // Check first that collection has district_no
                        //if true, use district_no to save student details including other details
                        //if false, use school_id and admn_no to save student details. Also, generated an alphanumeric 5 digit code for district number for the students

//                             "admn_no" => "4485"
//                                "student_name" => "Paul-Maduka Faith Obianuju"
//                                "spin" => null
//                                "sex" => "F"
//                                "subdivision" => "A"
//                                "class_level" => "JSS1"
//                                "academic_year" => 2019.0


//                        if($loop_breaker > 10)
//                        {
//                            break;
//                        }

                        $loop_breaker++;

                        if($eachRegisteredStudent->has('district_no') )
                        {
                            //if true, use district_no to get student/user model
                            $student_district_no =  $eachRegisteredStudent->get('district_no');
                        }
                        else
                        {
                            //if false, auto generate student district ID
                            $random_alphanum_array = Utilities::getUniqueFixedCharacters(5, ["district_number" => "students"]);
                            $student_district_no =   $random_alphanum_array["generated_character"];

                        }

                        $student_admission_no =  $eachRegisteredStudent->has('admn_no') ? $eachRegisteredStudent->get('admn_no') : null;
                        $student_spin_no =  $eachRegisteredStudent->has('spin') ? $eachRegisteredStudent->get('spin') : null;
                        $student_sex =  $eachRegisteredStudent->has('sex') ? $eachRegisteredStudent->get('sex') : null;
                        $student_name_surname_first =  $eachRegisteredStudent->has('student_name') ? $eachRegisteredStudent->get('student_name') : null;

//                        $student_academic_year =  $academic_year;
                        $student_academic_year =  $eachRegisteredStudent->has('academic_year') ? $eachRegisteredStudent->get('academic_year') : null;
                        $student_school_id =  $school_id;
//                        $student_term =  $term_object->id;
                        $student_class_level =  $eachRegisteredStudent->has('class_level') ? $eachRegisteredStudent->get('class_level') : null;

                        $classubdivision_array =
                            [
                                "a" => 1,
                                "b" => 2,
                                "c" => 3,
                                "d" => 4,
                                "e" => 5,
                                "f" => 6,
                                "g" => 7,
                                "h" => 8,
                                "i" => 9,
                                "j" => 10,
                                "k" => 11
                            ];

                        /* Check Values of Class Sub division for correctness */
                        $student_subdivision =  $eachRegisteredStudent->has('subdivision') ? $eachRegisteredStudent->get('subdivision') : null;

                        $student_subdivision = strtolower($student_subdivision);

                        $student_division_id = array_key_exists($student_subdivision, $classubdivision_array )  ? $classubdivision_array[$student_subdivision] : null ;

                        if(is_null($student_division_id))
                        {
                            // continue but dont execute any code further for this iteration
                            //Record this student name and Admin_id
                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "The Class Subdivision is invalid. This student was skipped."
                            ];
                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;
                            continue;
                        }
                        /* Check Values of Class Sub division for correctness */


                        /* Check Values of Class Levels for correctness */

                        $class_level_array =
                            [
                                "jss1" => 1,
                                "jss2" => 2,
                                "jss3" => 3,
                                "ss1" => 4,
                                "ss2" => 5,
                                "ss3" => 6
                            ];

                        $student_class_level =  preg_replace('/\s+/', '', strtolower($student_class_level)) ;


                        $student_class_level_id = array_key_exists($student_class_level, $class_level_array )  ? $class_level_array[$student_class_level] : null;

//                        dd($student_class_level, $student_class_level_id);


                        if(is_null($student_class_level_id))
                        {
                            // continue but don't execute any code further for this iteration
                            //Record this student name and Admin_id
                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "The Student Class Level is invalid. This student was skipped."
                            ];
                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;

                            continue;
                        }

                        /* Check Values of Class Levels for correctness */

                        /* Check Values of Academic Year for correctness */


                        $student_academic_year = (int)$student_academic_year;

                        $student_academic_year =  preg_replace('/\s+/', '', strtolower($student_academic_year)) ;

                        $student_academic_year_object =  AcademicYear::where("academic_year", $student_academic_year )->get();
                        $student_academic_year_id = null;
                        if(count($student_academic_year_object) > 0)
                        {
                            $student_academic_year_id =  $student_academic_year_object->first()->id;
                        }
                        else
                        {
                            $student_academic_year_id = null;
                        }

//                        dd($student_academic_year, $student_academic_year_id);


                        if(is_null($student_academic_year_id))
                        {
                            // continue but dont execute any code further for this iteration
                            //Record this student name and Admin_id
                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "The Academic Year is invalid. This student was skipped."
                            ];
                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;
                            continue;
                        }

                        /* Check Values of Academic Year for correctness */


                        // Build Student Model and Users Model and populate Student , Users and Student Registration Tables
                        $user_object = null;
                        $student_model = null;
                        $student_registration = null;
                        try
                        {
                            $studentNameArray = $this->extractName($student_name_surname_first);
                            $builtStudentEmail = $this->buildEmail($studentNameArray, $student_district_no);
                            $raw_password = Utilities::create_random_name(20);
                            $userInsertStudentArrayCount = count($studentNameArray);
                            $studentSex = strtolower($student_sex) == "m" ? "Male" : "Female";

                            $surname =   $userInsertStudentArrayCount > 0 ?  $studentNameArray[0] : " ";
                            $firstname =   $userInsertStudentArrayCount > 1 ?  $studentNameArray[1] : " ";
                            $middlename =   $userInsertStudentArrayCount > 2 ?  $studentNameArray[2] : " ";

                            $user_object = new Users();

                            $user_object->useremail = $builtStudentEmail;
                            $user_object->firstname = $firstname;
                            $user_object->middlename = $middlename;
                            $user_object->surname = $surname;
                            $user_object->activated =  1; // Activated by default
                            $user_object->password =  bcrypt($raw_password);
                            $user_object->sex = $studentSex;

//                            dd($user_object);
                            $user_object->save();

                            // Create Student Model

                            $student_model = new Students();

                            $student_model->userid =   $user_object->id;
                            $student_model->district_number =  $student_district_no;
                            $student_model->spin =  $student_spin_no;

//                            dd($student_model);
                            $student_model->save();

                            $student_registration =  new StudentRegistration();
                            $student_registration->student_id = $student_model->id;
                            $student_registration->academic_year = $student_academic_year_id;
                            $student_registration->school_id = $student_school_id;
                            $student_registration->class_level_id = $student_class_level_id;
                            $student_registration->class_type_id = null;
                            $student_registration->class_subdivision = $student_division_id;
                            $student_registration->admission_number = $student_admission_no;

//                            dd($student_registration);
                            $student_registration->save();

                            $successArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "Student details was successfully saved.",

                            ];

                            $StudentRegistrationArray["Success"][$student_registration_counter] = $successArray;
                        }
                        catch(\Exception $e)
                        {
                            Log::info($e->getMessage());

                            if(!is_null($student_registration))
                            {
                                $student_registration->forceDelete();
                            }

                            if(!is_null($student_model))
                            {
                                $student_model->forceDelete();
                            }

                            if(!is_null($user_object))
                            {
                                $user_object->forceDelete();
                            }

                            // Record failure situation here and store in recon Array

                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "There was an error in saving User, Student or Student Registration information. Please contact administrator.",
                                "errorText" => $e->getMessage()
                            ];

                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;

                            $StudentRegistrationArray["Success"][$student_registration_counter] = $failureArray;
                        }

                        $student_registration_counter++;

                    }

//                     dd("End of loop" );
                    // Build Response Object and return

                    $request->session()->flash('success' , "Bulk student registration is successful.");
                    $session_name = "StudentRegistrationExcelExportData";



                    Session::put("StudentRegistrationExcelExportData", $StudentRegistrationArray );
                    Session::put("session_name", $session_name );

                    $request->session()->flash('bulkOperationArray' , $StudentRegistrationArray );

                    return view('schools.registration_excel_upload',
                        [
                            'MyBreadCrumb' => '',
                            'Title' => 'EduApp Lagos',
                        ]);

//                    return back()->withInput();
                }
                else
                {
                    // Complain that excel does not have subdivision and many other headings  heading and that it must be added
                    // continue but don't execute any code further, loop out an return error through session
//                    dd("An important header is missing " );

                    $request->session()->flash('error' , "Bulk student registration was not done because an important heading was missing. Please ensure the following heading:  Student name, Admission Number, Sex, Class level, Academic Year, Sub Division.");

                    return view('schools.registration_excel_upload',
                        [
                            'MyBreadCrumb' => '',
                            'Title' => 'EduApp Lagos',
                        ]);

//                    return back()->withInput();
                }

            }
            else
            {
                // Return with error that excel data does not exist
//                dd("No data choosen or index is wrong" );

                $request->session()->flash('error' , "Student Registration Data is unavailable. Please try again.  ");

                return view('schools.registration_excel_upload',
                    [
                        'MyBreadCrumb' => '',
                        'Title' => 'EduApp Lagos',
                    ]);

//                return back()->withInput();
            }

        }
        else
        {
            return view('school.registration_excel_upload',
                [
                    'MyBreadCrumb' => '',
                    'Title' => 'EduApp Lagos',
                ]);
        }

    }

    public function download_all_class_subdivision(Request $request, $class_subdivision_loop_key)
    {
        $method = $request->isMethod('get');
        if ($method)
        {
//            dd($request->all() ,  $class_subdivision_loop_key);

//            dd(Session::get("class_level_object"));

            $term_object = Session::has("term_object") ? Session::get("term_object") : null;
            $school_object = Session::has("school_object") ? Session::get("school_object") : null;
            $class_level_object = Session::has("class_level_object") ? Session::get("class_level_object") : null;
            $academic_year_object = Session::has("academic_year_object") ? Session::get("academic_year_object") : null;

            // Get Excel Data from Session Varibale
            // Loop through data and use school_id and student admission number to get userid and save score in database
            $FullExcelData =  Session::has("StudentScoreResultData" ) ? Session::get("StudentScoreResultData" ) : null ;

            $selected_registration_list_index = $class_subdivision_loop_key;
//            $selected_student_registration_index = $student_registration_id;

            if(!is_null($FullExcelData) and ( count($FullExcelData) > 0) and !is_null($term_object) )
            {
                // Extended checks should be put in place
                $SelectedClassSubdivision =  $FullExcelData[$selected_registration_list_index];
//                dd($SelectedClassSubdivision);

                if(count($SelectedClassSubdivision) > 0)
                {
                    $zip_file_name = str_slug($school_object->school_name, "_"). "_".
                                      $academic_year_object->academic_year. "_".
                                     strtolower($term_object->term). "_term_". strtoupper($class_level_object->class_level) . "_" .
                                     strtoupper($selected_registration_list_index).".zip";

//                    dd($zip_file_name);

                    $zip_file = storage_path( "app/$zip_file_name") ;

                    if(file_exists($zip_file))
                    {
                        unlink($zip_file);
                    }

//                    dd("Stope-");
                    $zip = new ZipArchive;
                    $res = $zip->open($zip_file , ZipArchive::CREATE);
                    if ($res === TRUE)
                    {
                        // Zip file was found and it openable
                        foreach ( $SelectedClassSubdivision as  $StudentScoreData )
                            {
                                ini_set('memory_limit', '-1');
                                ini_set('max_execution_time', 30000); //300 seconds = 5 minutes
//                                dd($StudentScoreData);
                                    $html = '';

                                    $surname = property_exists($StudentScoreData->first()->first(), "student_surname") ? ucwords(strtolower($StudentScoreData->first()->first()->student_surname))  : " ";

                                    $middle_name = property_exists($StudentScoreData->first()->first(), "student_middlename") ? ucwords(strtolower($StudentScoreData->first()->first()->student_middlename))  : " ";

                                    $first_name = property_exists($StudentScoreData->first()->first(), "student_firstname") ? ucwords(strtolower($StudentScoreData->first()->first()->student_firstname))  : " ";

                                    $class_level = property_exists($StudentScoreData->first()->first(), "student_class_level") ? ucwords(strtolower($StudentScoreData->first()->first()->student_class_level))  : " ";


                                    $student_admission_number = property_exists($StudentScoreData->first()->first(), "student_admission_number") ? ucwords(strtolower($StudentScoreData->first()->first()->student_admission_number))  : " ";

                                    $StudentName = $surname . " ". $middle_name . " ".  $first_name;

                                    $view_string = View::make('includes.reportviewpdf_review')
                                        ->with(array(
                                            'Title' => 'Student Report Page',
                                            'ThisStudent' => $StudentName,
                                            'SubjectScore' => $StudentScoreData,
                                            'Attendance' => [] ,
                                            'TermDuration' => [],
                                            'RequestedTerm'   => $term_object,
                                            "OfficialComments" => [],
                                            'School'   => $school_object,
                                            'ClassSubDivision'  => $class_subdivision_loop_key,
                                            'ClassLevel'  => $class_level,
                                            'AcademicYear'  => $academic_year_object,
                                            'StudentAdmissionNumber'  => $student_admission_number,
                                            'FirstTermSubjectScore'  => $StudentScoreData,
                                            'SecondTermSubjectScore'  => []))
                                        ->render();

                                    $view_string = preg_replace('/>\s+</', "><", $view_string);
                                    //                return $view_string;
                                    $pdf = App::make('dompdf.wrapper');
                                    $pdf->loadHTML($view_string);
                                    //                return $pdf->stream("Download.pdf");
                                    $pdfStream =  $pdf->stream("Download.pdf");

                                    //                dd ( $pdfStream->getContent() );
                                    $requested_term = $term_object->term;
                                        $zip->addFromString("$StudentName - $requested_term  term - " .  strtoupper($class_level) . "$class_subdivision_loop_key - Result.pdf",  $pdfStream->getContent());
                            }// end for loop

                        $zip->close();
                        //dd($zip_file);
                        $response  = response()->download($zip_file);
                        ob_end_clean();
//                        unlink($zip_file);
                        return $response;
                    }
                    else
                    {
                        // We cannot Loop  because the zip file cannot be opened
//                        echo 'failed';
                        dd("Cannot ;loop because the zip cannot be opened");
                    }
                }
                else
                {
                    // Count of Student in this subdividion is less than zeroo

                }

            }
            else
            {
                // Return That Data does not exist
            }



//            $academic_year_object = Session::get("academic_year_object");

//            $term_object = Session::get("term_object");

//            $class_level_object = Session::get("class_level_object");

//            $school_object = Session::get("school_object");

//            Log::info(json_encode($school_object) . " ". json_encode($term_object) );

//            $academic_year_object = Session::get("academic_year_object");
//
//            Session::put("academic_year_object", $this_academic_year);
//
//            Session::put("term_object", $this_term);
//
//            Session::put("class_level_object", $this_class_level);
//
//            Session::put("school_object", $this_school);

            if(Session::has("ExcelData" ))
            {
                $FullExcelData =  Session::get("ExcelData" );

                // Get The data list based on the selected index choosen

                $selectedRegistrationList = $FullExcelData->get($selected_registration_list_index);
//                dd($selectedRegistrationList);

                //Loop through list

//
//                $academic_year_object = Session::get("academic_year_object");

//                $term_object = Session::get("term_object");

                $class_level_object = Session::get("class_level_object");

                $school_object = Session::has("school_object") ? Session::get("school_object")  : "";

                $school_id = $school_object->id;

//                $academic_year = $academic_year_object->id;

                // Test Run And Check that Excel sheet has Sub division, or else bypass bulk student registration and complain

                //Get first student as a sample
//                dd(count($selectedRegistrationList));

                $StudentRegistrationArray = [];
                $student_registration_counter =  0;

                if( (count($selectedRegistrationList) > 0 )
                    and $selectedRegistrationList[0]->has("subdivision")
                    and $selectedRegistrationList[0]->has("sex")
                    and $selectedRegistrationList[0]->has("class_level")
                    and $selectedRegistrationList[0]->has("academic_year")
                    and $selectedRegistrationList[0]->has("admn_no")
                    and $selectedRegistrationList[0]->has("student_name")
                )
                {
                    ini_set('memory_limit', '-1');
                    ini_set('max_execution_time', 30000); //300 seconds = 5 minutes


                    $loop_breaker = 1;
                    foreach( $selectedRegistrationList as $eachRegisteredStudent)
                    {
//                        dd($eachRegisteredStudent);
                        // Check first that collection has district_no
                        //if true, use district_no to save student details including other details
                        //if false, use school_id and admn_no to save student details. Also, generated an alphanumeric 5 digit code for district number for the students

//                             "admn_no" => "4485"
//                                "student_name" => "Paul-Maduka Faith Obianuju"
//                                "spin" => null
//                                "sex" => "F"
//                                "subdivision" => "A"
//                                "class_level" => "JSS1"
//                                "academic_year" => 2019.0


//                        if($loop_breaker > 10)
//                        {
//                            break;
//                        }

                        $loop_breaker++;

                        if($eachRegisteredStudent->has('district_no') )
                        {
                            //if true, use district_no to get student/user model
                            $student_district_no =  $eachRegisteredStudent->get('district_no');
                        }
                        else
                        {
                            //if false, auto generate student district ID
                            $random_alphanum_array = Utilities::getUniqueFixedCharacters(5, ["district_number" => "students"]);
                            $student_district_no =   $random_alphanum_array["generated_character"];

                        }

                        $student_admission_no =  $eachRegisteredStudent->has('admn_no') ? $eachRegisteredStudent->get('admn_no') : null;
                        $student_spin_no =  $eachRegisteredStudent->has('spin') ? $eachRegisteredStudent->get('spin') : null;
                        $student_sex =  $eachRegisteredStudent->has('sex') ? $eachRegisteredStudent->get('sex') : null;
                        $student_name_surname_first =  $eachRegisteredStudent->has('student_name') ? $eachRegisteredStudent->get('student_name') : null;

//                        $student_academic_year =  $academic_year;
                        $student_academic_year =  $eachRegisteredStudent->has('academic_year') ? $eachRegisteredStudent->get('academic_year') : null;
                        $student_school_id =  $school_id;
//                        $student_term =  $term_object->id;
                        $student_class_level =  $eachRegisteredStudent->has('class_level') ? $eachRegisteredStudent->get('class_level') : null;

                        $classubdivision_array =
                            [
                                "a" => 1,
                                "b" => 2,
                                "c" => 3,
                                "d" => 4,
                                "e" => 5,
                                "f" => 6,
                                "g" => 7,
                                "h" => 8,
                                "i" => 9,
                                "j" => 10,
                                "k" => 11
                            ];

                        /* Check Values of Class Sub division for correctness */
                        $student_subdivision =  $eachRegisteredStudent->has('subdivision') ? $eachRegisteredStudent->get('subdivision') : null;

                        $student_subdivision = strtolower($student_subdivision);

                        $student_division_id = array_key_exists($student_subdivision, $classubdivision_array )  ? $classubdivision_array[$student_subdivision] : null ;

                        if(is_null($student_division_id))
                        {
                            // continue but dont execute any code further for this iteration
                            //Record this student name and Admin_id
                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "The Class Subdivision is invalid. This student was skipped."
                            ];
                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;
                            continue;
                        }
                        /* Check Values of Class Sub division for correctness */


                        /* Check Values of Class Levels for correctness */

                        $class_level_array =
                            [
                                "jss1" => 1,
                                "jss2" => 2,
                                "jss3" => 3,
                                "ss1" => 4,
                                "ss2" => 5,
                                "ss3" => 6
                            ];

                        $student_class_level =  preg_replace('/\s+/', '', strtolower($student_class_level)) ;


                        $student_class_level_id = array_key_exists($student_class_level, $class_level_array )  ? $class_level_array[$student_class_level] : null;

//                        dd($student_class_level, $student_class_level_id);


                        if(is_null($student_class_level_id))
                        {
                            // continue but don't execute any code further for this iteration
                            //Record this student name and Admin_id
                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "The Student Class Level is invalid. This student was skipped."
                            ];
                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;

                            continue;
                        }

                        /* Check Values of Class Levels for correctness */

                        /* Check Values of Academic Year for correctness */


                        $student_academic_year = (int)$student_academic_year;

                        $student_academic_year =  preg_replace('/\s+/', '', strtolower($student_academic_year)) ;

                        $student_academic_year_object =  AcademicYear::where("academic_year", $student_academic_year )->get();
                        $student_academic_year_id = null;
                        if(count($student_academic_year_object) > 0)
                        {
                            $student_academic_year_id =  $student_academic_year_object->first()->id;
                        }
                        else
                        {
                            $student_academic_year_id = null;
                        }

//                        dd($student_academic_year, $student_academic_year_id);


                        if(is_null($student_academic_year_id))
                        {
                            // continue but dont execute any code further for this iteration
                            //Record this student name and Admin_id
                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "The Academic Year is invalid. This student was skipped."
                            ];
                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;
                            continue;
                        }

                        /* Check Values of Academic Year for correctness */


                        // Build Student Model and Users Model and populate Student , Users and Student Registration Tables
                        $user_object = null;
                        $student_model = null;
                        $student_registration = null;
                        try
                        {
                            $studentNameArray = $this->extractName($student_name_surname_first);
                            $builtStudentEmail = $this->buildEmail($studentNameArray, $student_district_no);
                            $raw_password = Utilities::create_random_name(20);
                            $userInsertStudentArrayCount = count($studentNameArray);
                            $studentSex = strtolower($student_sex) == "m" ? "Male" : "Female";

                            $surname =   $userInsertStudentArrayCount > 0 ?  $studentNameArray[0] : " ";
                            $firstname =   $userInsertStudentArrayCount > 1 ?  $studentNameArray[1] : " ";
                            $middlename =   $userInsertStudentArrayCount > 2 ?  $studentNameArray[2] : " ";

                            $user_object = new Users();

                            $user_object->useremail = $builtStudentEmail;
                            $user_object->firstname = $firstname;
                            $user_object->middlename = $middlename;
                            $user_object->surname = $surname;
                            $user_object->activated =  1; // Activated by default
                            $user_object->password =  bcrypt($raw_password);
                            $user_object->sex = $studentSex;

//                            dd($user_object);
                            $user_object->save();

                            // Create Student Model

                            $student_model = new Students();

                            $student_model->userid =   $user_object->id;
                            $student_model->district_number =  $student_district_no;
                            $student_model->spin =  $student_spin_no;

//                            dd($student_model);
                            $student_model->save();

                            $student_registration =  new StudentRegistration();
                            $student_registration->student_id = $student_model->id;
                            $student_registration->academic_year = $student_academic_year_id;
                            $student_registration->school_id = $student_school_id;
                            $student_registration->class_level_id = $student_class_level_id;
                            $student_registration->class_type_id = null;
                            $student_registration->class_subdivision = $student_division_id;
                            $student_registration->admission_number = $student_admission_no;

//                            dd($student_registration);
                            $student_registration->save();

                            $successArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "Student details was successfully saved.",

                            ];

                            $StudentRegistrationArray["Success"][$student_registration_counter] = $successArray;
                        }
                        catch(\Exception $e)
                        {
                            Log::info($e->getMessage());

                            if(!is_null($student_registration))
                            {
                                $student_registration->forceDelete();
                            }

                            if(!is_null($student_model))
                            {
                                $student_model->forceDelete();
                            }

                            if(!is_null($user_object))
                            {
                                $user_object->forceDelete();
                            }

                            // Record failure situation here and store in recon Array

                            $failureArray = [
                                "student_registration_counter" =>  $student_registration_counter,
                                "student_name" => $student_name_surname_first ,
                                "admn_no" => $student_admission_no,
                                "school" => $school_object->school_name,
                                "reason" => "There was an error in saving User, Student or Student Registration information. Please contact administrator.",
                                "errorText" => $e->getMessage()
                            ];

                            $StudentRegistrationArray["Failure"][$student_registration_counter] = $failureArray;

                            $StudentRegistrationArray["Success"][$student_registration_counter] = $failureArray;
                        }

                        $student_registration_counter++;

                    }

//                     dd("End of loop" );
                    // Build Response Object and return

                    $request->session()->flash('success' , "Bulk student registration is successful.");
                    $session_name = "StudentRegistrationExcelExportData";



                    Session::put("StudentRegistrationExcelExportData", $StudentRegistrationArray );
                    Session::put("session_name", $session_name );

                    $request->session()->flash('bulkOperationArray' , $StudentRegistrationArray );

                    return view('schools.registration_excel_upload',
                        [
                            'MyBreadCrumb' => '',
                            'Title' => 'EduApp Lagos',
                        ]);

//                    return back()->withInput();
                }
                else
                {
                    // Complain that excel does not have subdivision and many other headings  heading and that it must be added
                    // continue but don't execute any code further, loop out an return error through session
//                    dd("An important header is missing " );

                    $request->session()->flash('error' , "Bulk student registration was not done because an important heading was missing. Please ensure the following heading:  Student name, Admission Number, Sex, Class level, Academic Year, Sub Division.");

                    return view('schools.registration_excel_upload',
                        [
                            'MyBreadCrumb' => '',
                            'Title' => 'EduApp Lagos',
                        ]);

//                    return back()->withInput();
                }

            }
            else
            {
                // Return with error that excel data does not exist
//                dd("No data choosen or index is wrong" );

                $request->session()->flash('error' , "Student Registration Data is unavailable. Please try again.  ");

                return view('schools.registration_excel_upload',
                    [
                        'MyBreadCrumb' => '',
                        'Title' => 'EduApp Lagos',
                    ]);

//                return back()->withInput();
            }

        }
        else
        {
            return view('school.registration_excel_upload',
                [
                    'MyBreadCrumb' => '',
                    'Title' => 'EduApp Lagos',
                ]);
        }

    }

}