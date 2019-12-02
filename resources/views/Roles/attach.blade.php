@extends('layouts.main')
@section('division_container')

<h1>This is user management </h1>
<div class="col-md-8">
    <h2>Select role to assign</h2>
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
                @foreach ($roles as $role)
                    <tr>
                    <td>{{$user->firstname}} </td>
                    <td>{{$user->surname}}</td>
                    <td>{{$user->useremail}}</td>
                    <td><form method="POST" action="{{ url('/searcheduser') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="user" value='{{$user->id}}' >
                        <button type="submit">Attach Role</button>
                        </form>
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    

@role('superadmin')
Administrator okay
@endrole 

@permission('create-users')
  Create User
@endpermission
</div>
@stop
