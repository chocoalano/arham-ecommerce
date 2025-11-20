@extends('layouts.app')

@section('content')
<div class="blog-page-container mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <!--=======  blog post container  =======-->

                <div class="blog-post-container mb-15">

                    @if(!empty($q))
                        <div class="alert alert-info mb-4">
                            <i class="fa fa-search"></i> Hasil pencarian untuk: <strong>"{{ $q }}"</strong>
                        </div>
                    @endif

                    <div class="row">
                        @forelse($articles as $article)
                            @php
                                $img = $article->cover_image ?? null;
                                $img = $img ? (preg_match('~^https?://~', $img) ? $img : asset('storage/' . $img)) : 'https://via.placeholder.com/800x517?text=No+Image';
                                $date = \Illuminate\Support\Carbon::parse($article->published_at ?? $article->created_at)
                                    ->locale(app()->getLocale() ?? 'id')
                                    ->translatedFormat('d F Y');
                                $author  = $article->author_name ?? 'Admin';
                                $excerpt = $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->content ?? $article->body ?? ''), 150);
                                $url     = route('article.show', $article->slug);
                            @endphp

                            <div class="col-lg-4 col-md-6">
                                <!--=======  single blog post  =======-->

                                <div class="single-blog-post mb-35">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="single-blog-post-media mb-20">
                                                <div class="image">
                                                    <a href="{{ $url }}">
                                                        <img width="800" height="517" src="{{ $img }}" class="img-fluid" alt="{{ $article->title }}" loading="lazy">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="single-blog-post-content">
                                                <h3 class="post-title">
                                                    <a href="{{ $url }}">{{ $article->title }}</a>
                                                </h3>
                                                <div class="post-meta">
                                                    <p>
                                                        <span><i class="fa fa-user-circle"></i> </span>
                                                        <a href="#">{{ $author }}</a>
                                                        <span class="separator">/</span>
                                                        <span><i class="fa fa-calendar"></i> <a href="#">{{ $date }}</a></span>
                                                    </p>
                                                </div>

                                                <p class="post-excerpt">
                                                    {{ $excerpt }}
                                                </p>
                                                <a href="{{ $url }}" class="blog-readmore-btn">continue <i class="fa fa-long-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--=======  End of single blog post  =======-->
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning text-center py-5">
                                    <i class="fa fa-info-circle fa-3x mb-3"></i>
                                    <h4>Belum Ada Artikel</h4>
                                    <p class="mb-0">Silakan cek kembali nanti untuk konten terbaru.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!--=======  End of blog post container  =======-->

                <!--=======  Pagination container  =======-->

                @if($articles->hasPages())
                    <div class="pagination-container pb-20 mb-md-80 mb-sm-80">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!--=======  pagination-content  =======-->

                                    <div class="pagination-content text-center">
                                        <ul>
                                            {{-- Previous Page Link --}}
                                            @if ($articles->onFirstPage())
                                                <li class="disabled"><span><i class="fa fa-angle-left"></i> Previous</span></li>
                                            @else
                                                <li><a href="{{ $articles->previousPageUrl() }}" rel="prev"><i class="fa fa-angle-left"></i> Previous</a></li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                                                @if ($page == $articles->currentPage())
                                                    <li><a class="active" href="#">{{ $page }}</a></li>
                                                @else
                                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($articles->hasMorePages())
                                                <li><a href="{{ $articles->nextPageUrl() }}" rel="next">Next <i class="fa fa-angle-right"></i></a></li>
                                            @else
                                                <li class="disabled"><span>Next <i class="fa fa-angle-right"></i></span></li>
                                            @endif
                                        </ul>
                                    </div>

                                    <!--=======  End of pagination-content  =======-->
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!--=======  End of Pagination container  =======-->
            </div>
        </div>
    </div>
</div>
@endsection
