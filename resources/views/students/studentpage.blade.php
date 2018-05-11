@extends('layouts.main')

@section('division_container')
    <div  class="row">
        <h3  class="center-align">Check and Download Report Sheet</h3>
        <div class="col l8 offset-l2 m12 s12 z-depth-4 card-panel">
            <form class="form-horizontal" action="{{URL::route('student-page')}}" method="post" data-parsley-validate>
                <div class="row">
                    @if(!empty($AllStudents) and is_array($AllStudents))
                        <div class="">
                            @include('includes.choosestudentasarray')
                        </div>
                    @else
                       <h5 class="center-align"> No Student Available </h5>
                    @endif
                </div>

                <div class="row">
                    <div class="StudentPageChooseTerm"> @include('includes.chooseterm')</div>
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                </div>

                <div class="row center-align">
                    @if(!empty($AllStudents) and is_array($AllStudents))
                        <button type = "submit"  class="StudentButton btn col l8 offset-l2 m12 s12  center-align red">
                            <i class="glyphicon glyphicon-check" > </i>
                            Get Report Sheet </button>
                    @endif
                </div>
                <br />

            </form>
        </div>
    </div>
<div>

</div>
@stop