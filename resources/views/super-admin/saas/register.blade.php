

<!DOCTYPE html>
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
        <meta name="msapplication-TileImage" content="{{ global_setting()->favicon_url }}">


    <!--title-->
    <title> {{ __(isset($seoDetail) ? $seoDetail->seo_title : $pageTitle) }} | {{ global_setting()->global_app_name}}</title>

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
      <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="{{asset('vendor/css/bootstrap-icons.css')}}">

    <style>
        .help-block{
            color:red;
        }

        .bg-dark {
    background-color: #daebfc !important;
}
.input-group:not(.has-validation) > :not(:last-child):not(.dropdown-toggle):not(.dropdown-menu):not( .form-floating ){
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
    </style>
    </style>
</head>

<body>
    <!--main content wrapper start-->
    <div class="main-wrapper">

        <!--register section start-->
        <section class="sign-up-in-section bg-dark ptb-60" >
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-7 col-md-8 col-12">
                        <div class="row text-center">
                        <div class="col-12" >
                              <a href="/" ><img src="/front/assets/img/back-button.png" alt="" height="50" width="50"></a>
                        <a href="/" class="mb-4  text-center"><img src="{{ global_setting()->logo_front_url }}" width="200"  alt="logo" class="img-fluid"></a>
                        </div>

                        </div>                      
                        @if (session('company_approval_pending'))
        <div class="alert alert-success">
            @lang('superadmin.signUpApprovalPending')
        </div>
        @else
        @if($registrationStatus->registration_open == 1)
        <div class="login-box mt-2 shadow bg-white form-section bg-white-subtle shadow rounded-custom">
        <h1 class="h3 text-center">SIGN UP</h1>
            {!! Form::open(['id'=>'register', 'method'=>'POST']) !!}
            <div class="row">
                <div class="col-12">
                    <div id="alert"></div>
                </div>
            </div>
            <div id="form-box">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group mb-4">
                            <label for="company_name">{{ __('modules.client.companyName') }} <sup
                                    class="f-14 mr-1">*</sup></label>
                            <input type="text" data-type="name" name="company_name" id="company_name"
                                placeholder="{{ __('modules.client.companyName') }}"
                                class="form-control validate-input">
                        </div>
                    </div>
                    @if(module_enabled('Subdomain'))
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="company_name clearfix">{{ __('subdomain::app.core.subdomain') }} <sup
                                    class="f-14 mr-1">*</sup></label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="subdomain" name="sub_domain"
                                    id="sub_domain">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">.{{ getDomain() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class=" col-sm-6">
                        <div class="form-group mb-4">
                            <label for="name">{{ __('modules.profile.yourName') }} <sup
                                    class="f-14 mr-1">*</sup></label>
                            <input type="text" name="name" data-type="name" id="name"
                                placeholder="{{ __('placeholders.name') }}" class="form-control validate-input">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group mb-4">
                            <label for="email">{{ __('modules.profile.yourEmail') }} <sup
                                    class="f-14 mr-1">*</sup></label>
                            <input type="email" name="email" id="email" data-type="email"
                                placeholder="{{ __('placeholders.email') }}" class="form-control validate-input">
                        </div>
                    </div>

                    <div class=" col-sm-6">
                        <div class="form-group mb-4">
                            <label for="company_phone">Company Phone <sup class="f-14 mr-1">*</sup></label>
                            <input type="text" name="company_phone" id="company_phone"
                                placeholder="{{ __('placeholders.mobile') }}" class="form-control validate-input"
                                data-type="number">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group mb-4">
                            <label for="password">{{__('modules.client.password')}} <sup
                                    class="f-14 mr-1">*</sup></label>
                            <div class="input-group">
                            <input type="password" class="form-control " id="password" class="form-control" name="password"
                                placeholder="{{__('modules.client.password')}}">
                                
                              <div class="input-group-append" >
                            <span class="input-group-text" id="showPasswordToggle" style="padding-top:17px;border:none; background:none; cursor:pointer;    position: absolute;
    right: 0;
    z-index: 999;" >
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </span>
                        </div>
                          
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mb-4">
                            <label for="password_confirmation">{{__('app.confirmPassword')}} <sup
                                    class="f-14 mr-1">*</sup></label>
                         <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="{{__('app.confirmPassword')}}">
                                <div class="input-group-append" >
                                <span class="input-group-text" id="showPasswordToggleC" style="padding-top:17px;border:none; background:none; cursor:pointer;    position: absolute;
    right: 0;
    z-index: 999;" >
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </span>
                              </div>
                           </div>
                        </div>
                    </div>
                        
                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                             <label for="captcha">{{__('Enter Captcha')}} <sup
                                    class="f-14 mr-1">*</sup></label>
                            <input type="text" class="form-control " name="captcha" value="" placeholder="Enter Captcha"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="form-group mb-4">
                            <div class="col-12 input-icons" style="background-color: #FFFFFF; border-radius: 5px;">
                                <a class="d-flex justify-content-start align-items-center"
                                    onclick="javascript:re_captcha();">
                                    <img style="height: 54px;" class="form-control p-0" src="{{ URL('/captcha/1') }}"
                                        class="input-field" id="default_recaptcha_id">
                                    <i class="bi bi-arrow-clockwise pl-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        @if ($global->google_recaptcha_status == 'active' && $global->google_recaptcha_v2_status ==
                        'active')
                        <div class="form-group" id="captcha_container"></div>
                        <input type="hidden" id="g_recaptcha" name="g_recaptcha">
                        @endif
                        @if ($global->google_recaptcha_status == 'active' && $global->google_recaptcha_v3_status ==
                        'active')
                        <div class="form-group">
                            <input type="hidden" id="g_recaptcha" name="g_recaptcha">
                        </div>
                        @endif
                    </div>

                    @if ($global->sign_up_terms == 'yes')
                    <div class="col-sm-12">
                        <div class="form-group mb-4">
                            <input autocomplete="off" id="read_agreement" name="terms_and_conditions" type="checkbox">
                            <label for="read_agreement">@lang('superadmin.superadmin.acceptTerms') <a
                                    href="{{ $global->terms_link }}"
                                    target="_blank">@lang('superadmin.superadmin.termsAndCondition')</a></label>
                        </div>
                    </div>
                    @endif

                    <div class="col-sm-12 text-end">
                        <button type="button" class="btn btn-primary mt-3"
                            id="submit-form">
                            @lang('app.signUp')
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            @else
            <div class="login-box mt-5 form-section register-message">
                <h5 class="mb-0 text-center">
                    {!! $signUpMessage->message !!}
                </h5>
            </div>

            @endif
            @endif
                    </div>
                </div>
            </div>
        </section>
        <!--register section end-->
    </div>
     <!-- Scripts -->
     <script src="https://www.vetanwala.com/saas/vendor/jquery/jquery.min.js"></script>
    <script src="https://www.vetanwala.com/saas/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.vetanwala.com/saas/vendor/slick/slick.min.js"></script>
    <script src="https://www.vetanwala.com/saas/vendor/wowjs/wow.min.js"></script>
    <script src="https://www.vetanwala.com/saas/js/main.js"></script>
    <script src="https://www.vetanwala.com/front/plugin/froiden-helper/helper.js"></script>
