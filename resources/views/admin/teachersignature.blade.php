@extends('layouts.main_table')

@section('division_container')
<h3> upload  Teachers signature </h3>
<br />
<br />

  @if(Session::has('TeacherSignatureResponse'))
    {{Session::get('TeacherSignatureResponse')}}
  @endif
<br />
  @if($errors->has('AssignedTeacher'))
   <b> {{$errors->first('AssignedTeacher')}}</b>
  @endif
  @if($errors->has('SignatureImage'))
    <b>{{$errors->first('SignatureImage')}}</b>
  @endif

  <div class="w3-row">

  {{ Form::open(array( 'route' => 'post-teachers-signature' ,'files' => true, 'method'=> 'post',
      'class'=>'w3-third w3-container w3-blue w3-text-black')) }}  
      <h4 class="w3-text-white"> New Signature </h4>
      <select name = "AssignedTeacher" >
        <option> -- Choose User -- </option>
        @if(  isset($AllAssignedRoles) and is_array($AllAssignedRoles) )
            @foreach($AllAssignedRoles as $EveryUser)
              <option value="{{$EveryUser['user_id']}}">
                {{$EveryUser['user_belong']['useremail']}} 
              </option>
            @endforeach
        @else
          <option> No assigned user available in database </option>
        @endif
      </select>
     <input  type="file" name="SignatureImage"/>  
     <input  type="submit" value="upload" class="w3-center" />    
     {{Form::token()}}     
  {{Form::close()}}   
 

  {{ Form::open(array( 'route' => 'update-teachers-signature' ,'files' => true, 'method'=> 'post', 'class'=>'w3-third w3-container w3-blue w3-text-black')) }}  
       <h4 class="w3-text-white"> Update Signature </h4>
      <select name = "AssignedTeacher" >
        <option> -- Choose User -- </option>
        @if(  isset($AllAssignedRoles) and is_array($AllAssignedRoles) )
            @foreach($AllAssignedRoles as $EveryUser)
              <option value="{{$EveryUser['user_id']}}">
                {{$EveryUser['user_belong']['useremail']}} 
              </option>
            @endforeach
        @else
          <option> No assigned user available in database </option>
        @endif
      </select>
     <input  type="file" name="SignatureImage"/>  
     <input  type="submit" value="update signature" class="w3-center" />    
     {{Form::token()}}     
  {{Form::close()}}   

   {{ Form::open(array( 'route' => 'delete-teachers-signature' ,'files' => true, 'method'=> 'post', 'class'=>'w3-third w3-container w3-blue w3-text-black')) }}  
       <h4 class="w3-text-white"> Delete Signature </h4>
      <select name = "AssignedTeacher" >
        <option> -- Choose User -- </option>
        @if(  isset($AllAssignedRoles) and is_array($AllAssignedRoles) )
            @foreach($AllAssignedRoles as $EveryUser)
              <option value="{{$EveryUser['user_id']}}">
                {{$EveryUser['user_belong']['useremail']}} 
              </option>
            @endforeach
        @else
          <option> No assigned user available in database </option>
        @endif
      </select>
      <br />
      <br />
     <input  type="submit" value="delete signature" class="w3-center" />    
     {{Form::token()}}     
  {{Form::close()}}   
   </div>
