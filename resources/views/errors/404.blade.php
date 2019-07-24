@extends('layouts.app')

@section('content')
    <!-- end Page Content -->

    <div class="section padding-top-hero" style="padding-top: 17%;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 page-center-text-wrap text-center">
                    <h1 class="parallax-fade-top-2"><strong>-</strong> Page Not Found </span> <strong>-</strong><br>
                        <span> 404 -  Page Not Found </span>
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section padding-top-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12" data-scroll-reveal="enter bottom move 60px over 0.6s after 0.1s" style="padding-top: 10px; margin-bottom:100px;">

                    <div class="col-md-12  ">
                        {{--<h4>{!!$reason!!}</h4>--}}

                        <div id="page-main">
                            <section id="about">
                                <div class="alert alert-info"><h2><span class="fa fa-info-circle"></span> Sorry, the page you are looking for is not available</h2></div>
                                <?php $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url('/'); ?>
                                <a href="{{$ref}}" class="btn btn-primary"><span class="fa fa-undo"></span> Continue</a>
                            </section>
                        </div><!-- /#page-main -->

                    </div>

                </div>
            </div>
        </div>
    </div>



    <div class="section padding-top-bottom-small">
        <div class="row justify-content-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="project-nav-wrap">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection