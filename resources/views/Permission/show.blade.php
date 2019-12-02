@extends('layouts.main')
@section('division_container')

  Here you will create the permissions
    <div class="row">
      <div class="col-md-10">
        <h1>Manage Permissions</h1>
        <a href="{{route('permissions.create')}}" class="btn btn-success">Create New Permissions</a>
      </div>
    </div>
    <div class="row">
      <table class="table table-stripped table-condensed table-bordered">
        <thead>
          <tr>
            <td>Display Name</td>
            <td>Slug</td>
            <td>Desription</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ $permission->display_name }}</td>
            <td>{{ $permission->name }}</td>
            <td>{{ $permission->description }}</td>
          </tr>
        </tbody>
      </table>  
    </div>
    </div>
    <!-- End container-fluid-->
   </div><!--End content-wrapper-->
@stop