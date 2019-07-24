@extends('layouts.main')

@section('division_container')
<h3> Restrict Row in database using soft deletes  </h3>

 {{ Form::open(array( 'route' => 'admin-create-roles' , 'method'=> 'post')) }}           
              @if($errors->has('name'))
                 {{ $errors->first('name')}}
               @endif
               <input type="radio" name="ChooseTables" values="Subjects" /> Subjects <br />
               <input type="radio" name="ChooseTables" values="StaffTable" /> stafftable  <br />
               <input type="radio" name="ChooseTables" values="Students" /> students <br />
               
              Add Name: <input type="text" name="name" />
              {{Form::token()}}       
               <input type = "submit"  value="Check table and get data" class="CheckTable" />
  {{Form::close()}}

 @stop