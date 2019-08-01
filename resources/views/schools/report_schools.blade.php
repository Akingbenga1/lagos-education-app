@extends('layouts.main')
@section('division_container')

    <h1> Student Reports Downloads </h1>

    @if(isset($all_schools) and is_array($all_schools))

            <?php $Count = 1; $YesString ='<b> Yes </b>'; $NoString =  'No';  ?>
            <?php $SplitStudentClass = array_chunk($all_schools, ceil(count($all_schools) / 3));
            // var_dump(count($things[0]));
            $count = 1;   ?>
            <b> Search for anyschhol  using their name
            </b> <input type="text" id="CardSearch" placeholder="Search this list"></input> <p class="ShowTheCount"> </p>
            <div class="row">
                @include("errors.error")
                @include("errors.allerrors")
            </div>


            <div class="row">
                @foreach($SplitStudentClass as $EachSplitedStudent)
                    @php
                     //dd($EachSplitedStudent);
                    @endphp

                    @foreach($EachSplitedStudent as $SecondLeveSplitedStudent)

                        <div class="col s12 m6 l3">
                        <div class="card sticky-action large" style="height: 300px!important;">
                            <div class="card-content" style="height: 60%!important;">
                                {{--<p class="card-title activator grey-text text-darken-4" style="font-size: 18px!important;"> <b>   {{ $SecondLeveSplitedStudent["school_name"]  }} </b> </p>--}}

                                 <a class=" tooltipped modal-trigger card-title  grey-text text-darken-4"  data-position="left" data-delay="50" data-tooltip="Click to Upload Scores" style="font-size: 18px!important;"  href="#modal_{{  $SecondLeveSplitedStudent["id"]}}" >
                                     <b>  {{ $SecondLeveSplitedStudent["school_name"]  }}  </b>

                                </a>


                             <p> {{ $SecondLeveSplitedStudent["school_type_id"]  == 1 ? "Junior" : "Senior" }}  </p>

                                <!-- Modal Structure -->
                                <div id="modal_{{  $SecondLeveSplitedStudent["id"]}}" class="modal" style="height: 600px!important;">
                                    <div class="modal-content">
                                        <!--
                                        Show Academic year as select
                                        show term has drop down
                                        show class level all or individual sub division
                                        give options for excel upload method
                                        -->

                                        <form method="post" action="{{url('/school_report_download')}}" class="submit_form_{{$SecondLeveSplitedStudent["id"]  }}">

                                            {{ csrf_field() }}
                                            <h4> Choose Year, Term and Class Parameters </h4>

                                            <input type="hidden" name="school_value"  class="school_value_{{$SecondLeveSplitedStudent["id"] }}" value="{{$SecondLeveSplitedStudent["id"] }}"    />

                                            <div class="row">

                                                <div class="col s12 m6 l3">

                                                <select name="academic_year" id="" class="academic_year" data-schoolid="{{$SecondLeveSplitedStudent["id"]  }}">
                                                    <option value="" > Academic Year  </option>
                                                    @foreach($academic_years as $academic_year)
                                                        <option {{old('academic_year') ==  $academic_year->id ? 'selected' : ''}} value="{{$academic_year->id}}"> {{$academic_year->academic_year}} </option>
                                                    @endforeach
                                                </select>
                                                    <input type="hidden"  class="academic_year_value_{{$SecondLeveSplitedStudent["id"] }}" name="academic_year_value"   />
                                                </div>

                                                <div class="col s12 m6 l3">

                                                    <select name="term" id="term" class="term" data-schoolid="{{$SecondLeveSplitedStudent["id"]  }}" >
                                                        <option> Choose Term  </option>
                                                        @foreach($terms as $term)
                                                            <option {{old('term') ==  $term->id ? 'selected' : ''}} value="{{$term->id}}">{{$term->term}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="term_value"  class="term_value_{{$SecondLeveSplitedStudent["id"] }}"    />
                                                </div>

                                                <div class="col s12 m6 l3">

                                                    <select name="class_level" class="class_level" data-schoolid="{{$SecondLeveSplitedStudent["id"]  }}">
                                                        <option> Choose Class  </option>
                                                        @foreach($classlevels as $classlevel)
                                                            <option {{old('class_level') ==  $classlevel->id ? 'selected' : ''}} value="{{$classlevel->id}}"> {{$classlevel->class_level}}</option>
                                                        @endforeach
                                                    </select>

                                                    <input type="hidden"  class="class_level_value_{{$SecondLeveSplitedStudent["id"] }}"  name="class_level_value"     />

                                                </div>

                                                {{--<div class="col s12 m6 l3">--}}
                                                    {{--<select name="class_subdivision" id="">--}}
                                                        {{--<option> Choose Class  </option>--}}
                                                        {{--@foreach($class_subdivisions as $class_subdivision)--}}
                                                            {{--<option {{old('class_level') ==  $class_subdivision->id ? 'selected' : ''}} value="{{$class_subdivision->id}}"> {{ $class_subdivision->class_subdivision }}</option>--}}
                                                        {{--@endforeach--}}
                                                    {{--</select>--}}
                                                {{--</div>--}}

                                                <div class="col s12 m6 l3">
                                                    <input type = "submit"  value="Upload score"  class="btn btn-default saveScore" data-schoolid="{{$SecondLeveSplitedStudent["id"] }}" />
                                                </div>

                                            </div>
                                        </form>



                                    </div>
                                    <div class="modal-footer">
                                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-reveal" style="height: 60%!important;" >
                             <span class="card-title grey-text text-darken-4">
                                 <h6>  School Address</h6>

                                 {{ $SecondLeveSplitedStudent["school_address"]  }}
                             </span>

                            </div>
                        </div>
                    </div>
                    @endforeach
                    <?php $count++ ?>
                @endforeach
            </div>
        @else

            <br />There are no student in this class yet
    @endif



    {{--<div class="fixed-action-btn"  style="top: 55px; right: 54px;">--}}
        {{--<a class="btn-floating btn-large light-blue darken-4 tooltipped pulse modal-trigger"  data-position="left" data-delay="50" data-tooltip="Change Academic Year"--}}
           {{--href="#modal1" >--}}
            {{--<i class="material-icons">autorenew</i>--}}
        {{--</a>--}}
    {{--</div>--}}

    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <div class="modal-content">
            <h4> Change Academic Year </h4>
            <p> Which Acedmic Year would you like to View? Choose from the option Below </p>
            <div class="YearSelectDiv row">
                <select name = "Year" id="YearSelectAdmin" >
                    <option> -- Select Academic Year -- </option>
                    <option value="2015"> 2015/2016 </option>
                    <option value="2016" > 2016/2017 </option>
                </select>

            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
    </div>


    <script>

        $( document ).ready( function()
        {

            // $('.saveScore').prop("disabled", true);

            $('.academic_year').on("change", function (e)
            {
                e.preventDefault();

                var academic_year = $(this).val();
                var school_id = $(this).data('schoolid');
                $(".academic_year_value_"+ school_id).val(academic_year);

                // console.log(academic_year, this, school_id,  $(".academic_year_value_"+ school_id));

            });

            $('.term').on("change", function (e)
            {
                e.preventDefault();

                var term = $(this).val();
                var school_id = $(this).data('schoolid');
                $(".term_value_"+ school_id).val(term);

                // console.log(term, this, school_id,  $(".term_value_"+ school_id));

            });

            $('.class_level').on("change", function (e)
            {
                e.preventDefault();

                var class_level = $(this).val();
                var school_id = $(this).data('schoolid');
                $(".class_level_value_"+ school_id).val(class_level);

                // console.log(class_level, this, school_id,  $(".class_level_value_"+ school_id));

            });

            $('.saveScore').on("click", function (e)
            {
                // var class_level = $(this).val();
                var school_id = $(this).data('schoolid');

                var class_level_value  = $(".class_level_value_"+ school_id).val();

                var term_value  = $(".term_value_"+ school_id).val();

                var academic_year_value  = $(".academic_year_value_"+ school_id).val();

                var school_value  = $(".school_value_"+ school_id).val();

                console.log($(".school_value_"+ school_id));


            {{--<input type="hidden" name="school_value"  class="school_value_{{$SecondLeveSplitedStudent["id"] }}"    />--}}

               if((typeof  class_level_value !==   'undefined' && class_level_value !==  ""  )  && (typeof  term_value !== 'undefined'  && term_value !== ""  ) && (typeof  academic_year_value !== 'undefined'  && academic_year_value !== ""  ) && (typeof  school_value !== 'undefined'  && school_value !== ""  ) )
//                 if(  (typeof  term_value !== 'undefined'  && term_value !== ""  )  )
            {
                // $(".submit_form_"+ school_id).submit();
               // this.prop("disabled", true);

            }
            else
            {
                alert("You must choose  academic year, term and class");
                // alert("You must choose term");
                e.preventDefault();
                // this.prop("disabled", false);

            }
                console.log(school_value, class_level_value,  term_value , academic_year_value );

            });

        });





    </script>


@stop
