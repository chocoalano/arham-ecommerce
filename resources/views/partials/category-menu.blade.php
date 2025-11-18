<!--=======  category menu  =======-->
<div class="hero-side-category">
    <!-- Category Toggle Wrap -->
    <div class="category-toggle-wrap">
        <!-- Category Toggle -->
        <button class="category-toggle">
            <span class="lnr lnr-text-align-left"></span> Pilih Kategori
            <span class="lnr lnr-chevron-down"></span>
        </button>
    </div>

    <!-- Category Menu -->
    <nav class="category-menu">
        <ul>
            @foreach($globalCategories->take(7) as $category)
                @if($category->children->isNotEmpty())
                    <li class="menu-item-has-children">
                        <a href="{{ route('catalog.index', ['cat[0]' => $category->slug]) }}">{{ $category->name }}</a>

                        <!-- Mega Category Menu Start -->
                        <ul class="category-mega-menu">
                            @foreach($category->children->chunk(ceil($category->children->count() / 3)) as $chunk)
                                <li class="menu-item-has-children">
                                    @foreach($chunk as $child)
                                        @if($loop->first)
                                            <a class="megamenu-head" href="{{ route('catalog.index', ['cat[0]' => $child->slug]) }}">
                                                {{ $child->name }}
                                            </a>
                                            <ul>
                                        @else
                                            <li><a href="{{ route('catalog.index', ['cat[0]' => $child->slug]) }}">{{ $child->name }}</a></li>
                                        @endif
                                    @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Mega Category Menu End -->
                    </li>
                @else
                    <li>
                        <a href="{{ route('catalog.index', ['cat[0]' => $category->slug]) }}">{{ $category->name }}</a>
                    </li>
                @endif
            @endforeach

            @if($globalCategories->count() > 7)
                @foreach($globalCategories->skip(7) as $category)
                    <li class="hidden">
                        <a href="{{ route('catalog.index', ['cat[0]' => $category->slug]) }}">{{ $category->name }}</a>
                    </li>
                @endforeach
                <li>
                    <a href="#" id="more-btn">
                        <span class="lnr lnr-plus-circle"></span> Lebih Banyak Kategori
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>
<!--=======  End of category menu =======-->
