@extends('layouts.app')

@section('content')
@livewire('breadscrumb')

@php
    $img = $article->cover_image ?? null;
    $img = $img ? (preg_match('~^https?://~', $img) ? $img : asset('storage/' . $img)) : 'https://via.placeholder.com/800x517?text=No+Image';
    $date = \Illuminate\Support\Carbon::parse($article->published_at ?? $article->created_at)
        ->locale(app()->getLocale() ?? 'id')
        ->translatedFormat('d F Y');
    $author = $article->author->name ?? $article->author_name ?? 'Admin';

    // Get categories for sidebar
    $categories = \App\Models\ArticleCategory::withCount('articles')->get();

    // Get tags for sidebar
    $tags = \App\Models\Tag::take(5)->get();
@endphp

<div class="blog-page-container mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 order-2 order-lg-1">
                <!--=======  sidebar  =======-->

                <div class="sidebar-container shop-sidebar-container">

                    <!--=======  Search  =======-->
                    <div class="single-sidebar-widget mb-30">
                        <h3 class="sidebar-title">Search</h3>
                        <div class="sidebar-search-box">
                            <form action="{{ route('article.index') }}" method="GET">
                                <input type="search" name="q" placeholder="Search articles..." value="{{ request('q') }}">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                    <!--=======  End of Search  =======-->

                    <!--=======  Categories  =======-->
                    @if($categories->count() > 0)
                        <div class="single-sidebar-widget mb-30">
                            <h3 class="sidebar-title">Categories</h3>
                            <ul class="category-dropdown">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('article.index', ['category' => $category->slug]) }}">
                                            {{ $category->name }}
                                            @if($category->articles_count > 0)
                                                <span>({{ $category->articles_count }})</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!--=======  End of Categories  =======-->

                    <!--=======  Top Artikel  =======-->
                    <div class="single-sidebar-widget mb-30">
                        <h3 class="sidebar-title">Top Artikel</h3>
                        @if($topArticles->count() > 0)
                            <div class="block-container">
                                @foreach($topArticles as $top)
                                    @php
                                        $topImg = $top->cover_image ?? null;
                                        $topImg = $topImg ? (preg_match('~^https?://~', $topImg) ? $topImg : asset('storage/' . $topImg)) : 'https://via.placeholder.com/100x100?text=No+Image';
                                        $topDate = \Illuminate\Support\Carbon::parse($top->published_at ?? $top->created_at)
                                            ->locale(app()->getLocale() ?? 'id')
                                            ->translatedFormat('F d, Y');
                                    @endphp
                                    <div class="single-block d-flex">
                                        <div class="image">
                                            <a href="{{ route('article.show', $top->slug) }}">
                                                <img width="100" height="100" src="{{ $topImg }}" class="img-fluid" alt="{{ $top->title }}">
                                            </a>
                                        </div>
                                        <div class="content">
                                            <p>
                                                <a href="{{ route('article.show', $top->slug) }}">{{ \Illuminate\Support\Str::limit($top->title, 50) }}</a>
                                                <span>{{ strtoupper($topDate) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small mb-0">
                                <i class="fa fa-info-circle"></i>
                                Belum ada artikel unggulan saat ini.
                            </p>
                        @endif
                    </div>
                    <!--=======  End of Top Artikel  =======-->

                    <!--=======  Artikel Terbaru  =======-->
                    <div class="single-sidebar-widget mb-30">
                        <h3 class="sidebar-title">Artikel Terbaru</h3>
                        @if($latestArticles->count() > 0)
                            <div class="block-container">
                                @foreach($latestArticles as $latest)
                                    @php
                                        $latestImg = $latest->cover_image ?? null;
                                        $latestImg = $latestImg ? (preg_match('~^https?://~', $latestImg) ? $latestImg : asset('storage/' . $latestImg)) : 'https://via.placeholder.com/100x100?text=No+Image';
                                        $latestDate = \Illuminate\Support\Carbon::parse($latest->published_at ?? $latest->created_at)
                                            ->locale(app()->getLocale() ?? 'id')
                                            ->translatedFormat('F d, Y');
                                    @endphp
                                    <div class="single-block d-flex">
                                        <div class="image">
                                            <a href="{{ route('article.show', $latest->slug) }}">
                                                <img width="100" height="100" src="{{ $latestImg }}" class="img-fluid" alt="{{ $latest->title }}">
                                            </a>
                                        </div>
                                        <div class="content">
                                            <p>
                                                <a href="{{ route('article.show', $latest->slug) }}">{{ \Illuminate\Support\Str::limit($latest->title, 50) }}</a>
                                                <span>{{ strtoupper($latestDate) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small mb-0">
                                <i class="fa fa-info-circle"></i>
                                Belum ada artikel terbaru yang tersedia.
                            </p>
                        @endif
                    </div>
                    <!--=======  End of Artikel Terbaru  =======-->

                    <!--=======  Tags  =======-->
                    @if($tags->count() > 0)
                        <div class="single-sidebar-widget">
                            <h3 class="sidebar-title">Tags</h3>
                            <ul class="tag-container">
                                @foreach($tags as $tag)
                                    <li><a href="{{ route('article.index', ['tag' => $tag->slug]) }}">{{ $tag->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!--=======  End of Tags  =======-->

                </div>
                <!--=======  End of sidebar  =======-->
            </div>

            <div class="col-lg-9 order-1 order-lg-2">
                <!--=======  blog post container  =======-->

                <div class="blog-single-post-container mb-80">

                    <!--=======  post title  =======-->
                    <h3 class="post-title">{{ $article->title }}</h3>
                    <!--=======  End of post title  =======-->

                    <!--=======  Post meta  =======-->
                    <div class="post-meta">
                        <p>
                            <span><i class="fa fa-user-circle"></i> Posted By: </span>
                            <a href="#">{{ $author }}</a>
                            <span class="separator">/</span>
                            <span><i class="fa fa-calendar"></i> Posted On: <a href="#">{{ $date }}</a></span>
                        </p>
                    </div>
                    <!--=======  End of Post meta  =======-->

                    <!--=======  Post media  =======-->
                    <div class="single-blog-post-media mb-xs-20">
                        <div class="image">
                            <img width="800" height="517" src="{{ $img }}" class="img-fluid" alt="{{ $article->title }}">
                        </div>
                    </div>
                    <!--=======  End of Post media  =======-->

                    <!--=======  Post content  =======-->
                    <div class="post-content mb-40">
                        {!! $article->content ?? $article->body ?? '' !!}
                    </div>
                    <!--=======  End of Post content  =======-->

                    @if($article->tags && $article->tags->count() > 0)
                        <!--=======  Tags area  =======-->
                        <div class="tag-area mb-40">
                            <span>Tags: </span>
                            <ul>
                                @foreach($article->tags as $index => $tag)
                                    <li>
                                        <a href="{{ route('article.index', ['tag' => $tag->slug]) }}">{{ $tag->name }}</a>@if($index < $article->tags->count() - 1),@endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!--=======  End of Tags area  =======-->
                    @endif

                    <!--=======  Share post area  =======-->
                    <div class="social-share-buttons mb-40">
                        <h3>share this post</h3>
                        <ul>
                            <li><a class="twitter" href="https://twitter.com/intent/tweet?url={{ urlencode(route('article.show', $article->slug)) }}&text={{ urlencode($article->title) }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('article.show', $article->slug)) }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <li><a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('article.show', $article->slug)) }}&title={{ urlencode($article->title) }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            <li><a class="whatsapp" href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . route('article.show', $article->slug)) }}" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
                        </ul>
                    </div>
                    <!--=====  End of Share post area  ======-->

                    <!--=======  Artikel Terkait  =======-->
                    <div class="related-post-container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="related-post-title mb-30">ARTIKEL TERKAIT</h3>
                            </div>
                        </div>

                        @if($related->count() > 0)
                            <div class="row">
                                @foreach($related as $rel)
                                    @php
                                        $rimg = $rel->cover_image ?? null;
                                        $rimg = $rimg ? (preg_match('~^https?://~', $rimg) ? $rimg : asset('storage/' . $rimg)) : 'https://via.placeholder.com/800x517?text=No+Image';
                                        $rurl = route('article.show', $rel->slug);
                                        $rdate = \Illuminate\Support\Carbon::parse($rel->published_at ?? $rel->created_at)
                                            ->locale(app()->getLocale() ?? 'id')
                                            ->translatedFormat('F d, Y');
                                    @endphp
                                    <div class="col-lg-4 col-md-4 mb-30">
                                        <div class="single-related-post">
                                            <div class="image">
                                                <a href="{{ $rurl }}">
                                                    <img width="800" height="517" src="{{ $rimg }}" class="img-fluid" alt="{{ $rel->title }}">
                                                </a>
                                            </div>
                                            <div class="content">
                                                <h3 class="related-post-title">
                                                    <a href="{{ $rurl }}">{{ $rel->title }}</a>
                                                    <span>{{ $rdate }}</span>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 px-3" style="background:#f8f9fa; border-radius:8px;">
                                <i class="fa fa-file-text-o fa-2x text-muted" style="display:block; margin-bottom:10px;"></i>
                                <p class="text-muted mb-1">
                                    <strong>Tidak ada artikel terkait ditemukan.</strong>
                                </p>
                                <p class="text-muted small mb-2">
                                    Artikel ini belum memiliki artikel lain dalam kategori yang sama.
                                </p>
                                <a href="{{ route('article.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa fa-th-list"></i> Jelajahi Semua Artikel
                                </a>
                            </div>
                        @endif
                    </div>
                    <!--=======  End of Artikel Terkait  =======-->

                </div>
                <!--=======  End of blog post container  =======-->

            </div>
        </div>
    </div>
</div>
@endsection
