


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

    <!--title-->

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&amp;display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lily+Script+One&amp;display=swap" rel="stylesheet">
    <!-- Font -->

    <!--build:css-->
    <link rel="stylesheet" href="/front/assets/css/main.css">
    <!-- endbuild -->

    <!--custom css start-->
    <link rel="stylesheet" href="/front/assets/css/custom.css">
    <!--custom css end-->

    <style>
        .bg-dark {
    background-color: #daebfc !important;
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
                        <a href="/" class="mb-4 d-block text-center"><img src="{{ global_setting()->logo_front_url }}" width="200" alt="logo" class="img-fluid"></a>
                       
                        <div class="alert alert-success m-t-10 d-none" id="success-msg"></div>

                          
                                <form class="ajax-form" id="forgot-password-form" ction="{{ route('password.email') }}" method="POST">
                                    
                    {{ csrf_field() }}

                        <div class="register-wrap p-5 group  bg-white shadow rounded-custom">
                        <h1 class="h3 text-center">Forget Password</h1>

                    @if (session('message'))
                        <div class="alert alert-danger m-t-10">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <div class="col-sm-12">
                        <label for="email" class="mb-1">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control height-50 f-15 light_text"
                       autofocus placeholder="@lang('placeholders.email')" id="email">

                        </div>
                    </div>
                      <div class="forgot_pswd mt-3">
                      <button
                type="button"
                id="submit-login"
                class="btn btn-primary f-w-500 rounded w-100 height-50 f-18">
                @lang('app.sendPasswordLink') <i class="fa fa-arrow-right pl-1"></i>
            </button>        </div>
             </div>
                </form>
                       

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

            $('#submit-login').click(function () {

                const url = "{{ route('password.email') }}";
                $.easyAjax({
                    url: url,
                    container: '#forgot-password-form',
                    disableButton: true,
                    blockUI: true,
                    buttonSelector: "#submit-login",
                    type: "POST",
                    data: $('#forgot-password-form').serialize(),
                    success: function (response) {
                        $('#success-msg').removeClass('d-none');
                        $('#success-msg').html(response.message);
                        $('.group').remove();
                    }
                })
            });

        </script>

        @foreach ($frontWidgets as $item)
        @if(!is_null($item->footer_script))
            {!! $item->footer_script !!}
        @endif

        @endforeach
</body>

</html>