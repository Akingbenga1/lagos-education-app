@extends('layouts.main')
@section('division_container')


    @if(  Session::has("academic_year_object")  and Session::has("term_object") and Session::has("class_level_object") and Session::has("school_object") )

        @php
            $academic_year_object = Session::get("academic_year_object");
            $term_object = Session::get("term_object");
            $class_level_object = Session::get("class_level_object");
            $school_object = Session::get("school_object");
        // dd($academic_year_object, )
        @endphp
        <form action="{{ url('/schools_excel_upload') }}"  class="col-6" method="POST"  enctype="multipart/form-data">

            {{ csrf_field() }}

            <h1>    {{  $school_object->school_name  }}   </h1>

            <input type="hidden"  class="school_value" value="{{  $school_object->id  }}}" name="school_value"   />

           <div class="row">

               <div class="col s4 l4 ">
                   Academic Year :  {{  $academic_year_object->academic_year  }}
                   <input type="hidden"  class="academic_year_value" value="{{ $academic_year_object->id   }}}" name="academic_year_value"   />
               </div>

               <div class="col s4 l4 ">
                   Term  :  {{  $term_object->term  }}
                   <input type="hidden"  class="term_value" value="{{ $term_object->id   }}}" name="term_value"   />
               </div>

               <div class="col s4 l4 ">
                   Class Level  :  {{  $class_level_object->class_level  }}
                   <input type="hidden"  class="class_level_value" value="{{ $class_level_object->id   }}}" name="class_level_value"   />
               </div>

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
                            <form id="{{ $dataCount }}"  method="POST" action="{{ url('/schools_registration_save_students')}}" >

                                <input type="hidden" class="selected_registration_list_index" name="selected_registration_list_index" value="{{ $dataCount  }}" />
                                {{ csrf_field()  }}

                                <br />

                                <br />

                                <button type="submit"   class="form-control btn btn-success green"> Register New Students    </button> </form>




                            <table class="striped centered responsive-table bordered">
                                <thead>
                                <tr>
                                    <th>Admission Number</th>
                                    <th> Name </th>
                                    <th> Spin </th>
                                    <th> Sex  </th>
                                    <th> Sub Division </th>
                                </tr>
                                </thead>
                                <tbody>

                            @foreach($workSheet as $EachWorkSheet)

                                    <tr>
                                        <td>
                                            {{  $EachWorkSheet->has("admn_no")  ?   $EachWorkSheet["admn_no"] : " "   }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("name_surname_first")   ?  $EachWorkSheet["name_surname_first"] : " "     }}
                                        </td>
                                        <td>
                                            {{  $EachWorkSheet->has("spin")  ?  $EachWorkSheet["spin"]  : " "   }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("sex")   ?  $EachWorkSheet["sex"]  : " "    }}
                                        </td>
                                        <td>
                                            {{ $EachWorkSheet->has("subdivision")   ?  $EachWorkSheet["subdivision"]  : " "    }}
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
                $(".selected_registration_list_index").val(selectedIndex);
                console.log( selectedIndex,  "and " ,  $(".selected_registration_list_index").val())
            }
        });

    });


</script>


@stop
