@extends('layouts.main')
@section('division_container')

<h1>This is user management </h1>
@if (Session::has('success'))
    <div class="alert alert-success">
            {{Session::get('success')}}
    </div>
@endif
<div class="col-md-8">
    <div class="col-md-5">
            @role('superadmin')
                <a href="{{ route('users.create') }}" class="btn btn-primary">Create New User</a>
            @endrole
            @role('principal')
                <a href="{{ route('users.create') }}" class="btn btn-primary">Create New Student/Teacher</a>
            @endrole
            @role('teacher')
                <a href="{{ route('users.create') }}" class="btn btn-primary">Create New Student</a>
            @endrole
    </div>
        @role(['principal', 'teacher'])
        <h2>User Management</h2>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Middle Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                        <td>{{$role->firstname}} </td>
                        <td>{{$role->surname}}</td>
                        <td>{{$role->middlename}}</td>
                        <td>{{$role->useremail}}</td>
                        <td>{{$role->name}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $roles->links() }}
</div>
@endrole
@role('superadmin')
    <div class="col-md-10">
        Assign new Role to a user
        <form action="/searchuser" method="post">
            {{ csrf_field() }}
        <input type="text" name="searchuser" id="searchuser" value="{{old('searchuser')}}">
        <button class="btn btn-primary">Find User to assign Role</button>
        </form>
    </div>
    <div class="col-md-8">
        <h2>Role Management</h2>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Remove Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                        <td>{{$role->firstname}} </td>
                        <td>{{$role->surname}}</td>
                        <td>{{$role->name}}</td>
                        <td><form method="POST" action="{{ route('detachUserRole') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="role" value='{{$role->role_id}}' >
                            <input type="hidden" name="user" value='{{$role->user_id}}' >
                            <button type="submit">Detach Role</button>
                            </form>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $roles->links() }}
    <h3>Permission Management</h3>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Permission</th>
                    <th scope="col">Delete Permission</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                        <td>{{$permission->firstname}} </td>
                        <td>{{$permission->surname}}</td>
                        <td>{{$permission->display_name}}</td>
                        <td><form method="POST" action="{{ route('detachUserPermission') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="permission" value='{{$permission->permission_id}}' >
                            <input type="hidden" name="user" value='{{$permission->user_id}}' >
                            <button type="submit">Detach Permission</button>
                            </form>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
        {{ $permissions->links() }}
    </div>
@endrole
@stop
