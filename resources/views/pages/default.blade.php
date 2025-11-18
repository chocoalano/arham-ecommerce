@extends('layouts.app')

@section('title', $page->title)

@if(isset($page->meta['description']))
@section('meta_description', $page->meta['description'])
@endif

@push('styles')
<style>
    .page-section {
        padding: 50px 0;
    }
    .page-content {
        line-height: 1.8;
    }
    .page-content h1,
    .page-content h2,
    .page-content h3,
    .page-content h4 {
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .page-content p {
        margin-bottom: 15px;
        color: #666;
    }
    .page-content ul,
    .page-content ol {
        margin-bottom: 20px;
        padding-left: 30px;
    }
    .page-content li {
        margin-bottom: 8px;
    }
</style>
@endpush

@section('content')
@livewire('breadscrumb')

<div class="page-section mb-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">{{ $page->title }}</h1>
                <div class="page-content">
                    {!! $page->content !!}
                </div>

                @if(isset($page->sections) && !empty($page->sections))
                    @foreach($page->sections as $sectionKey => $sectionData)
                        @if($sectionKey === 'faqs' && is_array($sectionData))
                            <!-- FAQ Section -->
                            <div class="faq-section mt-5">
                                <div class="accordion" id="faqAccordion">
                                    @foreach($sectionData as $index => $faq)
                                    <div class="accordion-item mb-3">
                                        <h3 class="accordion-header">
                                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#faq{{ $index }}">
                                                {{ $faq['question'] }}
                                            </button>
                                        </h3>
                                        <div id="faq{{ $index }}"
                                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                             data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                {{ $faq['answer'] }}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($sectionKey === 'contact_info' && is_array($sectionData))
                            <!-- Contact Info Section -->
                            <div class="contact-info-section mt-5">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h4>Contact Information</h4>
                                        @if(isset($sectionData['address']))
                                        <p><strong>Address:</strong><br>{{ $sectionData['address'] }}</p>
                                        @endif
                                        @if(isset($sectionData['phone']))
                                        <p><strong>Phone:</strong><br>{{ $sectionData['phone'] }}</p>
                                        @endif
                                        @if(isset($sectionData['email']))
                                        <p><strong>Email:</strong><br>{{ $sectionData['email'] }}</p>
                                        @endif
                                        @if(isset($sectionData['hours']))
                                        <p><strong>Business Hours:</strong><br>{{ $sectionData['hours'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
