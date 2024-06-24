
<div class="price-feature pricing-action-info pt-0">
                  <div class="col-lg-12 text-center">
                    <ul class="nav nav-pills mb-4 pricing-tab-list" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Monthly
                            <br>
                             <badge style="
                                    font-size: 14px;
                                    font-weight:500;
                            ">Cancel Anytime</badge>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" class="">Yearly
                          <br>
                           <badge  style="
                                    font-size: 14px;
                                    font-weight:500;
                            ">Save Upto 20%</badge>
                            </button>
                        </li>
                    </ul>
                      
                  </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade active show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                           <div class="col-md-12">
                            <div class="row">
                            <?php $n = 1; ?>
                            
                            @foreach ($packages as $key => $item)
                            @if ($item->monthly_status == '1')
                                <div class="col-lg-3 col-md-4">
                                <div class="position-relative single-pricing-wrap rounded-custom {{$item->is_recommended == 1 ? 'pricing-h' : 'bg-white ' }} custom-shadow p-4 mb-4 mb-lg-0">

                                        <div class="pricing-header mb-32">
                                            <h3 class="package-name {{ $item->is_recommended != 1  ? 'text-primary' : 'text-warning' }}  d-block">{{$item->name}}</h3>
                                       <h6 class="fs-12 {{$item->is_recommended == 1 ? 'pricing-heading' : '' }}"><span>Upto&nbsp;{{$item->max_employees}}&nbsp;Employees</span></h6>
                                            <h4 class="display-6 m-0 fw-semi-bold {{$item->is_recommended == 1 ? 'pricing-heading' : '' }}">₹{{$item->monthly_price}}</h4>
                                           <p class="pb-3 price-mrp {{$item->is_recommended == 1 ? 'pricing-heading' : '' }}"><span>@if($item->monthly_versional_price!=null)<strike>₹{{$item->monthly_versional_price}}</strike></span>@endif<span>/Monthly</span></p>
                                             <?php  $roundValue=round(($item->monthly_price)/($item->max_employees)) ?>
                                            <h4 class="fw-semi-bold {{$item->is_recommended == 1 ? 'pricing-heading' : '' }}">&#8377;{{$roundValue}}<span>/Emp</span><span>/Month</span></h4>
                                             <a href="{{ route('front.signup.index') }}" class="{{ ($item->is_recommended == 1 ?' btn gradient-button  ' : 'btn btn-outline-primary') }} mt-2 btn-sm w-100 ">Buy Now</a>

                                        </div>
                                        <div class="pricing-info mb-4">
                                            <ul class="pricing-feature-list list-unstyled">
                                                @php
                                                    $packageModules = (array) json_decode($item->module_in_package);
                                                @endphp
                                                   <?php $j=1; ?>
                                            @foreach ($packageFeatures as $i=>$packageFeature)
                                         

                                    @if (in_array($packageFeature, $activeModule))
                                        @if (in_array($packageFeature, $packageModules))

                                        @if($j<=8)
                                        <li class="{{ $item->is_recommended == 1 ? 'pricing-heading' : 'bg-white ' }}">
                                        <i class="fas fa-check fa-5  {{ $item->is_recommended != 1 ? 'text-primary' : 'text-warning ' }} me-2"></i>{{ __('modules.module.' . $packageFeature) }}
                                        </li>
                                        @else
                                        <li class="hidden-module-{{$n+8}} {{ $item->is_recommended == 1 ? 'pricing-heading' : 'bg-white ' }}" style="display:none">
                                        <i class="fas fa-check fa-5  {{ $item->is_recommended != 1 ? 'text-primary' : 'text-warning ' }} me-2"></i>{{ __('modules.module.' . $packageFeature) }}
                                        </li>
                                        @endif
                                        <?php $j++; ?>

                                        @endif
                                    @endif

                                @endforeach
                                            </ul>
                                               @if($j>8)
                                            <a href="#" class="read-more-modules-btn" data-target="{{$n+8}}" style="border: none; color: {{$item->is_recommended != 1 ? 'black' : 'white'}}; cursor: pointer;">Read More</a>
                                        @endif
                                        </div>

                                        <!--<a href="{{ route('front.signup.index') }}" class="{{ ($item->is_recommended == 1 ?' btn btn-primary  ' : 'btn btn-outline-primary') }} mt-2 btn-sm ">Buy Now</a>-->
                                     
                                        <!--pattern start-->
                                        <div class="dot-shape-bg position-absolute z--1 left--40 bottom--40">
                                            <img src="front/assets/img/shape/dot-big-square.svg" alt="shape">
                                        </div>
                                        <!--pattern end-->
                                    </div>
                                </div>
                                <?php $n++; ?>

                                @endif
                                @endforeach
                          
                              
                              
                            </div>
                           </div>
                        </div>

                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-home-tab">
                           <div class="col-md-12">
                            <div class="row">
                                <?php $m = 1;?>
                            @foreach ($packages as $key => $item)
                            @if ($item->annual_status == '1')
                                <div class="col-lg-3 col-md-4">
                                    <div class="position-relative single-pricing-wrap rounded-custom  {{ $item->is_recommended == 1 ? 'pricing-h' : 'bg-white ' }} custom-shadow p-4 mb-4 mb-lg-0">

                                        <div class="pricing-header mb-32">
                                            <h3 class="package-name {{ $item->is_recommended != 1 ? 'text-primary' : 'text-warning' }} d-block">{{$item->name}}</h3>
                                                                                       <h6 class="fs-12 {{$item->is_recommended == 1 ? 'pricing-heading' : '' }}"> <span>Upto&nbsp;{{$item->max_employees}}&nbsp;Employees</span></h6>

                                             <?php  $annualPrice=round(($item->annual_price)/12);
                                             $versionalPrice=round(($item->annual_versional_price)/12);
                                             
                                             ?>

                                            <h4 class="display-6 m-0 fw-semi-bold {{$item->is_recommended == 1 ? 'pricing-heading' : '' }}">₹{{$annualPrice}}</h4>
                                            <p class="pb-3 price-mrp {{$item->is_recommended == 1 ? 'pricing-heading' : '' }}"><span>@if($item->annual_versional_price!=null)<strike>₹{{$versionalPrice}}</strike></span>@endif<span>/Monthly</span></p>
                                             <?php  $roundValue=round((($item->annual_price)/($item->max_employees))/12) ?>
                                            <h4 class="fw-semi-bold {{$item->is_recommended == 1 ? 'pricing-heading' : '' }}">&#8377;{{$roundValue}}<span>/Emp</span><span>/Month</span></h4>
                                           <a href="{{ route('front.signup.index') }}" class="{{ ($item->is_recommended == 1 ?' btn gradient-button  ' : 'btn btn-outline-primary') }} mt-2 btn-sm w-100 ">Buy Now</a>

                                        </div>
                                        <div class="pricing-info mb-4">
                                            <ul class="pricing-feature-list list-unstyled">
                                                @php
                                                    $packageModules = (array) json_decode($item->module_in_package);
                                                @endphp
                                               <?php $k=1; ?>

                                            @foreach ($packageFeatures as $i=>$packageFeature)
                                    @if (in_array($packageFeature, $activeModule))
                                        @if (in_array($packageFeature, $packageModules))
                                        
                                        @if($k<=8)
                                        <li class="{{ $item->is_recommended == 1 ? 'pricing-heading' : 'bg-white ' }}">
                                        <i class="fas fa-check fa-5  {{$item->is_recommended != 1 ? 'text-primary' : 'text-warning ' }} me-2"></i>{{ __('modules.module.' . $packageFeature) }}
                                        </li>
                                        @else
                                        <li class="hidden-module-{{$m+8}} {{ $item->is_recommended == 1 ? 'pricing-heading' : 'bg-white ' }}" style="display:none">
                                        <i class="fas fa-check fa-5  {{ $item->is_recommended != 1 ? 'text-primary' : 'text-warning ' }} me-2"></i>{{ __('modules.module.' . $packageFeature) }}
                                        </li>
                                        @endif
                                        <?php $k++; ?>
                                        @endif
                                    @endif
                                @endforeach
                                            </ul>
                                             @if($k>8)
                                            <a href="#" class="read-more-modules-btn" data-target="{{$m+8}}" style="border: none; color: {{$item->is_recommended != 1 ? 'black' : 'white'}}; cursor: pointer;">Read More</a>
                                        @endif
                                        </div>


                                        <!--<a href="{{ route('front.signup.index') }}" class="{{ ($item->is_recommended == 1 ?' btn btn-primary btn-sm  ' : 'btn btn-outline-primary') }} mt-2 btn-sm ">Buy Now</a>-->
                                        <!--pattern start-->
                                        <div class="dot-shape-bg position-absolute z--1 left--40 bottom--40">
                                            <img src="front/assets/img/shape/dot-big-square.svg" alt="shape">
                                        </div>
                                        <!--pattern end-->
                                    </div>
                                </div>
                                @endif
                                <?php $m++ ?>
                                @endforeach
                          
                              
                              
                            </div>
                           </div>
                        </div>
                    </div>
</div>