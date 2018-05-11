@extends('layouts.main')

@section('division_container')

 <h3> Score Progress Report for  <b> {{
          $BigSearchArray['ChooseTerm']['Year'] ." ".
          $BigSearchArray['ChooseTerm']['Class'] ." ".
          $BigSearchArray['ChooseTerm']['SubClass']
          }} </b> for <b> {{$TermName}} </b>
    </h3>
 
          
    @if( is_array($BigSearchArray) and is_array($Massive) and is_integer($CountofAvailableReport)  )

    @if(isset($StudentListMessage))
         <b> {{$StudentListMessage}}</b>
    @endif
    <hr />
      <b> Please note: </b> 
      If the <b> Number of Students with recorded results </b> is shown and there is not table data.
      It means that the scores are not valid because the student does not exist in this class 
       <b> {{ $BigSearchArray['ChooseTerm']['Year'] ." ".
          $BigSearchArray['ChooseTerm']['Class'] ." ".
          $BigSearchArray['ChooseTerm']['SubClass']
      }} </b>
      <hr />
    <br />

     <?php unset($Checker);  $Checker = ""; $count = 8;   ?>
     <b> Search for anybody on this table: </b> <input type="text" id="search" placeholder="Search this table"></input> <br /><br > 
     <div class="w3-row"> 
                   <table border= 1  class="w3-table w3-threequarter w3-bordered w3-striped w3-border w3-hoverable" >
                                <tr class="w3-grey w3-text-white">
                                  <th style="text-align:center,font-weight:bold;" > S/N </th>
                                  <th style="text-align:center;"> Name of Student </th>
                                  <th style="text-align:center;"> Student Admission Number </th>
                                <!--  <th style="text-align:center;"> Student ID </th> -->
                                  <th style="text-align:center;"> Number of Subjects recorded</th>
                                  <th style="text-align:center;"> List of Actual subjects Recorded </th>

                                </tr>   
                                <?php $count = 1 ?>          
                                @foreach($BigSearchArray['AllThisSTudent'] as $bigSearchArray)
                                  
                                  @foreach($Massive as $massive)
                                         <?php  $MassiveStudentID = $massive['StudentId'];   
                                         $Checker[$MassiveStudentID] =  $massive['StudentId']; ?>
                                      @if($bigSearchArray['studentid'] == $massive['StudentId'] ) 
                                        
                                       <tr class="w3-hover-light-green">
                                          <td style="text-align:center,font-weight:bold;" >{{$count++ }} </td>
                                           <td style="text-align:center;">
                                                     {{ $bigSearchArray['user_belong']['surname'] ." ".
                                                        $bigSearchArray['user_belong']['middlename']." ".
                                                        $bigSearchArray['user_belong']['firstname']
                                                      }}  
                                           </td>
                                           <td style="text-align:center;"> {{ $bigSearchArray['school_admission_number'] }}</td>
                                          <!-- <td style="text-align:center;"> {{ $bigSearchArray['studentid'] }}

                        </td> -->
                                          <td style="text-align:center;" > <b> {{$massive['StudentSubjectCount'] }} 

                        </b> </td>
                                           <td style="tet-align:center;" >
                                          @foreach($massive['SubjectList'] as $subjectList)
                                                 {{ ucfirst(strtolower( $subjectList['subject_belong']['subject'] 

                        ))}}
                                                 (by  <b> {{ Str::lower( $subjectList['modified_by_belong']['surname'] )}}</b> ), 
                                          @endforeach
                                            </td> 
                            
                                       </tr>
                                        

                                      @endif
                                     
                                   @endforeach
                                   <?php unset($Checker[$massive['StudentId']]);  ?>
                                @endforeach
                                     
                              </table>
     
   
      <div class="w3-quarter w3-container w3-deep-orange">

      <br /><br />
       <b> Completion Rate</b>
      <div class="w3-progress-container">     
        <div id="myBar" class="w3-progressbar w3-green" style="width:{{ $TotalStudentInClass != 0 ? (int)(( ($count - 1)/ $TotalStudentInClass )*100) : 0 }}%">
        <div class="w3-center w3-text-white"> {{ $TotalStudentInClass != 0 ? (int)(( ($count - 1)/ $TotalStudentInClass )*100) : 0 }}% </div>
        </div>
      </div>
      <br /><br />

      Total Number of Students in class: <b> {{$TotalStudentInClass}}</b> <br /><hr />
      Students with recorded results is: <b> {{$CountofAvailableReport}} </b> <br /><hr />
      Students with recorded results who are student of  <b> {{ $BigSearchArray['ChooseTerm']['Year'] ." ".
          $BigSearchArray['ChooseTerm']['Class'] ." ".
          $BigSearchArray['ChooseTerm']['SubClass']
      }} </b>  is: <b> {{$count - 1}} </b> <br /><br />

      </div>

      </div>
      
      @else
          No Table to show  - No student with <b> score record</b> in this class
      @endif

@stop