<script>
  $('#company_phone').on('input', function () {
    var maxlength = 10;
    var value = this.value.replace(/\D/g, ''); // Remove any non-numeric characters
    if (value.length > maxlength) {
        value = value.slice(0, maxlength); // Trim the input to the first 10 characters
    }
    this.value = value; // Set the input field value to the processed numeric value
});
</script>
<script>
    function validateEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
        return re.test(email);
    }
    function validateNumber(number) {
        var re =  /^[6-9]{1}[0-9]{9}$/;
        return re.test(number);
    }
    // function validateName(name) {
    //     var re = /^[a-zA-Z\s]+$/;
    //     return re.test(name);
    // }

    $('.validate-input').change(function() {
    var input = $(this).val();
    var type = $(this).data('type');
    var isValid = false;

    // Perform validation based on input type
    switch(type) {
        case 'email':
            isValid = validateEmail(input);
            break;
        case 'number':
            isValid = validateNumber(input);
            break;
        default:
            isValid = true; // Default to true if type is not recognized
    }

    if (isValid) {
        $(this).removeClass('invalid');
    } else {
        $(this).addClass('invalid');
    }
});


    function re_captcha() {
        $url = "{{ URL('captcha') }}";
        $url = $url + "/" + Math.random();
        document.getElementById('default_recaptcha_id').src = $url;
        console.log('url: '+ $url);
    }
</script>
<script>
    $('#submit-form').click(function () {

    
            $.easyAjax({
                url: '{{route('front.signup.store')}}',
                container: '.form-section',
                type: "POST",
                data: $('#register').serialize(),
                blockUI: true,
                disableButton: true,
                buttonSelector: "#submit-form",
                messagePosition: "inline",

                success: function (response) {
                    if (response.status === 'success') {
                        $('#form-box').remove();
                    } else if (response.status === 'fail') {

                        @if($global->google_recaptcha_status)
                        grecaptcha.reset();
                        @endif

                    }
                },
            });
            @if($global->google_recaptcha_status)
                grecaptcha.reset();
            @endif

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

    $('#showPasswordToggleC').click(function() {
        var passwordField = $('#password_confirmation');
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
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
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
<script src="https://www.google.com/recaptcha/api.js?render={{ $global->google_recaptcha_v3_site_key }}"></script>
<script>
    grecaptcha.ready(function () {
                grecaptcha.execute('{{ $global->google_recaptcha_v3_site_key }}').then(function (token) {
                    // Add your logic to submit to your backend server here.
                    $('#g_recaptcha').val(token);
                });
            });
</script>
<script>
    // Function to validate email
 <script src="https://code.jquery.com/jquery-3.6.0.min.js">
</script>
@endif
</body>
</html>