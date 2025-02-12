<div class="product__item">
    
    <div class="product__item__pic set-bg" data-setbg="{{ $image }}">
        <!-- Label "New" -->
    

        <!-- Badge Diskon -->
        @if(isset($discounted_price) && $discounted_price > 0) <!-- Jika ada diskon -->
            <span class="discount-badge">{{ $discount_percentage }}%</span>
        @endif

        <!-- Badge Habis -->
        @if($stok <= 0) <!-- Jika stok nol -->
            <div class="label out-of-stock">Habis</div>
        @else if($stok > 0)
        <div class="label new">New</div>
        @endif

        <!-- Tombol Hover -->
        <ul class="product__hover">
            <li>
                <a href="{{ $image }}" class="image-popup">
                    <span class="arrow_expand"></span>
                </a>
            </li>
            <li>
                <a href="{{ $route }}" @if($stok <= 0) style="pointer-events: none; opacity: 0.5;" @endif>
                    <span><i class="fa fa-eye"></i></span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Informasi Produk -->
    <div class="product__item__text mt-2 mb-4">
        <h6>{{ $name }}</h6>
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
        <div class="rating">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
        </div>
        <div class="product__stock">
        <i class="fa fa-cubes"></i> <!-- Ikon stok -->
        <span>{{ $stok }} Unit</span> <!-- Jumlah stok -->
        <span style="margin-left: 10px;"> <!-- Menambahkan jarak -->
            <i class="fa fa-shopping-cart"></i> <!-- Ikon penjualan -->
            {{ $penjualan }} Terjual
        </span> <!-- Angka penjualan -->
    </div>
        <!-- <div class="product__actions">
            <a class="btn btn-warning mb-4 mt-2" href="{{ $route }}" @if($stok <= 0) style="pointer-events: none; opacity: 0.5;" @endif>
                <i class="fa fa-shopping-cart"></i> Add to Cart
            </a>
            <a class="btn btn-success mb-4 mt-2" href="{{ $route }}" @if($stok <= 0) style="pointer-events: none; opacity: 0.5;" @endif>
                <i class="fa fa-plus"></i> Buy Now
            </a>
</div> -->
    </div>
</div>