<?php
/**
 * Created by PhpStorm.
 * User: gbenga
 * Date: 5/8/18
 * Time: 7:45 AM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function __construct(  )
    {
        $this->middleware('web');
    }

    public function processLogin(Request $request)
    {
        $method = $request->isMethod('post');
        if($method)
        {
            $validator = Validator::make($request->all(),
                array(
                    'Email' => 'required|email',
                    'Password' => 'required|min:6',
                )
            );
            if($validator->fails())
            {

                return Redirect::to('login-form')->with(
                    'LoginInfo',
                    'There was a problem with the information you provided')->withErrors($validator)->withInput(Input::except('Password'));

            }
            else
            {

                $auth = Auth::attempt( array(
                    'useremail' => Input::get('Email'),
                    'password' => Input::get('Password'),
                    'activated' => 1
                ));



                if($auth) // check that auth object is created
                {
                    return Redirect::intended('/userprofile.html');

                }
                else
                {
                    return Redirect::route('login-form')->with(
                        'LoginInfo',
                        'Username or Password do not match!')->withInput(Input::except('Password'));
                }
            }
        }
        else
        {
            $Title =  'Login';
            return view('account.loginform', compact('Title' ));
        }
    }

    public function userSignOut()
    {
        Auth::logout();
        return Redirect::route('login');
    }
}