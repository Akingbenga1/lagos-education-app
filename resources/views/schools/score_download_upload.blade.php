@extends('layouts.main')
@section('division_container')

    <div class="col-12">
        @include('errors.error')
        @include('errors.allerrors')
    </div>

    {{--@if(  Session::has("academic_year_object")  and Session::has("term_object") and Session::has("class_level_object") and Session::has("school_object") )--}}

        @if( Session::has("term_object") and Session::has("school_object") )

            @php
            $academic_year_object = Session::get("academic_year_object");
            $term_object = Session::get("term_object");
            $class_level_object = Session::get("class_level_object");
            $school_object = Session::get("school_object");
            $subjects_array =  (\App\Models\Subjects::all());
         //dd($subjects_array )
        @endphp

            @if(  Session::has("session_name")  and Session::has("StudentScoreExcelExportData")  )
                @php
                    $session_name = Session::get("session_name");
                @endphp

            @endif



            <form action="{{ url('/schools_excel_upload') }}"  class="col-6" method="POST"  enctype="multipart/form-data">

            {{ csrf_field() }}

            <h1>    {{  $school_object->school_name  }}   </h1>

            <input type="hidden"  class="school_value" value="{{  $school_object->id  }}}" name="school_value"   />

           <div class="row">

               {{--<div class="col s4 l4 ">--}}
                   {{--Academic Year :  {{  $academic_year_object->academic_year  }}--}}
                   {{--<input type="hidden"  class="academic_year_value" value="{{ $academic_year_object->id   }}}" name="academic_year_value"   />--}}
               {{--</div>--}}

               <div class="col s4 l4 ">
                   Term  :  {{  $term_object->term  }} Term
                   <input type="hidden"  class="term_value" value="{{ $term_object->id   }}}" name="term_value"   />
               </div>

               <div class="col s4 l4 ">
                   Class Level  :  {{  $class_level_object->class_level  }}
                   <input type="hidden"  class="class_level_value" value="{{ $class_level_object->id   }}}" name="class_level_value"   />
               </div>

           </div>

            {{--<input type="file"  name="bulk_excel_upload" class="form-control"   />--}}

            {{--<button type="submit"   class="form-control btn btn-success"> Submit  </button>--}}
        </form>


    @else

        @endif


    {{--@if( Session::has("workSheetNameArray" ) and Session::has("ExcelData" ) )--}}

        @if( isset($score_data_grouped) and ( count($score_data_grouped) > 0 ) and isset($score_grouped_class_subdivision)  )
        @php
          $sheetCount = 0;
          $dataCount = 0;
          $number_score_grouped_class_subdivision = count($score_grouped_class_subdivision);
          $numberOfGrid = (int)floor( 12/ $number_score_grouped_class_subdivision );
        //dd($numberOfGrid, $number_score_grouped_class_subdivision);
        @endphp

        @if( count($score_data_grouped) > 0 )

            <h3> Class Level Score Download List: </h3>
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                    @foreach( $score_grouped_class_subdivision as $each_score_grouped_class_subdivision )

                                    <li class="tab  tabs-fixed-width col l{{ $numberOfGrid  }} s{{ $numberOfGrid }}  pink darken-1">
                                        <a href="#test_{{ $sheetCount  }}" class="tooltipped" data-position="top" data-tooltip="{{ $class_level_object->class_level . " ".  $each_score_grouped_class_subdivision  }}" style="color: white!important;"> <b> {{ $class_level_object->class_level . " ".  $each_score_grouped_class_subdivision  }}  </b>
                                        </a>
                                    </li>

                        @php   $sheetCount++; @endphp
                    @endforeach
                        </ul>
                    </div>

                    @foreach( $score_data_grouped as $class_subdivision_loop_key =>   $class_subdivision_loop )
                        @php
                         //dd($class_subdivision_loop);
                        @endphp

                            <div id="test_{{ $dataCount }}" data-listid="{{ $dataCount }}" class="col s12">

                                <form id="{{ $dataCount }}"  method="POST" action="{{ url('/schools_save_scores')}}" >

                                    <input type="hidden" class="selected_score_list_index" name="selected_score_list_index" value="{{ $dataCount  }}" />
                                    {{ csrf_field()  }}

                                    <br />

                                    <br />

                                    {{--<button type="submit"   class="form-control btn btn-success green"> Download All  </button> </form>--}}

                                    <a class="btn btn-success btn-small btn-success green" href="{{ url("/download_all_class_subdivision/class_subdivision/". $class_subdivision_loop_key )  }}" > Download All </a>
                                        <table class="striped centered responsive-table bordered">
                                            <thead>
                                            <tr>
                                                <th> S/N </th>
                                                <th> Name </th>
                                                <th> Admission Number  </th>
                                                <th> Download Result  </th>
                                                {{--<th colspan="3"> Subjects  </th>--}}
                                            </tr>
                                            </thead>
                                            <tbody >
                                            @php
                                                $table_counter = 1;
                                            @endphp
                                            @foreach( $class_subdivision_loop as $each_student_level_loop )
                                                {{--Student Level Forloop--}}
                                                @php

                                                $student_registration_id = property_exists($each_student_level_loop->first()->first(), "student_registration_id") ? ucwords(strtolower($each_student_level_loop->first()->first()->student_registration_id))  : " ";

                                                    //dd( $each_student_level_loop );
                                                @endphp

                                                <tr rowspan="{{ count($each_student_level_loop) }}" >
                                                    <td> {{  $table_counter++   }}</td>
                                                    <td >
                                                        {{ property_exists($each_student_level_loop->first()->first(), "student_surname") ? ucwords(strtolower($each_student_level_loop->first()->first()->student_surname))  : " "  }}

                                                        {{ property_exists($each_student_level_loop->first()->first(), "student_middlename") ? ucwords(strtolower($each_student_level_loop->first()->first()->student_middlename))  : " "  }}

                                                        {{ property_exists($each_student_level_loop->first()->first(), "student_firstname") ? ucfirst(strtolower($each_student_level_loop->first()->first()->student_firstname))  : " "  }}

                                                    </td>
                                                    <td > {{ property_exists($each_student_level_loop->first()->first(), "student_admission_number") ? $each_student_level_loop->first()->first()->student_admission_number  : " "  }}</td>
                                                    <td>
                                                        <a class="btn btn-success btn-small light-blue" href="{{ url("/student_score_download/".$student_registration_id. "/class_subdivision/". $class_subdivision_loop_key )  }}" > Download Report ( PDF ) </a>
                                                    </td>
                                                    {{--<td colspan="3"  >--}}

                                                        {{--<table  border="1" class="centered responsive-table bordered">--}}
                                                            {{--<thead>--}}
                                                            {{--<td> Serial  </td>--}}
                                                            {{--<td> Subject </td>--}}
                                                            {{--<td> Score  </td>--}}

                                                            {{--</thead>--}}
                                                            {{--<tbody>--}}
                                                                {{--@php--}}
                                                                  {{--$subject_counter = 1;--}}
                                                                {{--@endphp--}}
                                                                {{--@foreach( $subjects_array as $each_subjects )--}}
                                                                {{--@if( ($each_student_level_loop->has($each_subjects->subject_slug))  and ( count( $each_student_level_loop->get($each_subjects->subject_slug) ) > 0 )  )--}}
                                                                {{--<tr>--}}
                                                                    {{--<td>--}}
                                                                        {{--{{ $subject_counter++ }}--}}
                                                                    {{--</td>--}}
                                                                {{--<td>--}}
                                                                {{--{{ $each_subjects->subject }}--}}
                                                                {{--</td>--}}
                                                                {{--<td>--}}
                                                                {{--{{    property_exists($each_student_level_loop->get($each_subjects->subject_slug)->get("0"), "exam_score") ? $each_student_level_loop->get($each_subjects->subject_slug)->get("0")->exam_score  : " "  }}--}}
                                                                {{--</td>--}}
                                                                {{--</tr>--}}
                                                                {{--@endif--}}
                                                                {{--@endforeach--}}
                                                            {{--</tbody>--}}
                                                        {{--</table>--}}
                                                    {{--</td>--}}
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                            </div>
                         @php   $dataCount++; @endphp
                        @endforeach

                </div>
        @endif

    @endif






<script>

    $( document ).ready( function()
    {

        var instance =   $('.tabs').tabs({

            onShow : function(e)
            {
                var selectedIndex = $( e.selector).data('listid');
                $(".selected_score_list_index").val(selectedIndex);
                console.log( selectedIndex,  "and " ,  $(".selected_score_list_index").val())
            }
        });

    });


</script>


@stop
