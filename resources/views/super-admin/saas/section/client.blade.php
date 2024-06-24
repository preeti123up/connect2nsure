
            <section class="customer-review pt-80 pb-40 " style="background:#D4E9FF;">
    <div class="container">
        <div class="row">
            <div class="section-heading text-center my-4 fade-in-right" data-aos="fade-right" data-aos-delay="200">
                    <h1 class="fw-bold text-uppercase" style="color:#262626"> From Cl🕐ck-in to Paycheck📝</h1>
               <p>Experience Smooth Attendance, Leave, and Payroll with Vetanwala</p>   
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4" data-aos="fade-right" data-aos-delay="200">
                <div
                    class="review-info-wrap text-center rounded-custom d-flex flex-column justify-content-center h-100 p-lg-5 p-4 fade-in-right">
                    <div class="client-image flip-container" ontouchstart="this.classList.toggle('hover');">
                        <div class="flipper">
                            <div class="front" style="position: absolute; z-index:999;
    width: 100%;">
                                <img src="front/assets/img/feature-img/Attendance.png" class="img-fluid m-auto"
                                    style="position:relative;">
                            </div>
                            <div class="back" style="position: relative;
    width: 100%;">
                                <img src="front/assets/img/Attendance-flip.png" class="img-fluid m-auto"
                                    style="position:relative;">
                            </div>
                        </div>
                    </div>
                    <div class="review-info-content mt-5">
                        <h3 class="font-bold" style="#262626">Attendance Management</h3>
                        <p class="mb-4" style="#262626">Empower Team with Seamless Attendance Management</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4" data-aos="fade-right" data-aos-delay="200">
                <div
                    class="review-info-wrap text-center rounded-custom d-flex flex-column justify-content-center p-lg-5 p-4 fade-in-right">
                    <div class="client-image flip-container" ontouchstart="this.classList.toggle('hover');">
                        <div class="flipper">
                            <div class="front" style="position: absolute; z-index:999;
    width: 100%;">
                                <img src="front/assets/img/feature-img/Leave.png" class="img-fluid m-auto"
                                    style="position:relative;">
                            </div>
                            <div class="back" style="position: relative;
    width: 100%;">
                                <img src="front/assets/img/Leave-flip.png" class="img-fluid m-auto"
                                    style="position:relative;">
                            </div>
                        </div>
                    </div>
                    <div class="review-info-content mt-5">
                        <h3 class="font-bold" style="#262626">Leave Management</h3>
                        <p class="mb-4" style="#262626">Smarter Leave Management Enhance Satisfaction</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4" data-aos="fade-right" data-aos-delay="200">
                <div
                    class="review-info-wrap text-center rounded-custom d-flex flex-column justify-content-center  p-lg-5 p-4 fade-in-right">
                    <div class="client-image flip-container" ontouchstart="this.classList.toggle('hover');">
                        <div class="flipper">
                            <div class="front" style="position: absolute; z-index:999;
    width: 100%;">
                                <img src="front/assets/img/feature-img/Payroll.png" class="img-fluid m-auto"
                                    style="position:relative;">
                            </div>
                            <div class="back" style="position: relative;
    width: 100%;">
                                <img src="front/assets/img/Payroll-flip.png" class="img-fluid m-auto"
                                    style="position:relative;">
                            </div>
                        </div>
                    </div>
                    <div class="review-info-content mt-5">
                        <h3 class="font-bold " style="#262626">Payroll Management</h3>
                        <p class="mb-4 " style="#262626">Transform Your Payroll Experience Today.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="pt-60 pb-40" style="background:#ECF8F4;">
    <div class="container">
        <div class="row">
            <div class="section-heading text-center my-4 fade-in-right" data-aos="fade-right" data-aos-delay="200">
                <h1 class="fw-bold" style="color:#C27802;">Trusted by Businesses, Loved by Users!</h1>
            </div>
        </div>
        <div class="row  mt-3">
            <div class="col-md-6 mb-3">
<!--<iframe width="100%" height="308"-->
<!--                    src="https://www.youtube.com/embed/e2kijRZ6pJY?playlist=W1hq7S9ltd1_PXQl&loop=1&autoplay=1&mute=1">-->
<!--                </iframe>-->
                <iframe width="100%" height="308"
                    src="https://www.youtube.com/embed/e2kijRZ6pJY?playlist=e2kijRZ6pJY&loop=1&autoplay=1&mute=1">
                </iframe>
            </div>
            <div class="col-md-6">
                <div id="demo" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                    @forelse($testimonials as $i=>$testimonial)

                        <div class="carousel-item {{$i==0?'active':''}}">
                            <div class="carousel-caption">
                                <img src="front/assets/img/fi_11042331.svg" alt="testimonial quote"
                                    width="50" class="img-fluid mb-3">
                                <div class="blockquote-title-review mb-2">
                                    <!-- <h3 class="mb-0 h4 fw-semi-bold">The Best Template You Got to Have it!</h3> -->
                                    <span style="font-size:40px; font-weight:bold"><b>{{number_format($testimonial->rating, 1) }}</b></span>
                                    <ul class="review-rate mb-0 list-unstyled list-inline">
                 @for ($i = 1; $i <= 5; $i++)
          <li class="list-inline-item"><i class="fas fa-star @if($testimonial->rating >= $i) text-warning @else text-red-50 @endif"></i></li>
             @endfor

                        </ul>
                      
                                </div>

                                <p class="mb-2 t-massege">{{$testimonial->comment}} </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="mb-0">{{$testimonial->name}}</h6>
                                        <div class="m-0" id="image-caption">{{$testimonial->company_name}}</div>
                                    </div>
                                    <div class="col-md-6 image-c pb-4">
                                        <img src="{{asset('user-uploads/app-logo/'.$testimonial->logo)}}">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
   <div class="tab-pane fade active show" id="testimonial-tab-empty" role="tabpanel">
        <p>No testimonials found!</p>
    </div>
    @endforelse
                        <!-- </div> <a class="carousel-control-prev" href="#demo" data-slide="prev"> <i style=" background-color: rgb(223, 56, 89);
  padding: 1.4rem;" class='fas fa-arrow-left'></i> </a> <a class="carousel-control-next" href="#demo"
                        data-slide="next"> <i style=" background-color: rgb(223, 56, 89);
  padding: 1.4rem;" class='fas fa-arrow-right'></i> </a>
                </div> -->
                    </div>
                </div>

            </div>
</section>
