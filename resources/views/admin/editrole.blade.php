@extends('layouts.main')
<!--  extend the particular  layout that you want. so, you can have as much layouts as you like  -->

<!--  extends  adminmain. is front page of admin side. contains product upload forms,  -->

  @section('division_container')
 
      <h3> Add a new role </h3>
       @if(Session::has('ComplainError'))
     {{ Session::get('ComplainError')}}
  @endif
          {{ Form::open(array( 'route' => 'admin-create-roles' , 'method'=> 'post')) }}           
              @if($errors->has('name'))
                 {{ $errors->first('name')}}
               @endif
              Add Name: <input type="text" name="name" />
              {{Form::token()}}       
               <input type = "submit"  value="Add role" />
              {{Form::close()}}

               <h3> Add a new Permission </h3>
          {{ Form::open(array( 'route' => 'admin-create-permissions' , 'method'=> 'post')) }}           
              @if($errors->has('permission_name'))
                 {{  $errors->first('permission_name')}} <br />
               @endif
              Permission Name: <input type="text" name="permission_name" /><br/>

              @if($errors->has('display_name'))
                 {{$errors->first('display_name')}} <br />
               @endif
              Display name: <input type="text" name="display_name" /><br/>
              {{Form::token()}}       
               <input type = "submit"  value="Add Permission" />
              {{Form::close()}}

       <h3>  Assign Permissions to Roles</h3>
          @if(!empty($Roles) and !empty($Permissions) )
          {{ Form::open(array( 'route' => 'admin-attach-permissions' , 'method'=> 'post')) }}           
          <select name = "Roles" >
              <option> -- Choose Roles -- </option>
              @if(isset($Roles) and is_array($Roles))
                    @foreach($Roles as $EveryRole)
                            <option value="{{$EveryRole['id']}}"> {{$EveryRole['name']}} </option>
                    @endforeach
          @else
              <option> No Category Available  </option>
          @endif
        </select>



        <h2> Permissions</h2>
             Choose Permissions 
              @if(isset($Permissions) and is_array($Permissions))
                    @foreach($Permissions as $EveryPermission)
                        <input type="checkbox" name="PermissionList[]" value="{{$EveryPermission['id']}}"> 
                        {{$EveryPermission['name']}}<br />
                    @endforeach
          @else
              No Permission Available from database. Please add new Permissions usding the form above.  
          @endif
               
 <input type = "submit"  value="Attach Permissions" />
 {{Form::close()}}

@endif



<h3> Assigned Roles Table </h3> 

        @if( is_array($AssignedRoles) and !empty($AssignedRoles) )
        <table border = "1">
          <tr> 
            <th> Staff full name </th>
            <th> Staff email  </th>
            <th> Staff Roles  </th>
            <th> Edit Roles  </th>
            <th> Detach/Delete role </th>
          </tr>
          @foreach($AssignedRoles as $EachAssignedRoles)
            <tr> 
              <td> {{ $EachAssignedRoles['user_belong']['surname']." ". 
                      $EachAssignedRoles['user_belong']['middlename']." ". 
                      $EachAssignedRoles['user_belong']['firstname']
                   }} 
              </td>
              <td> {{$EachAssignedRoles['user_belong']['useremail']}} </td>
              <td> {{ $EachAssignedRoles['role_belong']['name']}} </td>
              <td>  <a href="EditRolePage.html/{{ $EachAssignedRoles['id']}}" > Edit Staff Role </a> </td>           
              <td>  <a href="{{URL::route('admin-detach-roles' , ['EachAssignedRoles' => $EachAssignedRoles['id'],
                             'EachUserId' => $EachAssignedRoles['user_belong']['userid'] ])}}" > Detach/Delete Staff Role </a> </td>           

            </tr>
          @endforeach
        </table>
        @else
            <p>No Staff found in database</p>
        @endif


 @if(!empty($Users) and !empty($Roles) )
   <h3>Assign  Roles to User</h3>
 {{ Form::open(array( 'route' => 'admin-attach-roles' , 'method'=> 'post')) }}    
          <select name = "UserName" >
              <option> -- Choose User -- </option>
              @if(isset($Users) and is_array($Users))
                    @foreach($Users as $EveryUser)
                            <option value="{{$EveryUser['userid']}}"> {{$EveryUser['useremail']}} </option>
                    @endforeach
              @else
                  <option> No user available  </option>
              @endif
          </select>

          <select name = "Roles" >
              <option> -- Choose Roles -- </option>
              @if(isset($Roles) and is_array($Roles))
                    @foreach($Roles as $EveryRole)
                            <option value="{{$EveryRole['id']}}"> {{$EveryRole['name']}} </option>
                    @endforeach
                <input type = "submit"  value="attach role" />
              {{Form::close()}}
          @else
              <option> No roles Available  </option>
          @endif
        </select>
              
 @else
 <h2> Sorry!</h2> This Form cannot be loaded at the moment. 
@endif
   Go to <a href="{{URL::route('admin-home')}}"> Admin Home </a><br/>



@stop