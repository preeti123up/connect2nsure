<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title> {{ __(isset($seoDetail) ? $seoDetail->seo_title : $pageTitle) }} | {{ global_setting()->global_app_name}}</title>

    <meta name="description" content="{{ isset($seoDetail) ? $seoDetail->seo_description : '' }}">
    <meta name="author" content="{{ isset($seoDetail) ? $seoDetail->seo_author : '' }}">
    <meta name="keywords" content="{{ isset($seoDetail) ? $seoDetail->seo_keywords : '' }}">

    <meta property="og:title" content="{{ isset($seoDetail) ? $seoDetail->seo_title : '' }}">
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:site_name" content="{{global_setting()->global_app_name}}"/>
    <meta property="og:description" content="{{ isset($seoDetail) ? $seoDetail->seo_description : '' }}">
    <meta property="og:image" content="{{ isset($seoDetail) ? $seoDetail->og_image_url : '' }}"/>

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ global_setting()->favicon_url }}">
    <meta name="msapplication-TileImage" content="{{ global_setting()->favicon_url }}">

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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


    <!--favicon icon-->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ global_setting()->favicon_url }}">

    <!--title-->
    <title> {{ __(isset($seoDetail) ? $seoDetail->seo_title : $pageTitle) }} | {{ global_setting()->global_app_name}}</title>

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.3.0/css/flat-ui.min.css" integrity="sha512-6f7HT84a/AplPkpSRSKWqbseRTG4aRrhadjZezYQ0oVk/B+nm/US5KzQkyyOyh0Mn9cyDdChRdS9qaxJRHayww==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

    <!-- Font -->
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
    
     <!-- Font -->
    
     <!-- icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!--build:css-->
    <link rel="stylesheet" href="{{asset('front/assets/css/main.css')}}">
    <!-- endbuild -->

    <!--custom css start-->
    <link rel="stylesheet" href="{{asset('front/assets/css/custom.css')}}">
    <!--custom css end-->
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="{{asset('vendor/css/bootstrap-icons.css')}}">
        
    <link rel="stylesheet" href="{{ asset('saas/css/cookieconsent.css') }}" media="print" onload="this.media='all'">
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('saas/css/quill.snow.css') }}">
    <script src="https://www.google.com/recaptcha/api.js"></script>

        <style>
        .btn-outline-white:hover {
    color: #fff;
    background-color:#ecb70b;
    border-color: #ecb70b;
}
           .nav-link:active {
            color: #012a8b !important; /* Change text color to red */
        }
         .modal-body{
               padding-bottom:0px;
           }
           .footer-mobile{
               display:none;
           }
       @media screen and (max-width: 767px) {
           .img-1{
               display:none;
           }
           .modal-body{
               padding-bottom:20px;
           }
           .footer-mobile{
               display:block;
           }
       }
        </style>
    @if ($frontDetail->homepage_background != 'default')

        @if ($frontDetail->homepage_background == 'image' || $frontDetail->homepage_background == 'image_and_color')
        <style>
            .section-hero .banner {
                background: url("{{ $frontDetail->background_image_url }}") center center/cover no-repeat !important;
            }
        </style>
        @endif
        @if ($frontDetail->homepage_background == 'image')
            <style>
                .section-hero .banner::after {
                    background-color: unset !important;
                }
            </style>
        @endif

        @if ($frontDetail->homepage_background == 'color' || $frontDetail->homepage_background == 'image_and_color')
            <style>
                .section-hero .banner::after {
                    background-color: {{ $frontDetail->background_color }} !important;
                }
                .breadcrumb-section {
                    background-color: {{ $frontDetail->background_color }}30 !important;
                }
                 p{
                margin:0;
                padding:0;
            }
            </style>
        @endif

    @endif

    @foreach ($frontWidgets as $item)
        @if(!is_null($item->header_script))
            {!! $item->header_script !!}
        @endif

    @endforeach

<style>
    .h1-2{
        
        font-size:36px;
    }
    .help-block{
        color:red;
    }
    .nav-link.active{
        color:#0044e3 !important;
    }
    .toast-message{
        color:black;
    }
       .swiper-slide{
  width: 100% !important;
  margin-right: 0px !important;

}
      .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
