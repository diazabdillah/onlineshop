@extends('layouts.frontend.app')
@section('content')
    <!-- Categories Section Begin -->
    <section class="categories">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="categories__item categories__large__item set-bg"
                                    data-setbg="{{ asset('me') }}/img/banner.jpg">
                                    <div class="categories__text">
                                        <p class="justify-content-center" style="color:white;">Beragam Produk Mulai Dari Tshirt, Hoddie, Skincare, Software Aplikasi dan Frozen Food Semuanya Tersedia Di Anekabarangsby
                                            Store.</p>
                                        <a class="text-center" style="justify-content: center;" href="/product">Jelajahi Sekarang</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="categories__item categories__large__item set-bg"
                                    data-setbg="{{ asset('me') }}/img/bannerramadhan.jpg">
                                    <!-- <div class="categories__text">
                                        <p style="color:white;">Deskripsi untuk gambar kedua.</p>
                                        <a href="/product">Jelajahi Sekarang</a>
                                    </div> -->
                                </div>
                            </div>
                            <!-- Tambahkan lebih banyak item carousel sesuai kebutuhan -->
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
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
                                    @slot('likes', $product->likes)
                                    @slot('penjualan', $product->penjualan)
                                    @endcomponent
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
        <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>

<script>
        var botmanWidget = {
            aboutText: 'Anekabarangsby',
            introMessage: 'Selamat Datang di toko kami🙏💓.</br> 🏠Disini anekabarangsby, Ada yang bisa kami bantu?🏠Ada yang ditanyakan terkait produk kami silahkan. </br> 🚛Pengiriman JNE, POS Indonesia, dan Tiki cepat kirim.<br>🗣Pelayanan jam 8.00-jam 21.00.</br> 📦Pengiriman Barang Senin-Minggu di jam 16:30.</br> 🎟 Dapetin kode voucher diskon.</br>🎁Produk kosong di hubungi melalui chat.</br>🍦Pembayaran toko kami dapat berbagai macam bank, qris, debit dan kartu kredit.</br>🛍Selamat Berbelanja,Terima Kasih Pelanggan🙋',
            title: 'BOT Anekabarangsby',
            mainColor: '#408591',
            bubbleBackground: '#408591',
            aboutLink: 'https://botman.io',
            usePusher: true,
            pusher: {
                key: '{{ env("PUSHER_APP_KEY") }}',
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}'
            }
        };
    </script>
        <!-- Product Section End -->
        
    @endsection
