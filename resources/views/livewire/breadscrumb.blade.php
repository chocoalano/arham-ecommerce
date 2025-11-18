<div class="breadcrumb-area breadcrumb-bg pt-85 pb-85 mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-container">
                    {{-- Daftar Breadcrumb --}}
                    <ul>
                        @foreach ($breadcrumbs as $index => $breadcrumb)
                            @if ($breadcrumb['active'])
                                {{-- Item Terakhir (Aktif): Tidak memiliki link dan separator --}}
                                <li class="active">
                                    {{ $breadcrumb['title'] }}
                                </li>
                            @else
                                {{-- Item Navigasi: Memiliki link dan separator --}}
                                <li>
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                    <span class="separator">/</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
