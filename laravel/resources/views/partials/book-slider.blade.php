@if(isset($items) && $items->isNotEmpty())
    <section class="container page-container-card">
        <h2 class="mb-3">{{ $title ?? 'Knihy' }}</h2>

        <div class="slider-wrapper" style="position: relative;">
            <button class="carousel-control-prev" type="button" onclick="scrollSlider(this, -1)">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <div class="book-slider">
                @foreach($items as $item)
                    @php
                        $productItem = $item->product ?? $item;
                        $sliderImage = $productItem?->images?->first()?->image_path ?? 'images/default.webp';
                        $authors = $item->authors ?? $productItem?->book?->authors ?? collect();
                    @endphp

                    @if($productItem && $productItem->id)
                        <a href="{{ route('products.show', $item['product_id'])}}" class="text-decoration-none text-dark">
                            <div class="cardd">
                                <div class="card-img-placeholder">
                                    <img
                                        src="{{ asset($sliderImage) }}"
                                        class="cardd-img"
                                        alt="{{ $productItem->name ?? 'Kniha' }}"
                                    >
                                </div>

                                <div class="cardd-body">
                                    <h6 class="cardd-body-title">
                                        {{ $productItem->name ?? 'Bez názvu' }}
                                    </h6>

                                    <p class="cardd-body-author">
                                        {{ $authors->pluck('full_name')->implode(', ') ?: 'Neznámy autor' }}
                                    </p>

                                    <p class="cardd-body-price">
                                        {{ number_format($productItem->price ?? 0, 2, ',', ' ') }} €
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>

            <button class="carousel-control-next" type="button" onclick="scrollSlider(this, 1)">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>
@endif