.form-control,.form-select{
    background-color: #ffffff;
    color:#2c2828;
}
.form-control:focus, .form-select:focus {
    background-color: #ffffff;
   color:#2c2828;

}

        .form-group label {
    position: absolute;
    top: -0.8em;
    left: 2.0em;
    background: #ffffff;
    padding: 0 0.25em;
    font-size: 0.85em;
    color: #000205;
}
.form-select {
    border-radius: 12px;
}
        .form-group input {
            width: 100%;
            padding: 0.75em;
            border: 1px solid #ced4da;
            border-radius: 12px;
            font-size: 1em;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff; / Change to your desired focus color /
        }
        .quotes{
    color: #fff;
}
#demo {
  /*background:#ECF8F4;*/
  max-width:100%;
  margin: auto;
  max-height: 308px;
   min-height: 308px;
}

.carousel-caption {
  position: initial;
  z-index: 10;
  padding: 2rem 2rem;
  color: rgba(78, 77, 77, 0.856);
  text-align: left;
  font-size: 1.2rem;
  font-style: italic;
  font-weight: bold;
  line-height: 2rem;
}
.t-massege{
        font-size: 14px;
    line-height: 20px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    text-overflow: -o-ellipsis-lastline;
}
@media (max-width: 767px) {
  .carousel-caption {
    position: initial;
    z-index: 10;
    color: rgba(78, 77, 77, 0.856);
    text-align: center;
    font-size: 0.7rem;
    font-style: italic;
    font-weight: bold;
    line-height: 1.5rem;
    padding:3px;
  }
}
.bg-c-1{
    background:#ffffff;
    border-radius:60px;

}
.bg-c-2{
    background:#ffffff;
    border-radius:60px;
    padding:40px;

}

/* .carousel-caption img {
  width: 6rem;
  border-radius: 5rem;
  margin-top: 2rem;
} */

/* @media (max-width: 767px) {
  .carousel-caption img {
    width: 4rem;
    border-radius: 4rem;
    margin-top: 1rem;
  }
} */

#image-caption {
  font-style: normal;
  font-size: 12px;
  margin-top: 0.5rem;
}

