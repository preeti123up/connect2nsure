
    
@extends('super-admin.layouts.saas-app')


@section('content')

<div class="main-wrapper">

 @foreach($details as $key => $value)
             @if($loop->iteration % 2 == 0)

<section class="image-feature ptb-60 bg-light-subtle">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                <div class="col-lg-5 col-12 order-lg-1">
                        <div class="feature-img-content">
                            <div class="section-heading" data-aos="fade-up">
                                <h2>{{ $value->title }}</h2>
                                <p>
                                {!! $value->description !!}
                                </p>
                            </div>
                           
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 order-lg-0">
                        <div class="feature-img-holder">
                            <div class="p-lg-5 p-3 rounded-custom position-relative d-block feature-img-wrap">
                                <div class="position-relative">
                                    <img src="{{asset('user-uploads/front/read-more/'.$value->image) }}" class="img-fluid rounded-custom position-relative" alt="feature-image" data-aos="fade-up" data-aos-delay="50" />
                                </div>
                                <div class="position-absolute bg-dark-soft z--1 dot-mask dm-size-12 dm-wh-250 bottom-right"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
        @else
        <section class="image-feature ptb-60">
            <div class="container">
                
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-5 col-12">
                        <div class="feature-img-content">
                            <div class="section-heading" data-aos="fade-up">
                                <h2>{{ $value->title }}</h2>
                                <p>
                                {!! $value->description !!}
                                </p>
                            </div>
                           
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="feature-img-holder">
                            <div class="p-lg-5 p-3  position-relative rounded-custom d-block feature-img-wrap">
                                <div class="position-relative">
                                    <img src="{{asset('user-uploads/front/read-more/'.$value->image) }}" class="img-fluid rounded-custom position-relative" alt="feature-image" data-aos="fade-up" data-aos-delay="50" />
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
        
     
        @endsection
@push('footer-script')

@endpush



