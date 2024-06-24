    <!--hero section start-->
        <section class="hero-section pt-4 position-relative ">
            <div class="container">
                <div class="row justify-content-center">
                 <div class="col-xl-12 col-lg-12 mb-5">
   <div class="row">
       <div class="col-md-6">
            <div class="hero-content-wrap">
        <img src="{{asset('front/assets/img/rocket.png')}}" height="100" width="100"> <h4 class="fw-bold display-5 fade-left-to-right">{{ $trFrontDetail->header_title }}</h4>
        <p class="lead fade-left-to-right" data-aos="fade-up" data-aos-delay="50">{!! $trFrontDetail->header_description !!}</p>
        <div class="action-btns fade-left-to-right" data-aos="fade-up" data-aos-delay="100">
  <!--          @if($setting->enable_register)-->
  <!--              @if (isset($packageSetting) && isset($trialPackage) && $packageSetting && !is_null($trialPackage))-->
  <!--                  <a href="{{ route('front.signup.index') }}" class="me-lg-3 me-sm-3 animated-button1">Free Demo <i class="bi bi-arrow-right"></i>-->
  <!--                   <span></span>-->
  <!--<span></span>-->
  <!--<span></span>-->
  <!--<span></span>-->
                    
                    
  <!--                  </a>-->
  <!--              @else-->
  <!--                  <a href="{{ route('front.signup.index') }}" style="margin-bottom: 46px;" class="me-lg-3 animated-button1 me-sm-3">{{ $frontMenu->get_start }} <i class="bi bi-arrow-right"></i>-->
  <!--                   <span></span>-->
  <!--<span></span>-->
  <!--<span></span>-->
  <!--<span></span>-->
  <!--                  </a>-->
  <!--              @endif-->
  <!--          @endif-->
    <div class="row">
    <div class="col-md-12 col-sm-12 p-0">
         <a href="https://apps.apple.com/in/app/vetan-wala/id6446106014" target="_blank"> <img width="170" src="{{asset('front/assets/img/apple.png')}}"/></a>
          <a href="https://play.google.com/store/apps/details?id=com.vetanwala" target="_blank"><img width="170" src="{{asset('front/assets/img/playstore.png')}}"/></a>
      </div>
    </div>
        </div>
    </div>
       </div>
         <div class="col-lg-5 offset-md-1 col-md-5 banner-main-form" id="enquiry-mobile-us" data-aos="fade-left" data-aos-delay="100">
<div class="subscribe-info-wrap position-relative z-2 bg-c-1">
                                <div class="">
                                   {!! Form::open(['id'=>'contactUs_h', 'method'=>'POST']) !!}
                 <div class="container">
                           <div class="row" id="contactUsBox">
                            <h3>Register for Free Demo</h3>
                        <div class="form-group mb-4 col-12">
                        <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name_h" class="form-control" placeholder="@lang('Full Name')"
                                   id="name_h">
                        </div>
                        <div class="form-group mb-4 col-12">
                        <label>Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name_h" class="form-control" placeholder="Company Name"
                                   id="company_name_h">
                        </div>
                        <div class="form-group mb-4 col-12">
                        <label>Mobile No. <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_h" class="form-control" placeholder="Mobile Number"
                                   id="mobile_h">
                        </div>
                        <div class="form-group mb-4 col-12">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" placeholder="@lang('_Email')"
                                   name="email_h" id="email_h">
                            <span class="email_unique text-danger" ></span>

                        </div>
                        <div class="form-group mb-4 col-12">
                        <label>Company size </label>
                                  <select id="company_size_h" name="company_size_h" class="form-select">
                                    <option value="">Company Size ----</option>
                                    <option value="1-10">1-10</option>
                                    <option value="11-25">11-25</option>
                                    <option value="26-50">26-50</option>
                                    <option value="50-100">50-100</option>
                                    <option value="100+">100+</option>
                                </select>
                        </div>
                         <p class="terms-title">By providing your information, you hereby consent to the Vetanwala <a class="text-decoration-none" href=" http://127.0.0.1:8000/page/privacy-policy ">Privacy Policy</a> and <a class="text-decoration-none" href=" http://127.0.0.1:8000/page/terms-of-use ">Terms & Condition</a>.</p>
                        <input type="hidden" name="form_name" value="contactUs_h">

                        <div class="col-md-12 col-sm-12 d-flex justify-content-center">
                         <button type="button" class="btn pricing-h pricing-heading w-100"  data-page-id="#contactUs_h"  onclick="handleFormSubmit(event)">Submit</button>


                        </div>
                    </div>
            </div>
         </div>

      {!! Form::close() !!}

                                </div>                              
                            </div>
   </div>
</div>


                    <!--<div class="col-lg-9">-->
                    <!--    <div class="position-relative" data-aos="fade-up" data-aos-delay="200">-->
                    <!--        <ul class="position-absolute animate-element parallax-element widget-img-wrap z-2">-->
                    <!--            <li class="layer" data-depth="0.04">-->
                    <!--                <img src="front/assets/img/screen/widget-3.png" alt="widget-img" class="img-fluid widget-img-1 position-absolute">-->
                    <!--            </li>-->
                    <!--            <li class="layer" data-depth="0.02">-->
                    <!--                <img src="front/assets/img/screen/widget-4.png" alt="widget-img" class="img-fluid widget-img-3 position-absolute">-->
                    <!--            </li>-->
                    <!--        </ul>-->
                    <!--        <img src="{{$trFrontDetail->image_url}}" alt="dashboard image" class="img-fluid position-relative rounded-custom mt-lg-5">-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            </div>
            <!--<div class="position-absolute bottom-0 h-25 bottom-0 left-0 right-0 z--1 py-5"></div>-->
        </section> 
        <!--hero section end-->