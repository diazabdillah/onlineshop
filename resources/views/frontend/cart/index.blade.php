@extends('layouts.frontend.app')
@section('content')
<style>
    .pro-qty {
    display: inline-flex;
    align-items: center;
}

.pro-qty .qtybtn {
    cursor: pointer;
    padding: 5px 10px;
    border: 1px solid #ccc;
    background-color: #f8f8f8;
}

.pro-qty .qtybtn:hover {
    background-color: #e8e8e8;
}

.pro-qty input {
    width: 50px;
    text-align: center;
    border: 1px solid #ccc;
    margin: 0 5px;
}
</style>
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Cart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="shop-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <form action="" method="post">
                        @csrf
                    <div class="shop__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['carts'] as $carts)
                                    <tr>
                                        <td class="cart__product__item">
                                            <img src="{{ asset($carts->Product->thumbnails_path) }}" alt="" width="90">
                                            <div class="cart__product__item__title">
                                                <h6>{{ $carts->Product->name }}</h6>
                                                <div class="rating">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="cart__price">
                                            @if($carts->Product->discounted_price)
                                                <span style="text-decoration: line-through;">{{ $carts->Product->price }}</span>
                                                <span>{{ $carts->Product->discounted_price }}</span>
                                            @else
                                                <span>{{ $carts->Product->price }}</span> <!-- Harga tanpa coret untuk produk tanpa diskon -->
                                            @endif
                                        </td>
                                        <input type="hidden" name="cart_id[]" value="{{ $carts->id }}">
                                        <td class="cart__quantity">
                                            <div class="pro-qty">
                                                <span class="qtybtn">-</span>
                                                <input type="text" value="{{ $carts->qty }}" name="cart_qty[]">
                                                <span class="qtybtn">+</span>
                                            </div>
                                        </td>
                                        <td class="cart__total">{{ rupiah($carts->total_price_per_product) }}</td>
                                        <td class="cart__close"><a href="{{ route('cart.delete',$carts->id) }}"><span class="icon_close"></span></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="cart__btn">
                        <a href="{{ route('product.index') }}">Continue Shopping</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    {{-- <div class="cart__btn update__btn">
                        <button type="submit"><span class="icon_loading"></span> Update cart</button> --}}
                    </form>
                    {{-- </div> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                </div>
                <div class="col-lg-4 offset-lg-2">
                    <div class="cart__total__procced">
                        <h6>Cart total</h6>
                        <ul>
                            <li>Total <span>{{ rupiah($data['carts']->sum('total_price_per_product')) }}</span></li>
                        </ul>
                        <a href="{{ route('checkout.index') }}" class="primary-btn">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.pro-qty').on('click', '.qtybtn', function(e) {
                e.preventDefault();
                var $button = $(this);
                var $input = $button.parent().find('input');
                var oldValue = $input.val();
                var newVal;
    
                // Tambahkan kelas 'inc' pada tombol tambah
                if ($button.hasClass('qtybtn') && $button.text() === '+') {
                    newVal = parseFloat(oldValue) + 1;
                } else {
                    if (oldValue > 1) {
                        newVal = parseFloat(oldValue) - 1;
                    } else {
                        newVal = 1;
                    }
                }
    
                $input.val(newVal);
    
                var cartId = $input.closest('tr').find('input[name="cart_id[]"]').val();
                var newQty = newVal;
    
                // Kirim permintaan AJAX untuk memperbarui kuantitas
                $.ajax({
    url: '/cart/update', // Ganti dengan URL yang sesuai
    type: 'POST',
    data: {
        cart_id: cartId, // ID keranjang yang ingin diperbarui
        cart_qty: newQty, // Jumlah baru yang ingin diupdate
        _token: '{{ csrf_token() }}' // Token CSRF untuk keamanan
    },
    success: function(response) {
        // Menampilkan total harga per produk dan total harga keranjang
        //$('#totalPricePerProduct').text(response.total_price_per_product);
        // $('#totalCartPrice').text(response.total_cart_price);
       // alert('Keranjang berhasil diperbarui!'); // Pesan sukses
    //    location.reload();
    var totalPricePerProduct = response.total_price_per_product; // Ambil total harga per produk dari respons
        var totalCartPrice = response.total_cart_price; // Ambil total harga keranjang dari respons
        
        // Update tampilan total harga per produk dan total harga keranjang
        $input.closest('tr').find('.cart__total').text(rupiah(totalPricePerProduct));
        $('.cart__total__procced ul li span').text(rupiah(totalCartPrice));
    },
    error: function(xhr) {
        // Menangani kesalahan
        alert('Terjadi kesalahan saat memperbarui keranjang: ' + xhr.responseText);
    }
});
            });
        });
    </script>
@endsection
