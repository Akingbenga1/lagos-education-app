@extends('layouts.main')

@section('division_container')

  @if(isset($ChoosenTerm) and is_array($ChoosenTerm))
                  <h3> List of Students in {{ $ChoosenTerm['Class']." ".strtoupper($ChoosenTerm['SubClass'])
                       ." ". $ChoosenTerm['Year'] }}
                  </h3>
    @if(isset($StudentListMessage))
         <b> {{$StudentListMessage}}</b>
    @endif

    @if(isset($ThisStudentTerm) and is_array($ThisStudentTerm) and !empty($ThisStudentTerm))
        <div class="row">

             <p class='StudentListInfo col-md-6'> Total number of students in this class:
              <b> {{count($ThisStudentTerm)}} </b>
             </p>

            {{ Form::open(array( 'route' => 'class-report-progress-page' , 'method'=> 'post',
                'class'=>'col-md-6')) }}
                  <div class="row">
                   <div class='TermDiv col-md-3'>
                      <select name = "TermName" class="TermSelect form-control " >
                          <option>Term</option>
                          <option value="first term"> First term</option>
                          <option value="second term"> Second term</option>
                          <option value="third term"> Third term</option>
                      </select>
                   </div>
                   <div class="col-md-3">
                      <input type = "submit"  value="View Class Report Sheet Progress"  class="btn btn-default" />
                   </div>
                   </div>

                    {{Form::token()}}
            {{Form::close()}}
        </div>
          <?php $Count = 1; $YesString ='<b> Yes </b>'; $NoString =  'No';  ?>
           <?php $SplitStudentClass = array_chunk($StudentClassList, ceil(count($StudentClassList) / 3));
                // var_dump(count($things[0]));
                 $count = 1;   ?>
                  <b> Search for anybody using their surname:
           </b> <input type="text" id="CardSearch" placeholder="Search this list"></input> <p class="ShowTheCount"> </p>

           <div class="row">
                   @foreach($StudentClassList as $EachSplitedStudent)
                     <div class="col s12 m6 l3">
                         <div class="card sticky-action large" style="height: 50%!important;">
                             <div class="card-image waves-effect waves-block waves-light">
                               <?php ($EachSplitedStudent); ?>  {{ array_key_exists( 'profileimage', $EachSplitedStudent['user_belong'])? HTML::image("/Images/Icons/" . $EachSplitedStudent['user_belong']['profileimage'] . ".jpg",'', array('class' => '',  'height' => '80', 'width' => '30') ) : HTML::image("/Images/Icons/avatar.jpg",'avatar', array('class' => 'circle',  'height' => '200', 'width' => '30',
                                                                                             'style' => "width:40%!important; height:100%!important;margin-left:30%;") ) }}
                             </div>
                             <div class="card-content">
                                           <span class="card-title activator grey-text text-darken-4" style="font-size: 15px!important;">
                                                       <span  ><b>{{$EachSplitedStudent['user_belong']['surname']}} </b></span>
                                                             <span>
                                                                   @if (strlen($EachSplitedStudent['user_belong']['firstname']) > 80)
                                                                       {{ $str = substr($EachSplitedStudent['user_belong']['firstname'] , 0, 7) . ' '; }}
                                                                     @else
                                                                       {{ $EachSplitedStudent['user_belong']['firstname'] }}
                                                                   @endif
                                                             </span>

                                                             <i class="material-icons right">more_vert</i>
                                            </span>
                                           <div class="row" >
                                             <div class="col s6 m6 l6 black-text">
                                                <select class="ClassSelect browser-default">
                                                   <option> Class </option>
                                                   <option value="SS1">SS1</option>
                                                   <option value="SS2">SS2</option>
                                                   <option value="SS3">SS3</option>
                                               </select>
                                             </div>
                                             <div class=" col s6 m6 l6 black-text" >
                                                   <select class="SubClassSelect browser-default">
                                                      <option>Sub Class</option>
                                                       <option value="A">A</option>
                                                       <option value="B">B</option>
                                                       <option value="C">C</option>
                                                       <option value="D">D</option>
                                                       <option value="E">E</option>
                                                       <option value="F">F</option>
                                                       <option value="G">G</option>
                                                       <option value="H">H</option>
                                                       <option value="J">J</option>
                                                       <option value="K">K</option>
                                                   </select>
                                             </div>

                                           </div>
                                           <div class="row  black-text">
                                               <button  class="btn col  s12 m12 l12 btn-large ChangeClassYear" type="button"> Change Class </button>
                                          </div>
                                          <div class="row black-text">
                                            <br />
                                            <br />

                                            <?php

                                               $Mystudentid = $EachSplitedStudent['studentid'];
                                                 $BigSearchArray['AllThisSTudent'][$Mystudentid] =  $EachSplitedStudent;
                                               if( $ChoosenTerm['Year'] == "2015"   and $ChoosenTerm['Class'] != "SS3")
                                                 {
                                                      foreach($NextTerm as $select)
                                                        {

                                                          $ComputedStudentTerm = StudentTerm::where('studentid' ,'=', $Mystudentid)->where('thistermid', '=', $select['id'])
                                                                                            ->with("ThistermBelong")->get()->toArray();
                                                           $PromotedStudentTerm[$Mystudentid][] = !empty($ComputedStudentTerm) ?
                                                                                                  strtoupper( $ComputedStudentTerm[0]["thisterm_belong"]['classname'] .
                                                                                                              " " . $ComputedStudentTerm[0]["class_subdivision"] . " ".
                                                                                                              $ComputedStudentTerm[0]["thisterm_belong"]['year']
                                                                                                            ) : false;
                                                        }
                                                           $PromotedNewClass[$Mystudentid] = " ";
                                                           $NewArrayStudentTerm = array_filter($PromotedStudentTerm[$Mystudentid] );
                                                           $WhichString = (count( $NewArrayStudentTerm) >= 1 )  ? $YesString : $NoString;
                                                           foreach($NewArrayStudentTerm as $NAST){ $PromotedNewClass[$Mystudentid] = $NAST ." ";  }

                                                        echo  '<input type="hidden" name="PromotionStudentId" class="StudentID" value = "' . $Mystudentid . '" />';
                                                        if( $PromotedNewClass[$Mystudentid] != " ")
                                                         {
                                                            list($PromotedNewClassName, $PromotedNewSubClass, $PromotedNewYear) = explode(" ",  $PromotedNewClass[$Mystudentid]);
                                                             "<b><a class='' href=" .
                                                            URL::route('student-term-list-page',array('Year' => $PromotedNewYear, 'Class' => $PromotedNewClassName,
                                                                         'SubClass' => $PromotedNewSubClass )). ">" . $PromotedNewClass[$Mystudentid] ."</a></b>";
                                                            if($PromotedNewClassName === $ChoosenTerm['Class'] )
                                                            {

                                                              //var_dump($PromotedNewClassName);
                                                              echo '<button class="col s12 m12 l12 black-text btn btn-large red lighten-4    RepeatedBadge " type="button"> Repeated </button>';
                                                            }
                                                            else
                                                            {
                                                                echo '<button class="col  s12 m12 l12   btn btn-large green lighten-3 black-text  bg-warning PromotedBadge" type="button" > Promoted </button>';
                                                            }

                                                         }
                                                         else
                                                         {

                                                            echo ' <button class="col  s12 m12 l12   btn btn-large grey lighten-3 black-text  bg-warning PromoteStudent" type="button"> Promote </button>';

                                                         }
                                                }

                                               if( $ChoosenTerm['Class'] == "SS3" and  $ChoosenTerm['Year'] != 2016)
                                               {

                                                            $CheckGraduate = GraduateTable::where('studentid' ,'=', $Mystudentid)->get()->toArray();
                                                            //var_dump($CheckGraduate);
                                                            //$PromotedStudentTerm[$Mystudentid][] = !empty($ComputedStudentTerm) ? $ComputedStudentTerm[0]['id'] : false;

                                                            // $NewArrayStudentTerm = array_filter($PromotedStudentTerm[$Mystudentid] );
                                                             $GraduateString = ( !empty($CheckGraduate) )  ? $YesString : $NoString;
                                                             $DisableMe = ( !empty($CheckGraduate) )  ? "disabled" : " " ;

                                                            echo '
                                                             <input type="hidden" name="GraduationStudentId" class="StudentID" value = "' . $Mystudentid . '" />'.
                                                            '<input type="button" class="GraduateStudent" value = "Graduate Student"' . $DisableMe .'/>'.
                                                            ''. $GraduateString .'';
                                                            echo  '
                                                                <input type="hidden" name="PromotionStudentId" class="StudentID" value = "' . $Mystudentid . '" />';
                                                }
                                                $BigSearchArray['ChooseTerm'] =  $ChoosenTerm;
                                                  //var_dump($BigSearchArray['AllThisSTudent']);die();
                                               Session::put('BigSearchArray', $BigSearchArray ) ;
                                                 ?>

                                              <?php

                                                    echo  '<input type="hidden" name="PromotionStudentId" class="StudentID" value = "' . $Mystudentid . '" />';
                                             ?>
                                          </div>

                             </div>
                             <div class="card-reveal">
                                         <span class="card-title grey-text text-darken-4">
                                             <h6>  Student Data</h6>
                                             <i class="material-icons right">close</i>
                                         </span>
                                 <br />
                                 <br />
                                 <br />
                                 <div class="row">
                                     <div class="col s12 m12 l12" >
                                        <h6> <b> Student FullName: </b> {{$EachSplitedStudent['user_belong']['surname']}} {{$EachSplitedStudent['user_belong']['firstname']}} {{$EachSplitedStudent['user_belong']['middlename']}}</h6>
                                     </div>
                                     <div class="col s12 m12 l12">
                                         <h6><b> Student Admission Number:</b>  {{$EachSplitedStudent['school_admission_number']}} </h6>
                                     </div>
                                 </div>

                             </div>
                         </div>
                     </div>
                       <?php $count++ ?>
                   @endforeach
           </div>
    @else

      <br />There are no student in this class yet
    @endif
  @endif
  <input type="hidden" name="PromotionURL" class="PromotionURL" value ={{ URL::route('promote-this-student') }}/>
  <input type="hidden" name="ChangeClassURL" class="ChangeClassURL" value ={{ URL::route('change-student-class') }}/>
  <input type="hidden" name="GraduationURL" class="GraduationURL" value ={{ URL::route('graduate-this-student') }}/>
  <input type="hidden" class="OldClass" name="OldClass" value= "{{$ChoosenTerm['Class']}}" />
  <input type="hidden" class="CurrentYear" name="CurrentYear" value= "{{$ChoosenTerm['Year']}}" />

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

@stop
