@extends('layouts.main')

@section('division_container')

  @if(isset($ChoosenTerm) and is_array($ChoosenTerm))
    <h3 class="center-align"> List of Students in {{ $ChoosenTerm['Class']." ".strtoupper($ChoosenTerm['SubClass'])." ". $ChoosenTerm['Year'] }}</h3>
    <div>
            @if(isset($StudentListMessage))
                 <b> {{$StudentListMessage}}</b> <br />
            @endif
    </div>

    @if(isset($StudentClassList) and is_array($StudentClassList) and !empty($StudentClassList))
            <b> Total number of students: {{ count($StudentClassList) }} </b>
            <?php $SplitStudentClass = array_chunk($StudentClassList, ceil(count($StudentClassList) / 3)); $count = 1;   ?>

          <b> Search for anybody on this list using their surname: </b>
          <input type="text" id="CardSearch"id="CardSearch"  placeholder="Search this list"/>
            <div class="ShowTheCount"> </div>

            <div class="row">
                    @foreach($StudentClassList as $EachSplitedStudent)
                      <div class="col s12 m6 l3">
                          <div class="card sticky-action small">
                              <div class="card-image waves-effect waves-block waves-light">
                                <?php ($EachSplitedStudent); ?>  {{ array_key_exists( 'profileimage', $EachSplitedStudent['user_belong'])? HTML::image("/Images/Icons/" . $EachSplitedStudent['user_belong']['profileimage'] . ".jpg",'', array('class' => '',  'height' => '80', 'width' => '30') ) : HTML::image("/Images/Icons/avatar.jpg",'avatar', array('class' => 'circle',  'height' => '250', 'width' => '30',
                                                                                              'style' => "width:40%!important; height:100%!important;margin-left:30%;") ) }}
                              </div>
                              <div class="card-content">
                                  <span class="card-title activator grey-text text-darken-4">
                                      <span ><b>{{$EachSplitedStudent['user_belong']['surname']}} </b></span>
                                            <span>
                                              @if (strlen($EachSplitedStudent['user_belong']['firstname']) > 80)
                                                  {{ $str = substr($EachSplitedStudent['user_belong']['firstname'] , 0, 7) . ' ' }}
                                                @else
                                                  {{ $EachSplitedStudent['user_belong']['firstname'] }}
                                              @endif

                                            </span>
                                            <i class="material-icons right">more_vert</i></span>
                                                  {{--<p><a href="#">This is a link</a></p>--}}
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
      <br /> There is no student in this class yet
    @endif
  @endif


@stop
