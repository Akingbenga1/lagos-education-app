@extends('layouts.main_table')

@section('division_container')
  @if(Auth::check())  <!--   if you are authenticated-->
       
  @else
    <a href = '{{URL::route("login-form")}}'> Teacher Login</a>
  @endif

  {{ Form::open(array( 'route' => 'sort-excel-header' , 'method'=> 'post', 'class'=>'AjaxScoreTermDuration')) }}
    <div class="table-responsive">
      <h2 class="text-center">  Student Excel Details  </h2>
        <input type = "hidden" value="{{ $SubjectsCount }}"  name="SubjectCount" />

        @if(Session::has('PreviewErrorMessage'))
            <span class="text-danger EmailError">{{Session::get('PreviewErrorMessage')}}</span>
        @endif
      <table id="ExcelPreviewTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead style="text-align: center;" class="alert-warning">
        <tr>
          <th  style="vertical-align: middle;" class="alert-warning"> Excel Teacher Name   </th>
          <th  style="vertical-align: middle;" class="alert-warning"> Excel Subject and Teacher Name   </th>
          <th  style="vertical-align: middle;" class="alert-warning"> Choose Subject  </th>
        </tr>
        </thead>
        <?php $SubjectCount = 0; ?>
        @foreach($AllExtractedSubjects as $EachExtractedSubjectsKey =>  $EachAllExtractedSubjects)
          <tr>
              <td> {{ $EachAllExtractedSubjects["SubjectTeacher"] }} </td >
              <td> {{ $EachAllExtractedSubjects["Subject"] }} </td >

              <td>
                    <select name = "SubjectsFromTheDB_{{$SubjectCount}}" class="form-control" >
                      <option> -- Choose Subject --  </option>
                      @if(isset($AllDBSubjects) and is_array($AllDBSubjects))
                        @foreach($AllDBSubjects as $EveryDBSubjects)
                          <option value="{{$EveryDBSubjects['subjectid']}}"
                                  <?php echo ( is_numeric(Input::old("SubjectsFromTheDB_".$SubjectCount))
                                  and (Input::old("SubjectsFromTheDB_".$SubjectCount) == $EveryDBSubjects['subjectid']) )? "selected" : ""  ?>
                                 >
                           {{$EveryDBSubjects['subject']}}
                          </option>
                        @endforeach
                      @else
                        <option> No Subjects available from the Database  </option>
                      @endif
                    </select>
              </td >
          </tr>
              <?php $SubjectCount++; ?>
        @endforeach
      </table>
    </div>
  {{Form::token()}}
  <br /><br /><br /><br /><br /><br /><br />
  <input type = "submit" value="Show Excel Sheet"   class="btn btn-block" />
  {{Form::close()}}




@stop
