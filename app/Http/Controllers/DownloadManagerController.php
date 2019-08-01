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
use App\Models\Terms;
use App\Models\Users;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;


class DownloadManagerController extends Controller
{

    public function __construct(  )
    {
        $this->middleware('web');
    }

    public function downloadExcelOperation($session_name)
    {
//        dd($session_name);
        if(Session::has($session_name))
        {
            $session_data = Session::get($session_name);
            $session_data_collection = collect(  Session::get($session_name) );
// dd($session_data, $session_data_collection);
            $export = new ExcelExport($session_data_collection);
           return  Utilities::downloadExcel($export, $session_name);
        }

    }


}