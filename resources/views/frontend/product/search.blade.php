@extends('layouts.frontend.app')
@section('content')
     <!-- Breadcrumb Begin -->
     <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                        <span>Search</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <p>Hasil Pencarian : {{ $_GET['q'] }} ({{ $data['product']->count() }} Hasil)</p>
                    <div class="row">
                        @foreach ($data['product'] as $product)
                        <div class="col-lg-3 col-md-4">
                            @component('components.frontend.product-card')
                            @slot('image', asset('storage/' . $product->thumbnails))
                            @slot('route', route('product.show', ['categoriSlug' => $product->Category->slug, 'productSlug' =>
                                $product->slug]))
                                @slot('name', $product->name)
                                @slot('price', $product->price)
                                @slot('discounted_price', $product->discounted_price ?? null) <!-- Harga diskon -->
                                @slot('discount_percentage', $product->discount_percentage ?? null)
                                @slot('stok', $product->stok ?? null)
                                @slot('penjualan', $product->penjualan ?? null)
                            @endcomponent
                        </div>
                        @endforeach
                        <div class="col-lg-12 text-center">
                          {{ $data['product']->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>

<script>
        var botmanWidget = {
            aboutText: 'Anekabarangsby',
            introMessage: 'Selamat Datang di toko kami🙏💓.</br> 🏠Disini anekabarangsby, Ada yang bisa kami bantu?🏠Ada yang ditanyakan terkait produk kami silahkan. </br> 🚛Pengiriman JNE, POS Indonesia, dan Tiki cepat kirim.<br>🗣Pelayanan jam 8.00-jam 21.00.</br> 📦Pengiriman Barang Senin-Minggu di jam 16:30.</br> 🎟 Dapetin kode voucher diskon.</br>🎁Produk kosong di hubungi melalui chat.</br>🍦Barang wajib pakai packing luar jawa timur.</br>🛍Selamat Berbelanja,Terima Kasih Pelanggan🙋',
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
    <!-- Shop Section End -->
@endsection