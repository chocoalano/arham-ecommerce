@extends('layouts.app')

@section('title', 'Tentang Kami')

@push('styles')
<style>
    .about-section {
        padding-top: 30px;
    }
    .about-image img {
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .about-content h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }
    .about-content h1 span {
        color: #ff6b6b;
    }
    .about-content h4 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .about-content p {
        line-height: 1.8;
        color: #666;
    }
    .banner {
        overflow: hidden;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }
    .banner:hover {
        transform: translateY(-5px);
    }
    .banner img {
        transition: transform 0.3s ease;
    }
    .banner:hover img {
        transform: scale(1.05);
    }
    .about-mission-vission-goal h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #333;
    }
    .about-mission-vission-goal p {
        line-height: 1.8;
        color: #666;
    }
    .about-section-title h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #333;
    }
    .about-section-title p {
        color: #666;
        font-size: 1rem;
    }
    .about-feature h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }
    .about-feature p {
        color: #666;
        line-height: 1.6;
        font-size: 0.95rem;
    }
    .mb-sm-30 {
        margin-bottom: 30px;
    }
    @media (max-width: 767px) {
        .about-content h1 {
            font-size: 2rem;
        }
        .about-section-title h3 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
@livewire('breadscrumb')

<!-- About Section -->
<div class="about-section mb-80">
    <div class="container">

        <div class="row row-30">

            <!-- About Image -->
            <div class="about-image col-lg-6 mb-80">
                <img class="img-fluid" width="800" height="517"
                     src="{{ asset($page->sections['hero']['image'] ?? 'images/banners/about-banner.webp') }}"
                     alt="{{ $page->title }}">
            </div>

            <!-- About Content -->
            <div class="about-content col-lg-6">
                <div class="row">
                    <div class="col-12 mb-50">
                        <h1>{!! $page->sections['hero']['title'] ?? 'WELCOME TO <span>ARHAM E-COMMERCE.</span>' !!}</h1>
                        <p>{{ $page->sections['hero']['description'] ?? $page->content }}</p>
                    </div>

                    @if(isset($page->sections['award']))
                    <div class="col-12 mb-50">
                        <h4>{{ $page->sections['award']['title'] }}</h4>
                        <p>{{ $page->sections['award']['description'] }}</p>
                    </div>
                    @endif

                </div>
            </div>

        </div>

        @if(isset($page->sections['banners']))
        <div class="row row-10 mb-80">
            @foreach($page->sections['banners'] as $banner)
            <!-- Banner -->
            <div class="col-md-4 col-12 mb-sm-30">
                <div class="banner">
                    <a href="{{ $banner['link'] ?? route('catalog.index') }}">
                        <img class="img-fluid" width="350" height="230"
                             src="{{ asset($banner['image']) }}"
                             alt="Banner">
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Mission, Vission & Goal -->
        <div class="about-mission-vission-goal row row-20 mb-80">

            @if(isset($page->sections['vision']))
            <div class="col-lg-4 col-md-6 col-12 mb-sm-30">
                <h3>{{ $page->sections['vision']['title'] }}</h3>
                <p>{{ $page->sections['vision']['description'] }}</p>
            </div>
            @endif

            @if(isset($page->sections['mission']))
            <div class="col-lg-4 col-md-6 col-12 mb-sm-30">
                <h3>{{ $page->sections['mission']['title'] }}</h3>
                <p>{{ $page->sections['mission']['description'] }}</p>
            </div>
            @endif

            @if(isset($page->sections['goal']))
            <div class="col-lg-4 col-md-6 col-12 mb-sm-30">
                <h3>{{ $page->sections['goal']['title'] }}</h3>
                <p>{{ $page->sections['goal']['description'] }}</p>
            </div>
            @endif

        </div>

        @if(isset($page->sections['why_choose']))
        <div class="row mb-30">

            <!-- About Section Title -->
            <div class="about-section-title col-12 mb-50">
                <h3>{!! nl2br(e($page->sections['why_choose']['title'])) !!}</h3>
                <p>{{ $page->sections['why_choose']['description'] }}</p>
            </div>

            <!-- About Feature -->
            <div class="about-feature col-md-7 col-12 mb-sm-30">
                <div class="row">
                    @foreach($page->sections['why_choose']['features'] as $feature)
                    <div class="col-md-6 col-12 mb-30 {{ $loop->last ? 'mb-sm-0' : '' }}">
                        <h4>{{ $feature['title'] }}</h4>
                        <p>{{ $feature['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- About Feature Banner -->
            <div class="about-feature-banner col-md-5 col-12">
                <div class="banner">
                    <a href="{{ route('catalog.index') }}">
                        <img class="img-fluid" width="255" height="226"
                             src="{{ asset($page->sections['why_choose']['banner']) }}"
                             alt="Banner">
                    </a>
                </div>
            </div>

        </div>
        @endif

    </div>
</div>
@endsection
