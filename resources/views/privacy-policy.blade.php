@extends('layouts.frontend')

@section('menu')
    @include('layouts.secondary-menu')
@endsection

@section('content')
    <div class="container-fluid secondary-background">
        <div class="row text-center">
            <div class="col-md-12">
                <div class="section-title">
                    <!-- SECTION TITLE -->
                    <div class="text-center mb-9 mt-9 pt-7" id="contact-row">

                        <h6 class="fs-30 mt-6 font-weight-bold text-center">{{ __('Privacy Policy') }}</h6>
                        <p class="fs-12 text-center text-muted mb-5"><span>{{ __('We guarantee your data privacy') }}</p>

                    </div> <!-- END SECTION TITLE -->
                </div>
            </div>
        </div>
    </div>

    <section id="about-wrapper" class="secondary-background">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 policy">  
                    <div class="card border-0 p-4 pt-7 pb-7 mb-9 special-border-right special-border-left">              
                        <div class="card-body"> 

                            <div class="mb-7">
                                {!! $pages['privacy'] !!}
                            </div>
        
                            <div class="form-group mt-6 text-center">                        
                                <a href="{{ route('register') }}" class="btn btn-primary mr-2">{{ __('I Agree, Sign me Up') }}</a> 
                                <a href="{{ route('login') }}" class="btn btn-primary mr-2">{{ __('I Agree, Let me Login') }}</a>                               
                            </div>
                        
                        </div>     
                    </div>  
                </div>
            </div>
        </div>
    </section>
    @section('js')
        <script src="{{URL::asset('js/minimize.js')}}"></script>
    @endsection
@endsection

@section('curve')
    <div class="container-fluid" id="curve-container">
        <div class="curve-box">
            <div class="overflow-hidden">
                <svg class="curve secodary-curve" preserveAspectRatio="none" width="1440" height="86" viewBox="0 0 1440 86" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 85.662C240 29.1253 480 0.857 720 0.857C960 0.857 1200 29.1253 1440 85.662V0H0V85.662Z"></path>
                </svg>
            </div>
        </div>
    </div>
@endsection

