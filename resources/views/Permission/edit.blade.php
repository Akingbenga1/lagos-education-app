@extends('layouts.main')
@section('division_container')

  
    <div class="row">
      <div class="col-md-10">
        <h1>Manage Permissions</h1>
        <a href="{{route('permissions.create')}}" class="btn btn-success">Create New Permissions</a>
      </div>
    </div>
    <div class="row">
      <form action="{{route('permissions.update', $permission->id)}}" method="POST">
        {{method_field('PUT')}}
        {{csrf_field()}}
        <div class="form-group">
          <label for="display_name">Display Name</label>
          <input type="text" name="display_name" id="display_name" value="{{ $permission->display_name}}">
        </div>
        <div class="form-group">
          <label for="display_name">Description<label>
          <input type="text" name="description" id="description" value="{{ $permission->description}}">
        </div>
        <div class="form-group">
          <label for="display_name">Name<label>
          <input type="text" name="name" id="name" value="{{ $permission->name}}" disabled>
        </div>
        <button class="btn btn-primary">Edit Permission</button>      
      </form> 
    </div>  
  </div>  
    </div>
    <!-- End container-fluid-->
   </div><!--End content-wrapper-->
@stop