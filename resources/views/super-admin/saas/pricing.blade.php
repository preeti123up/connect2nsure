@extends('super-admin.layouts.saas-app')

@section('content')

    <!--main content wrapper start-->
    <div class="main-wrapper">



        <!--page header section start-->
     @include('super-admin.saas.section.breadcrumb')
        <!--page header section end-->

           <!--pricing section start-->
           <section class="pricing-section ptb-60 position-relative z-2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-10">
                        <div class="section-heading text-center">
                            <h3 class="h1-2 text-primary">{{ $trFrontDetail->price_title }}</h3>
                        <p>{{ $trFrontDetail->price_description }}</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center align-items-center mb-5">
                     <div class="col-lg-3">
                        <div class="media d-flex align-items-center py-2 p-sm-2">
                            <div class="icon-box mb-0 bg-success-soft rounded-circle d-block me-3">
                                <i class="fas fa-calendar-check text-success"></i>
                            </div>
                            <div class="media-body fw-medium h6 mb-0">
                                Start Free Trial
                            </div>
                            <!--<div class="media-body fw-medium h6 mb-0">-->
                            <!--    Get {{$packageSetting->no_of_days}} day free trial-->
                            <!--</div>-->
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="media d-flex align-items-center py-2 p-sm-2">
                            <div class="icon-box mb-0 bg-primary-soft rounded-circle d-block me-3">
                                <i class="fas fa-credit-card text-primary"></i>
                            </div>
                            <div class="media-body fw-medium h6 mb-0">
                                No credit card required
                            </div>
                        </div>
                    </div>
                   
                    <!--<div class="col-lg-3">-->
                    <!--    <div class="media d-flex align-items-center py-2 p-sm-2">-->
                    <!--        <div class="icon-box mb-0 bg-danger-soft rounded-circle d-block me-3">-->
                    <!--            <i class="fas fa-calendar-times text-danger"></i>-->
                    <!--        </div>-->
                    <!--        <div class="media-body fw-medium h6 mb-0">-->
                    <!--            Cancel anytime-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
                               @include('super-admin.saas.pricing-plan')


            </div>
        </section>
        <!--pricing section end-->

        <!--faq section start-->
        <section class="faq-section ptb-60 bg-light-subtle">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-12">
                        <div class="section-heading text-center">
                            <h2>{{ $trFrontDetail->faq_title }}</h2>
                            <p>{{$trFrontDetail->faq_detail}}</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-12">
                        <div class="accordion faq-accordion" id="accordionExample">
                            <?php $i=1; ?>
                        @forelse($frontFaqs as $frontFaq)
                            <div class="accordion-item border border-2 {{$i==1?'active':''}}">
                                <h5 class="accordion-header" id="faq-1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$frontFaq->id }}" aria-expanded="{{$i==1?'true':'false';}}">
                                    {{ $frontFaq->question }}
                                    </button>
                                </h5>
                                <div id="collapse-{{$frontFaq->id }}" class="accordion-collapse collapse {{$i==1?'show':''}}" aria-labelledby="faq-1" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                    <p>{!! $frontFaq->answer  !!}</p>

                                    </div>
                                </div>
                            </div>
                            <?php $i++ ?>
                         @empty
                         @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--faq section end-->

     
      
        

    </div>

    @endsection
@push('footer-script')
<script>
 $('body').on('click','.read-more-modules-btn',function(event){
     event.preventDefault()

    //Show hidden modules when "Read More " button is clicked
    var target = $(this).data('target'); // Get the target identifier

// Toggle visibility of the corresponding hidden module
$('.hidden-module-' + target).slideToggle();

// Toggle button text between "Read More" and "Read Less"
$(this).text(function(i, text) {
    return text === "Read More" ? "Read Less" : "Read More";
});
     
   
})

    </script>

@endpush





