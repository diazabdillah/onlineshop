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
                        @php 
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
                                <div class="qtybtn d-flex align-items-center">
                                    <button type="button" class="btn-minus">-</button>
                                    <input type="number" name="cart_qty" value="1" min="1" max="{{ $data['product']->stok }}" id="cart_qty" class="mx-2 text-center" style="width: 50px;">
                                    <button type="button" class="btn-plus">+</button>
                                </div>
                                <input type="hidden" name="cart_product_id" value="{{ $data['product']->id }}">
                            </div> <br>
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
                                <li>
                                    <span>Kondisi : </span>
                                    <p>{{ $data['product']->kondisi }}</p> <!-- Menambahkan informasi stok -->
                                </li>
                                <li>
                                    <span>Ukuran : </span>
                                    <p>{{ $data['product']->ukuran }} </p> <!-- Menambahkan informasi stok -->
                                </li>
                                <li>
                                    <span>Warna : </span>
                                    <p>{{ $data['product']->warna }} </p> <!-- Menambahkan informasi stok -->
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
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Testimoni Produk</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                        <div class="summary mb-4">
        <p><strong>Total Users Rated:</strong> {{ $totalUsers > 0 ? $totalUsers : 0 }}</p>
        <p><strong>Total Stars:</strong> {{ $averageRating > 0 ? $averageRating : 0 }} dari 5</p>
        @php $ratenum = number_format($averageRating) > 0 ? number_format($averageRating) : 0 @endphp
        <div class="rating">
            @for($i = 1; $i <= $ratenum; $i++)
                <i class="fa fa-star checked" style="color: yellow;"></i> <!-- Bintang terisi berwarna kuning -->
            @endfor
            @for($j=$ratenum+1; $j<= 5; $j++)
                <i class="fa fa-star" style="color: gray;"></i> <!-- Bintang tidak terisi berwarna abu-abu -->
            @endfor
        </div>
        <br>
    </div>

    @if ($data['product']->reviews->isEmpty())
        <p class="text-muted text-center">No reviews yet. Be the first to review this product!</p>
    @else
        <div class="reviews-container card p-3">
            @foreach ($data['product']->reviews as $review)
                <div class="review card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="review-user">{{ $review->user->name }}</strong>
                            <div class="rating">
                                @php $ratenum = number_format($review->rating) @endphp
                                @for($i = 1; $i <= $ratenum; $i++)
                                    <i class="fa fa-star checked" style="color: yellow;"></i> <!-- Bintang terisi berwarna kuning -->
                                @endfor
                                @for($j=$ratenum+1; $j<= 5; $j++)
                                    <i class="fa fa-star" style="color: gray;"></i> <!-- Bintang tidak terisi berwarna abu-abu -->
                                @endfor
                            </div>
                        </div>
                        <p class="review-text mb-3">{{ $review->review }}</p>

                        <!-- Container untuk gambar dan video -->
                        <div class="row">
                            <!-- Tampilkan gambar jika ada -->
                            @if ($review->image)
                                <div class="col-md-4 mb-3">
                                    <div class="review-media">
                                        <img src="{{ asset('storage/' . $review->image) }}" alt="Review Image" class="img-fluid rounded" style="max-width: 100%; height: 100%;">
                                    </div>
                                </div>
                            @endif

                            <!-- Tampilkan video jika ada -->
                            @if ($review->video)
                                <div class="col-md-4 mb-3">
                                    <div class="review-media">
                                        <video controls class="img-fluid rounded" style="max-width: 100%; height: 100%;">
                                            <source src="{{ asset('storage/' . $review->video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
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
                    @slot('id', $product_related->id)
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
    <script>
         const stock = {{ $data['product']->stok }};
                                const qtyInput = document.getElementById('cart_qty');
                                const btnPlus = document.querySelector('.btn-plus');
                                const btnMinus = document.querySelector('.btn-minus');

                                function updateButtonState() {
                                    const qtyValue = parseInt(qtyInput.value);
                                    btnPlus.disabled = qtyValue >= stock; // Disable "+" button if qty >= stock
                                    btnMinus.disabled = qtyValue <= 1; // Disable "-" button if qty <= 1
                                }

                                qtyInput.addEventListener('input', function() {
                                    if (this.value > stock) {
                                        this.value = stock; // Set to max stock if exceeded
                                    }
                                    updateButtonState();
                                });

                                btnPlus.addEventListener('click', function() {
                                    if (parseInt(qtyInput.value) < stock) {
                                        qtyInput.value = parseInt(qtyInput.value) + 1;
                                        updateButtonState();
                                    }
                                });

                                btnMinus.addEventListener('click', function() {
                                    if (parseInt(qtyInput.value) > 1) {
                                        qtyInput.value = parseInt(qtyInput.value) - 1;
                                        updateButtonState();
                                    }
                                });

                                // Initial button state update
                                updateButtonState();
    </script>
    <!-- Product Details Section End -->
@endsection