@extends('layouts.main')
@section('division_container')

<h1>This is user management </h1>
<div class="col-md-10">
    <h4>Create New User</h4>
    @if ($errors->any())
    <div class="alert alert-danger col-md-5">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form method="POST" action="{{ route('users.store') }}"> 
        {{ csrf_field() }}
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="useremail">Email</label>
          <input type="email" class="form-control" id="useremail" placeholder="johndoe@gmail.com" name="useremail" value="{{old('useremail')}}">
          </div>
          <div class="form-group col-md-6">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name="password">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="firstname">First Name</label>
          <input type="text" class="form-control" id="firstname" placeholder="John" name="firstname" value="{{old('firstname')}}">
          </div>
          <div class="form-group col-md-4">
            <label for="middlename">Middle Name</label>
          <input type="text" class="form-control" id="middlename" placeholder="" value="{{old('middlename')}}" name="middlename">
          </div>
          <div class="form-group col-md-4">
            <label for="surname">Surname</label>
          <input type="text" class="form-control" id="surname" placeholder="" value="{{old('surname')}}" name="surname" placeholder="Doe">
          </div>
        </div>
        <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="sex">Sex</label>
                  <select name="sex" id="sex">
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="dob">Date of Birth</label>
                <input type="date" name="date_of_birth" id="dob" value="{{old('date_of_birth')}}">
                </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-10">
              <label for="roles">Role</label>
              <br>
              @role('superadmin')
              @foreach ($roles as $role)
              <input type="checkbox" name="role['{{ $role->id }}']" id="{{$role->display_name}}" value="{{$role->id}}">
              {{-- <input class="form-check-input"  type="radio" name="role[principal]" id="{{$role->display_name}}" value="student"> --}}
              <label class="form-check-label" for="studentRole">
                    {{$role->display_name}}
              </label>
              @endforeach
              @endrole
              @role('principal')
              <input class="form-check-input"  type="radio" name="role[principal]" id="studentRole" value="student">
              <label class="form-check-label" for="studentRole">
              Student
              </label>
              <input class="form-check-input"  type="radio" name="role[principal]" id="studentRole" value="teacher">
              <label class="form-check-label" for="teacherRole">
              Teacher
              </label>
              @endrole
              @role('teacher')
              <input class="form-check-input"  type="radio" name="role[teacher]" id="studentRole" value="teacher">
                <label class="form-check-label" for="studentrole">
                student
                </label>
              @endrole
            </div>
          </div>
        <button type="submit" class="btn btn-primary">Sign in</button>
      </form>
    @role('principal')

    @endrole
    @role('teacher')

    @endrole
    

</div>
@stop
