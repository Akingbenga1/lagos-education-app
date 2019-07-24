@extends('layouts.main')
<!--  extend the particular  layout that you want. so, you can have as much layouts as you like  -->

<!--  extends  adminmain. is front page of admin side. contains product upload forms,  -->

  @section('division_container')
 
     
      <span class="ReportStudentClass"></span>
      
        
        <div class=" col-md-6">
          <div class="panel panel-default">
                       <form class="form-horizontal" action="{{URL::route('admin-create-roles')}}" method="post">
                          <div class="panel-heading clearfix ">
                                <h3 class="panel-title">  Add a new role </h3>
                          </div>
                            <div class="modal-body">       
                                <div class="form-group">      
                                      <div class="ChooseStudent row">      
                                         
                                           <p class="col-md-12">
                                            @if(Session::has('ComplainError'))
                                                 <span class="text-danger"> {{ Session::get('ComplainError')}} </span>
                                            @endif 
                                               <br />
                                          Add Name: </p> 
                                           <input type="text" name="name" class="form-control col-md-12 TypeStudentName" />
                                           &nbsp;
                                      </div>
                                </div>      
                            </div>
                            <div class="panel-footer">
                                <button type = "submit"  class="StudentButton btn btn-default btn-lg btn-block">
                                      <i class="glyphicon glyphicon-check" > </i>
                                      Add role 
                                </button>
                                 <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            </div>
                        </form>
          </div>
        </div>
       @if(!empty($Users) and !empty($Roles) ) 
          <div class=" col-md-6">
             <div class="panel panel-default">
                 <form class="form-horizontal" action="{{URL::route('admin-attach-roles')}}" method="post">
                    <div class="panel-heading clearfix ">
                          <h3 class="panel-title"> Assign  Roles to User </h3>
                    </div>
                      <div class="modal-body">       
                          <div class="form-group">      
                                 @if(Session::has('ComplainError'))
                                                 <span class="text-danger"> {{ Session::get('ComplainError')}} </span>
                                            @endif 
                          </div> 
                           <div class="form-group">      
                                <div class="ChooseStudent row">      
                                     <select name = "UserName" class="form-control TypeStudentName" >
                                        <option> -- Choose User -- </option>
                                        @if(isset($Users) and is_array($Users))
                                              @foreach($Users as $EveryUser)
                                                      <option value="{{$EveryUser['userid']}}"> {{$EveryUser['useremail']}} </option>
                                              @endforeach
                                        @else
                                            <option> No user available  </option>
                                        @endif
                                    </select>
                                </div>
                          </div>      
                          <div class="form-group">      
                              <div class="ChooseStudent row">      
                                  <select name = "Roles" class="form-control TypeStudentName" >
                                        <option> -- Choose Roles -- </option>
                                        @if(isset($Roles) and is_array($Roles))
                                              @foreach($Roles as $EveryRole)
                                                      <option value="{{$EveryRole['id']}}"> {{$EveryRole['name']}} </option>
                                              @endforeach
                                                                
                                    @else
                                        <option> No roles Available  </option>
                                    @endif
                                  </select>
                              </div>
                          </div> 
                      </div>
                      <div class="panel-footer">
                          <button type = "submit"  class="StudentButton btn btn-default btn-lg btn-block">
                                <i class="glyphicon glyphicon-check" > </i>
                                Attach role
                          </button>
                           <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                      </div>
                  </form>
             </div>
          </div>
       @else
           <div class="col-md-6">
            <h2> Sorry!</h2> This Form cannot be loaded at the moment. 
            </div>
       @endif

            

   
