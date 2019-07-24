@extends('layouts.main')
<!--  extend the particular  layout that you want. so, you can have as much layouts as you like  -->

<!--  extends  adminmain. is front page of admin side. contains product upload forms,  -->

  @section('division_container')

    <h3> Edit This Staff (   @if(is_array($ThisAssignedRoles))  {{$ThisAssignedRoles['user_belong']['surname']}}
                                                               {{$ThisAssignedRoles['user_belong']['middlename']}} 
                                                               {{$ThisAssignedRoles['user_belong']['firstname']}} -
                                                               <strong> {{$ThisAssignedRoles['role_belong']['name'] }} </strong>  )
                              @else
                                  Name unavailable
                              @endif
    </h3>
  @if(Session::has('ComplainError'))
	   {{ Session::get('ComplainError')}}
  @endif 

    @if( is_array($ThisStaff) and is_array($ThisAssignedRoles) )

    {{ Form::open(array( 'route' => 'edit-this-staff' , 'method'=> 'post')) }}     
        @if($errors->has('Year'))
           {{ $errors->first('Year')}}
        @endif
              
    <div class="YearDiv">
      <span class="YearLabel"> Year </span>

      <select name = "Year" class="Year" >
        <option> -- Choose year -- </option>
        <option value="2015" {{($ThisStaff["year"] == "2015") ? "selected":'' }}> 2015/2016 </option>
      </select>
    </div>

     @if($errors->has('TermName'))
       {{ $errors->first('TermName')}}
     @endif
    <div class="TermDiv">
        <span class="TermLabel"> Term </span>
        <select name = "TermName" class="TermName" >
            <option> -- Choose term -- </option>
            <option value="first term" {{($ThisStaff["termname"] == "first term") ? "selected":'' }}> First term</option>
            <option value="second term" {{($ThisStaff['termname'] == "second term") ? "selected":'' }} > Second term</option>
            <option value="third term" {{($ThisStaff['termname'] == "third term") ? "selected":'' }}> Third term</option>
        </select>
    </div>

     @if($errors->has('Class'))
          {{ $errors->first('Class')}}
     @endif
             
    <div class="ClassDiv">
        <span class="ClassLabel"> Class </span>
        <select name = "Class" class="Class" >
            <option> -- Choose class -- </option>
            <option value="SS1" {{($ThisStaff["classname"] == "SS1") ? "selected":'' }}>SS1</option>
            <option value="SS2" {{($ThisStaff["classname"] == "SS2") ? "selected":'' }}>SS2</option>
            <option value="SS3" {{($ThisStaff["classname"] == "SS3") ? "selected":'' }}>SS3</option>
        </select>
    </div>
      @if($errors->has('SubClass'))
        {{ $errors->first('SubClass')}}
      @endif             

    <div class="SubClassDiv">
        <span class="SubClassLabel"> SubClass </span>
        <select name = "SubClass" class="SubClassSelect" >
             <option> -- Choose subclass -- </option>
             <option value="a"  {{(($ThisStaff["subclass"] )== "a") ? "selected":'' }}>A</option>
             <option value="b" {{(($ThisStaff["subclass"] )== "b") ? "selected":'' }}>B</option>
             <option value="c" {{(($ThisStaff["subclass"] )== "c") ? "selected":'' }}>C</option>
             <option value="d" {{(($ThisStaff["subclass"] )== "d") ? "selected":'' }}>D</option>
             <option value="e" {{(($ThisStaff["subclass"] )== "e") ? "selected":'' }}>E</option>
             <option value="f" {{(($ThisStaff["subclass"] )== "f") ? "selected":'' }}>F</option>
             <option value="g" {{(($ThisStaff["subclass"] )== "g") ? "selected":'' }}>G</option>
             <option value="h" {{(($ThisStaff["subclass"] )== "h") ? "selected":'' }}>H</option>
             <option value="i" {{(($ThisStaff["subclass"] )== "i") ? "selected":'' }}>I</option>
             <option value="j" {{(($ThisStaff["subclass"] )== "j") ? "selected":'' }}>J</option>
             <option value="k" {{(($ThisStaff["subclass"] )== "k") ? "selected":'' }}>K</option>
        </select> 
    </div> 

     <div class="SubjectDiv">
        <span class="SubjectLabel"> Subjects </span>
        <select name = "Subject" class="Subject" >
            <option> -- Choose Subject -- </option>
            @if(isset($AllSubjects) and is_array($AllSubjects))
                @foreach($AllSubjects as $EverySubject)
                     <option value="{{$EverySubject['subjectid']}}" {{($ThisStaff["subjectid"] == $EverySubject['subjectid']) ? "selected":'' }}> 
                                    {{$EverySubject['subject']}}
                       </option>
                @endforeach
            @else
                <option> No Subject Available  </option>
            @endif
        </select>
    </div>            
        
    @if($errors->has('AssignedUser'))
       {{ $errors->first('AssignedUser')}}
    @endif
     <input type="hidden" name ="StaffId"  value="{{$ThisStaff['stafftableid']}}"  /> 
      AssignedUser is : {{$ThisAssignedRoles['user_belong']['surname']}} {{$ThisAssignedRoles['user_belong']['middlename']}} 
                        {{$ThisAssignedRoles['user_belong']['firstname']}} 
                        <strong>({{$ThisAssignedRoles['user_belong']['useremail'] }})</strong> ==>
                       <strong>  {{$ThisAssignedRoles['role_belong']['name'] }} </strong>
             
    If this particular staff has an added designation ( for example, Vice Principal, academics ), add designation here: 
         @if($errors->has('Designation'))
       {{ $errors->first('Designation')}}
     @endif
        <input type="text" name ="Designation" class ="Designation" value="{{$ThisStaff['designation']}}"  /> 
        {{Form::token()}}       
        <input type = "submit" value="Edit This Staff" class ="EditStaffButton" />
      {{Form::close()}}
  @else
    This form cannot be displayed dnow
  @endif
@stop