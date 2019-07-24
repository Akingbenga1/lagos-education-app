<?php

namespace App\Http\Controllers;

use App\Imports\ExcelImport;
use App\Libraries\Utilities;
use App\Models\AcademicYear;
use App\Models\ClassLevels;
use App\Models\ClassSubdivisions;
use App\Models\School;
use App\Models\StudentRegistration;
use App\Models\Students;
use App\Models\Terms;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;


class SchoolController extends Controller
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
//                dd($academic_year, $term_value, $class_level_value  );



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

    public function schools_registration(Request $request)
    {
        $method = $request->isMethod('post');
        if ($method)
        {
//            dd($request->all());

//            "school_value" => "22"
//              "academic_year" => "1"
//              "academic_year_value" => "1"
//              "term" => "2"
//              "term_value" => "2"
//              "class_level" => "4"
//              "class_level_value" => "4"
//              "class_subdivision" => "5"
//              "class_subdivision_value" => "5"
//
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
//                dd($academic_year, $term_value, $class_level_value, $class_subdivision_value  );


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

            return view('schools.schools_registration', $data);
        }

    }

    public function schools_registration_excel_upload(Request $request)
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
            return view('data_upload.excel_upload',
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
                dd($validator->errors());
                return back()->withErrors($validator)->withInput();
            }

            // Get Excel Data from Session Varibale
            // Loop through data and use school_id and student admission number to get userid and save score in database
            $FullExcelData = null;

            $selected_registration_list_index = $request->selected_registration_list_index;

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

                $selectedRegistrationList = $FullExcelData->get($selected_registration_list_index);
//                dd($selectedRegistrationList);

                //Loop through list

//
                $academic_year_object = Session::get("academic_year_object");

                $term_object = Session::get("term_object");

                $class_level_object = Session::get("class_level_object");

                $school_object = Session::get("school_object");

                $school_id = $school_object->id;

                $academic_year = $academic_year_object->id;

                // Test Run And Check that Excel sheet has Sub division, or else bypass bulk student registration and complain

                //Get first student as a sample
//                dd(count($selectedRegistrationList));

                if( (count($selectedRegistrationList) > 0 ) and $selectedRegistrationList[0]->has("subdivision") )
                {
                    foreach( $selectedRegistrationList as $eachRegisteredStudent)
                    {
//                        dd($eachRegisteredStudent);
                        // Check first that collection has district_no
                        //if true, use district_no to save student details including other details
                        //if false, use school_id and admn_no to save student details. Also, generated an alphanumeric 5 digit code for district number for the students
                        //                        "admn_no" => "3863"
//                        "name_surname_first" => "Abdulazeez Zulaikoh"
//                        "spin" => null
//                        "sex" => "F"
//                        "" => null
                        //subdivision

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

//                        dd("check" , $eachRegisteredStudent);
                        $eachRegisteredStudent->get('district_no');
                        $student_admission_no =  $eachRegisteredStudent->has('admn_no') ? $eachRegisteredStudent->get('admn_no') : null;
                        $student_spin_no =  $eachRegisteredStudent->has('spin') ? $eachRegisteredStudent->get('spin') : null;
                        $student_sex =  $eachRegisteredStudent->has('sex') ? $eachRegisteredStudent->get('sex') : null;
                        $student_name_surname_first =  $eachRegisteredStudent->has('name_surname_first') ? $eachRegisteredStudent->get('name_surname_first') : null;

                        $student_academic_year =  $academic_year;
                        $student_school_id =  $school_id;
                        $student_term =  $term_object->id;
                        $student_class_level = $class_level_object->id; //

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

                        $student_subdivision =  $eachRegisteredStudent->has('subdivision') ? $eachRegisteredStudent->get('subdivision') : null;

                        $student_subdivision = strtolower($student_subdivision);

                        $student_division_id = array_key_exists($student_subdivision, $classubdivision_array )  ?
                            $classubdivision_array[$student_subdivision] : null ;

                        if(is_null($student_division_id))
                        {
                            // continue but dont execute any code further for this iteration
                            //Record this student name and Admin_id
                            continue;
                        }

                        // Build
                        // Build Student Model and Users Model and populate Student , Users and Student Term Tables

                        $user_object = null;
                        $student_object = null;
                        $student_registration = null;
                        try
                        {
                            $studentNameArray = $this->extractName($student_name_surname_first);
                            $builtStudentEmail = $this->buildEmail($studentNameArray, $student_district_no);
                            $raw_password = Utilities::create_random_name(20);
                            $userInsertStudentArrayCount = count($studentNameArray);
                            $studentSex = strtolower($student_sex) == "m" ? "Male" : "Female";

                            $surname =   $userInsertStudentArrayCount > 0 ?  $userInsertStudentArrayCount[0] : " ";
                            $firstname =   $userInsertStudentArrayCount > 1 ?  $userInsertStudentArrayCount[1] : " ";
                            $middlename =   $userInsertStudentArrayCount > 2 ?  $userInsertStudentArrayCount[2] : " ";

                            $user_object = new User();

                            $user_object->useremail = $builtStudentEmail;
                            $user_object->firstname = $firstname;
                            $user_object->middlename = $middlename;
                            $user_object->surname = $surname;
                            $user_object->activated =  1; // Activated by default
                            $user_object->password =  bcrypt($raw_password);
                            $user_object->sex = $studentSex;

                            dd($user_object);
//                            $user_object->save();

                            // Create Student Model

                            $student_model = new Students();

                            $student_model->userid =   $user_object->id;
                            $student_model->district_number =
                            $student_model->spin =  $student_spin_no;

                            dd($student_model);
//                            $student_model->save();

                            $student_registration =  new StudentRegistration();
                            $student_registration->student_id = $student_model->id;
                            $student_registration->academic_year = $student_academic_year;
                            $student_registration->school_id = $student_school_id;
                            $student_registration->class_level_id = $student_class_level;
                            $student_registration->class_type_id = null;
                            $student_registration->class_subdivision = $student_division_id;
                            $student_registration->admission_number = $student_admission_no;

                            dd($student_registration);
//                            $student_registration->save

                        }
                        catch(\Exception $e)
                        {
                            if(!is_null($student_registration))
                            {
                                $student_registration->forceDelete();

                            }


                        }


                    }
                }
                else
                {
                    // Complain that excel does not have subdivision heading and that it must be added
                    // continue but dont execute any code further, loop out an return error through session
                    
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
            return view('data_upload.excel_upload',
                [
                    'MyBreadCrumb' => '',
                    'Title' => 'EduApp Lagos',
                ]);
        }

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
                    $extracted_email_name .=   substr($each_name , 0, 3)   . ".";
                }
                else
                {
                    $extracted_email_name .= substr($each_name , 0, 3);
                }
            }

            // concatenate  extracted name to district number
            $extracted_email = $extracted_email_name . "." . $district_number ."@eduapp.com";
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


}