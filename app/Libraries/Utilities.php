<?php
namespace App\Libraries;

use App\Individual;
use App\Models\Employee;
use App\Models\EmployeeAbsence;
use App\Models\EmployeeArrear;
use App\Models\EmployeeBonus;
use App\Models\EmployeeDeduction;
use App\Models\EmployeeLoan;
use App\Models\EmployeeRenumeration;
use App\Models\MonthDay;
use App\Models\PayStack;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Created by PhpStorm.
 * User: noibilism
 * Date: 7/13/17
 * Time: 10:21 AM
 */
class Utilities
{
    function createFullWordOrdinal($num)
    {
        $number = intval($num);
        $ord1 = array(1 => "first", 2 => "second", 3 => "third", 5 => "fifth", 8 => "eight", 9 => "ninth", 11 => "eleventh", 12 => "twelfth", 13 => "thirteenth", 14 => "fourteenth", 15 => "fifteenth", 16 => "sixteenth", 17 => "seventeenth", 18 => "eighteenth", 19 => "nineteenth");
        $num1 = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eightteen", "nineteen");
        $num10 = array("zero", "ten", "twenty", "thirty", "fourty", "fifty", "sixty", "seventy", "eighty", "ninety");
        $places = array(2 => "hundred", 3 => "thousand", 6 => "million", 9 => "billion", 12 => "trillion", 15 => "quadrillion", 18 => "quintillion", 21 => "sextillion", 24 => "septillion", 27 => "octillion");
        $number = array_reverse(str_split($number));
        if ($number[0] == 0) {
            if ($number[1] >= 2)
                $out = str_replace("y", "ieth", $num10[$number[1]]);
            else
                $out = $num10[$number[1]] . "th";
        } else if (isset($number[1]) && $number[1] == 1) {
            $out = $ord1[$number[1] . $number[0]];
        } else {
            if (array_key_exists($number[0], $ord1))
                $out = $ord1[$number[0]];
            else
                $out = $num1[$number[0]] . "th";
        }
        if ((isset($number[0]) && $number[0] == 0) || (isset($number[1]) && $number[1] == 1)) {
            $i = 2;
        } else {
            $i = 1;
        }
        while ($i < count($number)) {
            if ($i == 1) {
                $out = $num10[$number[$i]] . " " . $out;
                $i++;
            } else if ($i == 2) {
                $out = $num1[$number[$i]] . " hundred " . $out;
                $i++;
            } else {
                if (isset($number[$i + 2])) {
                    $tmp = $num1[$number[$i + 2]] . " hundred ";
                    $tmpnum = $number[$i + 1] . $number[$i];
                    if ($tmpnum < 20)
                        $tmp .= $num1[$tmpnum] . " " . $places[$i] . " ";
                    else
                        $tmp .= $num10[$number[$i + 1]] . " " . $num1[$number[$i]] . " " . $places[$i] . " ";

                    $out = $tmp . $out;
                    $i += 3;
                } else if (isset($number[$i + 1])) {
                    $tmpnum = $number[$i + 1] . $number[$i];
                    if ($tmpnum < 20)
                        $out = $num1[$tmpnum] . " " . $places[$i] . " " . $out;
                    else
                        $out = $num10[$number[$i + 1]] . " " . $num1[$number[$i]] . " " . $places[$i] . " " . $out;
                    $i += 2;
                } else {
                    $out = $num1[$number[$i]] . " " . $places[$i] . " " . $out;
                    $i++;
                }
            }
        }
        return $out;
    }

    public static function getMonths()
    {
        $month_names = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        return $month_names;
    }

    public static function getYears()
    {
        $current_year = date('Y');
        $date_range = range($current_year, $current_year - 10);
        return $date_range;
    }

