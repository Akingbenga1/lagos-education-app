           <?php
               foreach($AllStudents as $allstudents) 
               {
                   $key =   $allstudents['studentid'] ;
                   $value = $allstudents['school_admission_number'] ." - ". $allstudents['user_belong']['surname']." ".
                            $allstudents['user_belong']['firstname']." ".
                            $allstudents['user_belong']['middlename'];

                   $ThisStudentImage = asset("/Images/Icons/avatar.jpg");
                   $SearchArray[$value] =  $ThisStudentImage; //$key;
                }
            ?>
          
             <script type='text/javascript'>
                 <?php
                      $JSArray = json_encode($SearchArray);
                      echo "var StudentList = ". $JSArray . ";\n";
                  ?>

              </script>
              
       <!--  <div class="AutoCompleteContainMe">
            <input type="text" name="TypeStudentName" class="form-control TypeStudentName" id="autocomplete-custom-append" style="float: left;"/>
            <input type="hidden" name="StudentId" id="StuId" style="float: left;"/>
            <div id="suggestions-container" style="position: relative;  float: left; margin: 10px;"></div>
        </div> -->

           <div class="row">
               <div class="col s12 m12 l12">
                   <div class="row">
                       <div class="input-field col s12 m12 l12">
                           <i class="material-icons prefix">search</i>
                           <input type="text" id="autocomplete-input" class="autocomplete" autocomplete="off" required >
                           <input type="hidden" name="StudentNumber" class="StudentNumber" >
                           <label for="autocomplete-input"><b> Type Student Name or Student Admission Number</b></label>
                           @if($errors->has('TypeStudentName'))
                               <span class="center-align LoginError">{{$errors->first('TypeStudentName')}}</span>
                           @endif
                       </div>
                   </div>
               </div>
           </div>


