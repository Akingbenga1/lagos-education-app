@extends('layouts.main')
@section('division_container')

    <div class="col-12">
        @include('errors.error')
        @include('errors.allerrors')
    </div>


    {{--@if(  Session::has("academic_year_object")  and Session::has("term_object") and Session::has("class_level_object") and Session::has("school_object") )--}}

        @if( Session::has("term_object") and Session::has("school_object") )

            @php
            //$academic_year_object = Session::get("academic_year_object");
            $term_object = Session::get("term_object");
            //$class_level_object = Session::get("class_level_object");
            $school_object = Session::get("school_object");
        // dd($academic_year_object, )
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

               {{--<div class="col s4 l4 ">--}}
                   {{--Class Level  :  {{  $class_level_object->class_level  }}--}}
                   {{--<input type="hidden"  class="class_level_value" value="{{ $class_level_object->id   }}}" name="class_level_value"   />--}}
               {{--</div>--}}

           </div>

            <input type="file"  name="bulk_excel_upload" class="form-control"   />

            <button type="submit"   class="form-control btn btn-success"> Submit  </button>
        </form>


    @else

        @endif


    @if( Session::has("workSheetNameArray" ) and Session::has("ExcelData" ) )

        @php
          $workSheetNameArray =  Session::get("workSheetNameArray" );
          $ExcelData =  Session::get("ExcelData" );
          $sheetCount = 0;
          $dataCount = 0;
          $numberOfSheets = count($workSheetNameArray);
          $numberOfGrid = (int)floor( 12/ $numberOfSheets );
        //dd($numberOfGrid);
        @endphp

        @if( count($ExcelData) > 0 )

                Sheet List:
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                    @foreach( $ExcelData as $workSheet )

                                    <li class="tab col s{{ $numberOfGrid }}  purple darken-1">
                                        <a href="#test_{{ $sheetCount  }}" class="tooltipped" data-position="top" data-tooltip="{{ $workSheetNameArray[$sheetCount]  }}" style="color: white!important;"> <b> {{ $workSheetNameArray[$sheetCount]    }}  </b>
                                        </a>
                                    </li>

                        @php   $sheetCount++; @endphp
                    @endforeach
                        </ul>
                    </div>

                    @foreach( $ExcelData as $workSheet )

                        <div id="test_{{ $dataCount }}" data-listid="{{ $dataCount }}" class="col s12">
                            <form id="{{ $dataCount }}"  method="POST" action="{{ url('/schools_save_scores')}}" >
                                <input type="hidden" class="selected_score_list_index" name="selected_score_list_index" value="{{ $dataCount  }}" />
                                {{ csrf_field()  }}

                                <br />

                                <br />

                                <button type="submit"   class="form-control btn btn-success green"> Save Scores  to Database   </button> </form>




                            <table class="striped centered responsive-table bordered">
                                <thead>
                                <tr>
                                    <th> S/N </th>
                                    <th> Academic Year </th>
                                    <th> Class Level </th>
                                    <th> Sub Division </th>
                                    <th>Admission Number</th>
                                    <th> Name </th>
                                    <th> Sex  </th>
                                    <th> English</th>
                                    <th> Mathematics</th>
                                    <th> Basic Science and Tech </th>
                                    <th> French</th>
                                    <th> Yoruba </th>
                                    <th> Religious Studies </th>
                                    <th> Vocational Studies  </th>
                                    <th> Business Studies</th>
                                    <th> CCA </th>
                                    <th> Arabic </th>
                                </tr>
                                </thead>
                                <tbody>

                                @php

                                    $table_counter = 1;
                                @endphp
                            @foreach($workSheet as $EachWorkSheet)
                            @php
                            //dd($EachWorkSheet);
                            @endphp

                                    <tr>
                                        <td>
                                            {{  $table_counter++   }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("academic_year") ?  $EachWorkSheet["academic_year"]   : " "   }}
                                        </td>

                                        <td>
                                            {{ $EachWorkSheet->has("class_level") ? $EachWorkSheet["class_level"]  : " "   }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("subdivision") ?  $EachWorkSheet["subdivision"]  : " "  }}
                                        </td>
                                        <td>
                                            {{  $EachWorkSheet->has("admn_no")  ?   $EachWorkSheet["admn_no"] : " "   }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("student_name")   ?  $EachWorkSheet["student_name"] : " "     }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("sex")   ?  $EachWorkSheet["sex"]  : " "    }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("english")  ?  $EachWorkSheet["english"]  : " "    }}
                                        </td>
                                        <td>
                                            {{  $EachWorkSheet->has("mathematics")  ?  $EachWorkSheet["mathematics"]  : " "   }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("french")  ?  $EachWorkSheet["french"]   : " "  }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("basic_sc_tech")  ?  $EachWorkSheet["basic_sc_tech"] : " "    }}
                                        </td>
                                        <td>
                                            {{  $EachWorkSheet->has("yoruba")  ?  $EachWorkSheet["yoruba"] : " "    }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("rel_val_edu")  ?   $EachWorkSheet["rel_val_edu"]  : " "   }}
                                        </td>
                                        <td>
                                            {{$EachWorkSheet->has("pre_voc_std")  ?  $EachWorkSheet["pre_voc_std"] : " "    }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("bus_studies") ?  $EachWorkSheet["bus_studies"]  : " "  }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("cca") ? $EachWorkSheet["cca"]  : " "   }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("arabic") ?  $EachWorkSheet["arabic"]   : " "   }}
                                        </td>
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
