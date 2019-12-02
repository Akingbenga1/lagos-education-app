@extends('layouts.main')
@section('division_container')

<h1>This is user management </h1>
<div class="col-md-5">
    Assign new Role to a user
    <form action="/searchuser" method="post">
        {{ csrf_field() }}
    <input type="text" name="searchuser" id="searchuser" value="{{old('searchuser')}}">
    <button class="btn btn-primary">Find User to assign Role</button>
    </form>
</div>
<div class="col-md-10">
    <h2>Pick User to Assign Role</h2>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">Attach Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                    <td>{{$user->firstname}} </td>
                    <td>{{$user->surname}}</td>
                    <td>{{$user->useremail}}</td>
                    <td><form method="POST" action="{{ url('/attachrole') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="user" value='{{$user->id}}' >
                        @foreach ($roles as $role)
                            <label for="{{$role->display_name}}{{$user->id}}">{{$role->display_name}}</label>
                            <input type="checkbox" name="role['{{ $role->id }}']" id="{{$role->display_name}}{{$user->id}}" value="{{$role->id}}">
                        @endforeach
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" class="btn btn-primary">Attach Role</button>
                        </form>
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    

</div>
@stop
