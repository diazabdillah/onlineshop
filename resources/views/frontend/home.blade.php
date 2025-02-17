@extends('layouts.frontend.app')
@section('content')
    <!-- Categories Section Begin -->
    <section class="categories">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="categories__item categories__large__item set-bg"
                        data-setbg="{{ asset('me') }}/img/banner.jpg">
                        {{-- <video width="320" height="240" controls>
                            <source src="{{ asset('me') }}/img/banner.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video> --}}
                        <div class="categories__text">
                            <p style="color:white;">Beragam Produk Mulai Dari Tshirt, Hoddie, Skincare, Software Aplikasi dan Frozen Food Semuanya Tersedia Di Anekabarangsby
                                Store.</p>
                            <a href="/product">Jelajahi Sekarang</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        @foreach ($data['new_categories'] as $category)
                            <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                                <div class="categories__item set-bg"
                                    data-setbg="{{ asset('storage/' . $category->thumbnails) }}">
                                    <div class="categories__text">
                                        <h4>{{ $category->name }}</h4>
                                        <p>{{ $category->Products()->count() }} item</p>
                                        <a href="{{ route('category.show',$category->slug) }}">Jelajahi</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Categories Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="section-title">
                        <h4>New product</h4>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8">
                    <ul class="filter__controls">
                        <li class="active" data-filter="*">All</li>
                        @foreach ($data['new_categories'] as $new_categories)
                            <li data-filter=".{{ $new_categories->slug }}">{{ $new_categories->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row property__gallery">
                @foreach ($data['new_categories'] as $new_categories2)
                    @foreach ($new_categories2->Products()->limit(4)->get()
        as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mix {{ $new_categories2->slug }}">
                            @component('components.frontend.product-card')
                                @slot('image', asset('storage/' . $product->thumbnails))
                                @slot('route', route('product.show', ['categoriSlug' => $new_categories2->slug, 'productSlug' =>
                                    $product->slug]))
                                    @slot('name', $product->name)
                                    @slot('price', $product->price)
                                    @slot('discounted_price', $product->discounted_price ?? null) <!-- Harga diskon -->
                                    @slot('discount_percentage', $product->discount_percentage ?? null)
                                    @slot('stok', $product->stok)
                                    @slot('penjualan', $product->penjualan)
                                    @endcomponent
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
        <!-- Product Section End -->
        <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>

<script>
        var botmanWidget = {
            aboutText: 'Anekabarangsby',
            introMessage: 'Selamat Datang di toko kami🙏💓.</br> 🏠Disini anekabarangsby, Ada yang bisa kami bantu?🏠Ada yang ditanyakan terkait produk kami silahkan. </br> 🚛Pengiriman JNE, POS Indonesia, dan Tiki cepat kirim.<br>🗣Pelayanan jam 8.00-jam 21.00.</br> 📦Pengiriman Barang Senin-Minggu di jam 16:30.</br> 🎟 Dapetin kode voucher diskon.</br>🎁Produk kosong di hubungi melalui chat.</br>🍦Barang wajib pakai packing luar jawa timur.</br>🛍Selamat Berbelanja,Terima Kasih Pelanggan🙋',
            title: 'Chat Anekabarangsby',
            mainColor: '#408591',
            bubbleBackground: '#408591',
            aboutLink: 'https://botman.io',
        };
    </script>
    @endsection