    public static function soft_delete($id, $table)
    {
        $result = DB::table($table)
            ->where('id', $id)
            ->update(['active' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
        return $result;
    }

    public static function generate_assessment($company_id, $month, $yr)
    {
        $employees = Individual::where('company_id', $company_id)->where('active', 1)->get();
        $checkduplicate = DB::table('assessments')->where('tax_month', $month)
                                ->where('tax_year', $yr)
                                ->where('company_id', $company_id)
                                ->get();
        if (count($checkduplicate) > 0) {
            return false;
        } else {
            $result = [];
            foreach ($employees as $employee) {

                if($employee){
                    $id = $employee->id;
                    $annualsalary = $employee->basic;
                    $monthlysalary = $annualsalary / 12;
                    $transport = $employee->transport;
                    $trn = $transport / 12;
                    $leave = $employee->leave;
                    $lve = $leave / 12;
                    $housing = $employee->housing;
                    $hsn = $housing / 12;
                    $utility = $employee->utility;
                    $uti = $utility / 12;
                    $tf = 25 / 100;
                    $tpf = 2.5 / 100;
                    $spf = 7.5 / 100;
                    $onepct = 1 / 100;
                    $twtpct = 20 / 100;
                    $annualbenefit = $annualsalary + $transport + $leave + $housing + $utility;
                    $total_benefits = $monthlysalary + $lve + $hsn + $uti + $trn;
                    $totalbenefits = $total_benefits;
                    $fixedcra_status = 1;
                    $nhf_status = 1;
                    $pension_status = 1;
                    $cra_status = 1;
                    $dr_status = 1;
                    $chd_status = 1;
                    $life_assurance = $employee->life_assurance;
                    $la = $life_assurance / 12;
                    $disability_status = $employee->disabled;
                    if ($fixedcra_status == 1) {
                        if ($annualbenefit <= 20000000) {
                            $fixed_cra_monthly = 200000 / 12;
                            $fixed_cra = $fixed_cra_monthly;
                        } else {
                            $one_pct = $onepct / $annualbenefit;
                            $fixed_cra_mth = $one_pct / 12;
                            $fixed_cra = $fixed_cra_mth;
                        }
                    } else {
                        $fixed_cra = 0;
                    }

                    if ($nhf_status == 1) {
                        $nhf = $tpf * $monthlysalary;
                    } else {
                        $nhf = 0;
                    }

                    if ($pension_status == 1) {
                        $htb = $monthlysalary + $hsn + $trn;
                        $pension_init = $spf * $htb;
                        $pension = $pension_init;
                    } else {
                        $pension = 0;
                    }
                    if ($cra_status == 1) {
                        $cra = $twtpct * $total_benefits;
                    } else {
                        $cra = 0;
                    }
                    if ($dr_status == 1) {
                        $dependent_relative_init = 4000 / 12;
                        $dependent_relative = $dependent_relative_init;
                    } else {
                        $dependent_relative = 0;
                    }
                    if ($chd_status == 1) {
                        $children_init = 10000 / 12;
                        $children = $children_init;
                    } else {
                        $children = 0;
                    }
                    if ($disability_status) {
                        $disability_init = $tf * $totalbenefits;
                        $disability = $disability_init;
                    } else {
                        $disability = 0;
                    }

                    $tax_reliefs = $cra + $pension + $fixed_cra + $disability + $dependent_relative + $children;
                    $taxable_pay = $total_benefits - $tax_reliefs;
                    $sevenpercent = 7 / 100;
                    $elevenpercent = 11 / 100;
                    $fifteenpercent = 15 / 100;
                    $nineteenpercent = 19 / 100;
                    $twentyonepercent = 21 / 100;
                    $twentyfourpercent = 24 / 100;
                    $sevenpercentoftwentyfivethousand = $sevenpercent * 25000;
                    $elevenpercentoftwentyfivethousand = $elevenpercent * 25000;
                    $fifteenpercentoffortyonethousand = $fifteenpercent * 41666.67;
                    $sevenpercentoffortyonethousand = $sevenpercent * 41666.67;
                    $twentyonepercentofonethreethree = $twentyonepercent * 133333.33;
                    $value_1 = 25000;
                    $value_2 = 41666.67;
                    $value_3 = 133333.33;
                    $value_4 = 266666.67;
                    $sevenpercentofvalueone = $sevenpercent * $value_1;
                    if ($taxable_pay > $value_1) {
                        $first = $taxable_pay - $value_1;
                        $tax_1 = $sevenpercentofvalueone;
                    } else {
                        $tax_1 = $taxable_pay * $sevenpercent;
                        $first = 0;
                    }
                    if ($first < $value_1) {
                        $tax_2 = $elevenpercent * $first;
                        $second = 0;
                    } else {
                        $tax_2 = $elevenpercent * $value_1;
                        $second = $first - $value_1;
                    }
                    if ($second < $value_2) {
                        $tax_3 = $fifteenpercent * $second;
                        $third = 0;
                    } else {
                        $third = $second - $value_2;
                        $tax_3 = $fifteenpercent * $value_2;
                    }
                    if ($third < $value_2) {
                        $tax_4 = $nineteenpercent * $third;
                        $fourth = 0;
                    } else {
                        $fourth = $third - $value_2;
                        $tax_4 = $nineteenpercent * $value_2;
                    }
                    if ($fourth < $value_3) {

                        $tax_5 = $twentyonepercent * $fourth;
                        $fifth = 0;
                    } else {
                        $fifth = $fourth - $value_3;
                        $tax_5 = $twentyonepercent * $value_3;
                    }
                    if ($taxable_pay > $value_4) {
                        $tax_6 = $twentyfourpercent * $fifth;
                    } else {
                        $tax_6 = 0;
                    }
                    $onepctoftotalbenefit = $onepct * $totalbenefits;
                    $total_tax = $tax_1 + $tax_2 + $tax_3 + $tax_4 + $tax_5 + $tax_6;
                    //$total_tax = $total_tax_init * $division;
                    if ($onepctoftotalbenefit < $total_tax) {
                        $tax_payable = $total_tax;
                    } else {
                        $tax_payable = $onepctoftotalbenefit;
                    }
                    $totaldeductions = $pension + $nhf + $tax_payable;
                    $result[] = [
                        'net_pay' => $total_benefits - $totaldeductions,
                        'employee_id' => $employee->id,
                        'company_id' => $employee->company_id,
                        'salary' => $monthlysalary,
                        'transport' => $trn,
                        'leave' => $lve,
                        'housing' => $hsn,
                        'year' => $yr,
                        'month' => $month,
                        'life_assurance' => $la,
                        'cra' => $cra,
                        'pension' => $pension,
                        'nhf' => $nhf,
                        'dependable_relative' => $dependent_relative,
                        'disability' => $disability,
                        'fixed_cra' => $fixed_cra,
                        'total_tax_reliefs' => $tax_reliefs,
                        'taxable_pay' => $taxable_pay,
                        'total_gross_payable' => $totalbenefits,
                        'utility' => $uti,
                        'tax' => $tax_payable
                    ];
                }
            }
            return json_encode($result, true);
        }
    }

    // Fetch all countries list
    public static function getCountries() {
        try {
            $query = "SELECT id, name FROM countries";
            $results = DB::select($query);
            if(!$results) {
                throw new exception("Country not found.");
            }
            $res = array();
            foreach ($results as $result){
                $res[$result->id] = $result->name;
            }
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$res);
        } catch (Exception $e) {
            $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        } finally {
            return $data;
        }
    }

    // Fetch all states list by country id
    public static function getStates($countryId) {
        try {
            $query = "SELECT id, name FROM states WHERE country_id=".$countryId;
            $results = DB::select($query);
            if(!$results) {
                throw new exception("State not found.");
            }
            $res = array();
            foreach ($results as $result){
                $res[$result->id] = $result->name;
            }
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"States fetched successfully.", 'result'=>$res);
        } catch (Exception $e) {
            $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        } finally {
            return $data;
        }
    }

