@extends('layouts.main')
@section('division_container')

        <form action="{{route('permissions.store')}}" method="POST">
    {{csrf_field()}}
    <ul class="tab">
      <li><a href="#" class="tablinks" onclick="openCity(event, 'basic')">Basic Permissions</a></li>
      <li><a href="#" class="tablinks" onclick="openCity(event, 'crud')">Crud Permissions</a></li>
    </ul>

    <div id="basic" class="tabcontent">
      <div class="form-group">
        <label for="basic">Permission Type</label>
        <input type="radio" name="permission_type" id="permission_type" class="form-control" value="basic">
      </div>
      <div class="form-group">
        <label for="display_name">Name</label>
        <input type="text" name="display_name" id="display_name" class="form-control">
      </div>
      <div class="form-group">
        <label for="name">Slug</label>
        <input type="text" name="name" id="name" class="form-control">
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <input type="text" name="description" id="description" class="form-control">
      </div>
    </div>

    <div id="crud" class="tabcontent">
      <div class="form-group">
        <label for="crud">Permission Type</label>
        <input type="radio" name="permission_type" id="permission_type" class="form-control" value="crud">
      </div>
      <div class="form-group">
        <label for="resource">Resource</label>
        <input type="text" name="resource" id="resource" class="form-control">
      </div>
      <div class="form-group">
        <label for="create">Create</label>
        <input type="checkbox" name="crud_selected['create']" id="create" value="create"><br>
        <label for="read">Read</label>
        <input type="checkbox" name="crud_selected['read']" id="read" value="read"><br>
        <label for="update">Update</label>
        <input type="checkbox" name="crud_selected['update']" id="update" value="update"><br>
        <label for="delete">Delete</label>
        <input type="checkbox" name="crud_selected['delete']" id="delete" value="delete"><br>
      </div>
    </div>
    
    <button class="btn btn-primary">Create User</button>
  </form>
    </div>
    <!-- End container-fluid-->
   </div><!--End content-wrapper-->

    @stop
    