@media (max-width: 767px) {
  #image-caption {
    font-style: normal;
    font-size: 0.6rem;
    margin-top: 0.5rem;
  }
  .price-feature{
      padding:3px;
  } 
  .image-c {
     text-align: center !important;
  }
  .pricing-tab-list li:last-child button {
            border-radius: 40px;
             width:auto !important;
}
.pricing-tab-list li:last-child button {
            border-radius: 40px;
             width:auto !important;
}
.phone-padding{
    padding-right: 2px !important;
}
.font-contact{
    font-size: 15px !important;
}
}
.image-c{
     text-align: end;
  }
  .image-c img{
     max-height: 50px;
     min-height: 50px;
  }
  .form-group label{
    color:#000205 !important
}
.video-stream{
    width:100% !important;
    left:0 !important;
}
.swal2-confirm{
    background:#175cff !Important;
}
.pricing-h {
  background-color: #051ABB !important;
}
    .gradient-button {
    background: linear-gradient(45deg, #2653F2, #087BF8, #1AA5FE);
    color:white;
}
.terms-title{
        font-size: 14px;
    margin-bottom: 10px;
}
.pricing-tab-list li button.active {
    background: #175cff;
    color: #fff;
}


.pricing-tab-list {
    border: 1px solid #DADADA;
    border-radius: 40px;
    padding: 3px;
}

.pricing-tab-list li button {
    background: #ffffff;
}
.pricing-tab-list li:first-child button {
    border-radius: 40px;
    width:202px;
}
.fs-12 {
    font-size: 0.9rem !important;
    font-weight:700;
}
.pricing-header h4 span, .pricing-header .h4 span {
    font-size: 0.875rem !important;
    font-weight: 700 !important;
    margin-left: 5px !important;
}
.price-mrp{
        margin-top: -5px;
    font-weight: 700;
    font-size: 16px;
}
@media (min-width: 1200px) {
    .display-6 {
        font-size: 3.0rem !important;
        font-weight: 700 !important;
    }
}
.pricing-tab-list li:last-child button {
            border-radius: 40px;
             width:202px;
}
  .price-feature{
      padding:10px;
  } 
  @media (min-width: 1200px) {
    h1, .h1-2 {
        font-size: 3rem !important;
    }
}
.pricing-heading{
color:white !important;
}
.gradient-button:hover{
    color:white !important

}
.contact-home{
    background: linear-gradient(360deg, #FDFCFF 100%, #EDF3FC 100%)
}
.phone-padding{
    padding-right: 60px;
}
.font-contact{
    font-size: 33px;
}
</style>
    @stack('head-script')

</head>

<body id="home">

    @include('super-admin.sections.saas.saas_header')

    @yield('header-web')

    @yield('content')

    @include('super-admin.saas.section.cta')
    <?php
    $fullUrlPath = $_SERVER['REQUEST_URI'];
    
    ?>
     @if($fullUrlPath =='/')
             <section class="pt-50 pb-40 contact-home" id="enquiry-us">
              
                    <div class="ptb-80 pb-40 position-relative overflow-hidden">
                   <div class="container">
                        <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="subscribe-info-wrap position-relative z-2">
                                <div class="section-heading">
                                    <!--<h4 class="h5 text-primary">Let's Try! Get Enquiry Now</h4>-->
                                    <h2>Get in touch with us</h2>
                                    <p>We're happy to answer questions and get you acquainted with Vetanwala.</p>
                                    <ul style="list-style-type:none;padding-left:0px;">
                                         <li class="pt-4"><span><i class="bi bi-check-circle-fill"></i></span>&nbsp; Learn how to increase team productivity</li>
                                          <li class="pt-4"><span><i class="bi bi-check-circle-fill"></i></span>&nbsp; Get pricing information</li>
                                         <li class="pt-4"><span><i class="bi bi-check-circle-fill"></i></span>&nbsp; Explore use cases for your team</li>
                                        <li class="pt-4"><span><i class="bi bi-patch-question-fill"></i></span>&nbsp; For technical issues and product questions, please visit our Help Centre.</li>
                                    </ul>
                                </div>
                              
                            </div>
                        </div>
                         <div class="col-lg-6 col-md-6 mt-5" id="enquiry-mobile-us">
                             <div class="subscribe-info-wrap  z-2 bg-c-2 text-center ">
                                     <ul class="list-unstyled footer-nav-list">
                                            <li class="d-flex justify-content-center align-items-center"><i class="fa fa-envelope  font-contact" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<a class="mt-2" style="font-size:33px" href="mailto:{{$frontDetail->email}}">{{ $frontDetail->email }}</a></li>
                                            <li class="d-flex justify-content-center align-items-center phone-padding"" ><i class="fa fa-phone font-contact"  aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<a class="mt-2" style="font-size:33px" href="tel:{{$frontDetail->phone}}">{{ $frontDetail->phone }}</a></li>
                                        </ul>
                        </div>
      <!--                      <div class="subscribe-info-wrap position-relative z-2 bg-c-1">-->
      <!--                          <div class="">-->
      <!--                             {!! Form::open(['id'=>'contactUs-home', 'method'=>'POST']) !!}-->
      <!--<div class="container">-->
      <!--                     <div class="row" id="contactUsBox">-->

      <!--                   <div class="form-group mb-4 col-12">-->
      <!--                  <label>Name <span class="text-danger">*</span></label>-->
      <!--                      <input type="text" name="name" class="form-control" placeholder="@lang('Full Name')"-->
      <!--                             id="name">-->
      <!--                  </div>-->
      <!--                  <div class="form-group mb-4 col-12">-->
      <!--                  <label>Company Name <span class="text-danger">*</span></label>-->
      <!--                      <input type="text" name="company_name" class="form-control" placeholder="Company Name"-->
      <!--                             id="company_name">-->
      <!--                  </div>-->
      <!--                  <div class="form-group mb-4 col-12">-->
      <!--                  <label>Mobile No. <span class="text-danger">*</span></label>-->
      <!--                      <input type="text" name="mobile" class="form-control" placeholder="Mobile Number"-->
      <!--                             id="mobile">-->
      <!--                  </div>-->
      <!--                  <div class="form-group mb-4 col-12">-->
      <!--                      <label>Email <span class="text-danger">*</span></label>-->
      <!--                      <input type="email" class="form-control" placeholder="@lang('_Email')"-->
      <!--                             name="email" id="email">-->
      <!--                  </div>-->
      <!--                  <div class="form-group mb-4 col-12">-->
      <!--                  <label>Company size </label>-->
      <!--                            <select id="company_size" name="company_size" class="form-select">-->
      <!--                                <option value="">Company Size ----</option>-->
      <!--                              <option value="1-10">1-10</option>-->
      <!--                              <option value="11-25">11-25</option>-->
      <!--                              <option value="26-50">26-50</option>-->
      <!--                              <option value="50-100">50-100</option>-->
      <!--                              <option value="100+">100+</option>-->
      <!--                          </select>-->
      <!--                  </div>-->
      <!--                <p class="terms-title">By providing your information, you hereby consent to the Vetanwala <a class="text-decoration-none" href=" http://127.0.0.1:8000/page/privacy-policy ">Privacy Policy</a> and <a class="text-decoration-none" href=" http://127.0.0.1:8000/page/terms-of-use ">Terms & Condition</a>.</p>-->
      <!--                  <div class="col-md-12 col-sm-12 d-flex justify-content-center">-->
      <!--                   <button type="button" class="btn enq-btn w-100"  data-page-id="#contactUs-home"  onclick="handleFormSubmit(event)">Submit</button>-->


      <!--                  </div>-->
      <!--              </div>-->
      <!--      </div>-->
      <!--   </div>-->
        
      <!--{!! Form::close() !!}-->

      <!--                          </div>-->
                              
                            </div>
                        </div>
                   </div>
                    </div>
                   
               
                </div>
        </section>
@endif
    @include('super-admin.sections.saas.saas_footer')

   <div class="fixed-bottom">
        <div class="bottom_button">
           @if($fullUrlPath =='/')
            <a href="#" class="anchor" id="scroll2">
                <button class="btn enq_ftr " id="enquiry-mobile">
                    Enquire Now</button>
                    </a>
                      @else
                     <a href="{{route('front.contact')}}" class="anchor" id="scroll2">
                <button class="btn enq_ftr " id="enquiry-mobile">
                    Enquire Now</button>
                    </a>
                    @endif
                    <a href="tel:{{$frontDetail->phone}}" class="anchor" id="scroll2">
                        <button class="btn enq_ftr-1">
                            Call Now</button></a>
                           
        </div>
    </div>

<!-- Modal -->
<!--<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">-->
<!--  <div class="modal-dialog modal-dialog-centered">-->
<!--    <div class="modal-content"style="background-image: url({{asset('front/assets/img/feature-image/Frame2.png')}});background-size:500px 224px;border-radius:9px;background-repeat: no-repeat;">-->
<!--      <div class="" style="position: relative;">-->
<!--        <div style="position: absolute;right: -8px;top: -8px;background: #e9e9e9;border-radius: 14%;font-size: 6px;padding: 3px;z-index: 999;">-->
<!--            <button style="font-size: 12px;" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
<!--        </div>-->
<!--      </div>-->
<!--      <div class="modal-body px-4 pt-4">-->
<!--    <div class="row d-flex justify-content-between">-->
<!--        <div class="col-md-8">-->
<!--             <h3 class="text-white">-->
<!--             Upgrade your -->
<!--HR capabilities.-->
<!--         </h3> -->
<!--    <p class="text-white"style="line-height: initial;">Enjoy precise solutions that takes the-->
<!--complexity out of HR</p>-->
<!--<div class="mt-4 butn-1">-->
<!--    <a href="{{ route('front.signup.index') }}" class="btn btn-success btn-sm">Start Free Trial</a>-->
<!--       <button type="button" class="btn btn-outline-white btn-sm"  id="enquiry" >Enquiry Now</button>-->
<!--</div>-->
<!--    </div>-->
<!--    <div class="col-md-4 img-1">-->
<!--        <img width="100%" src="{{asset('front/assets/img/feature-image/ladki.png')}}"/>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
<!--</div>-->
    <!-- Scripts -->
    <script src="{{ asset('saas/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('saas/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('saas/vendor/slick/slick.min.js') }}"></script>
    <script src="{{ asset('saas/vendor/wowjs/wow.min.js') }}"></script>
    <script src="{{ asset('saas/js/main.js') }}"></script>
    <script src="{{ asset('front/plugin/froiden-helper/helper.js') }}"></script>

      <!--build:js-->
      <!-- <script src="front/assets/js/vendors/jquery-3.6.0.min.js"></script> -->
    <script src="{{asset('front/assets/js/vendors/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('front/assets/js/vendors/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('front/assets/js/vendors/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('front/assets/js/vendors/parallax.min.js')}}"></script>
    <script src="{{asset('front/assets/js/vendors/aos.js')}}"></script>
    <script src="{{asset('front/assets/js/vendors/massonry.min.js')}}"></script>
    <script src="{{asset('front/assets/js/app.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!--endbuild-->

    <!-- Global Required JS -->
    @foreach ($frontWidgets as $item)
        @if(!is_null($item->footer_script))
            {!! $item->footer_script !!}
        @endif

    @endforeach

    @stack('footer-script')

    @includeIf('super-admin.sections.cookie-consent')
    <script>
   $(document).ready(function(){
    // Select all elements containing <p><br></p>
    $("p").each(function(){
        // Check if the inner HTML matches <p><br></p>
        if ($(this).html() === "<br>") {
            // Replace <p><br></p> with <p> </p>
            $(this).html(" ");
            $(this).css("margin-top", "8px");
        }
    });
});
</script>

<script>
  function limitInputLength(selector, maxlength) {
    $(selector).on('input', function () {
      var value = this.value.replace(/\D/g, ''); // Remove any non-numeric characters
      if (value.length > maxlength) {
          value = value.slice(0, maxlength); // Trim the input to the first 10 characters
      }
      this.value = value; // Set the input field value to the processed numeric value
    });
  }

  // Apply the function to multiple input fields
  limitInputLength('#mobile', 10);
  limitInputLength('#mobile_h', 10);
  limitInputLength('#mobile-home', 10);
</script>
<script>
    $(document).ready(function() {
        // Handle link clicks
        $('.nav-link').on('click', function(event) {
            // Remove active class from all links
            $('.nav-link').removeClass('active');

            // Add active class to the clicked link
            $(this).addClass('active');
        });

        // Get the current URL
        var currentUrl = window.location.href;
        // Find the matching menu item and make it active
        $('.nav-link').each(function() {
            var href = $(this).attr('href');

            // Check if the href matches the current URL
            if (currentUrl === href || currentUrl=='https://www.vetanwala.com/') {
                $(this).addClass('active');
                return false; // Stop iterating once a match is found
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
		$('#enquiry-mobile').click(function(){
	     
           var offset = $('#enquiry-mobile-us').offset().top - 190; // Subtracting 100 pixels as an example
            $('html, body').animate({
                scrollTop: offset
            }, 800);
            		$('#exampleModal').modal('hide');

        });

});
</script>

<script>
     function handleFormSubmit(event) {
         event.preventDefault();
         let dataId = event.target.getAttribute('data-page-id');
$.easyAjax({
    url: "{{route('front.enquiry-us')}}",
    container: dataId,
    blockUI: true,
    type: "POST",
    data: $(dataId).serialize(),
    messagePosition: "inline",
    success: function (response) {
          $('.email_unique').hide();
        if(response.error){
            $('.email_unique').show();
        $('.email_unique').text(response.error);
        }
        if (response.status === 'success') {
              Swal.fire({
  title: "Success!",
  text: "Thankyou for registering with us, Our team will get in touch with you to arrange a free demo ASAP",
  icon: "success",
  customClass: {
    confirmButton: 'my-custom-swal' // Apply the custom class
  }
});
  $('#contactUs, #contactUs_h, #contactUs-home').each(function() {
                        this.reset();
                    });


        }
    }
})
  }
  
</script>
<script>
    var testimonialSwiper = new Swiper('.testimonialSwiper', {
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        loop: true,
        slidesPerView: 1,
        spaceBetween: 0,
    });
  

</script>
</body>
</html>