    // Fetch all cities list by state id
    public static function getCities($stateId) {
        try {
            $query = "SELECT id, name FROM cities WHERE state_id=".$stateId;
            $results = DB::select($query);
            if(!$results) {
                throw new exception("City not found.");
            }
            $res = array();
            foreach ($results as $result){
                $res[$result->id] = $result->name;
            }
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Cities fetched successfully.", 'result'=>$res);
        } catch (Exception $e) {
            $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        } finally {
            return $data;
        }
    }

    public static function cleanData($a) {

        if(preg_match("/^[0-9,]+$/", $a)){
            dd($a);
            $a = str_replace(',','',$a);
        }
        return $a;

    }

    public static function dummyData() {

     $DummyArray =  [
                        'personal_information' =>
                        [
                            'address' => 'Egunla Estate Arigbajo',
                            'title' => 'mr',
                            'firstname' => 'Oluwagbenga',
                            'othernames' => 'Akinsola',
                            'dob' => '1988-09-19',
                            'surname' => 'Akinbami',
                            'maidenname' => 'Ishola',
                            'sex' => 'M',
                            'nationality' => 'nigerian',
                            'state' => '28',
                            'identification' => 'passport',
                            'idno' => '23567447474',
                            'residencydetails' => 'Egunla Estate Arigbajo',
                            'residencydetails2' => 'Egunla Estate Arigbajo',
                            'phone' => '09053509432',
                            'country_code' => '234',
                            'email' => 'akinbami.gbenga@gmail.com',
                        ],
                         'personal_information_2' =>
                             [
                                 'address' => 'Egunla Estate Arigbajo',
                                 'title' => 'mr',
                                 'firstname' => 'Oluwagbenga',
                                 'othernames' => 'Akinsola',
                                 'dob' => '1988-09-19',
                                 'surname' => 'Akinbami',
                                 'maidenname' => 'Ishola',
                                 'sex' => 'M',
                                 'nationality' => 'nigerian',
                                 'state' => 'Ogun',
                                 'identification' => 'Egunla Estate Arigbajo',
                                 'idno' => '23567447474',
                                 'residencydetails' => 'Egunla Estate Arigbajo',
                                 'residencydetails2' => 'Egunla Estate Arigbajo',
                                 'phone' => 'Egunla Estate Arigbajo',
                                 'country_code' => '234',
                                 'email' => 'akinbami.gbenga@gmail.com',
                             ]

                    ];
     return $DummyArray;

    }