<!--   {{var_dump($OfficialSignatures)}}  -->
   @include('includes.SchoolArray')
 
   @if(  isset($OfficialSignatures) and is_array($OfficialSignatures) )
    @if( is_array($OfficialSignatures) and !empty($OfficialSignatures) )
                      
           <br /><b> Search for anybody on this table: </b> <input type="text" id="search" placeholder="Search this table"></input> <br /><br > 
                <!-- <table id="ColSpanTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                  <tr> 
                    <th> S/N </th>
                    <th> Staff full name </th>
                    <th> Staff email  </th>
                    <th> Staff Signature  </th>
                  </tr>
                  <?php $Count = 1; ?>
                  @foreach($OfficialSignatures as $EveryOfficialSignatures)
                    <tr> 
                     <?php $Image = $EveryOfficialSignatures['signatureimage'];?> 
                      <td> {{$Count++}} </td>
                      <td> {{ $EveryOfficialSignatures['user_belong']['surname']." ". 
                              $EveryOfficialSignatures['user_belong']['middlename']." ". 
                              $EveryOfficialSignatures['user_belong']['firstname']
                           }} 
                      </td>
                      <td> {{$EveryOfficialSignatures['user_belong']['useremail']}} </td>
                      <td>  {{HTML::image("/Images/Signatures/$Image", '', array('class' => '') )}}</td>      

                    </tr>
                  @endforeach
                </table> -->

           <!-- ##################### Pre-Primary School Enrolment By Class and Age ############################# -->
                  <table id="ColSpanTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead style="text-align: center;">
                        <tr> 
                          <th colspan="11" style="text-align: center;"> Public ( Pre-Primary School Enrolment By Class and Age ) </th>
                        </tr>

                         <tr> 
                          <th rowspan="2" style=" vertical-align: middle;"> Pupil age </th>
                          <th colspan="2" class="text-muted"> Kindergarten 1/ECCD  </th>
                          <th colspan="2" class="text-muted"> Kindergarten 2/ECCD  </th>
                          <th colspan="2" class="text-muted"> Nursery 1            </th>
                          <th colspan="2" class="text-muted"> Nursery 2            </th>
                          <th colspan="2" class="text-muted"> Nursery 3            </th>
                        </tr>
                         <tr> 
                          <th> Male                </th>
                          <th> Female              </th>
                          <th> Male                </th>
                          <th> Female              </th>
                          <th> Male                </th>
                          <th> Female              </th>
                          <th> Male                </th>
                          <th> Female              </th>
                          <th> Male                </th>
                          <th> Female               </th>
                        </tr>

                    </thead>
                        <?php 
                              $PrePrimaryCount = 0; // include 
                              //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                              include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way 
                         ?>
                        
                          <?php  $PrePrimaryKeys = array_keys($PrePrimary); ?>

                        @foreach($PrePrimaryOfficialSignatures as $EveryOfficialSignatures)
                         
                          <tr> 
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?> 
                            <td style="font-weight: 900">  {{ $PrePrimary[$PrePrimaryKeys[$PrePrimaryCount++]] }} </td>
                            <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                            <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                            <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                            <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                            <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                            <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                            <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                            <!-- <td>  {{HTML::image("/Images/Signatures/$Image", '', array('class' => '') )}}</td> -->
                             <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                             <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                              @if ( $PrePrimaryCount == (count($PrePrimary))  )
                                      <td> &nbsp; </td>
                              @else
                                      <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                              @endif
                          </tr>
                        @endforeach
                  </table>

           <!-- ##################### Primary School Enrolment By Class and Age  ############################# -->
           <div class="table-responsive">
                   <h2 class="text-center">  Primary School Enrolment By Class and Age  </h2>
                   <table id="ColSpanTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                       <thead style="text-align: center;" class="alert-warning">
                       <tr>
                           <th rowspan="3" style="vertical-align: middle;" class="alert-warning"> Pupil age   </th>
                           <th colspan="12" style="text-align: center;">  PRIMARY 2013/2014 </th>
                           <th colspan="2" rowspan="2" class="text-muted text-center alert-warning"  style="vertical-align: middle;">
                               NEW INTAKE (PRY 1)  ONLY </th>
                       </tr>

                       <tr>
                           <th colspan="2" class="text-muted text-center alert-warning"> PR1  </th>
                           <th colspan="2" class="text-muted text-center alert-warning"> PR2  </th>
                           <th colspan="2" class="text-muted text-center alert-warning"> PR3  </th>
                           <th colspan="2" class="text-muted text-center alert-warning"> PR4  </th>
                           <th colspan="2" class="text-muted text-center alert-warning"> PR5  </th>
                           <th colspan="2" class="text-muted text-center alert-warning"> PR6  </th>

                       </tr>
                       <tr>
                           <th> Male                </th>
                           <th> Female              </th>
                           <th> Male                </th>
                           <th> Female              </th>
                           <th> Male                </th>
                           <th> Female              </th>
                           <th> Male                </th>
                           <th> Female              </th>
                           <th> Male                </th>
                           <th> Female              </th>
                           <th> Male                </th>
                           <th> Female              </th>
                           <th> Male                </th>
                           <th> Female              </th>
                       </tr>

                       </thead>
                       <?php
                       $PrimaryCount = 0; // include
                       //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                       include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                       ?>

                       <?php  $PrimaryKeys = array_keys($Primary); ?>

                       @foreach($PrimaryOfficialSignatures as $EveryOfficialSignatures)

                           <tr style="border:solid 1px black;">
                               <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                               <td style="font-weight: 900">  {{ $Primary[$PrimaryKeys[$PrimaryCount++]] }} </td>
                               <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                               <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                               <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                               <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                               <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                               <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                               <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                               <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                               <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                               <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                               <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                               @if ( $PrimaryCount == (count($Primary))  )
                                   <td> &nbsp; </td>
                               @else
                                   <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                               @endif
                           </tr>
                       @endforeach
                   </table>
           </div>

           <!-- ##################### Primary School Enrolment By Class and Age  ############################# -->
           <div class="table-responsive">
               <h2>  STATE  SUMMARY ON  ENROLMENT  BY  LOCATION (RURAL AND URBAN)  2013/2014  </h2>
               <table id="ColSpanTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="15" style="text-align: center;" class="alert-warning"> STATE  SUMMARY ON  ENROLMENT  BY  LOCATION (RURAL AND URBAN)  2013/2014  </th>
                   </tr>

                   <tr>
                       <th class="text-muted text-center alert-warning"> &nbsp;  </th>
                       <th  rowspan="2"  class="text-muted text-center alert-warning"> NO SCHOOL  </th>
                       <th  rowspan="2"  class="text-muted text-center alert-warning"> CLASSROOM  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR1  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR2  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR3  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR4  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR5  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR6  </th>

                   </tr>
                   <tr>
                       <th class="alert-warning">                 </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                   </tr>

                   </thead>
                   <?php
                   $UrbanRuralCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php  $UrbanRuralKeys = array_keys($UrbanRural); ?>

                   @foreach($UrbanRuralOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $UrbanRural[$UrbanRuralKeys[$UrbanRuralCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           @if ( $UrbanRuralCount == (count($UrbanRural))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
               </table>
           </div>

           <!-- ##################### STATE  SUMMARY ON REPEATERS/PROMOTION  PRIMARY  SCHOOLS  2013/2014  ############################# -->
           <div>
               <h2>  STATE  SUMMARY ON REPEATERS/PROMOTION  PRIMARY  SCHOOLS  2013/2014  </h2>
               <table id="ColSpanTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="13" style="text-align: center;" class="alert-warning"> STATE  SUMMARY ON  ENROLMENT  BY  LOCATION (RURAL AND URBAN)  2013/2014  </th>
                   </tr>

                   <tr>
                       <th class="text-muted text-center alert-warning"> &nbsp;  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR1  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR2  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR3  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR4  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR5  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> PR6  </th>

                   </tr>
                   <tr>
                       <th class="alert-warning">                 </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                       <th> Male                </th>
                       <th> Female              </th>
                   </tr>

                   </thead>
                   <?php
                   $StateSummaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php  $StateSummaryKeys = array_keys($StateSummary); ?>

                   @foreach($StateSummaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $StateSummary[$StateSummaryKeys[$StateSummaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>

                           @if ( $StateSummaryCount == (count($StateSummary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
               </table>
           </div>

           <!-- ##################### PRIMARY SCHOOL FACILITIES INFORMATION 2014/2015  ############################# -->
           <div class="table-responsive">
               <h2>  PRIMARY SCHOOL FACILITIES INFORMATION 2014/2015  </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="18" style="text-align: center;" class="alert-warning"> PRIMARY SCHOOL FACILITIES INFORMATION 2014/2015  </th>
                   </tr>

                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> No of schools </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Number of  Classrooms  </th>
                       <th colspan="4" class="text-muted text-center alert-warning"> Number of Toilets  </th>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Number of Schools with Playground  </th>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle;" > Number of Schools with ICT Facilities  </th>
                       <th colspan="4" rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Number of schools with  </th>
                       <th colspan="5" rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Sources of Electricity  </th>
                   </tr>
                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;">  Good  </th>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;" > Bad </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Pupils  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Teachers  </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Pipe borne water    </th>
                       <th class="alert-warning"> Borehole            </th>
                       <th class="alert-warning"> Well                </th>
                       <th class="alert-warning"> Other source        </th>
                       <th class="alert-warning"> None                </th>
                       <th class="alert-warning"> PHCN                </th>
                       <th class="alert-warning"> Generators          </th>
                       <th class="alert-warning"> Solar               </th>
                       <th class="alert-warning"> Others              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $StateSummaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php  $StateSummaryKeys = array_keys($StateSummary); ?>

                   @foreach($StateSummaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $StateSummary[$StateSummaryKeys[$StateSummaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>

                           @if ( $StateSummaryCount == (count($StateSummary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                        </tbody>
               </table>
           </div>

           <!-- ##################### PRIMARY SCHOOL ENROLMENT BY LGA, SCHOOL, CLASSROOM AND ARMS 2014/2015  ############################# -->
           <div class="table-responsive">
               <h2>  PRIMARY SCHOOL ENROLMENT BY LGA, SCHOOL, CLASSROOM AND ARMS 2014/2015   </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="18" style="text-align: center;" class="alert-warning"> PRIMARY SCHOOL ENROLMENT BY LGA, SCHOOL, CLASSROOM AND ARMS 2014/2015 </th>
                   </tr>

                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LGA </th>
                       <th rowspan="2" class="text-muted text-center alert-warning">NUMBER OF SCHOOLS   </th>
                       <th rowspan="2" class="text-muted text-center alert-warning"> NUMBER OF CLASSROOMS  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;">PRY 1 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;" > PRY 2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> PRY 3 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> PRY 4  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> PRY 5  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> PRY 6  </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $StateSummaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php  $StateSummaryKeys = array_keys($StateSummary); ?>

                   @foreach($StateSummaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $StateSummary[$StateSummaryKeys[$StateSummaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           @if ( $StateSummaryCount == (count($StateSummary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- ##################### NUMBER OF PUPILS WITH PHYSICAL AND MENTAL CHALLENGES OR SPECIAL NEEDS 2014/2015 (PRIMARY)  ############################# -->
           <div class="table-responsive">
               <h2>  NUMBER OF PUPILS WITH PHYSICAL AND MENTAL CHALLENGES OR SPECIAL NEEDS 2014/2015 (PRIMARY)   </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="18" style="text-align: center;" class="alert-warning"> NUMBER OF PUPILS WITH PHYSICAL AND MENTAL CHALLENGES OR SPECIAL NEEDS 2014/2015 (PRIMARY) </th>
                   </tr>

                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> CHALLENGE THAT IMPACTS THE ABILITY TO LEARN </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;">PRY 1 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;" > PRY 2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> PRY 3 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> PRY 4  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> PRY 5  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> PRY 6  </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $PhysicalMentalCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $PhysicalMentalKeys = array_keys( $PhysicalMental); ?>

                   @foreach( $PhysicalMentalOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $PhysicalMental[$PhysicalMentalKeys[$PhysicalMentalCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           @if ( $PhysicalMentalCount == (count($PhysicalMental))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  NUMBER OF  CARE-GIVERS BY SEX AND LGA  ############################# -->
           <div class="table-responsive">
               <h2>  NUMBER OF  CARE-GIVERS BY SEX AND LGA  </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LGA </th>
                       <th colspan="6" style="text-align: center;" class="alert-warning"> NUMBER OF  CARE-GIVERS BY SEX AND LGA </th>
                   </tr>
                   <tr>
                       <th colspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> ALL  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> QUALIFIED </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Total               </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $CareGiverCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $CareGiverKeys = array_keys( $CareGiver); ?>

                   @foreach( $CareGiverOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $CareGiver[$CareGiverKeys[$CareGiverCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $CareGiverCount == (count($CareGiver))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  PRIMARY SCHOOL TEACHERS DISTRIBUTIONS BY SEX AND LGA  ############################# -->
           <div class="table-responsive">
               <h2>  PRIMARY SCHOOL TEACHERS DISTRIBUTIONS BY SEX AND LGA </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LGA </th>
                       <th colspan="5" style="text-align: center;" class="alert-warning"> PRIMARY SCHOOL TEACHERS DISTRIBUTIONS BY SEX AND LGA  </th>
                   </tr>
                   <tr>
                       <th colspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> ALL  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> QUALIFIED </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Total               </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $CareGiverCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $CareGiverKeys = array_keys( $CareGiver); ?>

                   @foreach( $CareGiverOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $CareGiver[$CareGiverKeys[$CareGiverCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $CareGiverCount == (count($CareGiver))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  NUMBER OF  CARE-GIVERS BY LOCATION  ############################# -->
           <div class="table-responsive">
               <h2>  NUMBER OF  CARE-GIVERS BY LOCATION </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LOCATION </th>
                       <th colspan="5" style="text-align: center;" class="alert-warning"> NUMBER OF  CARE-GIVERS BY LOCATION  </th>
                   </tr>
                   <tr>
                       <th colspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> ALL  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> QUALIFIED </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Total               </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $UrbanRuralCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $UrbanRuralKeys = array_keys( $UrbanRural); ?>

                   @foreach( $UrbanRuralOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $UrbanRural[$UrbanRuralKeys[$UrbanRuralCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $UrbanRuralCount == (count($UrbanRural))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  PRIMARY SCHOOL TEACHERS DISTRIBUTIONS BY LOCATION  ############################# -->
           <div class="table-responsive">
               <h2>  PRIMARY SCHOOL TEACHERS DISTRIBUTIONS BY LOCATION </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LOCATION </th>
                       <th colspan="5" style="text-align: center;" class="alert-warning"> NUMBER OF  CARE-GIVERS BY LOCATION  </th>
                   </tr>
                   <tr>
                       <th colspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> ALL  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> QUALIFIED </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Total               </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $UrbanRuralCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $UrbanRuralKeys = array_keys( $UrbanRural); ?>

                   @foreach( $UrbanRuralOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $UrbanRural[$UrbanRuralKeys[$UrbanRuralCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $UrbanRuralCount == (count($UrbanRural))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  Junior Secondary  School Enrolment By Class and Age   ############################# -->
           <div class="table-responsive">
               <h2>  Junior Secondary  School Enrolment By Class and Age  </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;">Pupil age </th>
                       <th colspan="6" style="text-align: center;" class="alert-warning"> PUBLIC JS 2014/2015  </th>
                       <th colspan="2" rowspan="2" style="text-align: center;" class="alert-warning"> NEW INTAKE  (JS1) ONLY  </th>
                   </tr>
                   <tr>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> JS1  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> JS2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> JS3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $JuniorSecondaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $JuniorSecondaryKeys = array_keys( $JuniorSecondary); ?>

                   @foreach( $JuniorSecondaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $JuniorSecondary[$JuniorSecondaryKeys[$JuniorSecondaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $JuniorSecondaryCount == (count($JuniorSecondary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  STATE  SUMMARY ON REPEATERS/PROMOTION  JUNIOR    SEC.  SCHOOLS  2014/2015  ############################# -->
           <div class="table-responsive">
               <h2>  STATE  SUMMARY ON REPEATERS/PROMOTION  JUNIOR    SEC.  SCHOOLS  2014/2015  </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th class="alert-warning">                                   </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> JS1  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> JS2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> JS3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning">                     </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $JuniorSecondaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $JuniorSecondaryKeys = array_keys( $JuniorSecondary); ?>

                   @foreach( $JuniorSecondaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $JuniorSecondary[$JuniorSecondaryKeys[$JuniorSecondaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $JuniorSecondaryCount == (count($JuniorSecondary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  STATE  SUMMARY ON  ENROLMENT  BY  LOCATION (RURAL AND URBAN)  2014/2015  ############################# -->
           <div class="table-responsive">
               <h2>  STATE  STATE  SUMMARY ON  ENROLMENT  BY  LOCATION (RURAL AND URBAN)  2014/2015  </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="9"  class="alert-warning text-center text-center">   STATE  SUMMARY ON  ENROLMENT  BY  LOCATION (RURAL AND URBAN)  2014/2015 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning">                                   </th>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> SCHOOLS  </th>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> CLASSROOMS </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> JS1 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> JS2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> JS3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning">                                   </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $UrbanRuralCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $UrbanRuralKeys = array_keys( $UrbanRural); ?>

                   @foreach( $UrbanRuralOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $UrbanRural[$UrbanRuralKeys[$UrbanRuralCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $UrbanRuralCount == (count($UrbanRural))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- ##################### JUNIOR SECONDARY SCHOOLFACILITIES INFORMATION 2014/2015	############################# -->
           <div class="table-responsive">
               <h2> JUNIOR SECONDARY SCHOOLFACILITIES INFORMATION 2014/2015      </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="18" style="text-align: center;" class="alert-warning"> JUNIOR SECONDARY SCHOOLFACILITIES INFORMATION 2014/2015  </th>
                   </tr>

                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> No of schools </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Number of  Classrooms  </th>
                       <th colspan="4" class="text-muted text-center alert-warning"> Number of Toilets  </th>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Number of Schools with Playground  </th>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle;" > Number of Schools with ICT Facilities  </th>
                       <th colspan="4" rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Number of schools with  </th>
                       <th colspan="5" rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Sources of Electricity  </th>
                   </tr>
                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;">  Good  </th>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;" > Bad </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Pupils  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Teachers  </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Pipe borne water    </th>
                       <th class="alert-warning"> Borehole            </th>
                       <th class="alert-warning"> Well                </th>
                       <th class="alert-warning"> Other source        </th>
                       <th class="alert-warning"> None                </th>
                       <th class="alert-warning"> PHCN                </th>
                       <th class="alert-warning"> Generators          </th>
                       <th class="alert-warning"> Solar               </th>
                       <th class="alert-warning"> Others              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $StateSummaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php  $StateSummaryKeys = array_keys($StateSummary); ?>

                   @foreach($StateSummaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $StateSummary[$StateSummaryKeys[$StateSummaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>

                           @if ( $StateSummaryCount == (count($StateSummary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- ##################### JUNIOR SECONDARY SCHOOLFACILITIES INFORMATION 2014/2015	############################# -->
           <div class="table-responsive">
               <h2> JUNIOR SECONDARY SCHOOLFACILITIES INFORMATION 2014/2015      </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="18" style="text-align: center;" class="alert-warning"> JUNIOR SECONDARY SCHOOLFACILITIES INFORMATION 2014/2015  </th>
                   </tr>

                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> No of schools </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Number of  Classrooms  </th>
                       <th colspan="4" class="text-muted text-center alert-warning"> Number of Toilets  </th>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Number of Schools with Playground  </th>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle;" > Number of Schools with ICT Facilities  </th>
                       <th colspan="4" rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Number of schools with  </th>
                       <th colspan="5" rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Sources of Electricity  </th>
                   </tr>
                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;">  Good  </th>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;" > Bad </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Pupils  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Teachers  </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Pipe borne water    </th>
                       <th class="alert-warning"> Borehole            </th>
                       <th class="alert-warning"> Well                </th>
                       <th class="alert-warning"> Other source        </th>
                       <th class="alert-warning"> None                </th>
                       <th class="alert-warning"> PHCN                </th>
                       <th class="alert-warning"> Generators          </th>
                       <th class="alert-warning"> Solar               </th>
                       <th class="alert-warning"> Others              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $StateSummaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php  $StateSummaryKeys = array_keys($StateSummary); ?>

                   @foreach($StateSummaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $StateSummary[$StateSummaryKeys[$StateSummaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>

                           @if ( $StateSummaryCount == (count($StateSummary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>



           <!-- ##################### JUNIOR SECONDARY SCHOOL ENROLMENT BY LGA, SCHOOL, CLASSROOM AND ARMS 2014/2015  ############################# -->
           <div class="table-responsive">
               <h2>  JUNIOR SECONDARY SCHOOL ENROLMENT BY LGA, SCHOOL, CLASSROOM AND ARMS 2014/2015  </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="18" style="text-align: center;" class="alert-warning"> JUNIOR SECONDARY SCHOOL ENROLMENT BY LGA, SCHOOL, CLASSROOM AND ARMS 2014/2015 </th>
                   </tr>

                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LGA </th>
                       <th rowspan="2" class="text-muted text-center alert-warning">NUMBER OF SCHOOLS   </th>
                       <th rowspan="2" class="text-muted text-center alert-warning"> NUMBER OF CLASSROOMS  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;">JS 1 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;" > JS 2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> JS 3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $StateSummaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php  $StateSummaryKeys = array_keys($StateSummary); ?>

                   @foreach($StateSummaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $StateSummary[$StateSummaryKeys[$StateSummaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $StateSummaryCount == (count($StateSummary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- ##################### NUMBER OF PUPILS WITH PHYSICAL AND MENTAL CHALLENGES OR SPECIAL NEEDS 2014/2015 (JUNIOR)  ############################# -->
           <div class="table-responsive">
               <h2>  NUMBER OF PUPILS WITH PHYSICAL AND MENTAL CHALLENGES OR SPECIAL NEEDS 2014/2015 (JUNIOR)  </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="7" style="text-align: center;" class="alert-warning"> NUMBER OF PUPILS WITH PHYSICAL AND MENTAL CHALLENGES OR SPECIAL NEEDS 2014/2015 (JUNIOR) </th>
                   </tr>

                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> CHALLENGE THAT IMPACTS THE ABILITY TO LEARN </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;">JS 1 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;" >JS 2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> JS 3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $PhysicalMentalCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $PhysicalMentalKeys = array_keys( $PhysicalMental); ?>

                   @foreach( $PhysicalMentalOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $PhysicalMental[$PhysicalMentalKeys[$PhysicalMentalCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $PhysicalMentalCount == (count($PhysicalMental))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  JUNIOR SECONDARY SCHOOL TEACHERS DISTRIBUTIONS BY SEX AND LGA ############################# -->
           <div class="table-responsive">
               <h2>  JUNIOR SECONDARY SCHOOL TEACHERS DISTRIBUTIONS BY SEX AND LGA </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LGA </th>
                       <th colspan="5" style="text-align: center;" class="alert-warning"> JUNIOR SECONDARY SCHOOL TEACHERS DISTRIBUTIONS BY SEX AND LGA  </th>
                   </tr>
                   <tr>
                       <th colspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> ALL  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> QUALIFIED </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Total               </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $CareGiverCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $CareGiverKeys = array_keys( $CareGiver); ?>

                   @foreach( $CareGiverOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $CareGiver[$CareGiverKeys[$CareGiverCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $CareGiverCount == (count($CareGiver))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  JUNIOR  SECONDARY SCHOOL TEACHERS DISTRIBUTIONS LOCATION ############################# -->
           <div class="table-responsive">
               <h2>  JUNIOR  SECONDARY SCHOOL TEACHERS DISTRIBUTIONS LOCATION </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LOCATION </th>
                       <th colspan="5" style="text-align: center;" class="alert-warning"> NUMBER OF  JUNIOR  SECONDARY SCHOOL TEACHERS DISTRIBUTIONS LOCATION  </th>
                   </tr>
                   <tr>
                       <th colspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> ALL  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> QUALIFIED </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Total               </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $UrbanRuralCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $UrbanRuralKeys = array_keys( $UrbanRural); ?>

                   @foreach( $UrbanRuralOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $UrbanRural[$UrbanRuralKeys[$UrbanRuralCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $UrbanRuralCount == (count($UrbanRural))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  Senior Secondary  School Enrolment By Class and Age   ############################# -->
           <div class="table-responsive">
               <h2> Senior Secondary  School Enrolment By Class and Age   </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;">Pupil age </th>
                       <th colspan="6" style="text-align: center;" class="alert-warning"> PUBLIC SS 2014/2015  </th>
                       <th colspan="2" rowspan="2" style="text-align: center;" class="alert-warning"> NEW INTAKE  (SS1) ONLY  </th>
                   </tr>
                   <tr>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> SS1  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> SS2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> SS3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $SeniorSecondaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $SeniorSecondaryKeys = array_keys( $SeniorSecondary); ?>

                   @foreach( $SeniorSecondaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $SeniorSecondary[$SeniorSecondaryKeys[$SeniorSecondaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $SeniorSecondaryCount == (count($SeniorSecondary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>



           <!-- #####################  STATE SUMMARY ON REPEATERS/PROMOTION  JUNIOR    SEC.  SCHOOLS  2014/2015 ############################# -->
           <div class="table-responsive">
               <h2>  STATE  SUMMARY ON REPEATERS/PROMOTION  SENIOR    SECONDARY  SCHOOLS  2014/2015 </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th class="alert-warning">                                   </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> SS1  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> SS2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> SS3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning">                     </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $JuniorSecondaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $JuniorSecondaryKeys = array_keys( $JuniorSecondary); ?>

                   @foreach( $JuniorSecondaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $JuniorSecondary[$JuniorSecondaryKeys[$JuniorSecondaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $JuniorSecondaryCount == (count($JuniorSecondary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- #####################  STATE  SUMMARY ON  ENROLMENT  BY  LOCATION (RURAL AND URBAN)  2014/2015  ############################# -->
           <div class="table-responsive">
               <h2>  STATE  STATE  SUMMARY ON  ENROLMENT  BY  LOCATION (RURAL AND URBAN)  2014/2015  </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="9"  class="alert-warning text-center text-center">   STATE  SUMMARY ON  ENROLMENT  BY  LOCATION (RURAL AND URBAN)  2014/2015 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning">                                   </th>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> SCHOOLS  </th>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> CLASSROOMS </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> SS1 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> SS2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> SS3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning">                                   </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $UrbanRuralCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $UrbanRuralKeys = array_keys( $UrbanRural); ?>

                   @foreach( $UrbanRuralOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $UrbanRural[$UrbanRuralKeys[$UrbanRuralCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $UrbanRuralCount == (count($UrbanRural))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- ##################### SECONDARY SCHOOL FACILITIES INFORMATION 2014/2015  ############################# -->
           <div class="table-responsive">
               <h2>  SECONDARY SCHOOL FACILITIES INFORMATION 2014/2015  </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="18" style="text-align: center;" class="alert-warning"> SECONDARY SCHOOL FACILITIES INFORMATION 2014/2015   </th>
                   </tr>

                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> No of schools </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Number of  Classrooms  </th>
                       <th colspan="4" class="text-muted text-center alert-warning"> Number of Toilets  </th>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Number of Schools with Playground  </th>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle;" > Number of Schools with ICT Facilities  </th>
                       <th colspan="4" rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Number of schools with  </th>
                       <th colspan="5" rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> Sources of Electricity  </th>
                   </tr>
                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;">  Good  </th>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;" > Bad </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Pupils  </th>
                       <th colspan="2" class="text-muted text-center alert-warning"> Teachers  </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Pipe borne water    </th>
                       <th class="alert-warning"> Borehole            </th>
                       <th class="alert-warning"> Well                </th>
                       <th class="alert-warning"> Other source        </th>
                       <th class="alert-warning"> None                </th>
                       <th class="alert-warning"> PHCN                </th>
                       <th class="alert-warning"> Generators          </th>
                       <th class="alert-warning"> Solar               </th>
                       <th class="alert-warning"> Others              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $StateSummaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php  $StateSummaryKeys = array_keys($StateSummary); ?>

                   @foreach($StateSummaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $StateSummary[$StateSummaryKeys[$StateSummaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['firstname']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>
                           <td> {{$EveryOfficialSignatures['user_belong']['middlename']}} </td>

                           @if ( $StateSummaryCount == (count($StateSummary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>

           <!-- ##################### SECONDARY SCHOOL ENROLMENT BY LGA, SCHOOL, CLASSROOM AND ARMS 2014/2015  ############################# -->
           <div class="table-responsive">
               <h2>  SECONDAY SCHOOL ENROLMENT BY LGA, SCHOOL, CLASSROOM AND ARMS 2014/2015   </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="9" style="text-align: center;" class="alert-warning"> SECONDARY SCHOOL ENROLMENT BY LGA, SCHOOL, CLASSROOM AND ARMS 2014/2015 </th>
                   </tr>

                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LGA </th>
                       <th rowspan="2" class="text-muted text-center alert-warning">NUMBER OF SCHOOLS   </th>
                       <th rowspan="2" class="text-muted text-center alert-warning"> NUMBER OF CLASSROOMS  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;">SS 1 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;" > SS 2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> SS 3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $StateSummaryCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php  $StateSummaryKeys = array_keys($StateSummary); ?>

                   @foreach($StateSummaryOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $StateSummary[$StateSummaryKeys[$StateSummaryCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $StateSummaryCount == (count($StateSummary))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>


           <!-- ##################### NUMBER OF PUPILS WITH PHYSICAL AND MENTAL CHALLENGES OR SPECIAL NEEDS 2014/2015 (SENIOR)  ############################# -->
           <div class="table-responsive">
               <h2>  NUMBER OF PUPILS WITH PHYSICAL AND MENTAL CHALLENGES OR SPECIAL NEEDS 2014/2015 (SENIOR)   </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th colspan="7" style="text-align: center;" class="alert-warning"> NUMBER OF PUPILS WITH PHYSICAL AND MENTAL CHALLENGES OR SPECIAL NEEDS 2014/2015 (SENIOR) </th>
                   </tr>

                   <tr>
                       <th rowspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> CHALLENGE THAT IMPACTS THE ABILITY TO LEARN </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;">SS 1 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;" > SS 2 </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> SS 3 </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $PhysicalMentalCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $PhysicalMentalKeys = array_keys( $PhysicalMental); ?>

                   @foreach( $PhysicalMentalOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $PhysicalMental[$PhysicalMentalKeys[$PhysicalMentalCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $PhysicalMentalCount == (count($PhysicalMental))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>


           <!-- #####################  SENIOR SECONDARY SCHOOL TEACHERS DISTRIBUTIONS BY SEX AND LGA ############################# -->
           <div class="table-responsive">
               <h2>  SENIOR SECONDARY SCHOOL TEACHERS DISTRIBUTIONS BY SEX AND LGA </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LGA </th>
                       <th colspan="5" style="text-align: center;" class="alert-warning"> SENIOR SECONDARY SCHOOL TEACHERS DISTRIBUTIONS BY SEX AND LGA  </th>
                   </tr>
                   <tr>
                       <th colspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> ALL  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> QUALIFIED </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Total               </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $CareGiverCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $CareGiverKeys = array_keys( $CareGiver); ?>

                   @foreach( $CareGiverOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $CareGiver[$CareGiverKeys[$CareGiverCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $CareGiverCount == (count($CareGiver))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>



           <!-- #####################  SENIOR  SECONDARY SCHOOL TEACHERS DISTRIBUTIONS LOCATION ############################# -->
           <div class="table-responsive">
               <h2>  SENIOR  SECONDARY SCHOOL TEACHERS DISTRIBUTIONS LOCATION </h2>
               <table class="table table-stripped table-bordered" cellspacing="0" width="100%">
                   <thead style="text-align: center;">
                   <tr>
                       <th rowspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> LOCATION </th>
                       <th colspan="5" style="text-align: center;" class="alert-warning"> NUMBER OF  SENIOR  SECONDARY SCHOOL TEACHERS DISTRIBUTIONS LOCATION  </th>
                   </tr>
                   <tr>
                       <th colspan="3" class="text-muted text-center alert-warning" style="vertical-align: middle; border-right: 1px dashed #333!important;"> ALL  </th>
                       <th colspan="2" class="text-muted text-center alert-warning" style="vertical-align: middle;"> QUALIFIED </th>
                   </tr>
                   <tr>
                       <th class="alert-warning"> Total               </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                       <th class="alert-warning"> Male                </th>
                       <th class="alert-warning"> Female              </th>
                   </tr>

                   </thead>
                   <tbody>
                   <?php
                   $UrbanRuralCount = 0; // include
                   //$SchoolArrayUrl =  asset('../app/include/SchoolArray.php');// doesnot work. will fustrate you
                   include app_path()."\\". "views\includes\SchoolArray.php";  // This is the right way
                   ?>

                   <?php   $UrbanRuralKeys = array_keys( $UrbanRural); ?>

                   @foreach( $UrbanRuralOfficialSignatures as $EveryOfficialSignatures)

                       <tr style="border-left:solid 1px black;">
                           <?php $Image = $EveryOfficialSignatures['signatureimage']; ?>
                           <td style="font-weight: 900">  {{ $UrbanRural[$UrbanRuralKeys[$UrbanRuralCount++]] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname']}}  </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           <td> {{ $EveryOfficialSignatures['user_belong']['surname'] }} </td>
                           @if ( $UrbanRuralCount == (count($UrbanRural))  )
                               <td> &nbsp; </td>
                           @else
                               <td> {{$EveryOfficialSignatures['user_belong']['surname']}} </td>
                           @endif
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>





           <table id="ColSpanTable2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                          <tr> 
                            <th> S/N </th>
                            <th> Staff full name </th>
                            <th> Staff email  </th>
                            <th> Staff Signature  </th>
                          </tr>
                          <?php $Count = 1; ?>
                          @foreach($OfficialSignatures as $EveryOfficialSignatures)
                            <tr> 
                             <?php $Image = $EveryOfficialSignatures['signatureimage'];?> 
                              <td> {{$Count++}} </td>
                              <td> {{ $EveryOfficialSignatures['user_belong']['surname']." ". 
                                      $EveryOfficialSignatures['user_belong']['middlename']." ". 
                                      $EveryOfficialSignatures['user_belong']['firstname']
                                   }} 
                              </td>
                              <td> {{$EveryOfficialSignatures['user_belong']['useremail']}} </td>
                              <td>  {{HTML::image("/Images/Signatures/$Image", '', array('class' => '') )}}</td>      

                            </tr>
                          @endforeach
                   </table>

        @else
            <p>No Staff found in database</p>
        @endif
        <!--
           @foreach($OfficialSignatures as $EveryOfficialSignatures)
           <?php $Image = $EveryOfficialSignatures['signatureimage'];?>
              {{HTML::image("/Images/Signatures/$Image", '', array('class' => '') )}}<br />
              {{ $EveryOfficialSignatures['user_belong']['useremail']}}<br />

              {{ $EveryOfficialSignatures['user_belong']['surname']. " ". 
              $EveryOfficialSignatures['user_belong']['firstname']}}<br />
              
            @endforeach-->

            <br />
            <br />
            <br />
  @endif

@stop