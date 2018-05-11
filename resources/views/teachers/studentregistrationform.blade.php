@extends('layouts.main')

@section('division_container')

         <h3> Student Registration</h3> 

        
            @include('includes.adminlinks')
             <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
         

    </div>
    <div role="tabpanel" class="tab-pane" id="profile">...</div>
    <div role="tabpanel" class="tab-pane" id="messages">...</div>
    <div role="tabpanel" class="tab-pane" id="settings">...</div>
  </div>


 <div class="RegistrationInfo"> 
            <div class="RegInstruction">
                 <h3> {{HTML::image("/Images/Icons/announce1.jpg", '', array('class' => 'HomeIcon') )}} Instruction </h3>
                    Register student information here by entering their names and Student admission number.<br />
                    <b> Student surname and student first name are compulsory.</b><br />
                    Student Admission Number <b> must be a 4-digit number </b>. <br />
                    <b> 3-digit is not accepted by the system.</b><br />
                       Student middlename is optioinal.<br/>
            </div>              
          </div>  
      
      <div class="UtilitySection">
          <div class="UtilityBar">
              <div class="QuickAction">
                  <a href="{{URL::action('edit-student-details-form')}}"> Edit student details  </a>
                 <!--  <a href="{{URL::action('edit-student-details-form')}}"> Remove student details  </a> -->
             </div>  

              @if(Session::has('AccountCreateInfo') and Session::has('GoodResponse'))
                    @if(Session::get('GoodResponse') == 1 )
                        <div class="RegistrationGoodResponse"> {{Session::get('AccountCreateInfo')}} </div>
                    @elseif(Session::get('GoodResponse') == 0 )
                        <div class="RegistrationBadResponse">{{Session::get('AccountCreateInfo')}}</div>
                    @else
                     <div class="EmptyVessels"></div>
                    @endif
              @else
                    <div class="EmptyVessels"></div>
                @endif                         
          </div>      
          <div class="StudentRegistrationForm">          
               {{ Form::open(array( 'route' => 'register-student-details' ,'files' => true, 'method'=> 'post')) }}

                   <div class="InputBlock"> 
                         <p> Student surname:(<b> compulsory </b>)</p>
                          <div> 
                                @if($errors->has('StudentSurname'))
                                    <span class="text-danger StudentRegisterError">{{$errors->first('StudentSurname')}}</span>
                                @endif<br />
                                <input type="text" name="StudentSurname"  id="StudentSurname" />
                          </div>
                    </div>

                    <div class="InputBlock"> 
                          <p> Student firstname: (<b> compulsory </b>)</p>
                          <div>
                             @if($errors->has('StudentFirstname'))
                                   <span class="text-danger StudentRegisterError">{{$errors->first('StudentFirstname')}}</span>
                             @endif<br />
                             <input type="text" name="StudentFirstname" id="StudentFirstname" />
                          </div>
                    </div>

                    <div class="InputBlock">
                          <p> Student admission number: (<b> compulsory </b>) </p>
                          <div>
                              @if($errors->has('StudentAdmissionNumber'))
                                    <span class="text-danger StudentRegisterError">{{$errors->first('StudentAdmissionNumber')}}</span>
                              @endif<br />
                              <input type="text" name="StudentAdmissionNumber" id="StudentAdmissionNumber" />
                          </div>
                    </div>    

                    <div class="InputBlock">
                        <p> Student middlename:(optional)</p>
                        <div> 
                            @if($errors->has('StudentMiddlename'))
                                <span class="text-danger StudentRegisterError">{{$errors->first('StudentMiddlename')}}</span>
                            @endif<br />
                            <input type="text" name="StudentMiddlename" id="StudentMiddlename" />
                        </div>
                    </div>
                      <br />
                       <br />        
                    {{Form::token()}}
                        <br /><br /><input type = "submit"  value="Register Student" id="RegistrationButton"  />
               {{Form::close()}}
          </div>
      </div>
@stop