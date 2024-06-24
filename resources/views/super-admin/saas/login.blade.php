
<html lang="en" data-bs-theme="light">

<head>
    <!--required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--twitter og-->
    <meta name="twitter:site" content="@themetags">
    <meta name="twitter:creator" content="@themetags">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Quiety - Creative SAAS Technology & IT Solutions Bootstrap 5 HTML Template">
    <meta name="twitter:description" content="Quiety creative Saas, software technology, Saas agency & business Bootstrap 5 Html template. It is best and famous software company and Saas website template.">
    <meta name="twitter:image" content="#">

    <!--facebook og-->
    <meta property="og:url" content="#">
    <meta name="twitter:title" content="Quiety - Creative SAAS Technology & IT Solutions Bootstrap 5 HTML Template">
    <meta property="og:description" content="Quiety creative Saas, software technology, Saas agency & business Bootstrap 5 Html template. It is best and famous software company and Saas website template.">
    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!--meta-->
    <meta name="description" content="Quiety creative Saas, software technology, Saas agency & business Bootstrap 5 Html template. It is best and famous software company and Saas website template.">
    <meta name="author" content="ThemeTags">

    <!--favicon icon-->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ global_setting()->favicon_url }}">

    <!--title-->
    <title>@lang('app.login') | {{ $setting->global_app_name}}</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&amp;display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lily+Script+One&amp;display=swap" rel="stylesheet">
    <!-- Font -->

    <!--build:css-->
    <link rel="stylesheet" href="front/assets/css/main.css">
    <!-- endbuild -->

    <!--custom css start-->
    <link rel="stylesheet" href="front/assets/css/custom.css">
    <!--custom css end-->

    <style>
        .bg-dark {
    background-color: #daebfc !important;
}

