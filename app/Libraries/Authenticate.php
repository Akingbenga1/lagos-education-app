<?php
/**
 * Created by PhpStorm.
 * User: noibilism
 * Date: 2/22/17
 * Time: 10:28 AM
 */

namespace App\Libraries;

use App\AccessToken;
use App\Libraries\Encryption;
use App\LockedIp;
use App\Models\Role;
use App\Throttle;
use App\Models\User;
use App\Models\RolesResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use UserAgentParser\Provider\WhichBrowser;

class Authenticate
{
    //use ThrottlesLogins;

    public static function login_user(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $hashed_password = Encryption::encryptString($password);
        $check_user = Auth::attempt(['email' => $email, 'password' => $hashed_password, 'status' => 1]);
        switch ($check_user) {
            case true;
                self::perform_post_login_operations($email, $request);
                return true;
                break;
            case false;
                return false;
                break;
            default;
                return false;
                break;
        }
    }

    public static function add_role(Request $request)
    {
        $request_array = $request->all();
        $name = $request_array['name'];
        unset($request_array['_token']);
        unset($request_array['name']);
        $nu_array = array_keys($request_array);
        $check_duplicate = Role::where('name', $name)->first();
        switch ($check_duplicate) {
            case false;
                $role = Role::create([
                        'name' => $name
                    ]
                );
                foreach ($nu_array as $nu) {
                    $res_id = $nu[2];
                    $perm = $nu[0];
                    self::create_permission($role->id, $res_id, $perm);
                }
                return true;
                break;
            case true;
                return false;
                break;
            default;
                return false;
                break;
        }
    }

    public static function update_role(Request $request, $id)
    {
        $request_array = $request->all();
        $name = $request_array['name'];
        unset($request_array['_token']);
        unset($request_array['name']);
        $nu_array = array_keys($request_array);
        $role = Role::where('id', $id)->first();
        switch ($role) {
            case true;
                $role->name = $name;
                $role->save();
                RolesResource::where('role_id',$role->id)->delete();
                foreach ($nu_array as $nu) {
                    $res_id = $nu[2];
                    $perm = $nu[0];
                    self::create_permission($role->id, $res_id, $perm);
                }
                return true;
                break;
            case false;
                return false;
                break;
            default;
                return false;
                break;
        }
    }

    public static function create_permission($role_id, $resource_id, $permission)
    {
        return RolesResource::create(
            [
                'role_id' => $role_id,
                'resource_id' => $resource_id,
                'permission' => $permission
            ]
        );
    }

    public static function update_permission($id, $role_id, $resource_id, $permission)
    {
        $permission = RolesResource::find($id);
        $permission->permission = $permission;
        $permission->role_id = $role_id;
        $permission->resource_id = $resource_id;
        $permission->save();
    }

    public static function check_if_user_is_logged_in()
    {
        if (Auth::check()) {
            return true;
        }
    }

    public static function check_if_user_is_logged_in_else_where(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $user = isset($user->id) ? $user->id : '';
        $token = AccessToken::where('user_id', $user)->where('active', 1)->first();
/*        $token = AccessToken::where('user_id', $user->id)->where('active', 1)->first();*/
        if ($token) {
            self::delete_token($token->id);
            return true;
        } else {
            return false;
        }
    }

    public static function register_user(Request $request)
    {
        $email = $request->email;
        $password = Encryption::encryptString($request->password);
        $check_duplicate = self::checkDuplicateUser($email);
        $role = $request->role_id;
        if ($check_duplicate) {
            $status = false;
            $msg = 'This email is already in use!';
            $user = [$status, $msg];
        } else {
            $status = User::firstOrCreate([
                'email' => $email,
                'password' => Hash::make($password),
                'encrypted_password' => $password,
                'role_id' => $role
            ]);
            $msg = 'User created successfully!';
            $user = [$status, $msg];
        }
        return $user;
    }

