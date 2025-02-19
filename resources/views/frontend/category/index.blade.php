@extends('layouts.frontend.app')
@section('content')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Category</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Categories Section Begin -->
    <section class="categories">
        <div class="container">
            <div class="row">
                @foreach ($data['category'] as $category)
                    <div class="col-lg-4 col-md-4 col-sm-4 p-0 ">
                        <div class="categories__item set-bg" data-setbg="{{ asset('storage/' . $category->thumbnails) }}">
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
    @endsection

