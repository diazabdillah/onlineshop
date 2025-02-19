@extends('layouts.frontend.app')
@section('content')
     <!-- Breadcrumb Begin -->
     <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                        <span>Shop</span>
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
                    <div class="row">
                        @foreach ($data['product'] as $product_related)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                @component('components.frontend.product-card')
                                    @slot('image', asset('storage/' . $product_related->thumbnails))
                                    @slot('route', route('product.show', ['categoriSlug' => $product_related->Category->slug, 'productSlug' => $product_related->slug]))
                                    @slot('name', $product_related->name)
                                    @slot('price', $product_related->price)
                                    @slot('discounted_price', $product_related->discounted_price ?? null) <!-- Harga diskon -->
                                    @slot('discount_percentage', $product_related->discount_percentage ?? null)
                                    @slot('stok', $product_related->stok ?? null)
                                    @slot('penjualan', $product_related->penjualan ?? null)
                                    <!-- @slot('rating', $product_related->rating ?? null)
                                    @slot('totalStars', $totalStars ?? 0)
                                    @slot('totalUsers', $totalUsers ?? 0)
                                    @slot('averageRating', $averageRating ?? 0) -->
                                
                                    @slot('chart', route('product.show', ['categoriSlug' => $product_related->Category->slug, 'productSlug' => $product_related->slug]))
                                    <!-- @slot('checkout', route('product.show', ['categoriSlug' => $product_related->Category->slug, 'productSlug' => $product_related->slug])) Persentase diskon -->
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