<h3> Assigned Roles Table </h3> 

    <div class="ChangeRoleClass"> </div>

        @if( is_array($AssignedRoles) and !empty($AssignedRoles) )
        <input type="hidden" class="ChangeRolesURL" value ="{{URL::route('admin-edit-roles')}}" />
        <b> Search for anybody on this table: </b> <input type="text" id="search" placeholder="Search this table"></input> <br /><br />
          <?php $Count = 1; 
              $checkVars = array('Super User', "Vice Principal", "Principal", "Administrator","SchoolAdministrator", 
                                  "SS1A", "SS1B", "SS1C", "SS1D", "SS1E", "SS1F", "SS1G", "SS1H", "SS1K", "SS1J",
                                  "SS2A", "SS2B", "SS2C", "SS2D", "SS2E", "SS2F",
                                  "SS3A", "SS3B", "SS3C");
              $SplitAssignRoles = array_chunk($AssignedRoles, ceil(count($AssignedRoles) / 3));
                //var_dump(count($SplitAssignRoles[0]));
                 $count = 1; 
               ?>
          <md-content class="md-padding" layout-xs="column" layout="row"> 
               @foreach($SplitAssignRoles as $EachSplitedAssignedRoles) 
                    <div flex-xs flex-gt-xs="33" layout="column">            
                         @foreach($EachSplitedAssignedRoles as $EachAssignedRoles)
                          <md-card id="{{$count}}" md-theme="<% showDarkTheme ? 'dark-purple' : 'default' %>" md-theme-watch>
                                 <div class="md-media-sm card-media"  layout="row" layout-align="center center"  >
                                           
                                                   {{HTML::image("/Images/Icons/avatar.jpg", 
                                                       'avater', 
                                                       array('class' => 'img-responsive', 'width' => "55%") )}}
                                          
                                  </div>
                                  <br />
                              <md-divider></md-divider>
                              <md-card-title>
                                <md-card-title-text>
                                  <span class="md-headline">
                                  @if (strlen($EachAssignedRoles['role_belong']['name']) > 10)
                                          {{ $str = substr($EachAssignedRoles['user_belong']['surname'] , 0, 5) . '...';
                                          }}                                           
                                          @else
                                               {{ $EachAssignedRoles['user_belong']['surname']
                                          }} 
                                  @endif
                                                 @if($EachAssignedRoles['role_belong']['name'] == 'Super User' or
                                              $EachAssignedRoles['role_belong']['name'] == "Principal"
                                             )
                                              <md-button class="md-raised md-warn">{{$EachAssignedRoles['role_belong']['name']}} </md-button> 
                                             @else
                                              <md-button class="md-raised md-primary">{{$EachAssignedRoles['role_belong']['name']}}</md-button>
                                            @endif   
                                  </span>
                                  <span class="md-subhead">   
                                   @if ( strlen($EachAssignedRoles['user_belong']['useremail']) < 1 )
                                             <span class="text-muted"> Email Unavailable </span>
                                          @else
                                              {{$EachAssignedRoles['user_belong']['useremail']}}     
                                         
                                  @endif                                   
                                                                               
                                  </span>
                                </md-card-title-text>                               
                              </md-card-title>
                              <md-divider></md-divider>
                              <md-card-actions layout="row">
                                <select name = "NewRoleId">
                                            <option> -- Choose Roles -- </option>
                                              @if(isset($Roles) and is_array($Roles))
                                                  @foreach($Roles as $EveryRoles)
                                                     <option value="{{$EveryRoles['id']}}" {{($EachAssignedRoles["role_id"] == $EveryRoles['id']) ? "selected":'' }}> 
                                                                    {{$EveryRoles['name']}}
                                                     </option>
                                                @endforeach
                                                 @else
                                                <option> No Role Available  </option>
                                            @endif
                                        </select> 
                                <md-button class="ChangeRolesButton" >Change</md-button>
                                         
                                 <md-button href="{{URL::route('admin-detach-roles' ,
                                                     ['EachAssignedRoles' => $EachAssignedRoles['role_id'],
                                                      'EachUserId' => $EachAssignedRoles['user_belong']['userid'] ])}}">
                                                       Delete</md-button> 
                              </md-card-actions>
                              <input type="hidden" name="OldRoleId" value="{{$EachAssignedRoles['role_id']}}" /> 
                            <input type="hidden" name="UserID" value="{{$EachAssignedRoles['user_id']}}" />  
                            <input type="hidden" name="AssignedRoleID" value="{{$EachAssignedRoles['id']}}" />  
                            </md-card>            
                          @endforeach
                    </div>
                 @endforeach
          </md-content>      
              
            
               
        @else
            <p>No Staff found in database</p>
        @endif

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







   Go to <a href="{{URL::route('admin-home')}}"> Admin Home </a><br/>


<script>
   $(function () {$('.ChangeRolesButton').on("click", function (e) 
                    { 
                        e.preventDefault(); 
                        
                         var ActionUrl  = $('.ChangeRolesURL').val();
                          var NewRoleId =    $(this).parents().eq(1).find('select').val();
                          var OldRoleId =    $(this).parents().eq(1).find('input[type="hidden"][name="OldRoleId"]').val();
                          var UserID =    $(this).parents().eq(1).find('input[type="hidden"][name="UserID"]').val(); 
                          var AssignedRoleID =    $(this).parents().eq(1).find('input[type="hidden"][name="AssignedRoleID"]').val();
                          
                          RoleEditData =  {'NewRoleId' :  NewRoleId, 'OldRoleId': OldRoleId,'UserID': UserID,'AssignedRoleID' : AssignedRoleID};
                           console.log( UserID );
                        console.log(RoleEditData);
                        $.ajax({
                                  type: 'POST',
                                  url:  ActionUrl,
                                  dataType: 'json',
                                  data: RoleEditData ,
                                  success: function(response, textStatus)
                                            {  
                                               $(".ChangeRoleClass").html(response.ReportChange);
                                               //console.log(response);
                                               alert(response.AlertReportChange);
                                               alert("This page will be reloaded now");                                               
                                                window.location.reload();                                    
                                            },
                                  error: function(xhr, textStatus, errorThrown) 
                                            {
                                                alert('Bad response. Reloading the page...');
                                                console.log(xhr);
                                                console.log(textStatus);
                                                console.log(errorThrown);
                                               window.location.reload();
                                            }
                                        });//end ajax
                            });//end anon function   
                      });     
    
</script>

@stop