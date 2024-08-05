<div class="secondary-navbar">
    <div class="row no-gutters">
        <nav class="navbar navbar-expand-lg navbar-light w-100" id="navbar-responsive">
            <a class="navbar-brand" href="{{ url('/') }}"><img id="brand-img"  src="{{ URL::asset('img/brand/logo.png') }}" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse section-links" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link scroll active" href="{{ url('/') }}/#main-wrapper">{{ __('Home') }} <span class="sr-only">(current)</span></a>
                    </li>	
                    @if (config('frontend.features_section') == 'on')
                        <li class="nav-item">
                            <a class="nav-link scroll" href="{{ url('/') }}/#features-wrapper">{{ __('Features') }}</a>
                        </li>
                    @endif	
                    @if (config('frontend.pricing_section') == 'on')
                        <li class="nav-item">
                            <a class="nav-link scroll" href="{{ url('/') }}/#prices-wrapper">{{ __('Pricing') }}</a>
                        </li>
                    @endif							
                    @if (config('frontend.faq_section') == 'on')
                        <li class="nav-item">
                            <a class="nav-link scroll" href="{{ url('/') }}/#faq-wrapper">{{ __('FAQs') }}</a>
                        </li>
                    @endif	
                    @if (config('frontend.blogs_section') == 'on')
                        <li class="nav-item">
                            <a class="nav-link scroll" href="{{ url('/') }}/#blog-wrapper">{{ __('Blogs') }}</a>
                        </li>
                    @endif										
                </ul>                    
            </div>
            @if (Route::has('login'))
                    <div id="login-buttons">
                        <div class="dropdown header-languages" id="frontend-local">
                            <a class="icon" data-bs-toggle="dropdown">
                                <span class="header-icon fa-solid fa-globe mr-4 fs-15"></span>
                            </a>
                            <div class="dropdown-menu animated">
                                <div class="local-menu">
                                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        @if (in_array($localeCode, explode(',', $settings->languages)))
                                            <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="dropdown-item d-flex pl-4" hreflang="{{ $localeCode }}">
                                                <div>
                                                    <span class="font-weight-normal fs-12">{{ ucfirst($properties['native']) }}</span> <span class="fs-10 text-muted">{{ $localeCode }}</span>
                                                </div>
                                            </a>   
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('user.templates') }}" class="action-button dashboard-button pl-5 pr-5">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="" id="login-button">{{ __('Sign In') }}</a>

                            @if (config('settings.registration') == 'enabled')
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-2 action-button register-button pl-5 pr-5">{{ __('Sign Up') }}</a>
                                @endif
                            @endif
                        @endauth
                    </div>
                @endif
        </nav>
    </div>
</div>