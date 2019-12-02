@extends('layouts.main')
@section('division_container')

        <div class="row">
      <div class="col-md-10">
        <h1>Manage Roles</h1>
        <a href="{{route('roles.create')}}" class="btn btn-success">Create New Roles</a>
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
          @foreach($roles as $role)
          <tr>
            <td>{{ $role->display_name }}</td>
            <td>{{ $role->name }}</td>
            <td>{{ $role->description }}</td>
             <td><a href="{{ route('roles.show', $role->id) }}">Show</a> </td>
            <td><a href="{{ route('roles.edit', $role->id) }}">Edit</a> </td> 

          </tr>
          @endforeach
        </tbody>
      </table>  
    </div>

    </div>
    <!-- End container-fluid-->
   </div><!--End content-wrapper-->
@stop