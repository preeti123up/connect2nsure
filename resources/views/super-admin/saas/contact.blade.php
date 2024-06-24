@extends('super-admin.layouts.saas-app')


@section('content')
<div class="main-wrapper">


<!--page header section start-->
@include('super-admin.saas.section.breadcrumb')

        <section class="contact-promo ptb-120">
            <div class="container">
                <div class="row justify-content-center">
                  
                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <div class="contact-us-promo p-5 bg-white rounded-custom custom-shadow text-center d-flex flex-column h-100">
                            <span class="fas fa-envelope fa-3x text-primary"></span>
                            <div class="contact-promo-info mb-4">
                                <h5>Email Us</h5>
                                <p>Simple drop us an email at <strong>{{$frontDetail->email}}</strong>
                                    and you'll receive a reply within 24 hours</p>
                            </div>
                            <a href="mailto:{{$frontDetail->email}}" class="btn btn-link mt-auto">Email Us</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mt-4 mt-lg-0">
                        <div class="contact-us-promo p-5 bg-white rounded-custom custom-shadow text-center d-flex flex-column h-100">
                            <span class="fas fa-phone fa-3x text-primary"></span>
                            <div class="contact-promo-info mb-4">
                                <h5>Give us a call</h5>
                                <p>Give us a ring.Our Experts are standing by <strong>monday to friday</strong> from
                                    <strong>9am to 5pm EST.</strong>
                                </p>
                            </div>
                            <a href="tel:{{$frontDetail->phone}}" class="btn btn-link mt-auto">{{$frontDetail->phone}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--contact us promo section end-->

        <!--contact us form start-->
        <section class="contact-us-form pt-60 pb-120" style="background: url('front/assets/img/shape/contact-us-bg.svg')no-repeat center bottom">
            <div class="container">
                <div class="row justify-content-lg-between align-items-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="section-heading">
                            <h2>Get connected to our team</h2>
                            <p>Collaboratively promote client-focused convergence vis-a-vis customer directed alignments via
                                standardized infrastructures.</p>
                        </div>
                        {!! Form::open(['id'=>'contactUs', 'method'=>'POST']) !!}
                    <div class="row mb-3">
                        <div id="alert" class="col-sm-12"></div>
                    </div>
                    <div class="row bg-c-1" id="contactUsBox">
                       <div class="form-group mb-4  col-12">
                            <label for="name" class="mb-1">Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="@lang('Full Name')"
                                   id="name">
                        </div>
                        <div class="form-group mb-4  col-12">
                            <label for="company_name" class="mb-1">Compay Name<span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" placeholder="Company Name"
                                   id="company_name">
                        </div>
                        <div class="form-group mb-4 col-12">
                            <label for="mobile" class="mb-1">Mobile<span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" placeholder="Mobile Number"
                                   id="mobile">
                        </div>
                        <div class="form-group mb-4 col-12">
                            <label for="email" class="mb-1">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" placeholder="@lang('Email')"
                                   name="email" id="email">
                        </div>
                        <div class="form-group mb-4  col-12">
                        <label for="mobile" class="mb-1">Company Size</label>
                                  <select id="company_size" name="company_size" class="form-select">
                                    <option value="">Company Size ----</option>
                                    <option value="1-10">1-10</option>
                                    <option value="11-25">11-25</option>
                                    <option value="26-50">26-50</option>
                                    <option value="50-100">50-100</option>
                                    <option value="100+">100+</option>
                                </select>
                        </div>

                        @if ($global->google_recaptcha_status == 'active' && $global->google_recaptcha_v2_status == 'active')
                            <div class="form-group col-12" id="captcha_container"></div>
                            <input type="hidden" id="g_recaptcha" name="g_recaptcha">
                        @endif
                        @if ($global->google_recaptcha_status == 'active' && $global->google_recaptcha_v3_status == 'active')
                            <div class="form-group col-12">
                                <input type="hidden" id="g_recaptcha" name="g_recaptcha">
                            </div>
                        @endif
                         <p class="terms-title">By providing your information, you hereby consent to the Vetanwala <a class="text-decoration-none" href=" http://127.0.0.1:8000/page/privacy-policy ">Privacy Policy</a> and <a class="text-decoration-none" href=" http://127.0.0.1:8000/page/terms-of-use ">Terms & Condition</a>.</p>

                        <div class="col-12" style="margin-top:5;">
                            <button type="button" class="btn btn-primary mt-3 w-100" id="contact-submit" data-page-id="#contactUs"  onclick="handleFormSubmit(event)">
                                {{ $frontMenu->contact_submit }}
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    </div>
                    <div class="col-lg-5 col-md-10">
                        <div class="contact-us-img">
                            <img src="front/assets/img/contact-us-img-2.svg" alt="contact us" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>
</div>
        <!--contact us form end-->

    <!-- END Contact Section -->
@endsection
@push('footer-script')
    

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

@endpush
