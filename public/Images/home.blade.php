@extends('layouts.main')

@section('division_container') 

<div class="DivisionContainer">

  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
      <li data-target="#carousel-example-generic" data-slide-to="1"></li>
      <li data-target="#carousel-example-generic" data-slide-to="2"></li>
      <li data-target="#carousel-example-generic" data-slide-to="3"></li>
      <li data-target="#carousel-example-generic" data-slide-to="4"></li>
      <li data-target="#carousel-example-generic" data-slide-to="5"></li>
      <li data-target="#carousel-example-generic" data-slide-to="6"></li>
      <li data-target="#carousel-example-generic" data-slide-to="7"></li>
      <li data-target="#carousel-example-generic" data-slide-to="8"></li>
      <li data-target="#carousel-example-generic" data-slide-to="9"></li>
      <li data-target="#carousel-example-generic" data-slide-to="10"></li>
      <li data-target="#carousel-example-generic" data-slide-to="11"></li>
    </ol>

      <!-- Wrapper for slides -->
      <div class="carousel-inner" role="listbox">
          <div class="item active">
           {{HTML::image("Images/Logos/bk.JPG", '',array('class' => '') )}}    
            <div class="carousel-caption">
              ...
            </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/bcv.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption"> 
                ...
              </div>
          </div>
           <div class="item">
              {{HTML::image("Images/Logos/bda.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption"> 
                ...
              </div>
          </div>
           <div class="item">
              {{HTML::image("Images/Logos/bfg.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption"> 
                ...
              </div>
          </div>
           <div class="item">
              {{HTML::image("Images/Logos/bgd.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption"> 
                ...
              </div>
          </div>
           <div class="item">
              {{HTML::image("Images/Logos/bgt.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption"> 
                ...
              </div>
          </div>
           <div class="item">
              {{HTML::image("Images/Logos/bgz.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption"> 
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/bjm.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption"> 
                ...
              </div>
          </div>
         <div class="item">
              {{HTML::image("Images/Logos/bju.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption"> 
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/bmv.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption"> 
                ...
              </div>
          </div>
          <div class="item">
             {{HTML::image("Images/Logos/cf.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>
          <div class="item">
            {{HTML::image("Images/Logos/cd.JPG", '',array('class' => '') )}}   
            <div class="carousel-caption">
              ...
            </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/ba.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/bb.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/bg.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/da.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/db.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/i.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/g.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/j.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>
          <div class="item">
              {{HTML::image("Images/Logos/m.JPG", '',array('class' => '') )}}     
              <div class="carousel-caption">
                ...
              </div>
          </div>

      </div>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>


<!--<div id="my_popup"> -->
<!--...popup content...-->
 <!-- Add an optional button to close the popup -->
    <!--<button class="my_popup_close"><i class="fa fa-times pull-left"></i>Close</button>
<div class="BossLevel">
          {{HTML::image("/Images/PopUp/LagosLogo.jpg", 'Lagos State Logo', array('class' => 'BossLevelLagosLogo') )}}
          <br/><br/>

                      <p class="BossLevelStatement">LAGOS STATE MINISTRY OF EDUCATION </p>
   </div>
  

<div class="MiddleLevel">
   <p class="MiddleLevelDSHeading"> EDUCATION DISTRICT 1</p>

      <div  class="MiddleLevelDSVision"> 
           <p>  DISTRICT VISION  </p>
            <span> TO PROMOTE TOMORROW'S LEADER OF DISTINCTIONS IN KNOWLEDGE, SKILLS AND CHARACTER </span>
      </div>
 <div  class="MiddleLevelDSMotto"> 
    <p> DISTRICT MOTTO</p>
     <span>DO IT RIGHT, BE THE BEST.</span>
   </div>
   </div>

<div class="LowLevel">
    <div class="LowLevelSCHHeading"> IJAIYE HOUSING ESTATE SENIOR GRAMMAR SCHOOL</div>
      <div class="LowLevelSCHVision">
        <div> SCHOOL VISION</div>
         <p> GROOMING YOUNG MEN AND WOMEN WITH ACTIVE AND CREATIVE MIND IN STRIVING TO BE THE BEST</p>
      </div>
      <div class="LowLevelSCHMission">
       <div>  SCHOOL MISSION </div>
          <p> 1. TO PROMOTE A CONDUCIVE LEARNING ENVIRONMENT. </p>
          <p> 2. TO PROMOTE THE DEVELOPMENT OF LITERACY, NUMERACY AND PRO SOCIAL SKILLS </p>
          <p> 3. TO HELP STUDENTS TRANSITION TO A SUCCESSFUL FUTURE</p> 
        
      </div>
</div>    

  </div> -->
  
  </div>

<script>
  $(document).ready(function() {
      setTimeout(function() {

             
               $('#my_popup').popup({
                autoopen: false,
                detach: true,
                transition: 'ease-in 0.8s',
                scrolllock: true,
                beforeopen: function(){ $('#Rotator').popup('hide');},
                 color: '#FFFFFF',
               opacity: 1 });
            // your code
            }, 1000);
             

            
        });

   
  </script>
  <script>
  // To attach Backstrech as the body's background
  //$.backstretch("Images/Logos/b.JPG");

  // You may also attach Backstretch to a block-level element
  //$(".foo").backstretch("path/to/image.jpg");

  // Or, to start a slideshow, just pass in an array of images
  /* $(".SecondStep").backstretch([
    "Images/Logos/fc.jpg"      
  ], {centeredX: false, centeredY: true});  */
</script>
  
@stop