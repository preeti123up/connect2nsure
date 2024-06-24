@extends('super-admin.layouts.saas-app')
@section('content')

    <div class="main-wrapper">
        <!--blog details section start-->
        @include('super-admin.saas.section.breadcrumb')

        <section class="blog-details ptb-120">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-12 pe-lg-5">
                        <h1>{{$singleBlog->title}}</h1>
                        <div class="blog-details-wrap">
                            {!! $singleBlog->description !!}
                        </div>
                    </div>
                </div>
            </div>
          
        </section>
        <!--blog details section end-->

        <!--related blog start-->
        <section class="related-blog-list ptb-120 bg-light-subtle">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-4 col-md-12">
                        <div class="section-heading">
                            <h4 class="h5 text-primary">Related Blogs</h4>
                            <h3>More Latest News and Blog </h3>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12">
                        <div class="text-start text-lg-end mb-4 mb-lg-0 mb-xl-0">
                            <a href="{{route('front.blog')}}" class="btn btn-primary">View All Article</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                @forelse($blog as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-article rounded-custom my-3">
                            <a href="{{route('front.single-blog',['id'=>$item->id])}}" class="article-img">
                                <img src="{{asset('user-uploads/front/blog/'.$item->image)}}" alt="article" class="img-fluid">
                            </a>
                            <div class="article-content p-4">
                                <div class="article-category mb-4 d-block">
                                    <a href="javascript:;" class="d-inline-block">{{$item->title}}</a>
                                </div>
                                <?php
                                        $description = strip_tags($item->description);
                                        $words = explode(' ', $description);
                                        $excerpt = count($words) > 20 ? implode(' ', array_slice($words, 0, 20)) . '...' : $description;
                                    ?>
                                    <p>{!! $excerpt !!} <a href="{{ route('front.single-blog',['id' => $item->id])}}">Read more</a></p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p>No Record Found</p>
                    @endforelse
                  
                </div>
            </div>
        </section>
        <!--related blog end-->



    </div>

@endsection