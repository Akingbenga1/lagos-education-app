@extends('layouts.main')
@section('division_container')


  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumb-->
    <div class="row">
      <div class="col-md-10">
        <h1>Manage {{ $role->display_name }} <small>{{ $role->name }}</small></h1>
        <a href="{{route('roles.create')}}" class="btn btn-success">Create New Role</a>
      </div>
    </div>
    <div class="row">
      <p>{{ $role->description }}</p>
      <ul>
        @foreach ($role->permissions as $r)
          <li>{{ $r->display_name }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({{$r->description}})</li>
        @endforeach
      </ul>
    </div>
  
@role('admin')
Administrator okay
@endrole



    </div>
    <!-- End container-fluid-->
   </div><!--End content-wrapper-->
@stop