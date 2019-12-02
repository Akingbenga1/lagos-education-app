<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Role;
use App\permission;
use Session;

class RoleController extends Controller
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
        $roles = Role::all();
        return view('roles.index')->withRoles($roles); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create')->withPermissions($permissions);
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
            'display_name' => 'required|max:255',
            'name' => 'required|max:100|min:5|alpha_dash',
            'description' => 'sometimes|max:255'
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();
        if ($request->permissions) {
             $newpermissions = implode(",", $request->permissions);
            $role->syncPermissions(explode(',', $newpermissions));
        Session::flash('success', 'Successfully updated thr role '.$role->display_name);
        return redirect()->route('roles.show', $role->id);
        } else {
            return redirect()->route('roles.edit')->withInput();
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
        // dd(
        //     class_uses(\App\User::class),
        //     get_class_methods(\App\User::first())
        // );
        $role = Role::findOrFail($id);
        return view('roles.show')->withRole($role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::where('id', $id)->with('permissions')->first();
        $permissions = Permission::all();
        return view('roles.edit')->withRole($role)->withPermissions($permissions);
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
        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255'
        ]);

        $role = Role::findOrFail($id);
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();
        if ($request->permissions) {
             $newpermissions = implode(",", $request->permissions);
        //return $request;
            $role->syncPermissions(explode(',', $newpermissions));
        Session::flash('success', 'Successfully updated thr role '.$role->display_name);
        return redirect()->route('roles.show', $id);
        } else {
            return redirect()->route('roles.edit')->withInput();
        }
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

    public function attachRole(Request $request)
    {        
        $user_id = $request->user;
        // $user->attachRoles([$admin, $owner]);
        
        $user = User::where('id', $user_id)->first();
        foreach ($request->role as $key ) {
            $user->attachRole($key);
        }
        // Session::flash('success', 'Sorry a problem occured while editing this event.');
        return redirect()->route('users.index'); 
        // return redirect()->back();
    }
}
