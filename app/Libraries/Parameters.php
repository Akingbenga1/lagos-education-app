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
use App\Role;
use App\Throttle;
use App\User;
use App\Registrar;
use App\RolesResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use UserAgentParser\Provider\WhichBrowser;

class Parameters
{

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

    public static function checkDuplicateRegistrar($email, $name)
    {
        $user = Registrar::where('email', $email)->where('company_name', $name)->first();
        return $user;
    }

}