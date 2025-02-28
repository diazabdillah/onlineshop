@extends('layouts.frontend.app')
@section('content')

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
                                <tbody id="cart-body">
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
                                                <button type="button" class="btn-minus">-</button>
                                                <input type="number" value="{{ $carts->qty }}" name="cart_qty[]" min="1" max="{{ $carts->Product->stok }}" class="qty-input" style="width: 50px;">
                                                <button type="button" class="btn-plus">+</button>
                                            </div>
                                        </td>
                                        <td class="cart__total">{{ rupiah($carts->total_price_per_product) }}</td>
                                        <td class="cart__close"><a href="{{ route('cart.delete',$carts->id) }}"><span class="icon_close"></span></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    </form>
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
                            <li>Total <span id="totalCartPrice">{{ rupiah($data['carts']->sum('total_price_per_product')) }}</span></li> <!-- This will show the total cart price -->
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
        $('.pro-qty').on('click', '.btn-plus, .btn-minus', function(e) {
            e.preventDefault();
            var $button = $(this);
            var $input = $button.siblings('.qty-input');
            var oldValue = parseInt($input.val());
            var maxStock = parseInt($input.attr('max'));
            var newVal = oldValue;

            if ($button.hasClass('btn-plus')) {
                if (oldValue < maxStock) {
                    newVal = oldValue + 1;
                }
            } else if ($button.hasClass('btn-minus')) {
                if (oldValue > 1) {
                    newVal = oldValue - 1;
                }
            }

            $input.val(newVal);
            updateCart($input);
        });

        function updateCart($input) {
            var cartId = $input.closest('tr').find('input[name="cart_id[]"]').val();
            var newQty = $input.val();

            $.ajax({
                url: '/cart/update',
                type: 'POST',
                data: {
                    cart_id: cartId,
                    cart_qty: newQty,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.total_price_per_product && response.total_cart_price) {
                        $input.closest('tr').find('.cart__total').text(rupiah(response.total_price_per_product));
                        $('#totalCartPrice').text(rupiah(response.total_cart_price));
                    }
                },
                error: function(xhr) {
                    alert('Error updating cart: ' + xhr.responseText);
                }
            });
        }

        function rupiah(value) {
            return 'Rp ' + value.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
    });
    </script>

@endsection
