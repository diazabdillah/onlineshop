<div class="product__item">
    
    <div class="product__item__pic set-bg" data-setbg="{{ $image }}">
        <!-- Label "New" -->
    

        <!-- Badge Diskon -->
        @if(isset($discounted_price) && $discounted_price > 0) <!-- Jika ada diskon -->
            <span class="discount-badge">{{ $discount_percentage }}%</span>
        @endif

        <!-- Badge Habis -->
        @if(isset($stok) && $stok <= 0) <!-- Jika stok nol -->
        <div class="label out-of-stock">Habis</div>
    @elseif(isset($stok) && $stok > 0)
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
        @php 
            $averageRating = $averageRating ?? 0;
            $totalUsers = $totalUsers ?? 0;
            $ratenum = number_format($averageRating) > 0 ? number_format($averageRating) : 0; 
            $totalUsersDisplay = $totalUsers > 0 ? $totalUsers : 0; 
        @endphp
        <div class="rating">
                        @for($i = 1; $i <= $ratenum; $i++)
                        <i class="fa fa-star checked" style="color: yellow;"></i> <!-- Bintang terisi berwarna kuning -->
                        @endfor
                        @for($j=$ratenum+1; $j<= 5; $j++)
                        <i class="fa fa-star" style="color: gray;"></i> <!-- Bintang tidak terisi berwarna abu-abu -->
                        @endfor
                        <span>{{ $averageRating > 0 ? $averageRating : 0 }}</span>
                        <span>({{ $totalUsersDisplay }} reviews)</span>
                    </div>
        <div class="product__stock">
        <i class="fa fa-cubes"></i>
        <span>{{ $stok ? $stok : '0' }} Unit</span> 
        <span style="margin-left: 10px;"> 
            <i class="fa fa-shopping-cart"></i> 
            {{ $penjualan ? $penjualan : '0' }} Terjual
        </span>
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