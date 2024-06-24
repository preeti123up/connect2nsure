

     <div class="main-wrapper">
     @if(!empty($featureWithImages))
 @foreach($featureWithImages as $key => $value)
             @if($loop->iteration % 2 == 0)

<section class="image-feature ptb-20" style="background: aliceblue">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                <div class="col-lg-5 col-12 order-lg-1">
                        <div class="feature-img-content">
                            <div class="section-heading" data-aos="fade-right">
                                <h1 class="fw-bold h1-2">{{ $value->title }}</h1>
                                <p>
                                {!! $value->description !!}
                                </p>
                            </div>
                            <ul class="list-unstyled d-flex flex-wrap list-two-col mb-0" data-aos="fade-right" data-aos-delay="50">
                                <li>
                                 @if($value->feature_icon1 !=null)
                                    <div class="icon-box">
                                    <img src="{{asset('/user-uploads/front/feature/'.$value->feature_icon1)}}" class="img-fluid rounded-custom position-relative" alt="feature-image" data-aos="fade-right" data-aos-delay="50" />
                                    </div>
                                @endif
                                    <h3 class="h5">{{$value->feature_title1}}</h3>
                                    <p>{{$value->feature_description1}}</p>

                                </li>
                                <li>
                                   @if($value->feature_icon2 !=null)
                                    <div class="icon-box">
                                    <img src="{{asset('/user-uploads/front/feature/'.$value->feature_icon2) }}" class="img-fluid rounded-custom position-relative" alt="feature-image" data-aos="fade-right" data-aos-delay="50" />
                                    </div>
                                    @endif
                                    <h3 class="h5">{{$value->feature_title2}}</h3>
                                    <p>
                                    {{$value->feature_description2}}
                                    </p>

                                </li>
                            </ul>
                              @if($value->featureReadMoreId != null)
                                <a href="{{route('front.read-more',['id'=>$value->featureId])}}">Read More</a>
                              @endif

                        </div>
                    </div>
                    <div class="col-lg-6 col-12 order-lg-0">
                        <div class="feature-img-holder">
                            <div class="p-lg-5 p-3 rounded-custom position-relative d-block feature-img-wrap">
                                <div class="position-relative">
                                    <img src="{{ $value->image_url }}" class="img-fluid rounded-custom position-relative" alt="feature-image" data-aos="fade-right" data-aos-delay="50" />
                                </div>
                                <div class="position-absolute bg-dark-soft z--1 dot-mask dm-size-12 dm-wh-250 bottom-right"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
        @else
        <section class="image-feature ptb-20">
            <div class="container">
                
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-5 col-12">
                        <div class="feature-img-content">
                            <div class="section-heading" data-aos="fade-up">
                                <h1 class="fw-bold h1-2">{{ $value->title }}</h1>
                                <p>
                                {!! $value->description !!}
                                </p>
                            </div>
                            <ul class="list-unstyled d-flex flex-wrap list-two-col mb-0" data-aos="fade-up" data-aos-delay="50">
                                <li>
                                    @if($value->feature_icon1 !=null)
                                    <div class="icon-box">
                                    <img src="{{asset('/user-uploads/front/feature/'.$value->feature_icon1)}}" class="img-fluid rounded-custom position-relative" alt="feature-image" data-aos="fade-up" data-aos-delay="50" />
                                    </div>
                                    @endif
                                    <h3 class="h5">{{$value->feature_title1}}</h3>
                                    <p>{{$value->feature_description1}}</p>
                                   

                                </li>
                                <li>
                                     @if($value->feature_icon2 !=null)
                                    <div class="icon-box">
                                    <img src="{{asset('/user-uploads/front/feature/'.$value->feature_icon2) }}" class="img-fluid rounded-custom position-relative" alt="feature-image" data-aos="fade-up" data-aos-delay="50" />
                                    </div>
                                    @endif
                                    <h3 class="h5">{{$value->feature_title2}}</h3>
                                    <p>
                                    {{$value->feature_description2}}
                                    </p>

                                </li>
                            </ul>
                              @if($value->featureReadMoreId != null)
                              <a href="{{route('front.read-more',['id'=>$value->featureId])}}">Read More</a>
                              @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="feature-img-holder">
                            <div class="p-lg-5 p-3  position-relative rounded-custom d-block feature-img-wrap">
                                <div class="position-relative">
                                    <img src="{{ $value->image_url }}" class="img-fluid rounded-custom position-relative" alt="feature-image" data-aos="fade-up" data-aos-delay="50" />
                                    <!-- <img src="front/assets/img/screen/widget-13.png" class="img-fluid rounded-custom shadow position-absolute bottom--100 right--100 hide-medium" alt="feature-image" data-aos="fade-up" data-aos-delay="100" /> -->
                                </div>
                                <div class="position-absolute bg-dark-soft z--1 dot-mask dm-size-12 dm-wh-250 top-left"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
        @endforeach
        @endif
        
        <!--our location address start-->
        <section class="office-address-section ptb-80">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-12">
                        <div class="section-heading text-center">
                            <h2>Crafting excellence for various sectors </h2>
                            <p>We serve businesses of all types and sizes.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 my-4 mt-lg-0 mt-xl-0">
                        <div class="rounded-custom d-block office-address overflow-hidden z-2" style="background-image: url({{asset('front/assets/img/feature-image/4.png')}}) ; background-size:100%;">
                            <div class="office-content text-center p-4">
                                <span class="office-overlay"></span>
                                <div class="office-info">
                                    <h5>Media and advertising</h5>
                                    <address>
                                        Streamline employee management, handle union compliance, and manage project-based contracts.
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-3 col-md-6 my-4 mt-lg-0 mt-xl-0">
                        <div class="rounded-custom d-block office-address overflow-hidden z-2" style="background-image: url({{asset('front/assets/img/feature-image/3.png')}}) ; background-size:100%;">
                            <div class="office-content text-center p-4">
                                <span class="office-overlay"></span>
                                <div class="office-info">
                                    <h5>Information technology</h5>
                                    <address>
                                        Streamline employee onboarding, attendance, leave, manage certifications, and track project-related tasks.
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-3 col-md-6 my-4 mt-lg-0 mt-xl-0">
                        <div class="rounded-custom d-block office-address overflow-hidden z-2" style="background-image: url({{asset('front/assets/img/feature-image/2.png')}}) ; background-size:100%;">
                            <div class="office-content text-center p-4">
                                <span class="office-overlay"></span>
                                <div class="office-info">
                                    <h5>Education</h5>
                                    <address>
                                        Track faculty credentials, streamline hiring processes, and manage other staff employment.
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-3 col-md-6 my-4 mt-lg-0 mt-xl-0">
                        <div class="rounded-custom d-block office-address overflow-hidden z-2" style="background-image: url({{asset('front/assets/img/feature-image/1.avif')}}) ; background-size:100%;">
                            <div class="office-content text-center p-4">
                                <span class="office-overlay"></span>
                                <div class="office-info">
                                    <h5>Healthcare</h5>
                                    <address>
                                       Simplify scheduling for shifts, manage training programs, and optimize labor costs.
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-3 col-md-6 my-4 mt-lg-0 mt-xl-0">
                        <div class="rounded-custom d-block office-address overflow-hidden z-2" style="background-image: url({{asset('front/assets/img/feature-image/5.png')}}) ; background-size:100%;">
                            <div class="office-content text-center p-4">
                                <span class="office-overlay"></span>
                                <div class="office-info">
                                    <h5>Small scale business</h5>
                                    <address>
                                       Simplify HR procedures and establish a considerate employee experience
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 my-4 mt-lg-0 mt-xl-0">
                        <div class="rounded-custom d-block office-address overflow-hidden z-2" style="background-image: url({{asset('front/assets/img/feature-image/6.png')}}) ; background-size:100%;">
                            <div class="office-content text-center p-4">
                                <span class="office-overlay"></span>
                                <div class="office-info">
                                    <h5>Finance</h5>
                                    <address>
                                       Automate payroll processing, track certifications, and ensure regulatory compliance.
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 my-4 mt-lg-0 mt-xl-0">
                        <div class="rounded-custom d-block office-address overflow-hidden z-2" style="background-image: url({{asset('front/assets/img/feature-image/7.avif')}}) ; background-size:100%;">
                            <div class="office-content text-center p-4">
                                <span class="office-overlay"></span>
                                <div class="office-info">
                                    <h5>Hotel</h5>
                                    <address>
                                        Consolidate HR processes and create a compassionate employee experience.
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 my-4 mt-lg-0 mt-xl-0">
                        <div class="rounded-custom d-block office-address overflow-hidden z-2" style="background-image: url({{asset('front/assets/img/feature-image/8.png')}}) ; background-size:100%;">
                            <div class="office-content text-center p-4">
                                <span class="office-overlay"></span>
                                <div class="office-info">
                                    <h5>AutoMobiles</h5>
                                    <address>
                                       Strategically analyze and account for your HR needs.
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--our location address end-->
