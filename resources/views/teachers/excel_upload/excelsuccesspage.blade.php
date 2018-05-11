@extends('layouts.main_table')

@section('division_container')
  @if(Auth::check())  <!--   if you are authenticated-->
       
  @else
    <a href = '{{URL::route("login-form")}}'> Teacher Login</a>
  @endif


  @if(isset($ExcelSuccessPage)  )
    <span class="text-success">{{$ExcelSuccessPage}}</span>
  @endif


<a href="{{URL::route('excel-upload-page')}}" class="btn btn-success">
      Excel Upload Page
</a>

  <div class="row">

      <div class="col-sm-5 pull-left">
        @if(count($UnRegisteredStudents) > 0 )
          <h2>  {{count($UnRegisteredStudents)}} students were initially not in the database and could not be inserted into the database </h2>
        <?php $SerialA = 0; ?>
          <table class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead style="text-align: center;" class="alert-warning">
            <tr>
              <th  style="vertical-align: middle;" class="alert-warning"> S/N</th>
              <th  style="vertical-align: middle;" class="alert-warning"> Student Name   </th>
              <th  style="vertical-align: middle;" class="alert-warning"> Student Admission Number   </th>
            </tr>
            </thead>
            @foreach($UnRegisteredStudents as  $EachUnRegisteredStudents)
              <tr>
                <td> {{ $SerialA++; }} </td >
                <td> {{ $EachUnRegisteredStudents["StudentName"] }} </td >
                <td> {{ $EachUnRegisteredStudents["StudentAdmissionNumber"] }} </td >
              </tr>
            @endforeach
          </table>
        @else
          <h2> All Students in the Excel sheet were successfully uploaded to the database</h2>
          @endif
      </div>

      <div class="col-sm-5 pull-right">
          <?php $SerialB = 0; ?>
        @if(count($NewRegisteredStudents) > 0 )
          <h2>  {{count($NewRegisteredStudents)}} students were initially not in the database BUT were successfully added to the database </h2>
          <table class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead style="text-align: center;" class="alert-warning">
            <tr>
              <th  style="vertical-align: middle;" class="alert-warning"> S/N</th>
              <th  style="vertical-align: middle;" class="alert-warning"> Student Name   </th>
              <th  style="vertical-align: middle;" class="alert-warning"> Student Admission Number   </th>
            </tr>
            </thead>
            @foreach($NewRegisteredStudents as  $EachNewRegisteredStudents)
              <tr>
                <td> {{ $SerialB++; }} </td >
                <td> {{ $EachNewRegisteredStudents["StudentName"] }} </td >
                <td> {{ $EachNewRegisteredStudents["StudentAdmissionNumber"] }} </td >
              </tr>
            @endforeach
          </table>
        @else
          <p> &nbsp; </p>
             @endif
      </div>
  </div>

@stop