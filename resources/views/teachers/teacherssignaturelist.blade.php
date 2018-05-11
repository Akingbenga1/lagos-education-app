@extends('layouts.main')

@section('division_container')
      <h3> Official List of Teachers Signature </h3> 

      <!-- {{var_dump($OfficialSignatures)}}  -->
       <p class="ScoreInputThirdUpdate"> 
              Processing of<b> Signatures </b> in progress...
          </p>
      <div class="OfficialSignatureList"> 
              @if(  isset($OfficialSignatures) and is_array($OfficialSignatures) )
                  @foreach($OfficialSignatures as $EveryOfficialSignatures)
                  <div class="IndividualSignature">
                        <?php $Image = $EveryOfficialSignatures['signatureimage'];?>
                        {{HTML::image("/Images/Signatures/$Image", '', array('class' => 'SignatureImage') )}}
                  <p class="SignatoryName">
                       {{ $EveryOfficialSignatures['user_belong']['surname']. " ". 
                       $EveryOfficialSignatures['user_belong']['firstname']. " ". 
                       $EveryOfficialSignatures['user_belong']['middlename']}}
                  </p>     
                  </div>      
                  @endforeach
              @endif
      </div>     
@stop