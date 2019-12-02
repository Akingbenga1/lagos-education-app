@extends('layouts.main')
@section('division_container')

  <div class="row">
      <div class="col-md-10">
        <h1> Edit {{ $role->display_name }} <small>{{ $role->name }}</small></h1>
        <a href="{{route('roles.create')}}" class="btn btn-success">Create New Role</a>
      </div>
    </div>
    <form method="POST" action="{{route('roles.update', $role->id)}}">
      {{csrf_field()}}
      {{method_field('PUT')}}
      <div class="form-group col-md-7">
        <label for="display_name">Display Name</label>
      <input type="text" name="display_name" id="display_name" value="{{$role->display_name}}" class="form-control">
      </div>
      <div class="form-group col-md-7">
        <label for="name (Cannot be edited)">Slug</label>
      <input type="text" name="name" id="name" value="{{$role->name}}" class="form-control" disabled>
      <div class="form-group col-md-7">
        <label for="description">Descripton</label>
      <input type="text" name="description" id="description" value="{{$role->description}}" class="form-control">
      </div>
        @foreach ($permissions as $permission)
        <div class="form-group col-md-10">
          <label for="{{ $permission->name }}">{{$permission->description}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$permission->display_name}}</label>
          <input type="checkbox" name="permissions['{{ $permission->id }}']" id="{{ $permission->name }}" value="{{ $permission->id }}">
        </div>
      <!-- {!!$role->permissions->pluck('name')!!} -->
        @endforeach
        <button class="btn btn-md btn-success`">Edit Roles</button>
    </form>
    </div>
    <!-- End container-fluid-->
   </div><!--End content-wrapper-->
@stop