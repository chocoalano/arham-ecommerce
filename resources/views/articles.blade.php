@extends('layouts.app')

@section('content')
<div class="blog-page-container mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <!--=======  blog post container  =======-->
                <div class="blog-post-container mb-15">

                    @if(!empty($q))
                        <div class="mb-3">
                            <em>Hasil pencarian untuk: "{{ $q }}"</em>
                        </div>
                    @endif

                    <div class="row">
                        @forelse($articles as $article)
                            @php
                                $img = $article->cover_image ?? null;
                                $img = $img ? (preg_match('~^https?://~', $img) ? $img : asset($img)) : 'https://via.placeholder.com/800x517?text=No+Image';
                                $date = \Illuminate\Support\Carbon::parse($article->published_at ?? $article->created_at)
                                    ->locale(app()->getLocale() ?? 'id')
                                    ->translatedFormat('d F Y');
                                $author  = $article->author_name ?? 'Admin';
                                $excerpt = $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->content ?? $article->body ?? ''), 180);
                                $url     = route('article.show', $article->slug);
                            @endphp

                            <div class="col-lg-4 col-md-6">
                                <!--=======  single blog post  =======-->
                                <div class="single-blog-post mb-35">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="single-blog-post-media mb-20">
                                                <div class="image">
                                                    <a href="{{ $url }}"><img width="800" height="517" src="{{ $img }}" class="img-fluid" alt="{{ $article->title }}"></a>
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
                                                <a href="{{ $url }}" class="blog-readmore-btn">Baca selengkapnya <i class="fa fa-long-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--=======  End of single blog post  =======-->
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning">Belum ada artikel.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <!--=======  End of blog post container  =======-->

                <!--=======  Pagination container  =======-->
                <div class="pagination-container pb-20 mb-md-80 mb-sm-80">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <!--=======  pagination-content  =======-->
                                <div class="pagination-content text-center">
                                    {{-- Laravel akan merender <nav><ul>…</ul></nav> yang umumnya kompatibel dengan styling tema --}}
                                    {{ $articles->onEachSide(1)->links() }}
                                </div>
                                <!--=======  End of pagination-content  =======-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--=======  End of Pagination container  =======-->
            </div>
        </div>
    </div>
</div>
@endsection
