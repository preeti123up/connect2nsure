
        <!--customer review tab section start-->
<!--        @if(sizeof($testimonials) > 0)-->
<!--        <section class="customer-review-tab ptb-60 position-relative z-2">-->
<!--            <div class="container">-->
<!--                <div class="row justify-content-center align-content-center">-->
<!--                    <div class="col-md-10 col-lg-6">-->
<!--                        <div class="section-heading text-center" data-aos="fade-up">-->
<!--                            <h4 class="h5  text-primary">Testimonial</h4>-->
<!--                            <h2>{{$trFrontDetail->testimonial_title}}</h2>-->
<!--                            <p>{{$trFrontDetail->testimonial_detail}}</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="row">-->
<!--                    <div class="col-12">-->
<!--                    <div class="tab-content" id="testimonial-tabContent" data-aos="fade-up" data-aos-delay="100">-->
<!--                                            <?php $k=1; ?>-->

<!--    @forelse($testimonials as $testimonial)-->
<!--    <div class="tab-pane fade {{ $k== 1 ? 'active show' : '' }}" id="testimonial-tab-{{$k}}" role="tabpanel">-->
<!--        <div class="row align-items-center justify-content-between">-->
<!--            <div class="col-lg-6 col-md-6">-->
<!--                <div class="testimonial-tab-content mb-5 mb-lg-0 mb-md-0">-->
<!--                    <img src="front/assets/img/testimonial/quotes-left.svg" alt="testimonial quote" width="65" class="img-fluid mb-32">-->
<!--                    <div class="blockquote-title-review mb-4">-->
<!--                        <h3 class="mb-0 h4 fw-semi-bold">The Best Template You Got to Have it!</h3>-->
<!--                        <ul class="review-rate mb-0 list-unstyled list-inline">-->
<!--                            @for ($i = 1; $i <= 5; $i++)-->
<!--                            <li class="list-inline-item"><i class="fas fa-star @if($testimonial->rating >= $i) text-warning @else text-red-50 @endif"></i></li>-->
<!--                            @endfor-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                    <blockquote class="blockquote">-->
<!--                        <p>{{$testimonial->comment}}</p>-->
<!--                    </blockquote>-->
<!--                    <div class="author-info mt-4">-->
<!--                        <h6 class="mb-0">{{ $testimonial->name}}</h6>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-5 col-md-6">-->
<!--                <div class="author-img-wrap pt-5 ps-5">-->
<!--                    <div class="testimonial-video-wrapper position-relative">-->
<!--                     @if($testimonial->image)-->
<!--                        <img src="{{ asset('/user-uploads/front/testimonial/' . $testimonial->image) }}" class="img-fluid rounded-custom shadow-lg" alt="testimonial author">-->
<!--                        @else-->
<!--                        <img src="{{asset('front/assets/img/no-image.jpg')}}" class="img-fluid rounded-custom shadow-lg" alt="testimonial author">-->
<!--                        @endif                        <div class="customer-info text-white d-flex align-items-center">-->
<!--                            <a href="{{$testimonial->video_link}}" class="video-icon popup-youtube text-decoration-none"><i class="fas fa-play"></i> <span class="text-white ms-2 small"> Watch Video</span></a>-->
<!--                        </div>-->
<!--                        <div class="position-absolute bg-primary-dark z--1 dot-mask dm-size-16 dm-wh-350 top--40 left--40 top-left"></div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--                        <?php $k++; ?>-->

<!--    @empty-->
<!--    <div class="tab-pane fade active show" id="testimonial-tab-empty" role="tabpanel">-->
<!--        <p>No testimonials found!</p>-->
<!--    </div>-->
<!--    @endforelse-->
<!--</div>-->

<!--<div class="row">-->
<!--    <div class="col-12">-->
<!--        <ul class="nav nav-pills testimonial-tab-menu mt-60" id="testimonial" role="tablist" data-aos="fade-up" data-aos-delay="100">-->
<!--                    <?php $j=1; ?>-->

<!--            @forelse($testimonials as $testimonial)-->
<!--            <li class="nav-item" role="presentation">-->
<!--                <div class="nav-link d-flex align-items-center rounded-custom border border-light border-2 testimonial-tab-link {{ $j == 1 ? 'active' : '' }}" data-bs-toggle="pill" data-bs-target="#testimonial-tab-{{$j}}" role="tab" aria-selected="{{ $j == 1 ? 'true' : 'false' }}">-->
<!--                    <div class="testimonial-thumb me-3">-->
<!--                         @if($testimonial->image)-->
<!--                        <img src="{{ asset('/user-uploads/front/testimonial/' . $testimonial->image) }}" class="img-fluid rounded-custom shadow-lg" alt="testimonial author">-->
<!--                        @else-->
<!--                        <img src="{{asset('front/assets/img/testimonial-user.png')}}" width="50" class="img-fluid rounded-custom shadow-lg" alt="testimonial author">-->
<!--                        @endif                    </div>-->
<!--                    <div class="author-info">-->
<!--                        <h6 class="mb-0">{{$testimonial->name}}</h6>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </li>-->
<!--                    <?php $j++; ?>-->

<!--            @empty-->
            <!-- No items found -->
<!--            @endforelse-->
<!--        </ul>-->
<!--    </div>-->
<!--</div>-->

<!--            </div>-->
<!--        </section> <!--customer review tab section end-->
<!--        @endif-->