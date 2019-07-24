@extends('layouts.main')
<!--  extend the particular  layout that you want. so, you can have as much layouts as you like  -->

<!--  extends  adminmain. is front page of admin side. contains product upload forms,  -->

  @section('division_container')
 
      <h3> Add a new Staff </h3>
        @if(Session::has('ComplainError'))
            {{ Session::get('ComplainError')}}
        @endif

     
          {{ Form::open(array( 'route' => 'add-staff-page' , 'method'=> 'post')) }}  

            <div class="w3-row">

                <div class="w3-third">
                    @if($errors->has('Year'))
                       {{ $errors->first('Year')}}
                     @endif
                    <div class="YearDiv">
                        <span class="YearLabel"> Year </span>
                        <select name = "Year" class="Year" >
                          <option> -- Choose year -- </option>
                          <option value="2015"> 2015/2016 </option>
                          <option value="2016"> 2016/2017 </option>
                        </select>
                    </div>  
                </div>

                <div class="w3-third">
                   @if($errors->has('Class'))
                     {{ $errors->first('Class')}}
                   @endif
                   <div class="ClassDiv">
                      <span class="ClassLabel"> Class </span>
                      <select name = "Class" class="Class" >
                          <option> -- Choose class -- </option>
                          <option value="SS1">SS1</option>
                          <option value="SS2">SS2</option>
                          <option value="SS3">SS3</option>
                      </select>
                   </div>
                </div>

                <div class="w3-third">
                    @if($errors->has('TermName'))
                       {{ $errors->first('TermName')}}
                     @endif
                    <div class="TermDiv">
                      <span class="TermLabel"> Term </span>
                      <select name = "TermName" class="TermName" >
                          <option> -- Choose term -- </option>
                          <option value="first term"> First term</option>
                          <option value="second term"> Second term</option>
                          <option value="third term"> Third term</option>
                      </select>
                    </div>
                </div>
            </div>

              <br /><br />

            <div class="w3-row">

                  <div class="w3-third">
                           @if($errors->has('SubClass'))
                             {{ $errors->first('SubClass')}}
                           @endif

                            <div class="SubClassDiv">
                                <span class="SubClassLabel"> SubClass </span>
                                <select name = "SubClass" class="SubClassSelect" >
                                     <option> -- Choose subclass -- </option>
                                     <option value="a">A</option>
                                     <option value="b">B</option>
                                     <option value="c">C</option>
                                     <option value="d">D</option>
                                     <option value="e">E</option>
                                     <option value="f">F</option>
                                     <option value="g">G</option>
                                     <option value="h">H</option>
                                     <option value="i">I</option>
                                     <option value="j">J</option>
                                     <option value="k">K</option>
                                </select> 
                            </div>
                  </div>

                   <div class="w3-third">
                          @if($errors->has('Subject'))
                                   {{ $errors->first('Subject')}}
                                 @endif
                            <div class="SubjectDiv">
                                 <span class="SubjectLabel"> Subjects </span>
                                  <select name = "Subject" class="Subject" >
                                  <option> -- Choose Subject -- </option>
                                  @if(isset($AllSubjects) and is_array($AllSubjects))
                                      @foreach($AllSubjects as $EverySubject)
                                           <option value="{{$EverySubject['subjectid']}}" > 
                                                          {{$EverySubject['subject']}}
                                             </option>
                                      @endforeach
                                  @else
                                      <option> No Subject Available  </option>
                                  @endif
                              </select>
                            </div>
                   </div>

                     <div class="w3-third">
                           @if($errors->has('AssignedUser'))
                             {{ $errors->first('AssignedUser')}}
                           @endif
                            <select name = "AssignedUser" >
                              <option> -- Choose Assigned User -- </option>
                              @if(isset($AllAssignedRoles) and is_array($AllAssignedRoles))
                                    @foreach($AllAssignedRoles as $EveryAllAssignedRole)
                                           <option value="{{$EveryAllAssignedRole['id']}}"> 
                                              {{$EveryAllAssignedRole['user_belong']['surname']}} 
                                              <strong>( {{$EveryAllAssignedRole['user_belong']['useremail'] }})</strong> ==>
                                              {{$EveryAllAssignedRole['role_belong']['name'] }}
                                           </option>
                                    @endforeach
                              @else
                                  <option> No user available  </option>
                              @endif
                            </select>
                     </div>
            </div>

                 @if($errors->has('Roles'))
                 {{ $errors->first('Roles')}}
               @endif
              <!--  <select name = "Roles" >
                    <option> -- Choose Roles -- </option>
                   @if(isset($AllAssignedRoles) and is_array($AllAssignedRoles))
                        @foreach($AllAssignedRoles as $EveryAllAssignedRole)
                               <option value="{{$EveryAllAssignedRole['role_belong']['id']}}"> 
                                  {{$EveryAllAssignedRole['role_belong']['name']}}
                               </option>
                        @endforeach
                    @else
                      <option> No Role Available  </option>
                   @endif
                </select> -->

                 <br /><br />
              If this particular staff has an added designation ( for example, Vice Principal, academics ), add designation here: 
                   @if($errors->has('Designation'))
                 {{ $errors->first('Designation')}}
               @endif
                  <input type="text" name ="Designation" class ="Designation"  /> 
              {{Form::token()}}       
               <input type = "submit" value="Add Staff" class ="AddStaffButton" />
              {{Form::close()}}

       @if( is_array($AllFullstaff) and !empty($AllFullstaff) )
       <b> Search for anybody on this table: </b> <input type="text" id="search" placeholder="Search this table"></input> <br /><br />          
                <table border = "1" class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
                  <tr class="w3-grey w3-text-white"> 
                    <th> S/N </th>
                    <th> Staff full name </th>
                    <th> Staff email  </th>
                    <th> Staff Roles  </th>
                    <th> Subject taught  </th>
                    <th> Designated class  </th>
                    <th> Relevant term  </th>
                    <th> Edit Staff Details  </th>
                  </tr>
                  <?php $Count = 1; ?>
                  @foreach($AllFullstaff as $EachStaffs)
                    <tr class="w3-hover-green"> 
                      <td> {{$Count++}} </td>
                      <td> {{ $EachStaffs['ThisStaffDetails']['user_belong']['surname']." ". 
                              $EachStaffs['ThisStaffDetails']['user_belong']['middlename']." ". 
                              $EachStaffs['ThisStaffDetails']['user_belong']['firstname']
                           }} 
                      </td>
                      <td> {{$EachStaffs['ThisStaffDetails']['user_belong']['useremail']}} </td>
                      <td> {{ $EachStaffs['ThisStaffDetails']['role_belong']['name']}} </td>
                      <td> {{$EachStaffs['ThisStaffSubject']}} </td>
                      <td> {{$EachStaffs['ThisStaff']['classname']. " ". strtoupper( $EachStaffs['ThisStaff']['subclass']) }} </td>
                      <td> {{$EachStaffs['ThisStaff']['termname'] }}</td>
                      <td>  <a href="StaffEditPage.html/{{ $EachStaffs['ThisStaff']['stafftableid']}}" > edit staff </a> </td>           

                    </tr>
                  @endforeach
                </table>
        @else
            <p>No Staff found in database</p>
        @endif

              
   Go to <a href="{{URL::route('admin-home')}}"> Admin Home </a><br/>


@stop