.input-group:not(.has-validation) > :not(:last-child):not(.dropdown-toggle):not(.dropdown-menu):not( .form-floating ){
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
    </style>
</head>

<body>


    <!--main content wrapper start-->
    <div class="main-wrapper">

        <!--register section start-->
        <section class="sign-up-in-section bg-dark ptb-60">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-5 col-md-8 col-12">
                            <div class="row">
                        <div class="col-12 text-center">
                             <a href="/" ><img src="/front/assets/img/back-button.png" alt="" height="50" width="50"></a>
                        <a href="/" class="mb-4  text-center"><img src="{{ global_setting()->logo_front_url }}" width="200" alt="logo" class="img-fluid"></a>
                        </div>

                        </div>                        <div class="register-wrap mt-4 bg-white shadow rounded-custom">
                            <h1 class="h3 text-center">Let's explore the experience</h1>
                            </br>
                            <!--<p class="text-muted text-center text-sm">Please log in to access your account web-enabled methods of innovative-->
                            <!--    niches.</p>-->
                          
                                <form class="form-horizontal form-material" id="save-form" action="{{ route('login') }}" method="POST">
                    {{ csrf_field() }}


                    @if (session('message'))
                        <div class="alert alert-danger m-t-10">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <div class="col-sm-12">
                        <label for="email" class="mb-1">Email <span class="text-danger">*</span></label>
                            <input class="form-control mb-1" id="email" type="email" name="email" value="{{ old('email') }}"
                                   autofocus required="" placeholder="@lang('app.email')">
                            @if ($errors->has('email'))
                                <div class="help-block with-errors" style="color:red">{{ $errors->first('email') }}</div>
                            @endif

                        </div>
                    </div>
                <div class="form-group">
                    <div class="col-sm-12">
                    <label for="password" class="mb-1">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" id="password" type="password" name="password" required=""
                               placeholder="@lang('modules.client.password')">
                        <div class="input-group-append" >
                            <span class="input-group-text" id="showPasswordToggle" style="padding-top:17px;border:none; background:none; cursor:pointer;    position: absolute;
    right: 0;
    z-index: 999;" >
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                    @if ($errors->has('password'))
                        <div class="help-block with-errors">{{ $errors->first('password') }}</div>
                    @endif
                </div>
                    </div>
                    @if ($global->google_recaptcha_status == 'active' && $global->google_recaptcha_v2_status == 'active')
                        <div class="form-group" id="captcha_container"></div>
                    @endif
                    @if ($errors->has('g-recaptcha-response'))
                        <div class="help-block with-errors">{{ $errors->first('g-recaptcha-response') }}
                        </div>
                    @endif

                    <input type="hidden" id="g_recaptcha" name="g_recaptcha">

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="checkbox checkbox-primary float-left p-t-0">
                                <input id="checkbox-signup" type="checkbox"
                                       name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="checkbox-signup" class="text-dark"> @lang('app.rememberMe') </label>
                            </div>

                    </div>
                    <div class="form-group text-center m-t-10">
                        <div class="col-sm-12">
                            <button
                                class="btn btn-primary mt-3 d-block w-100 "
                                id="submit-login"
                                type="submit">@lang('app.login')</button>
                        </div>
                    </div>
                    <p class="font-monospace fw-medium text-center text-muted mt-3 pt-4 mb-0">
                                {{-- @if($setting->enable_register)
                                <a href="{{ route('front.signup.index') }}" class="text-decoration-none">Sign up </a>
                                 <!--Don’t have an account?-->
                                @endif --}}
                                  
                                    <a  href="{{ route('password.request') }}" class="text-decoration-none">Forgot password</a>
                                </p>
                </form>
                        </div>

                        <script>
                const facebook = "{{ route('social_login', 'facebook') }}";
                const google = "{{ route('social_login', 'google') }}";
                const twitter = "{{ route('social_login', 'twitter') }}";
                const linkedin = "{{ route('social_login', 'linkedin') }}";
            </script>
            @if(isset($socialAuthSettings) && (in_array('enable',[$socialAuthSettings->facebook_status,$socialAuthSettings->google_status,$socialAuthSettings->twitter_status,$socialAuthSettings->linkedin_status])))
                <div class=" order-lg-2 ">
                    <div class="row  align-items-center">

                        <div class="col-xs-12 col-sm-12  text-center mb-2">
                            @if($socialAuthSettings->facebook_status == 'enable')
                                <a href="javascript:;" class="btn btn-primary btn-facebook" data-toggle="tooltip"
                                   title="@lang('auth.signInFacebook')" onclick="window.location.href = facebook;"
                                   data-original-title="@lang('auth.signInFacebook')">@lang('auth.signInFacebook')
                                    <i aria-hidden="true" class="zmdi zmdi-facebook"></i> </a>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 m-t-10 text-center mb-2">
                            @if($socialAuthSettings->google_status == 'enable')

                                <a href="javascript:;" class="btn btn-primary btn-google" data-toggle="tooltip"
                                   title="@lang('auth.signInGoogle')" onclick="window.location.href = google;"
                                   data-original-title="@lang('auth.signInGoogle')">@lang('auth.signInGoogle') <i
                                        aria-hidden="true" class="zmdi zmdi-google"></i> </a>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12  m-t-10 text-center mb-2">
                            @if($socialAuthSettings->twitter_status == 'enable')
                                <a href="javascript:;" class="btn btn-primary btn-twitter" data-toggle="tooltip"
                                   title="@lang('auth.signInTwitter')" onclick="window.location.href = twitter;"
                                   data-original-title="@lang('auth.signInTwitter')">@lang('auth.signInTwitter') <i
                                        aria-hidden="true" class="zmdi zmdi-twitter"></i> </a>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 m--10 text-center mb-lg-4">
                            @if($socialAuthSettings->linkedin_status == 'enable')
                                <a href="javascript:;" class="btn btn-primary btn-linkedin" data-toggle="tooltip"
                                   title="@lang('auth.signInLinkedin')" onclick="window.location.href = linkedin;"
                                   data-original-title="@lang('auth.signInLinkedin')">@lang('auth.signInLinkedin')
                                    <i aria-hidden="true" class="zmdi zmdi-linkedin"></i> </a>
                            @endif
                        </div>
                    </div>

                </div>
            @endif
                    </div>
                </div>
            </div>
        </section>
        <!--register section end-->
    </div>

   


    <!-- Scripts -->
<script src="{{ asset('saas/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('saas/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('saas/vendor/slick/slick.min.js') }}"></script>
<script src="{{ asset('saas/vendor/wowjs/wow.min.js') }}"></script>
<script src="{{ asset('front/plugin/froiden-helper/helper.js') }}"></script>
<script src="{{ asset('saas/js/main.js') }}"></script>
<script src="{{ asset('front/plugin/froiden-helper/helper.js') }}"></script>
<!-- Global Required JS -->
@foreach ($frontWidgets as $item)
@if(!is_null($item->footer_script))
    {!! $item->footer_script !!}
@endif

@endforeach
<script>
    $("form#save-form").submit(function () {
        const button = $('form#save-form').find('#submit-login');

        const text = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{__('app.loading')}}';

        button.prop("disabled", true);
        button.html(text);
    });
</script>
<script>
$('#showPasswordToggle').click(function() {
        var passwordField = $('#password');
        var fieldType = passwordField.attr('type');
        
        if (fieldType === 'password') {
            passwordField.attr('type', 'text');
            $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash'); // Change eye icon to eye slash
        } else {
            passwordField.attr('type', 'password');
            $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye'); // Change eye slash icon back to eye
        }
    });
</script>
@if ($global->google_recaptcha_status == 'active' && $global->google_recaptcha_v2_status == 'active')
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async
            defer></script>
    <script>
        var gcv3;
        var onloadCallback = function () {
            // Renders the HTML element with id 'captcha_container' as a reCAPTCHA widget.
            // The id of the reCAPTCHA widget is assigned to 'gcv3'.
            gcv3 = grecaptcha.render('captcha_container', {
                'sitekey': '{{ $global->google_recaptcha_v2_site_key }}',
                'theme': 'light',
                'callback': function (response) {
                    if (response) {
                        $('#g_recaptcha').val(response);
                    }
                },
            });
        };
    </script>
@endif
@if ($global->google_recaptcha_status == 'active' && $global->google_recaptcha_v3_status == 'active')
    <script
        src="https://www.google.com/recaptcha/api.js?render={{ $global->google_recaptcha_v3_site_key }}"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('{{ $global->google_recaptcha_v3_site_key }}').then(function (token) {
                // Add your logic to submit to your backend server here.
                $('#g_recaptcha').val(token);
            });
        });
    </script>
@endif
</body>

</html>