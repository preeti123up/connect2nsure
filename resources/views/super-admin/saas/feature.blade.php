@extends('super-admin.layouts.saas-app')


@section('content')




    <!--page header section start-->
     @include('super-admin.saas.section.breadcrumb')
     <div class="main-wrapper">
                            <h2 class=" text-primary text-center ptb-30" style="font-size:36px">Elevate Your Experience Explore Our Innovative Features</h2>
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
</div>
        
       

<!--integration section end-->

@endsection
@push('footer-script')

@endpush