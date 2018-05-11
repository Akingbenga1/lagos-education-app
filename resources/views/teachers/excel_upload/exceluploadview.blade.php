@extends('layouts.main_table')

@section('division_container')
  @if(Auth::check())  <!--   if you are authenticated-->
       
  @else
    <a href = '{{URL::route("login-form")}}'> Teacher Login</a>
  @endif
  @if(Session::has('ExcelUploadError'))
    <span class="text-danger EmailError">{{Session::get('ExcelUploadError')}}</span>
  @endif

  {{ Form::open(array( 'route' => 'send-excel-score-to-database' , 'method'=> 'post', 'class'=>'AjaxScoreTermDuration')) }}
    <div class="table-responsive">
      <h2 class="text-center">  Student Excel Details  </h2>
      <table id="ColSpanTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead style="text-align: center;" class="alert-warning">
        <tr>
          <th  style="vertical-align: middle;" class="alert-warning"> Student Count   </th>
          <th  style="vertical-align: middle;" class="alert-warning"> Pupil age   </th>
          <th  style="text-align: center;">  Student Admission Number  </th>
        @foreach($AllExtractedSubjects as $EachExtractedSubjectsKey =>  $EachAllExtractedSubjects)
                <th  colspan="2" class="text-muted text-center alert-warning"> {{ $EachAllExtractedSubjects["Subject"] }}<br />
                                                                               {{ $EachAllExtractedSubjects["SubjectTeacher"] }}
                </th >
        @endforeach


        </tr>
        <tr>
          <th colspan="3">  Name and Student Admission Number      </th>
          @foreach($AllExtractedSubjects as $EachExtractedSubjectsKey =>  $EachAllExtractedSubjects)
            <th>  CA Score    </th>
            <th> Exam Score  </th>
          @endforeach
        </tr>

        </thead>

        <?php
          $StudentCount = 0;



        ?>
        @foreach($ProcessedStudentScoreCollector as $EachProcessedKey =>  $EachProcessedStudentScoreCollector)
          @foreach($StudentDetailsCollector as $EachStudentDetailsKey =>  $EachStudentDetailsCollector)
            @if($EachProcessedKey == $EachStudentDetailsKey)
            <?php  $StudentAdmissionNumberSingle = substr($EachStudentDetailsCollector["StudentAdmissionNumber"], 10);?>
          <tr style="border:solid 1px black;">
            <td style="font-weight: 900">  {{++$StudentCount;}} </td>
            <td style="font-weight: 900">  {{ $EachStudentDetailsCollector["StudentName"]}} </td>
            <td> {{ $EachStudentDetailsCollector["StudentAdmissionNumber"]}}  </td>
            @foreach( $EachProcessedStudentScoreCollector as $EachProcessedSubjectKey => $eachStudentDetailCollector)
              <td>
                {{ !is_null($eachStudentDetailCollector['CA']) ? "<span class='text-success'>".  $eachStudentDetailCollector['CA'] . "</span>" :
                       "<span class='text-danger'> null </span>"  }} </b>
                <!-- {{"ExcelStudentScore_{$EachProcessedKey}_{$EachProcessedSubjectKey}_CA"}} -->
                <input type="hidden" name='{{"ExcelStudentScore_{$EachProcessedKey}_{$EachProcessedSubjectKey}_CA"}}' value="{{$eachStudentDetailCollector['CA']}}"  />
              </td>
              <td>  <b> {{ !is_null($eachStudentDetailCollector['Exam']) ? "<span class='text-success'>".  $eachStudentDetailCollector['Exam'] . "</span>" :
                        "<span class='text-danger'> null </span>"  }} </b>
                <!-- {{"ExcelStudentScore_{$EachProcessedKey}_{$EachProcessedSubjectKey}_Exam"}} -->
                <input type="hidden" name='{{"ExcelStudentScore_{$EachProcessedKey}_{$EachProcessedSubjectKey}_Exam"}}' value="{{ $eachStudentDetailCollector['Exam']}}"  />
              </td>

            @endforeach
          </tr>
            @endif
          @endforeach
        @endforeach
      </table>
    </div>
  {{Form::token()}}
  <br /><br /><br /><br /><br /><br /><br />
  <input type = "submit" value="Add Score Database"   class="btn btn-block" />
  {{Form::close()}}




@stop
