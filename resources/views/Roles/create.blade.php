@extends('layouts.main')
@section('division_container')

 <div class="row">
      <div class="col-md-10">
        <h1> Create new Role </h1>
      </div>
    </div>
    <div class="col-md-8"><div class="card">
      <div class="card-heading">Role Details</div>
      <div class="card-body">
        <form method="POST" action="{{route('roles.store')}}">
          {{csrf_field()}}
          <div class="form-group col-md-7">
            <label for="display_name">Display Name</label>
            <input type="text" name="display_name" id="display_name" class="form-control">
          </div>
          <div class="form-group col-md-7">
            <label for="name">Slug</label>
            <input type="text" name="name" id="name" class="form-control">
          </div>
          <div class="form-group col-md-7">
            <label for="description">Descripton</label>
            <input type="text" name="description" id="description" class="form-control">
          </div>
          @foreach ($permissions as $permission)
          <div class="form-group col-md-10">
            <label for="{{ $permission->name }}">{{$permission->description}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$permission->display_name}}</label>
            <input type="checkbox" name="permissions['{{ $permission->id }}']" id="{{ $permission->name }}" value="{{ $permission->id }}">
          </div>
          @endforeach
          <button class="btn btn-md btn-success">Create Role</button>
        </form>
      </div>
    </div>
  </div>
    
    
    </div>
    <!-- End container-fluid-->
   </div><!--End content-wrapper-->
@stop