    public static function update_user(Request $request, $id)
    {
        $password = !empty($request->password) ? Encryption::encryptString($request->password) : null;
        $user = User::where('id',$id)->first();
        if (!$user) {
            $status = false;
            $msg = 'This user does not exist!';
            $user = [$status, $msg];
        } else {
            $user->email = $request->email;
            if(!is_null($password)){
                $user->password = Hash::make($password);
            }
            $user->role_id = $request->role_id;
            $user->save();
            $msg = 'User updated successfully!';
            $user = [true, $msg];
        }
        return $user;
    }

    public static function perform_post_login_operations($user_details, Request $request)
    {
        $token = Encryption::encryptString(Resources::generateGuid() . '|' . date('Y-m-d H:i:s'));
        $user = User::where('email', $user_details)->first();
        self::save_token($user->id, $token);
        self::update_last_login($user, $token);
    }

    public static function check_user_token()
    {
        $token = AccessToken::where('user_id', Auth::user()->id)->where('token', Auth::user()->token)->where('active', 1)->first();
        return $token;
    }

    public static function save_token($user_id, $token)
    {
        AccessToken::firstOrCreate([
            'user_id' => $user_id,
            'token' => $token,
            'active' => 1
        ]);
    }

    public static function update_last_login($user, $token)
    {
        $user->token = $token;
        $user->online = 1;
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
    }

    public static function delete_token($id)
    {
        $token = AccessToken::where('id', $id)->first();
        $token->active = 0;
        $token->updated_at = date('Y-m-d H:i:s');
        $token->save();
    }

    public static function lock_user($user_details)
    {
        $user = User::where('email', $user_details)->first();
        $user->status = 0;
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
    }

    public static function logout()
    {
        $user = User::find(Auth::user()->id);
        $user->online = 0;
        $user->save();
        Auth::logout();
    }

    public static function checkDuplicateUser($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
    }

    public static function check_user_permission(Request $request, $role, $resource, $type)
    {
        $permission = RolesResource::where('role_id', $role)->where('resource_id', $resource)
            ->where('permission', 'like', '%' . $type . '%')->get();
        if (!$permission) {
            return false;
        } else {
            return true;
        }
    }

    public static function throttle()
    {
        $threshold = 5;
        $period = Carbon::now()->subMinutes(15);
        $count = Throttle::where('attempted_at', '>', $period)->count();
        if ($count > $threshold) sleep(2);
        return false;
    }

    public static function createThrottle(Request $request)
    {
        $provider = Resources::getBrowser();
        $expantiated_details = $provider['name'] . '|' . $provider['version'] . '|' . $provider['platform'] . '|' . $request->email;
        $hashed_details = Encryption::encryptString($expantiated_details);
        Throttle::create([
            'identifier' => $request->email,
            'ip_address' => $request->ip(),
            'attempted_at' => Carbon::now(),
            'unique_details' => $hashed_details
        ]);
    }

    public static function checkLoginAttempts(Request $request)
    {
        $threshold = env('THRESHOLD');
        self::createThrottle($request);
        $provider = Resources::getBrowser();
        $expantiated_details = $provider['name'] . '|' . $provider['version'] . '|' . $provider['platform'] . '|' . $request->email;
        $hashed_details = Encryption::encryptString($expantiated_details);
        $thr = Throttle::where('identifier', $request->email)->where('unique_details', $hashed_details);
        $count = $thr->count();
        if ($count > $threshold) {
            LockedIp::create([
                'identifier' => $request->email,
                'ip_address' => $request->ip(),
                'details' => $hashed_details,
                'lifespan' => Carbon::now()->addMinute(15)
            ]);
            $chk_user = self::checkDuplicateUser($request->email);
            if ($chk_user) {
                self::lock_user($request->email);
            }
            $msg = 'You have too many login attempts, so your account has been locked! Please wait for ' . $threshold . '
             minutes and try again';
        } else {
            $msg = 'Invalid Username & Password';
        }
        return $msg;
    }

    public static function checkLockedIps(Request $request)
    {
        $provider = Resources::getBrowser();
        $expantiated_details = $provider['name'] . '|' . $provider['version'] . '|' . $provider['platform'] . '|' . $request->email;
        $hashed_details = Encryption::encryptString($expantiated_details);
        $locked_ip = LockedIp::where('details', $hashed_details)->first();
        if ($locked_ip) {
            return false;
        } else {
            return true;
        }
    }

}