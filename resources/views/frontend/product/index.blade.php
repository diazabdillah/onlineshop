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
    <!-- Shop Section End -->
@endsection