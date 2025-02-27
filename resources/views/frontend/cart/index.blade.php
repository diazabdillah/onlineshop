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
                                    <!-- Cart items will be dynamically inserted here by AJAX -->
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
                            <li>Total <span id="totalCartPrice"></span></li> <!-- This will show the total cart price -->
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
    
            // Handle increment and decrement
            if ($button.text() === '+') {
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
    
            // Send AJAX request to update the cart
            $.ajax({
                url: '/cart/update', // Ensure this is the correct route
                type: 'POST',
                data: {
                    cart_id: cartId, // The cart item ID
                    cart_qty: newQty, // The new quantity
                    _token: '{{ csrf_token() }}' // CSRF token for security
                },
                success: function(response) {
                    if (response.total_price_per_product && response.total_cart_price) {
                        // Update the price per product and the total cart price
                        var totalPricePerProduct = response.total_price_per_product; // Total price per product
                        var totalCartPrice = response.total_cart_price; // Total price for the cart
                        
                        // Update the individual product total price
                        $input.closest('tr').find('.cart__total').text(rupiah(totalPricePerProduct));
    
                        // Update the total price for the entire cart
                        $('#totalCartPrice').text(rupiah(totalCartPrice));
                    }
                },
                error: function(xhr) {
                    alert('Error updating cart: ' + xhr.responseText);
                }
            });
        });
    });
    
    // Function to format numbers into Rupiah currency format
    function rupiah(value) {
        return 'Rp ' + value.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

    function fetchCartData() {
        $.ajax({
            url: '{{ route('cart.data') }}', // The route we defined in the backend
            type: 'GET',
            success: function(response) {
                var cartItems = response.cart_items;
                var totalCartPrice = response.total_cart_price;

                // Empty the current cart table
                $('.shop__cart__table tbody').empty();

                // Loop through the cart items and add them to the table
                cartItems.forEach(function(item) {
                    var totalPricePerProduct = item.qty * (item.Product.discounted_price || item.Product.price);
                    var productHtml = `
                        <tr>
                            <td class="cart__product__item">
                                <img src="{{ asset('${item.Product.thumbnails_path}') }}" alt="" width="90">
                                <div class="cart__product__item__title">
                                    <h6>${item.Product.name}</h6>
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
                                ${item.Product.discounted_price ? 
                                    '<span style="text-decoration: line-through;">' + rupiah(item.Product.price) + '</span>' +
                                    '<span>' + rupiah(item.Product.discounted_price) + '</span>' :
                                    '<span>' + rupiah(item.Product.price) + '</span>'
                                }
                            </td>
                            <input type="hidden" name="cart_id[]" value="${item.id}">
                            <td class="cart__quantity">
                                <div class="pro-qty">
                                    <span class="qtybtn">-</span>
                                    <input type="number" value="${item.qty}" name="cart_qty[]">
                                    <span class="qtybtn">+</span>
                                </div>
                            </td>
                            <td class="cart__total">${rupiah(totalPricePerProduct)}</td>
                            <td class="cart__close"><a href="{{ route('cart.delete', '${item.id}') }}"><span class="icon_close"></span></a></td>
                        </tr>
                    `;
                    $('.shop__cart__table tbody').append(productHtml);
                });

                // Update the total cart price
                $('#totalCartPrice').text(rupiah(totalCartPrice));
            },
            error: function(xhr) {
                alert('Error fetching cart data: ' + xhr.responseText);
            }
        });
    }

    // Call the fetchCartData function on page load to populate the cart
    fetchCartData();
    </script>
@endsection
