           <?php
               foreach($AllStudents as $allstudents) 
               {
                   $key =   $allstudents['studentid'] ;
                   $value = $allstudents['school_admission_number'] ." - ". $allstudents['user_belong']['surname']." ".
                            $allstudents['user_belong']['firstname']." ".
                            $allstudents['user_belong']['middlename'];
                     $SearchArray[$key] =  $value;
                     //if($allstudents['studentid'] == $r[])
                }
            ?>
           <!-- {{var_dump($SearchArray)}} -->
          
             <script type='text/javascript'>
                 <?php
                      $JSArray = json_encode($SearchArray);
                      echo "var StudentList = ". $JSArray . ";\n";
                  ?>

              </script>
              
        <div class="AutoCompleteContainMe">
            <input type="text" name="TypeStudentName" class="form-control TypeStudentName" id="autocomplete-custom-append" style="float: left;"/>
            <input type="hidden" name="StudentId" id="StuId" style="float: left;"/>
            <div id="suggestions-container" style="position: relative;  float: left; margin: 10px;"></div>
        </div>      

        {{HTML::script('JS/demo.js')}}

