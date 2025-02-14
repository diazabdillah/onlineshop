@extends('layouts.frontend.app')
@section('content')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <a href="">{{ $data['product']->Category->name }}</a>
                        <span>{{ $data['product']->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product__details__pic">
                        <div class="product__details__slider__content">
                            <div class="product__details__pic__slider owl-carousel">
                                <img data-hash="product-1" class="product__big__img" src="{{ asset($data['product']->thumbnails_path) }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="product__details__text">
                        <h3>{{ $data['product']->name }} <span>Kategori: {{ $data['product']->Category->name }}</span></h3>
                       @php $ratenum = number_format($averageRating) @endphp
                       <div class="rating">
                        @for($i = 1; $i <= $ratenum; $i++)
                        <i class="fa fa-star checked" style="color: yellow;"></i> <!-- Bintang terisi berwarna kuning -->
                        @endfor
                        @for($j=$ratenum+1; $j<= 5; $j++)
                        <i class="fa fa-star" style="color: gray;"></i> <!-- Bintang tidak terisi berwarna abu-abu -->
                        @endfor
                        <span>{{ $averageRating }}</span>
                        <span>({{$totalUsers}} riviews)</span>
                    </div>
                        <form action="{{ route('cart.store') }}" method="POST">
                        <div class="product__details__price">
                            @if ($data['product']->discounted_price)
                                Rp {{ number_format($data['product']->discounted_price, 0, ',', '.') }}
                                <span>Rp {{ number_format($data['product']->price, 0, ',', '.') }}</span>
                            @else
                                Rp {{ number_format($data['product']->price, 0, ',', '.') }}
                            @endif
                        </div>
                        @csrf
                        <div class="product__details__button">
                            <div class="quantity">
                                <span>Jumlah:</span>
                                <div class="pro-qty">
                                    <input type="text" name="cart_qty" value="1">
                                </div>
                                <input type="hidden" name="cart_product_id" value="{{ $data['product']->id }}">
                            </div>
                            <button type="submit" class="cart-btn"><span class="icon_bag_alt"></span> Tambah Ke Keranjang</button>
                        </div>
                        <div class="product__details__widget">
                        </form>
                            <ul>
                                <li>
                                    <span>Berat : </span>
                                    <p>{{ $data['product']->weight }} Gram</p>
                                </li>
                                <li>
                                    <span>Stok : </span>
                                    <p>{{ $data['product']->stok }} Unit</p> <!-- Menambahkan informasi stok -->
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Deskripsi Produk</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <h6>Deskripsi Produk</h6>
                                {!! $data['product']->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="related__title">
                        <h5>Produk Lainnya</h5>
                    </div>
                </div>
               @foreach ($data['product_related'] as $product_related)
               <div class="col-lg-3 col-md-4 col-sm-6">
                @component('components.frontend.product-card')
                @slot('image', asset('storage/' . $product_related->thumbnails))
                @slot('route', route('product.show', ['categoriSlug' => $product_related->Category->slug, 'productSlug' =>
                    $product_related->slug]))
                    @slot('name', $product_related->name)
                    @slot('price', $product_related->price)
                    @slot('discounted_price', $product_related->discounted_price ?? null) <!-- Harga diskon -->
                    @slot('discount_percentage', $product_related->discount_percentage ?? null)
                    @slot('stok', $product_related->stok ?? null) 
                    @slot('penjualan', $product_related->penjualan ?? null)<!-- Persentase diskon -->
                @endcomponent
                </div>
               @endforeach
            </div>
        </div>
    </section>
    <!-- Product Details Section End -->
@endsection