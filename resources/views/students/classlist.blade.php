@extends('layouts.main')
@section('division_container')
    <?php
 //dd($ChoosenTerm, $ClassListCount, $ClassStudentArray, $TotalStudentClassCount );
    $WhichRoute = 'public-student-term-list-page';
   if(Auth::check() and Auth::user()->ability( array('Super User', 'Administrator', 'SchoolAdministrator','Principal', 'Vice Principal', 'Secretary','Teacher'), array()))
    {
        $WhichRoute = 'student-term-list-page';
    }
    else
    {
      $WhichRoute = 'public-student-term-list-page';
    }
    ?>
  @if(isset($ChoosenTerm) and is_array($ChoosenTerm))
       <h3 class="center-align"> List of Classes in  {{ $ChoosenTerm['Class']." ".
          $ChoosenTerm['Year'].'/'. ($ChoosenTerm['Year'] + 1) ." Session" }}
      </h3>
      @if( $ChoosenTerm['Class'] === "SS1")
         <div>
             @if($ClassListCount > 0 and count($ClassStudentArray) > 0 )
                 <script type='text/javascript'>
                     <?php
                     if(isset($ClassListArray))
                     {
                         echo "var ClassListArray = ". $ClassListArray . ";\n";
                         echo "var ChoosenTerm = ". json_encode($ChoosenTerm) . ";\n";
                     }

                     ?>
                     if (typeof ClassListArray !== "undefined" || typeof ChoosenTerm !== "undefined")
                     {
                        console.log(ClassListArray);
                     }
                     </script>
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <div id="ClassListBar" class="">

                                </div>
                            </div>
                            {{--<div class="col s12 m6 l6">--}}
                                {{--<div id="ClassListPie" class="">--}}

                                {{--</div>--}}
                            {{--</div>--}}
                        </div>


                        <div class="row">
                            <div class="col s12 m12 l12" >
                                @foreach($ClassStudentArray as $EachClassStudentArray)
                                    <div class="col s12 m3 l3">
                                        <div class="card sticky-action small">
                                            <div class="card-image waves-effect waves-block waves-light">
                                                {{HTML::image("Images/Logos/t_ui.jpg", '', array('class' => 'responsive-img', 'height' => '50', 'width' => '50') )}}
                                            </div>
                                            <div class="card-content">
                                                <span class="card-title activator grey-text text-darken-4">{{ $EachClassStudentArray['FullClassName'] }} Class<i class="material-icons right">more_vert</i></span>
                                                {{--<p><a href="#">This is a link</a></p>--}}
                                            </div>
                                            <div class="card-action">
                                                <a href="{{URL::route($WhichRoute,
                                                array('Year' => $EachClassStudentArray['ClassYear'], 'Class' =>$EachClassStudentArray['ClassName'],
                                                'SubClass' => $EachClassStudentArray['ClassSubDivision'] ))}}">View Students</a>

                                            </div>
                                            <div class="card-reveal">
                                                <span class="card-title grey-text text-darken-4"> <h6> {{$EachClassStudentArray['FullClassName'] }} Class Statistics</h6>
                                                    <i class="material-icons right">close</i>
                                                </span>
                                                <p>
                                                <div style="display:inline;width:130px;height:50px;">
                                                    <canvas width="162" height="17" style="width: 130px; height: 20px;"></canvas>
                                                    <input type="text" class="dial" data-linecap=round  value="{{$EachClassStudentArray['ClassStudentCount'] }}" data-width="130" data-height="150"
                                                           data-min="0" data-max="{{$TotalStudentClassCount}}" data-readOnly=true  data-fgcolor="#ffa200"
                                                           data-angleOffset=-125 data-angleArc=250
                                                           data-thickness=".15" style="width: 69px; height: 43px; position: absolute; vertical-align: middle;
                                                           margin-top: 3px; margin-left: -99px; border: 0px; background: none; font-style: normal;
                                                           font-variant: normal; font-weight: 600; font-stretch: normal; font-size: 26px; line-height: normal;
                                                           font-family: &quot;Source Sans Pro&quot;, Arial; text-align: center; color: rgb(255, 162, 0);
                                                           padding: 0px; -webkit-appearance: none;"> <br /> out of  <b> {{$TotalStudentClassCount}}</b> Total Students in  {{$EachClassStudentArray['ClassName']}}
                                                </div>
                                                <br />
                                                    {{--Here is some more information about this product that is only revealed once clicked on.--}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
             @endif
                </div>
      @elseif($ChoosenTerm['Class'] === "SS2")
            <div>
                @if($ClassListCount > 0 and count($ClassStudentArray) > 0 )
                    <script type='text/javascript'>
                        <?php
                            if(isset($ClassListArray))
                            {
                                echo "var ClassListArray = ". $ClassListArray . ";\n";
                                echo "var ChoosenTerm = ". json_encode($ChoosenTerm) . ";\n";
                            }

                            ?>
                        if (typeof ClassListArray !== "undefined" || typeof ChoosenTerm !== "undefined")
                        {
                            console.log(ClassListArray);
                        }
                    </script>
                    <div class="row">
                        <div class="row">
                            <div class="col s12 m12 l12">
                                <div id="ClassListBar" class="">

                                </div>
                            </div>
                            {{--<div class="col s12 m6 l6">--}}
                            {{--<div id="ClassListPie" class="">--}}

                            {{--</div>--}}
                            {{--</div>--}}
                        </div>

                        <div class="col s12 m12 l12" >
                            @foreach($ClassStudentArray as $EachClassStudentArray)
                                <div class="col s12 m3 l3">
                                    <div class="card sticky-action small">
                                        <div class="card-image waves-effect waves-block waves-light">
                                            {{HTML::image("Images/Logos/t_ui.jpg", '', array('class' => 'responsive-img', 'height' => '50', 'width' => '50') )}}
                                        </div>
                                        <div class="card-content">
                                            <span class="card-title activator grey-text text-darken-4">{{ $EachClassStudentArray['FullClassName'] }} Class<i class="material-icons right">more_vert</i></span>
                                            {{--<p><a href="#">This is a link</a></p>--}}
                                        </div>
                                        <div class="card-action">
                                            <a href="{{URL::route($WhichRoute,
                                                array('Year' => $EachClassStudentArray['ClassYear'], 'Class' =>$EachClassStudentArray['ClassName'],
                                                'SubClass' => $EachClassStudentArray['ClassSubDivision'] ))}}">View Students</a>

                                        </div>
                                        <div class="card-reveal">
                                                <span class="card-title grey-text text-darken-4"> <h6> {{$EachClassStudentArray['FullClassName'] }} Class Statistics</h6>
                                                    <i class="material-icons right">close</i>
                                                </span>
                                            <p>
                                            <div style="display:inline;width:130px;height:50px;">
                                                <canvas width="162" height="17" style="width: 130px; height: 20px;"></canvas>
                                                <input type="text" class="dial" data-linecap=round  value="{{$EachClassStudentArray['ClassStudentCount'] }}" data-width="130" data-height="150"
                                                       data-min="0" data-max="{{$TotalStudentClassCount}}" data-readOnly=true  data-fgcolor="#ffa200"
                                                       data-angleOffset=-125 data-angleArc=250
                                                       data-thickness=".15" style="width: 69px; height: 43px; position: absolute; vertical-align: middle;
                                                           margin-top: 3px; margin-left: -99px; border: 0px; background: none; font-style: normal;
                                                           font-variant: normal; font-weight: 600; font-stretch: normal; font-size: 26px; line-height: normal;
                                                           font-family: &quot;Source Sans Pro&quot;, Arial; text-align: center; color: rgb(255, 162, 0);
                                                           padding: 0px; -webkit-appearance: none;"> <br /> out of  <b> {{$TotalStudentClassCount}}</b> Total Students in  {{$EachClassStudentArray['ClassName']}}
                                            </div>
                                            <br />
                                            {{--Here is some more information about this product that is only revealed once clicked on.--}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        {{--<div class="col s12 m12 l4" >--}}
                            {{--<div id="ClassListBar">--}}

                            {{--</div>--}}
                            {{--<div id="ClassListPie">--}}

                            {{--</div>--}}
                        {{--</div>--}}



                    </div>
                @endif
            </div>
      @elseif($ChoosenTerm['Class'] === "SS3")
          <div>
              @if($ClassListCount > 0 and count($ClassStudentArray) > 0 )
                  <script type='text/javascript'>
                      <?php
                          if(isset($ClassListArray))
                          {
                              echo "var ClassListArray = ". $ClassListArray . ";\n";
                              echo "var ChoosenTerm = ". json_encode($ChoosenTerm) . ";\n";
                          }

                          ?>
                      if (typeof ClassListArray !== "undefined" || typeof ChoosenTerm !== "undefined")
                      {
                          console.log(ClassListArray);
                      }
                  </script>
                  <div class="row">
                      <div class="row">
                          <div class="col s12 m12 l12">
                              <div id="ClassListBar" class="">

                              </div>
                          </div>
                          {{--<div class="col s12 m6 l6">--}}
                          {{--<div id="ClassListPie" class="">--}}

                          {{--</div>--}}
                          {{--</div>--}}
                      </div>
                      <div class="col s12 m12 l12" >
                          @foreach($ClassStudentArray as $EachClassStudentArray)
                              <div class="col s12 m3 l3">
                                  <div class="card sticky-action small">
                                      <div class="card-image waves-effect waves-block waves-light">
                                          {{HTML::image("Images/Logos/t_ui.jpg", '', array('class' => 'responsive-img', 'height' => '50', 'width' => '50') )}}
                                      </div>
                                      <div class="card-content">
                                          <span class="card-title activator grey-text text-darken-4">{{ $EachClassStudentArray['FullClassName'] }} Class<i class="material-icons right">more_vert</i></span>
                                          {{--<p><a href="#">This is a link</a></p>--}}
                                      </div>
                                      <div class="card-action">
                                          <a href="{{URL::route($WhichRoute,
                                                array('Year' => $EachClassStudentArray['ClassYear'], 'Class' =>$EachClassStudentArray['ClassName'],
                                                'SubClass' => $EachClassStudentArray['ClassSubDivision'] ))}}">View Students</a>

                                      </div>
                                      <div class="card-reveal">
                                                <span class="card-title grey-text text-darken-4"> <h6> {{$EachClassStudentArray['FullClassName'] }} Class Statistics</h6>
                                                    <i class="material-icons right">close</i>
                                                </span>
                                          <p>
                                          <div style="display:inline;width:130px;height:50px;">
                                              <canvas width="162" height="17" style="width: 130px; height: 20px;"></canvas>
                                              <input type="text" class="dial" data-linecap=round  value="{{$EachClassStudentArray['ClassStudentCount'] }}" data-width="130" data-height="150"
                                                     data-min="0" data-max="{{$TotalStudentClassCount}}" data-readOnly=true  data-fgcolor="#ffa200"
                                                     data-angleOffset=-125 data-angleArc=250
                                                     data-thickness=".15" style="width: 69px; height: 43px; position: absolute; vertical-align: middle;
                                                           margin-top: 3px; margin-left: -99px; border: 0px; background: none; font-style: normal;
                                                           font-variant: normal; font-weight: 600; font-stretch: normal; font-size: 26px; line-height: normal;
                                                           font-family: &quot;Source Sans Pro&quot;, Arial; text-align: center; color: rgb(255, 162, 0);
                                                           padding: 0px; -webkit-appearance: none;"> <br /> out of  <b> {{$TotalStudentClassCount}}</b> Total Students in  {{$EachClassStudentArray['ClassName']}}
                                          </div>
                                          <br />
                                          {{--Here is some more information about this product that is only revealed once clicked on.--}}
                                          </p>
                                      </div>
                                  </div>
                              </div>
                          @endforeach

                      </div>

                  </div>
              @endif
          </div>
      @endif

       <div class="fixed-action-btn"  style="top: 55px; right: 54px;">
           <a class="btn-floating btn-large light-blue darken-4 tooltipped pulse modal-trigger"  data-position="left" data-delay="50" data-tooltip="Change Academic Year"
              href="#modal1" >
               <i class="material-icons">autorenew</i>
           </a>
       </div>
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
                   <input type="hidden" class="ChangeYearAdmin" value="{{URL::route('class-list-ajax')}}" />
                   <input type="hidden" class="CurrentClass" value="{{ $ChoosenTerm['Class'] }}" />

               </div>
           </div>
           <div class="modal-footer">
               <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
           </div>
       </div>

  @endif
@stop