    public static function generateFileRef($i = null)
    {
        if (function_exists('com_create_guid') === true)
        {

            return trim(com_create_guid(), '{}');
        }else{

            return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        }
    }

    public static function getUniqueFixedCharacters($length, $table_array)
    {
//        dd($table_array);
       // This function enables us to get fixed unique character by ensuring that whatever is generated is checked against a column in any table by specifying
        // a table_array which is an associative array of the structure $table_arrary = ["$column1" => "$table1", "$column2" => "$table2" ]

        $check_result = [];

        if(!is_integer($length))
        {

            $check_result["generated_character"] = null;
            $check_result["status"] = 0;
            $check_result["response"][] = 0;
            $check_result["reason"] = "Length must be an integer";
            Log::info("Length must be an integer");

            return $check_result;

//            throw new \Exception('Length must be an integer ');
        }

        if(!is_array($table_array) or (count($table_array) <= 0) )
        {

            $check_result["generated_character"] = null;
            $check_result["status"] = 0;
            $check_result["response"][] = 0;
            $check_result["reason"] = "The second argument must be an associative array with at least one element and having this structure 'column1' => 'table1'";

            Log::info("Generate Unique AlphaNum Error:" . json_encode($check_result));
            return $check_result;

//            throw new \Exception('The second argument must be an associative array with at least one element and having this structure "$column1" => "$table1"   ');
        }

        while(true)
        {
            //Generate 6 character
            //run foreach on $table_array to check that ccolum exist for each table
            //then check that the 6 character does not existing in any of the table/column element of table_array
            //save result in check_result 0 0r 1
            // after foreach use, in_array to check for the existence of 0
            // if 0 is found do not break the loop, continue to loop through the while
            //if 0 is not found , break the loop and return the check_result array

            $generated_character = TransRef::getHashedToken($length);
            $check_result["generated_character"] = $generated_character;
            foreach( $table_array as $column => $table )
            {
                if(Schema::hasColumn($table, $column)) //check whether users table has email column
                {
                    $payments =  DB::table($table)->where( $column,  '=' , $generated_character )->get();
//                    dd($payments);
                    if(count($payments) > 0)
                    {
                        $check_result["generated_character"] = null;
                        $check_result["response"][] = 0;


                    }
                    else
                    {
                        $check_result["response"][] = 1;
                    }
                }
                else
                {
                    //May use Exception Handler
                    $check_result["generated_character"] = null;
                    $check_result["status"] = 0;
                    $check_result["response"][] = 0;
                    return $check_result;
                }

            }

            if(!in_array(0, $check_result))
            {
                $check_result["status"] = 1;
                break; //Break the loop
            }


        }

        return $check_result;

    }

    public static function create_random_name($lenght = 0)
    {
        $length = empty($lenght) ? 10 : $lenght;
        $characters = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ23456789';
        $string = '';
        for ($p = 0; $p < $length; $p++) {
            $string.=$characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }






}