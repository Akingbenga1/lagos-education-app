<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2/26/15
 * Time: 12:40 PM
 */

/**
 * This class is a global resource class
 * It provides methods that are made avaiable for use by all controllers in this App
 */
namespace App\Libraries;
use Session;
use Response;
use Auth;
use Input;
use DB;
use Hash;
use Request;
use App\Libraries\GoogleQpx;
use Illuminate\Pagination;
use GuzzleHttp\Client;


class Resources
{
    /**
     * Generate Globally Unique Identifier (GUID)
     * E.g. 2EF40F5A-ADE8-5AE3-2491-85CA5CBD6EA7
     * @param boolean $include_braces Set to true if the final guid needs
     * to be wrapped in curly braces
     * @return string
     */
    public static function generateGuid($include_braces = false)
    {
        if (function_exists('com_create_guid')) {
            if ($include_braces === true) {
                return com_create_guid();
            } else {
                return substr(com_create_guid(), 1, 36);
            }
        } else {
            mt_srand((double)microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));

            $guid = substr($charid, 0, 8) . '-' .
                substr($charid, 8, 4) . '-' .
                substr($charid, 12, 4) . '-' .
                substr($charid, 16, 4) . '-' .
                substr($charid, 20, 12);

            if ($include_braces) {
                $guid = '{' . $guid . '}';
            }

            return $guid;
        }
    }

    public static function generateReferenceNumber($length)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(1, 9);
        }
        return $result;
    }

    public static function defaultPaginationSetting($param)
    {
        if (isset($param) && self::checkRequestType('int', $param) == true) {
            return $param;
        } else {
            return 10;
        }
    }

    /**
     * This method
     * checks if a user has permission to use a resource
     */
    public static function roleCheck($type, $resource)
    {
        $user_role = Auth::user()->role_id;
        $permission = Resources::checkUserPermission($user_role, $resource, $type);
        return $permission;
    }

    public static function returnErrorData($type)
    {
        switch ($type) {
            case($type == 'permission');
                return array(
                    'title' => 'No Access',
                    'code' => 405,
                    'message' => 'Sorry, you do not have permission to use this resource'
                );
                break;
            case($type == 'empty data');
                return array(
                    'title' => 'Empty Data',
                    'code' => 444,
                    'message' => 'Request returned no information!'
                );
                break;
            case($type == 'parameters');
                return array(
                    'title' => 'Invalid Parameters',
                    'code' => 422,
                    'message' => 'Parameters does not meet request pre-conditions!'
                );
                break;
            default;
                return array(
                    'title' => '',
                    'code' => '',
                    'message' => ''
                );

        }
    }

    public static function generateURI()
    {
        mt_srand((double)microtime() * 10000);
        $charid = substr(strtoupper(md5(uniqid(rand(), true))), 0, 16);
        return $charid;
    }

    public static function wrongRequestResponse($title, $message, $code = null)
    {
        $error_data = array(
            'title' => $title,
            'message' => $message,
            'code' => $code
        );
        $collection_array = array(
            'version' => '0.9.1',
            'href' => 'http://api.agogotours.com/tours/',
            'errors' => $error_data
        );
        $response = Response::json(array(
            'collection' => $collection_array
        ), $code);
        $response->header('Content-Type', 'application/vnd.collection+json');
        return $response;
    }

    public static function setParams($param)
    {
        foreach ($param as $item) {
            if (isset($item)) {
                $params[] = $item;
            } else {
                $params[] = 'NULL';
            }
        }
        return $params;
    }

    public static function checkDataType($var)
    {
        switch ($var) {
            case (is_int($var));
                return 1;
                break;
            case (is_string($var));
                return 2;
                break;
            default;
                return false;
        }
    }

    /**
     * Formats a date to UTC
     * @param datetime $date
     * @param integer $add_date
     * @return boolean
     */
    public static function formatDateToUTC($date, $add_date = null)
    {

        switch ($add_date) {
            case($add_date !== null);
                $currentTime = strtotime($date);
                $fiveMinTIme = $currentTime + (60 * $add_date);
                $day = date("Y-m-d", $fiveMinTIme);
                $time = date("H:i:s", $fiveMinTIme);
                list($year, $month, $day) = explode('-', $day);
                list($hr, $min, $sec) = explode(':', $time);
                $formateddate = date(DATE_ATOM, mktime($hr, $min, $sec, $month, $day, $year));
                break;

            case($add_date == null);
                $currentTime = strtotime($date);
                $day = date("Y-m-d", $currentTime);
                $time = date("H:i:s", $currentTime);
                list($year, $month, $day) = explode('-', $day);
                list($hr, $min, $sec) = explode(':', $time);
                $formateddate = date(DATE_ATOM, mktime($hr, $min, $sec, $month, $day, $year));
                break;

            default;
                $date = date('Y-m-d H:i:s');
                $currentTime = strtotime($date);
                $day = date("Y-m-d", $currentTime);
                $time = date("H:i:s", $currentTime);
                list($year, $month, $day) = explode('-', $day);
                list($hr, $min, $sec) = explode(':', $time);
                $formateddate = date(DATE_ATOM, mktime($hr, $min, $sec, $month, $day, $year));
        }
        return $formateddate;

    }

    public static function callStoredProcedure($name, $input = null, $args_count, $output = null)
    {
        $arg = implode(',', array_fill(0, $args_count, '?'));
        if (!$output) {
            try {
                $result = DB::select('CALL ' . $name . '(' . $arg . ')',
                    $input
                );
            } catch (\PDOException $e) {
                $message = $e->getMessage();
                return $message;
            }
            if (!empty($result)) {
                return true;
            }
        } elseif (!$input) {
            try {
                $result = DB::select('CALL ' . $name);
            } catch (\PDOException $e) {
                $message = $e->getMessage();
                return $message;
            }
            if (!empty($result)) {
                $procedure_object = $result;
                if (count($procedure_object) < 1) {
                    $procedure_array = get_object_vars($procedure_object[0]);
                    $procedure_value = $procedure_array;
                } else {
                    foreach ($procedure_object as $object) {
                        $procedure_array[] = get_object_vars($object);
                    }
                    $procedure_value = $procedure_array;
                }
                return $procedure_value;
            }
        } else {
            try {
                $result = DB::statement('CALL ' . $name . '(' . $arg . ', ' . $output . ')',
                    $input
                );
            } catch (\PDOException $e) {
                $message = $e->getMessage();
                return $message;
            }
            if ($result == true) {
                $procedure_object = DB::select('SELECT ' . $output);
                $procedure_array = get_object_vars($procedure_object[0]);
                $procedure_value = $procedure_array[$output];
                return $procedure_value;
            }
        }
    }

    /**
     * Check Whether a user is allowed to use a resource
     * @param integer $role
     * @param integer $resource
     * @param string $type this can be C,R,U or D
     * @return boolean
     */
    //TODO: Implement SP here
    public static function checkUserPermission($role, $resource, $type)
    {
        $permissions = DB::table('permissions')
            ->where('roles_id', $role)
            ->where('resources_id', $resource)
            ->pluck('permission');
        $permissions_array = explode(",", $permissions);
        if (in_array($type, $permissions_array)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Split a string and return the last value
     * @return integer
     */
    public static function scatter_string($delimiter = null, $string = null, $position = null){
        $string_array = explode($delimiter, $string);
        if(!is_null($position)){
         return $string_array[$position];
        }else{
            return $string_array;
        }
    }


    public static function object_to_array($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = object_to_array($value);
            }
            return $result;
        }
        return $data;
    }


    /**
     * This Method gets user access codes
     * @param string $username
     * @return string
     */
    public static function getUserAccessCode($username)
    {
        $params = array(
            $username
        );
        $args = count($params);
        $access_code = self::callStoredProcedure('get_access_codes', $params, $args, '@access_code');
        return $access_code;
    }

    /**
     * This Method gets user profile URI
     * @param string $username
     * @return string
     */
    public static function getUserProfileURI($username)
    {
        $profile_params = array(
            $username
        );
        $profile_args = count($profile_params);
        $profile_uri = self::callStoredProcedure('get_profile_uri', $profile_params, $profile_args, '@profile_uri');
        return $profile_uri;
    }

    /**
     * This Method gets user_role's name
     * @param integer $role_id
     * @return string
     */
    public static function getUserRoleName($role_id)
    {
        $user_params = array(
            $role_id
        );
        $user_args = count($user_params);
        $role_name = self::callStoredProcedure('get_user_role_name', $user_params, $user_args, '@role_name');
        return $role_name;
    }

    /**
     * This Method is used to create a Custom JSON
     * @param integer $code
     * @param integer $status
     * @param string $title
     * @param string $message
     * @return string
     */
    public static function customJSON($code, $status, $title, $message)
    {
        $response = response()->json(array(
                'status' => array(
                    'title' => $title,
                    'code' => $code,
                    'message' => $message
                ),
                $status
            )
        );
        $response->header('Content-Type', 'application/vnd.collection+json');
        return $response;
    }

    /**
     * Get the POSTED login details from the Form
     * them in an array for use accross other methods in this controller
     * @return array
     */
    public static function getLoginDetails()
    {
        $username = Input::get('username');
        $password = Input::get('password');
        $userdata = array(
            'username' => $username,
            'password' => $password

        );
        return $userdata;
    }

    /**
     * This Method checks for adequate request type
     * @param string $type
     * @return string
     */
    public static function checkRequestType($type)
    {
        $method = Request::method();
        if ($method == strtoupper($type)) {
            return true;
        } else {
            return self::wrongRequestResponse('Wrong Request', 'Wrong request method!');
        }
    }

    /**
     * This Method Does an Update on a Resource in the table
     * @param string $table
     * @param int $id
     * @param array $fields
     */
    public static function updateResource($table, $id, $fields)
    {
        //TODO change to stored procedure
        foreach ($fields as $field) {
            if (!empty($field['value'])) {
                return DB::table($table)
                    ->where('id', $id)
                    ->update([$field['name'] => $field['value']]);
            }
        }
    }

    /**
     * This Method connects 3rd party flight APIs
     * @param string $type
     * @param string $data
     * @return string
     */
    public static function connectFlightsExternalApi($type, $data)
    {
        $validation = self::validateFlightsData($data);
        if ($validation !== false) {
            if ($type == 'GoogleQpx') {
                try {
                    $request = GoogleQpx::GoogleQpx($data);
                } catch (Exception $exception) {
                    return $exception->getCode();
                }

            } else {
                $request = self::customJSON(4503, 406, 'Invalid Request Type', 'Request type is invalid! Please check API type!');
            }
            return $request;
        } else {
            return self::customJSON(4503, 406, 'Invalid Data', 'Request data type is invalid');
        }

    }

    /**
     * This Method validates posted flights data
     * @param string $type
     * @return boolean
     */
    public static function validateFlightsData($data)
    {
        /*        [d_ic] => LOS [a_ic] => [maxpr] => [cab] => [ear_dep] => 2015-11-12 [lat_dep] => 2015-11-12 [dep] => [pasg] => 2A,1L [ref] => [access_code] =>*/
        foreach ($data as $datum) {
            $type[] = gettype($datum);
        }
        if (in_array('NULL', $type)) {
            return false;
        }
    }

    /**
     * This Method posts requests to 3rd party APIs
     * @param string $uri
     * @param string $hash
     * @return boolean
     */
    public static function post_http_request($uri, $hash = null)
    {
        $client = new Client();
        $results = $client->get(
            $uri,
            [
                'headers' => [
                    'User-Agent' => 'Laravel/5.0',
                    'Connection' => 'close',
                    'Hash' => $hash
                ]
            ]
        );
        return $results;
    }

    public static function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Trident/i',$u_agent))
        { // this condition is for IE11
            $bname = 'Internet Explorer';
            $ub = "rv";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        // Added "|:"
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/|: ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }


}