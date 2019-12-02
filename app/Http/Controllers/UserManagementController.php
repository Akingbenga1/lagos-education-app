<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Role;
use App\Permission;
use Session;
use Auth;

class UserManagementController extends Controller
{

    public function __construct(  )
    {

        $this->middleware('auth',  ['except' => 'register']);
        $this->middleware('web');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$user get the user details and then use hasRole to 
        //distinguish what any user see based on their levels
        $user = Auth::user();
        if ($user->hasRole('teacher')) 
        {
        $roles = DB::table('role_user')
        ->join('users', 'role_user.user_id', '=', 'users.id')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->where('roles.name', '=', "student")
        ->paginate(20);
        } elseif ($user->hasRole('principal')) 
        {
        $roles = DB::table('role_user')
        ->join('users', 'role_user.user_id', '=', 'users.id')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->where('roles.name', '=', "student")
        ->orWhere('roles.name', '=', "teacher")
        ->paginate(20);
        }elseif ($user->hasRole('superadmin')) {
        $roles = DB::table('role_user')
        ->join('users', 'role_user.user_id', '=', 'users.id')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->paginate(20);
        }

        $permissions = DB::table('permission_user')
        ->join('users', 'permission_user.user_id', '=', 'users.id')
        ->join('permissions', 'permission_user.permission_id', '=', 'permissions.id')
        // ->select('users.*', 'contacts.phone', 'orders.price')
        ->paginate(20);
            
        return view('user.index')->withRoles($roles)->withPermissions($permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view("user.create")->withRoles($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'useremail'=> 'required|max:100|email|unique:users,useremail',
            'firstname'=> 'required|max:200',
            'surname'=> 'required|max:200',
            'middlename'=> 'nullable|max:200',
            'date_of_birth' => 'nullable|date',
            'password' => 'required'
        ]);
        $user = new User;
        $user->useremail = $request->useremail;
        $user->firstname=$request->firstname;
        $user->surname=$request->surname;
        $user->middlename=$request->middlename;
        $user->date_of_birth=$request->date_of_birth;
        $user->password=$request->password;
        $user->activated=true;
        if($user->save())
        {
            $user_id = $user->id;
            $user = User::where('id', $user_id)->first();
            
            foreach ($request->role as $key ) {
                $role = Role::where('name', $key)->first();
                $user->attachRole($role);
            }
            Session::flash('success', 'User was successfully created');
            return redirect()->route('users.index'); 
        }
        return $user;

        return $request;

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

    public function detachUserRole(Request $request)
    {
        $user = User::where('id', $request->user)->first();
        // return $user;
        $role = Role::where('id', $request->role)->first();
        $user->detachRole($role);
        return redirect()->route('users.index');
    }

    public function detachUserPermission(Request $request)
    {
        $user = User::where('id', $request->user)->first();
        // return $user;
        $permision = Permission::where('id', $request->permission)->first();
        $user->detachPermission($permision);
        return redirect()->back();
    }

    public function searchUser(Request $request)
    {
        $searchTerm = $request->searchuser;
        $roles = Role::all();
        $permissons = Permission::all();
        $users = DB::table('users')
                ->where('firstname', 'LIKE', "%{$searchTerm}%") 
                ->orWhere('surname', 'LIKE', "%{$searchTerm}%") 
                ->orWhere('useremail', 'LIKE', "%{$searchTerm}%") 
                ->orWhere('middlename', 'LIKE', "%{$searchTerm}%")
                ->paginate(15);
        return view('user.search')->withUsers($users)->withRoles($roles);
    }

   
}
