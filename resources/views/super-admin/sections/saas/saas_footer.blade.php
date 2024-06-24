


 <!--footer section start-->
 <footer class="footer-section"style="background-color: aliceblue;">
            <!--footer top start-->
            <!--for light footer add .footer-light class and for dark footer add .bg-dark .text-white class-->
            <div class="footer-top footer-light pt-80 pb-30">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 mb-md-4 mb-lg-0">
                            <div class="footer-single-col">
                            <div class="footer-single-col mb-4">
                                    <img src="{{ global_setting()->logo_front_url }}" alt="logo" width="180" class="img-fluid logo-white" />
                                    <img src="{{ global_setting()->logo_front_url }}" alt="logo" width="180" class="img-fluid logo-color" />
                                </div>
                            </div>
                            <?php
                                $social_links = json_decode($frontDetail->social_links, true);

                                $facebook_link = null;
                                $instagram_link=null;
                                $twitter_link=null;
                                $dribbble_link=null;
                                $youtube_link=null;

                                foreach ($social_links as $link) {
                                    if ($link['name'] === 'facebook') {
                                        $facebook_link = $link['link'];
                                    }
                                    if ($link['name'] === 'twitter') {
                                        $twitter_link = $link['link'];
                                    }
                                    if ($link['name'] === 'instagram') {
                                        $instagram_link = $link['link'];
                                    }
                                    if ($link['name'] === 'youtube') {
                                        $youtube_link = $link['link'];
                                    }
                                    if ($link['name'] === 'dribbble') {
                                        $dribbble_link = $link['link'];
                                    }
                                }
                            ?>

                                <div class="footer-single-col text-start">
                                    <ul class="list-unstyled list-inline footer-social-list mb-0">
                                        
                                        <li class="list-inline-item"><a href="{{$facebook_link}}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                        <li class="list-inline-item"><a href="{{$instagram_link}}" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                        <li class="list-inline-item"><a href="{{$youtube_link}}" target="_blank"><i class="fab fa-youtube"></i></a></li>
                                        <!--<li class="list-inline-item"><a href="{{$dribbble_link}}"><i class="fab fa-dribbble"></i></a></li>-->
                                        <!--<li class="list-inline-item"><a href="#"><i class="fab fa-github"></i></a></li>-->
                                    </ul>
                                 
                                </div>
                                   <div class="footer-single-col">
                                <div class="footer-single-col mb-4">
                                     <a href="https://apps.apple.com/in/app/vetan-wala/id6446106014" target="_blank" style="margin-left: -9px;"> <img width="140" src="{{asset('front/assets/img/apple.png')}}"/></a>
          <a href="https://play.google.com/store/apps/details?id=com.vetanwala" target="_blank"><img width="140" src="{{asset('front/assets/img/playstore.png')}}"/></a>
                                </div>
                            </div>
                              
                        </div>
                                <div class="col-md-3 col-lg-3 mt-4 mt-md-0 mt-lg-0">
                                    <div class="footer-single-col">
                                        <h3>Main Pages</h3>
                                        <ul class="list-unstyled footer-nav-list mb-lg-0">
                                            @if($setting->enable_register == true)
                                    <li class="nav-item">
                                        <a class="nav-link"
                                           href="{{ route('front.signup.index') }}">{{ $frontMenu->get_start }}</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{ route('front.feature') }}"  class="text-decoration-none">{{ $frontMenu->feature }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('front.pricing') }}"  class="text-decoration-none">{{ $frontMenu->price }}</a>
                                </li>
                                <li class="nav-item">
                                    @if(module_enabled('Subdomain'))
                                        <a href="{{ route('front.workspace') }}"
                                        class="text-decoration-none" >{{ $frontMenu->login }}</a>
                                    @else
                                        <a href="{{ route('login') }}"  class="text-decoration-none">{{ $frontMenu->login }}</a>
                                    @endif
                                </li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mt-4 mt-md-0 mt-lg-0">
                                    <div class="footer-single-col">
                                        <h3>OTHERS</h3>
                                        <ul class="list-unstyled footer-nav-list mb-lg-0">
                                            @foreach($footerSettings as $footerSetting)
                                @if($footerSetting->type != 'header')
                                    <li class="nav-item active"><a class="text-decoration-none"
                                                                   href="@if(!is_null($footerSetting->external_link)) {{ $footerSetting->external_link }} @else {{ route('front.page', $footerSetting->slug) }} @endif">{{ $footerSetting->name }}</a>
                                    </li>
                                @endif
                            @endforeach
                            <li class="nav-item">
                                <a class="text-decoration-none" href="{{ route('front.contact') }}">{{ $frontMenu->contact }}</a>
                            </li>
                            <li class="nav-item">
                                 <a class="nav-link" href="{{ route('front.blog') }}">Blog </a>
                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 mt-4 mt-md-0 mt-lg-0">
                                    <div class="footer-single-col">
                                        <h3>Contact Us</h3>
                                        <ul class="list-unstyled footer-nav-list mb-lg-0">
                                            <li class="d-flex justify-content-start align-items-center"><i class="fa fa-envelope"  aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<a class="mt-2" href="mailto:{{$frontDetail->email}}">{{ $frontDetail->email }}</a></li>
                                            <li class="mb-2 d-flex justify-content-start align-items-center"><i class="fa fa-phone"  aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<a class="mt-2" href="tel:{{$frontDetail->phone}}">{{ $frontDetail->phone }}</a></li>
                                            <li class="mb-2 d-flex justify-content-start align-items-start"><i class="fa fa-map-marker mt-2" aria-hidden="true">&nbsp;&nbsp;&nbsp;</i><a class="mt-0" href="#-">{{ $frontDetail->address }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--footer top end-->

            <!--footer bottom start-->
            <div class="footer-bottom footer-light py-2">
                <div class="container">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 col-lg-12 text-center">
                            <div class="copyright-text">
                                <p class="mb-lg-0 mb-md-0">&copy; Copyright © 2024. All Rights Reserved</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--footer bottom end-->
        </footer> <!--footer section end-->
        

