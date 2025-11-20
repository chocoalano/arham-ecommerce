@extends('layouts.app')

@section('content')
@livewire('breadscrumb')
<div class="blog-page-container mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <article class="single-blog-post mb-35">
                    <div class="row">
                        @php
                            $img = $article->cover_image ?? null;
                            $img = $img ? (preg_match('~^https?://~', $img) ? $img : asset($img)) : 'https://via.placeholder.com/1200x600?text=No+Image';
                            $date = \Illuminate\Support\Carbon::parse($article->published_at ?? $article->created_at)
                                ->locale(app()->getLocale() ?? 'id')
                                ->translatedFormat('d F Y');
                            $author = $article->author_name ?? 'Admin';
                        @endphp

                        <div class="col-lg-12">
                            <div class="single-blog-post-media mb-20">
                                <div class="image text-center">
                                    <img src="{{ $img }}" class="img-fluid" alt="{{ $article->title }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="single-blog-post-content">
                                <h1 class="post-title mb-10">{{ $article->title }}</h1>
                                <div class="post-meta mb-20">
                                    <p>
                                        <span><i class="fa fa-user-circle"></i> </span>
                                        <a href="#">{{ $author }}</a>
                                        <span class="separator">/</span>
                                        <span><i class="fa fa-calendar"></i> <a href="#">{{ $date }}</a></span>
                                    </p>
                                </div>

                                <div class="post-body">
                                    {!! $article->content ?? $article->body ?? '' !!}
                                </div>

                                <div class="mt-30">
                                    <a href="{{ url()->previous() }}" class="blog-readmore-btn"><i class="fa fa-long-arrow-left"></i> Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                @if(!empty($related) && $related->count())
                    <div class="blog-post-container mb-15">
                        <h3 class="mb-20">Artikel terkait</h3>
                        <div class="row">
                            @foreach($related as $rel)
                                @php
                                    $rimg = $rel->cover_image ?? null;
                                    $rimg = $rimg ? (preg_match('~^https?://~', $rimg) ? $rimg : asset($rimg)) : 'https://via.placeholder.com/800x517?text=No+Image';
                                    $rurl = route('article.show', $rel->slug);
                                @endphp
                                <div class="col-lg-4 col-md-6">
                                    <div class="single-blog-post mb-35">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="single-blog-post-media mb-20">
                                                    <div class="image">
                                                        <a href="{{ $rurl }}"><img width="800" height="517" src="{{ $rimg }}" class="img-fluid" alt="{{ $rel->title }}"></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="single-blog-post-content">
                                                    <h3 class="post-title"><a href="{{ $rurl }}">{{ $rel->title }}</a></h3>
                                                    <a href="{{ $rurl }}" class="blog-readmore-btn">Baca selengkapnya <i class="fa fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
