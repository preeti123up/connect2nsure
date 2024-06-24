@extends('super-admin.layouts.saas-app')

@section('content')
    <!--main content wrapper start-->
    <div class="main-wrapper">
        @include('super-admin.saas.section.breadcrumb')

        <!--blog section start-->
        <section class="masonary-blog-section ptb-120">
            <div class="container">
                <div class="row">
                    @forelse($blog as $item)
                        <div class="col-lg-4 col-md-6">
                            <div class="single-article rounded-custom my-3">
                                <a href="{{ route('front.single-blog', ['id' => $item->id]) }}" class="article-img">
                                    <img src="{{ asset('user-uploads/front/blog/' . $item->image) }}" alt="article" class="img-fluid">
                                </a>
                                <div class="article-content p-3">
                                   
                                     <a href="{{ route('front.single-blog', ['id' => $item->id]) }}" class="d-inline-block">
                                         <h2 class="h5 article-title limit-2-line-text"> {{ $item->title }}</h2>
                                         </a>
                                    <?php
                                        $description = strip_tags($item->description);
                                        $words = explode(' ', $description);
                                        $excerpt = count($words) > 20 ? implode(' ', array_slice($words, 0, 20)) . '...' : $description;
                                    ?>
                                    <p>{!! $excerpt !!} <a href="{{ route('front.single-blog', ['id' => $item->id]) }}">Read more</a></p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <h1 class="text-center">No Record Found</1>
                    @endforelse
                </div>

                <!--pagination start-->
                @if ($blog->hasPages())
                    <div class="row justify-content-center align-items-center mt-5">
                        <div class="col-auto my-1">
                            @if ($blog->onFirstPage())
                                <span class="btn btn-soft-primary btn-sm disabled">Previous</span>
                            @else
                                <a href="{{ $blog->previousPageUrl() }}" class="btn btn-soft-primary btn-sm">Previous</a>
                            @endif
                        </div>

                        <div class="col-auto my-1">
                            <nav>
                                <ul class="pagination rounded mb-0">
                                    @foreach ($blog->links()->elements[0] as $page => $url)
                                        @if ($page == $blog->currentPage())
                                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </nav>
                        </div>

                        <div class="col-auto my-1">
                            @if ($blog->hasMorePages())
                                <a href="{{ $blog->nextPageUrl() }}" class="btn btn-soft-primary btn-sm">Next</a>
                            @else
                                <span class="btn btn-soft-primary btn-sm disabled">Next</span>
                            @endif
                        </div>
                    </div>
                @endif
                <!--pagination end-->
            </div>
        </section>
        <!--blog section end-->
    </div>
@endsection
