@extends('layouts.main')
@section('division_container')

    <div class="push_down" >

        <div class="row">

            <div class="col s12 m6 l4">

                <div class="card pink hoverable">
                    <div class="card-content">
                        <span class="card-title">
                          <a href="{{ url('/schools_registration') }}" class="white-text"> Students Registration  </a>
                        </span>
                    </div>
                </div>


            </div>
            <div class="col s12 m6 l4">

                <div class="card pink hoverable">
                    <div class="card-content ">
                        <span class="card-title">
                          <a href="{{ url('/schools') }}" class="white-text"> Score Upload   </a>
                        </span>
                    </div>
                </div>



            </div>

            <div class="col s12 m6 l4">

                <div class="card pink hoverable">
                    <div class="card-content ">
                        <span class="card-title">
                          <a class="white-text"> Result Download   </a>
                        </span>
                        <p>

                        </p>
                    </div>
                </div>



            </div>

            <div class="col s12 m6 l4">

                <div class="card pink hoverable">
                    <div class="card-content ">
                        <span class="card-title">
                          <a class="white-text"> Staff Registration  </a>
                        </span>
                        <p>

                        </p>
                    </div>
                </div>



            </div>

        </div>




    <script>

    </script>


@stop
