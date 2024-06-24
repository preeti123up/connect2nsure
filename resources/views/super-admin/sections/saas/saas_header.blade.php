

<!-- //New Implenment------------------------------------------------------------------------------------------------------------------------ -->

  <header class="main-header w-100 z-10">
            <nav class="navbar navbar-expand-xl navbar-light sticky-header">
                <div class="container d-flex align-items-center justify-content-lg-between position-relative">
                    <a href="{{ route('front.home') }}" class="navbar-brand d-flex align-items-center mb-md-0 text-decoration-none">
                        <img src="{{ global_setting()->logo_front_url }}" alt="logo" width="200" class="img-fluid logo-white" />
                        <img src="{{ global_setting()->logo_front_url }}" alt="logo" width="200" class="img-fluid logo-color" />

                    </a>

                    <a class="navbar-toggler position-absolute right-0 border-0" href="#offcanvasWithBackdrop" role="button">
                        <i class="flaticon-menu"
                           data-bs-toggle="offcanvas"
                           data-bs-target="#offcanvasWithBackdrop"
                           aria-controls="offcanvasWithBackdrop"></i>
                    </a>
                    <div class="clearfix"></div>
                    <div class="collapse navbar-collapse justify-content-center">
                        <ul class="nav col-12 col-md-auto justify-content-center main-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.home') }}">{{ $frontMenu->home }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.feature') }}">{{ $frontMenu->feature }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.pricing') }}">{{ $frontMenu->price }}</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.blog') }}">Blog </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.contact') }}">{{ $frontMenu->contact }}</a>
                        </li>
                        
                        @foreach ($footerSettings as $footerSetting)
                            @unless ($footerSetting->type == 'footer')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ !is_null($footerSetting->external_link) ? $footerSetting->external_link : route('front.page', $footerSetting->slug) }}">{{ $footerSetting->name }}</a>
                                </li>
                            @endif
                        @endforeach

                </ul>
                    </div>

                    <div class="action-btns text-end me-5 me-lg-0 d-none d-md-block d-lg-block">
                    <!--<a href="javascript:void(0)" class="btn btn-link p-1 tt-theme-toggle">-->
                    <!--        <div class="tt-theme-light" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Light"><i class="flaticon-sun-1 fs-lg"></i></div>-->
                    <!--        <div class="tt-theme-dark" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Dark"><i class="flaticon-moon-1 fs-lg"></i></div>-->
                    <!--    </a> -->
                    @guest
                            <a href="{{ module_enabled('Subdomain') ? route('front.workspace') : route('login') }}" class="btn btn-outline-primary">{{ $frontMenu->login }} / Signup <i class="bi bi-arrow-right"></i></a>
                            <!--@if ($global->enable_register)-->
                            <!--    <a href="{{ route('front.signup.index') }}" class="btn btn-outline-primary">{{ $frontMenu->get_start }} <i class="bi bi-arrow-right"></i></a>-->
                            <!--@endif-->
                        @else
                            <a href="{{ module_enabled('Subdomain') ? (user()->is_superadmin ? \App\Providers\RouteServiceProvider::SUPER_ADMIN_HOME : \App\Providers\RouteServiceProvider::HOME) : route('login') }}" class="btn btn-border shadow-none px-3 py-1">
                               @if(isset(user()->image_url))  <img src="{{ user()->image_url }}" class="rounded" width="25" alt="@lang('superadmin.myAccount')"> @endif @lang('superadmin.myAccount')
                            </a>
                        @endguest
                    </div>
                </div>
            </nav>

               <!--offcanvas menu start-->
             <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasWithBackdrop">
                <div class="offcanvas-header d-flex align-items-center mt-4">
                    <a href="/" class="d-flex align-items-center mb-md-0 text-decoration-none">
                        <img src="{{ global_setting()->logo_front_url }}" alt="logo" width="200 class="img-fluid ps-2" />
                    </a>
                    <button type="button" class="close-btn text-danger" data-bs-dismiss="offcanvas" aria-label="Close">
                        <i class="flaticon-cancel"></i>
                    </button>
                </div>
                <div class="offcanvas-body">
                <ul class="nav col-12 col-md-auto justify-content-center main-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.home') }}">{{ $frontMenu->home }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.feature') }}">{{ $frontMenu->feature }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.pricing') }}">{{ $frontMenu->price }}</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.blog') }}">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.contact') }}">{{ $frontMenu->contact }}</a>
                        </li>
                        
                        @foreach ($footerSettings as $footerSetting)
                            @unless ($footerSetting->type == 'footer')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ !is_null($footerSetting->external_link) ? $footerSetting->external_link : route('front.page', $footerSetting->slug) }}">{{ $footerSetting->name }}</a>
                                </li>
                            @endif
                        @endforeach

                </ul>
                    <div class="action-btns mt-4 ps-3">
                    @guest
                            <a href="{{ module_enabled('Subdomain') ? route('front.workspace') : route('login') }}" class="btn btn-outline-primary me-2">{{ $frontMenu->login }} / Sign Up</a>
                            <!--@if ($global->enable_register)-->
                            <!--    <a href="{{ route('front.signup.index') }}" class="btn btn-primary">{{ $frontMenu->get_start }}</a>-->
                            <!--@endif-->
                        @else
                            <a href="{{ module_enabled('Subdomain') ? (user()->is_superadmin ? \App\Providers\RouteServiceProvider::SUPER_ADMIN_HOME : \App\Providers\RouteServiceProvider::HOME) : route('login') }}" class="btn btn-border  shadow-none px-3 py-1">
                               @if(isset(user()->image_url))  <img src="{{ user()->image_url }}" class="rounded" width="25" alt="@lang('superadmin.myAccount')"> @endif @lang('superadmin.myAccount')
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
            <!--offcanvas menu end-->
        </header>

</header>
