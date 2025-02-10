<div class="product__item">
    <div class="product__item__pic set-bg" data-setbg="{{ $image }}">
        <!-- Label "New" -->
        <div class="label new">New</div>

        <!-- Badge Diskon -->
        @if(isset($discounted_price) && $discounted_price > 0) <!-- Jika ada diskon -->
            <span class="discount-badge">{{ $discount_percentage }}%</span>
        @endif

        <!-- Tombol Hover -->
        <ul class="product__hover">
            <li>
                <a href="{{ $image }}" class="image-popup">
                    <span class="arrow_expand"></span>
                </a>
            </li>
            <li>
                <a href="{{ $route }}">
                    <span><i class="fa fa-eye"></i></span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Informasi Produk -->
    <div class="product__item__text">
        <h6><a href="{{ $route }}">{{ $name }}</a></h6>
        <div class="rating">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
        </div>
        <div class="product__price">
            @if(isset($discounted_price) && $discounted_price > 0) <!-- Jika ada diskon -->
                <span class="harga" style="text-decoration: line-through; color: #999; margin-right: 10px;">
                    Rp {{ number_format($price, 0, ',', '.') }}
                </span>
                <span class="hargadiskon" style="color: #ff0000; font-weight: bold;">
                    Rp {{ number_format($discounted_price, 0, ',', '.') }}
                </span>
            @else <!-- Jika tidak ada diskon -->
                <span>Rp {{ number_format($price, 0, ',', '.') }}</span>
            @endif
        </div>
    </div>
</div>