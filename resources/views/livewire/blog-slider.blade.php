{{-- resources/views/livewire/blog-slider.blade.php --}}
<div class="blog-slider-section mt-100 mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-40">
                <div class="section-title">
                    <h2>Postingan <span>Artikel</span> Kami</h2>
                    <p>Maksimalkan daya tarik diri Anda! Lihat cara terbaik untuk menonjolkan bagian yang paling menarik pada diri anda dengan dukungan busana yang pas untuk anda gunakan.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                {{-- Jika memakai plugin slider (Swiper/Slick), gunakan wire:ignore agar tidak re-init saat re-render
                --}}
                <div class="blog-post-slider-container ptk-slider" @if(!app()->runningUnitTests()) wire:ignore @endif>
                    @forelse($posts as $post)
                        <div class="col" wire:key="blog-post-{{ $post['id'] }}">
                            <div class="single-slider-blog-post">
                                <div class="image">
                                    <a href="{{ $post['url'] }}">
                                        <img width="800" height="517" src="{{ $post['image'] }}" class="img-fluid"
                                            alt="{{ $post['title'] }}" loading="lazy">
                                    </a>
                                </div>
                                <div class="content">
                                    <p class="blog-title">
                                        <a href="{{ $post['url'] }}">
                                            {{ \Illuminate\Support\Str::limit($post['title'], 80) }}
                                        </a>
                                    </p>

                                    {{-- Jika ingin menampilkan excerpt singkat, buka komentar di bawah --}}
                                    <p class="text-muted mb-2">{{ \Illuminate\Support\Str::limit($post['content'], 110)
                                        }}</p>

                                    <a href="{{ $post['url'] }}" class="readmore-btn">Lihat</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                Belum ada artikel untuk ditampilkan.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
