<?php

namespace App\Http\Controllers;


use App\Imports\ExcelImport;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class ExcelUploadController extends Controller
{

    public function __construct(  )
    {
        $this->middleware('auth');
    }

    public function excel_upload(Request $request)
    {
        $method = $request->isMethod('post');
        if ($method)
        {

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


//            $ExcelUploadResponse  = $this->populateSchoolsTable($data); // Done

//            dd($ExcelUploadResponse);



            return back();

        }
        else
        {
//            dd(User::all());
            return view('data_upload.excel_upload',
               [
                   'MyBreadCrumb' => '',
                   'Title' => 'EduApp Lagos',
                ]);
        }

    }

    private function populateSchoolsTable($data)
    {
//        dd($data->get(1));
        $senior_school_data = $data->get(1);

        $temp_count = 1;
        if (count($senior_school_data) > 0)
        {
            $plate_number_response_array = [];
            foreach ($senior_school_data as $datum)
            {
    //                "school" => "AGEGE /JUNIOR COLLEGE"
    //                 "address" => "11-17, OLUFESO STR,CEMENT B/STOP,AGEGE"


                    $each_school_name = $datum->has("school")  ? trim($datum['school']) : null;
                    $each_school_address =   $datum->has("address") ? trim($datum['address']) : null;
                    $each_school_serial = "temp_plate_number_" . $temp_count;


//                    var_dump($each_school_name, $each_school_address);
                    if (is_null($each_school_name) or empty($each_school_name))
                    {
                        $plate_number_response_array["Failure"][$each_school_serial] = "School name is missing for this row number $temp_count.";
                        continue; // stop this iteration and move to the next one

                    }
                    if (is_null($each_school_address) or empty($each_school_address))
                    {
                        $plate_number_response_array["Failure"][$each_school_serial] = "School address is missing for this row number $temp_count.";

                        continue; // stop this iteration and move to the next one

                    }

                    $school_array = [
                        "school_name" => ucwords(strtolower($each_school_name)) ,
                        "school_type_id"  => 2 , // Senior School
                        "school_address"  => ucfirst(strtolower($each_school_address))
                    ];
//                    var_dump($school_array);
                    try
                    {
                        School::insert( $school_array  );
                        $plate_number_response_array["Success"][$each_school_serial] =  " School,  $each_school_name , was  successfully saved!";
                    }
                    catch (\Exception $e)
                    {
                        Log::info($e->getMessage());
                        $plate_number_response_array["Failure"][$each_school_serial] =  " Error saving School,  $each_school_name , to the database for number $temp_count";
                    }

                $temp_count++;
                }

                dd($plate_number_response_array);


        }
        else
        {
            // retunrn array indicating failure
            //

        }

    }

    public function index()
    {
        // Get the last 10 Plate Numbers and show them
        // Get all Letter Codes  and all LGACodes
        // Get all Letter Code  and LGA Code generated so far  from number_plate_generations_test
        // Get all LGA Code generated so far  from number_plate_generations_test
        // Generated Associative array marking generated / used LGACodes and LetterCodes
        // Work using ids for LETTERCODE and LGACODE

        $latest_number_plates = DB::table('number_plate_generations')
            ->orderByRaw('id DESC')
            ->take(10)
            ->get();
        $AllLGACodes = LgaCode::all();
//        $AllLGACodesPlucked = $AllLGACodes->pluck('lga_code' ,'id');
//        $AllLGACodesArray = $AllLGACodes->toArray();

        $AllLetterCodes = LetterCode::all()->pluck('letter_code' ,'id');

        // Get all LGA Code generated so far  from number_plate_generations table
//        $generated_lga_code =  DB::table('number_plate_generations_test')
//            ->groupBy('lga_code_id')
//            ->selectRaw('lga_code_id,  lga_code , COUNT(`lga_code_id`)  as lga_code_count')
//            ->get();

        // Get all Letter Code generated so far  from number_plate_generations
        // groupby lettercode id

        $generated_letter_code =  DB::table('number_plate_generations')
            ->groupBy('letter_code_id')
            ->selectRaw('letter_code_id,  letter_code , COUNT(`letter_code_id`)  as letter_code_count')
            ->get();

        // Get all Letter Code  and LGA Code generated so far  from number_plate_generations - // inner join must happen here
        $generated_lga_letter_code =  DB::table('number_plate_generations as  npt')
            ->join('lga_codes as lgc', 'lgc.id', '=', 'npt.lga_code_id')
            ->groupBy(['npt.lga_code_id', 'npt.letter_code_id'] )
            ->selectRaw('npt.lga_code_id,  npt.lga_code , npt.letter_code_id, npt.letter_code,  COUNT(npt.lga_code_id)  as lga_letter_code_count , lgc.lga_description')
            ->get();

        //Generated Associative array marking generated / used LGACodes and LetterCodes

        $letter_code_list_array = [];
        $generated_letter_code_plucked = $generated_letter_code->pluck('letter_code_id')->toArray();


        // This code is marked for deletion
        foreach($AllLetterCodes as $key =>  $EachLetterCode)
        {
            $EachLetterCodeID = $key;
            if(!in_array($EachLetterCodeID, $generated_letter_code_plucked ))
            {
                $letter_code_list_array[$EachLetterCodeID]["letter_code_id"] = $EachLetterCodeID;
                $letter_code_list_array[$EachLetterCodeID]["letter_code"] = $EachLetterCode;
            }
        }

        // Get next ten Letter Codes that have not yet been used for any form of  generation
        $next_ten_letter_code  = collect($letter_code_list_array)->take(10);


        $lga_letter_code_list_array = [];
        foreach($generated_lga_letter_code->toArray() as $key => $each_generated_lga_letter_code)
        {
            $EachLGACodeID = $each_generated_lga_letter_code->lga_code_id;
            $EachLetterCodeID = $each_generated_lga_letter_code->letter_code_id;
//            $lga_letter_code_list_array[$EachLGACodeID]
            $lga_letter_code_list_array[$EachLGACodeID]["lga_code_id"] = $each_generated_lga_letter_code->lga_code_id;
            $lga_letter_code_list_array[$EachLGACodeID]["lga_code"] = $each_generated_lga_letter_code->lga_code;
//            $lgaCodeObject =  LgaCode::where('id', $each_generated_lga_letter_code->lga_code_id )->get();
            $lga_letter_code_list_array[$EachLGACodeID]["lga_code_name"] = $each_generated_lga_letter_code->lga_description;//!is_null($lgaCodeObject) ?  $lgaCodeObject->first()->lga_description : "Unknown";
            $lga_letter_code_list_array[$EachLGACodeID]["letter_code"][$EachLetterCodeID]["letter_code_id"] = $each_generated_lga_letter_code->letter_code_id;
            $lga_letter_code_list_array[$EachLGACodeID]["letter_code"][$EachLetterCodeID]["letter_code"] = $each_generated_lga_letter_code->letter_code;
            $lga_letter_code_list_array[$EachLGACodeID]["letter_code"][$EachLetterCodeID]["lga_letter_code_count"] = $each_generated_lga_letter_code->lga_letter_code_count;

        }

//        dd($lga_letter_code_list_array);

        $data['lga_letter_code_list_array'] = $lga_letter_code_list_array;
        $data['next_ten_letter_code'] = $next_ten_letter_code;
        $data['latest_number_plates'] = $latest_number_plates;

//        dd($data);


        //Show all LGA as a drop down list

        // Check availability of letter code corresponding to LGA selected with information on whether generation is completed or not

        // Show next 10 Letter code in sequential other   (Laravel PHP, View )

        //Actions: Show Possible Plate Numbers that can be generated upon choosing the letter code (Javascript )
        // State that plate numbers generated are not in the database yet
        //Provide button that will saved the generated number plates to the table

        //Action: Provide button for each letters codes to view all Number plate series generated so far (new view: show.blade suggested )

        return view('plate_number_generation.index', $data);
    }

    public function code_placeholder()
    {
        ////
//        var compacted = document.querySelectorAll('.sf-dump-compact');
//
//        for (var i = 0; i < compacted.length; i++)
//   {
//       compacted[i].className = 'sf-dump-expanded';
//   }


//        table("activity_logs")
//            ->join('registration_channels', 'registration_channels.id', '=', 'activity_logs.registration_channel_id')
//            ->groupBy(['registration_channel_id'])->selectRaw('registration_channels.channel_name as registration_channel, count(activity_logs.registration_channel_id) as frequency ')->get();
//
//        ->where('tax_month', $month)
//        ->where('tax_year', $yr)
//        ->where('company_id', $company_id)

//        ->join('registration_channels as rc', 'rc.id', '=', 'g.registration_channel_id')
//        ->join('game_types as gt', 'gt.id', '=', 'g.game_type_id')
//        ->join('game_status as gs', 'gs.id', '=', 'g.game_status_id')
//        ->selectRaw('g.*, rc.channel_name , gt.name as game_name, gs.status')
//        ->get();

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


	
}//end PageController