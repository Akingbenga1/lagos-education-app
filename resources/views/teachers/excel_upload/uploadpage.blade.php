@extends('layouts.main_table')

@section('division_container')
  @if(Auth::check())  <!--   if you are authenticated-->
       
  @else
    <a href = '{{URL::route("login-form")}}'> Teacher Login</a>
  @endif

  <h2 class="text-center">  Student Excel Upload Page
  @if(isset($ExtractErrorMessage))
      <span class="text-danger EmailError">{{$ExtractErrorMessage}}</span>
  @endif
  </h2>


  <div class="col-sm-6 text-center">
      <div class="panel  panel-success">
          <div class="panel-heading ">
           Upload Excel Sheet Here
          </div>
          <div class="panel-body">
           {{ Form::open(array( 'route' => 'excel-server-upload'  ,'files' => true, 'method'=> 'post')) }}
              @if($errors->has('ExcelUpload'))
                  <span class="text-danger EmailError">{{$errors->first('ExcelUpload')}}</span>
              @endif
              <br />
              @if($errors->has('Year'))
                  <span class="text-danger EmailError">{{$errors->first('Year')}}</span>
              @endif
              <br />
              @if($errors->has('TermName'))
                  <span class="text-danger EmailError">{{$errors->first('TermName')}}</span>
              @endif
              <br />
              @if($errors->has('Class'))
                  <span class="text-danger EmailError">{{$errors->first('Class')}}</span>
              @endif
              <br />
              @if($errors->has('SubClass'))
                  <span class="text-danger EmailError">{{$errors->first('SubClass')}}</span>
              @endif
                  <div class="YearDiv">
                      <span class="YearLabel"> Year </span>
                      <select name = "Year" class="Year form-control" >
                          <option> -- Choose year --</option>
                          <option value="2015" {{(Input::old("Year") == "2015") ? "selected": "" }}>  2015/2016 Academic Year </option>
                          <option value="2016" {{ (Input::old("Year")  == "2016") ? "selected": "" }}> 2016/2017 Academic Year </option>
                      </select>
                  </div>

                  <div class="TermDiv">
                      <span class="TermLabel"> Term </span>
                      <select name = "TermName" class="TermName form-control" >
                          <option> -- Choose term -- </option>
                          <option value="first term" {{Input::old("TermName")  == "first term" ? "selected":"" }}> First term</option>
                          <option value="second term" {{Input::old("TermName")  == "second term" ? "selected":"" }} > Second term</option>
                          <option value="third term" {{ Input::old("TermName")  == "third term" ? "selected":"" }}> Third term</option>
                      </select>
                  </div>

                  <div class="ClassDiv">
                      <span class="ClassLabel"> Class </span>
                      <select name = "Class" class="Class form-control" >
                          <option> -- Choose class -- </option>
                          <option value="SS1" {{(Input::old("Class") == "SS1") ? "selected":'' }}>SS1</option>
                          <option value="SS2" {{(Input::old("Class") == "SS2") ? "selected":'' }}>SS2</option>
                          <option value="SS3" {{(Input::old("Class") == "SS3") ? "selected":'' }}>SS3</option>
                      </select>
                  </div>

                  <div class="SubClassDiv">
                        <span class="SubClassLabel"> SubClass </span>
                          <select name = "SubClass" class="SubClassSelect form-control" >
                              <option> -- Choose subclass -- </option>
                              <option value="a" {{(Input::old("SubClass") == "a") ? "selected":'' }}>A</option>
                              <option value="b" {{(Input::old("SubClass") == "b") ? "selected":'' }}>B</option>
                              <option value="c" {{(Input::old("SubClass") == "c") ? "selected":'' }}>C</option>
                              <option value="d" {{(Input::old("SubClass") == "d") ? "selected":'' }}>D</option>
                              <option value="e" {{(Input::old("SubClass") == "e") ? "selected":'' }}>E</option>
                              <option value="f" {{(Input::old("SubClass") == "f") ? "selected":'' }}>F</option>
                              <option value="g" {{(Input::old("SubClass") == "g") ? "selected":'' }}>G</option>
                              <option value="h" {{(Input::old("SubClass") == "h") ? "selected":'' }}>H</option>
                              <option value="i" {{(Input::old("SubClass") == "i") ? "selected":'' }}>I</option>
                              <option value="j" {{(Input::old("SubClass") == "j") ? "selected":'' }}>J</option>
                              <option value="k" {{(Input::old("SubClass") == "k") ? "selected":'' }}>K</option>
                          </select>
                  </div>

                <br />
                <br />



            <input  type="file" name="ExcelUpload" class="form-control"  />

              <br /><br /><br /><br /><br /><br /><br />
            <input type = "submit" value="Add Score Database" class="btn btn-block btn-success" />
            {{Form::close()}}
          </div>
      </div>
  </div>






@stop
