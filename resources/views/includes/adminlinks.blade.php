


<div class="AdminLinks">

    <!--<a href="{{URL::route('user-profile')}}" > Your Profile </a> /*(  Auth::user()->ability(array('Super User', 'Teacher'), array()) )  ) */ -->
    @if (true)
          <a href="{{URL::route('student-registration-form')}}" > Register student  </a>
          <a href="{{URL::route('get-this-student-details')}}" > Student Score Table  </a>
          <a href="{{URL::route('add-student-term')}}" > Add Student to Term </a>
          <a href="{{URL::route('student-question-input-page')}}" > Question Input Page </a>
	  
    @endif
    

</div>
{{--(   Auth::user()->ability(array('Super User', 'Teacher'), array())  )--}}
 @if (true)
<!--<div class="ClassLinks">
  <p> List of classes for<br />                      
 
      <select name = "Year" class="YearSelectAdmin" >
          <option> -- Year -- </option>
          <option value="2015"> 2015/2016 </option>
          <option value="2016" > 2016/2017 </option>
      </select> <br />
      {{ Session::has('MyYear') ? " " . Session::get('MyYear') . "/" . ( Session::get('MyYear') + 1 ): "2015/2016" }} session
      <input type="hidden" class="ChangeYearAdmin" value="{{URL::route('class-list-ajax')}}" />    
  
  </p>
  <a href="{{URL::route('class-list-page',array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015, 
  		  'Class' => 'SS1', 'SubClass' => 'H'))}}"> All SS1 Classes
  </a>

  <a href="{{URL::route('class-list-page',array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
          'Class' => 'SS2','SubClass' => 'H'))}}"> All SS2 Classes
  </a>

   <a href="{{URL::route('class-list-page',array('Year' => Session::has('MyYear') ? Session::get('MyYear') : 2015,
          'Class' => 'SS3','SubClass' => 'H'))}}"> All SS3 Classes
  </a>
</div> -->
 @endif
<div class="GeneralUpadte">
 <!--<p>  <b> Profile Update </b>(Sunday, 31st January, 2016 08:07pm ): <b> Your Personal data</b> can be editted. Please
              <b> <a href="{{URL::route('user-profile')}}" > Click here to view your profile.</a></b>
  </p> -->

   <!--<p>  <b> General Update </b>(Wednesday, 30th March, 2016 04:55am ):<b>  No new Update at the moment. </b> 
   </p> --> 

<!--<p> General Update (Wednesday, 27th January, 2016 08:34pm ): <b> Missing SS1G  students' names and admission numbers have been
entered into the database (100% complete).</b> 
</p> 
 <p>  <b> General Update (Friday, 29th January, 2016 03:07pm ):</b> Processing of <b> Signatures </b> into the database is in progress. Click here <b>
               <a href="{{URL::route('signauture-list')}}" > to see list of processed signatures</a></b>
   </p>
<p> General Update (Thursday, 28th January, 2016 06:25am ):  Missing and wrongly spelt students' names and admission numbers 
in <b> SS3 C, SS2 B, SS2 D, SS3 A, SS1 J, SS1 H </b> have been resolved (100% complete). 
</p> -->
</div>