@extends('layouts.frontend.app')
@section('content')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Checkout</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <form action="{{ route('checkout.process') }}" class="checkout__form" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <h5>Billing detail</h5>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="checkout__form__input">
                                    <p>Recipient Name <span>*</span></p>
                                    <input type="text" name="recipient_name" value="{{ auth()->user()->name }}" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="checkout__form__input">
                                    <p>Phone Number <span>*</span></p>
                                    <input type="text" value="{{ $data['profile']->phone }}" name="phone_number" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__form__input">
                                    <p>Province <span>*</span></p>
                                    <select name="province_id" id="province_id" class="select-2" required>
                                        <option value="" selected disabled>-- Select Province --</option>
                                        @foreach ($data['provinces'] as $province)
                                            <option value="{{ $province['province'] }}" data-id="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__form__input">
                                    <p>City <span>*</span></p>
                                    <select name="city_id" id="city_id" class="select-2" disabled required>
                                        <option value="" selected disabled>-- Select City --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="checkout__form__input">
                                    <p>Address Detail <span>*</span></p>
                                    <input type="text" value="{{$data['profile']->address}}" name="address_detail" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="checkout__form__input">
                                    <p>Courier <span>*</span></p>
                                    <select name="courier" id="courier">
                                        <option value="jne" selected>JNE</option>
                                        <option value="tiki">TIKI</option>
                                        <option value="pos">POS INDONESIA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="checkout__form__input">
                                    <p>Shipment Method <span>*</span></p>
                                    <select name="shipping_method" id="shipping_method" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <!-- <div class="checkout__form__input">
                                    <label for="get_voucher_code">Apply Voucher</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="voucher" id="get_voucher_code" placeholder="Enter Voucher Code">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success btn-sm" id="apply-voucher-btn">Apply</button>
                                        </div>
                                    </div>
                                    <div id="voucher-message"></div>
                                </div> -->
                                <div class="checkout__order__voucher">
                                    <h5>List Voucher:</h5>
                                    <div id="voucher-list">
                                        @foreach ($data['vouchers'] as $voucher)
                                            <div class="voucher-item">
                                               
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="checkout__order">
                            <h5>Your order</h5>
                            <div class="checkout__order__product">
                                <ul>
                                    <li>
                                        <span class="top__text">Product</span>
                                        <span class="top__text__right">Total</span>
                                    </li>
                                    @foreach ($data['carts'] as $cart)
                                        <li>{{ $loop->iteration }}. {{ $cart->Product->name }} x
                                            {{ $cart->qty }}<span>{{ rupiah($cart->total_price_per_product) }}</span>
                                        </li>
                                    @endforeach
                                    <li>
                                        <span class="top__text">Total Weight</span>
                                        <span class="top__text__right">{{ $data['carts']->sum('total_weight_per_product') / 1000 }} Kg</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="checkout__order__total">
                                <ul>
                                    <li>Subtotal <span>{{ rupiah($data['carts']->sum('total_price_per_product')) }}</span></li>
                                    <li>Voucher <span id="voucher">Rp 0</span></li>
                                    <li>Shipping Cost <span id="text-cost">Rp 0</span></li>
                                    <li>Total <span id="total">{{ rupiah($data['carts']->sum('total_price_per_product')) }}</span></li>
                                    <input type="hidden" name="shipping_cost" id="shipping_cost">
                                    <input type="text" name="voucher_code" id="voucher_code" value="0">
                                    <input type="hidden" name="total_weight" value="{{ $data['carts']->sum('total_weight_per_product') }}">
                                </ul>
                            </div>
                            <button type="submit" class="site-btn">Place order</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('js')
    <script>
        function checkCost() {
            var origin = '{{ $data["shipping_address"]->city_id }}';
            var destination = $('#city_id option:selected').data('id');
            var weight = "{{ $data['carts']->sum('total_weight_per_product') }}";
            var courier = $('#courier option:selected').val();

            let _url = `/rajaongkir/cost`;
            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: "POST",
                data: {
                    origin: origin,
                    destination: destination,
                    weight: weight,
                    courier: courier,
                    _token: _token
                },
                dataType: "json",
                success: function(response) {
                    if (response) {
                        $('#shipping_method').empty();
                        $('#shipping_method').append(
                            'option value="" selected disabled>-- Select Shipment Service --</option>');
                        $.each(response[0].costs, function(key, cost) {
                            $('select[name="shipping_method"]').append('<option value="' + cost.service + ' Rp.' + cost.cost[0].value + ' Estimasi ' +
                                cost.cost[0].etd +
                                '" data-ongkir="'+cost.cost[0].value+'">' + cost.service + ' Rp.' + cost.cost[0].value + ' Estimasi ' +
                                cost.cost[0].etd +
                                '</option>');
                            if (key == 0) {
                                countCost(cost.cost[0].value)
                            }
                        });
                    } else {
                        $('#shipping_method').append(
                            'option value="" selected disabled>-- Select Shipment Service --</option>');
                    }
                },
            });
        }

        $('#province_id').on('change', function() {
            var provinceId = $('#province_id option:selected').data('id');
            $('#city_id').empty();
            $('#city_id').append('<option value="">-- Loading Data --</option>');
            $.ajax({
                url: '/rajaongkir/province/' + provinceId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $('#city_id').empty();
                        $('#city_id').removeAttr('disabled');
                        $('select[name="city_id"]').append(
                            'option value="" selected>-- Select City --</option>');
                        $.each(data, function(key, city) {
                            $('select[name="city_id"]').append('<option value="' + city
                                .city_name + '" data-id="'+city.city_id+'">' + city.type + ' ' + city.city_name +
                                '</option>');
                        });
                        checkCost();
                    } else {
                        $('#city_id').empty();
                    }
                }
            });
        });

        $('#city_id').on('change', function() {
            checkCost();
        });
        $('#courier').on('change', function() {
            checkCost();
        });

        $('#shipping_method').on('change',function(){
            var ongkir = parseInt($('#shipping_method option:selected').data('ongkir'));
            countCost(ongkir);
        })

        function countCost(ongkir)
        {
            var subtotal = `{{ $data['carts']->sum('total_price_per_product') }}`;
            var discount = parseInt($('#voucher').text().replace(/[^0-9]/g, '')) || 0; // Ambil nilai diskon dari voucher
            var total = parseInt(subtotal) + ongkir - discount; // Kurangi total dengan diskon
            $('#text-cost').text(rupiah(ongkir));
            $('#shipping_cost').val(ongkir);
            $('#shipping_cost').val(ongkir);
            $('#total').text(rupiah(total));
        }

        $(document).ready(function() {
    function fetchVouchers() {
        $.ajax({
            url: "{{ route('vouchers.list') }}",
            method: "GET",
            success: function(response) {
                let voucherList = "";
                response.vouchers.forEach(voucher => {
                    let discountText = voucher.type === 'percentage' 
                        ? `${voucher.discount}%` 
                        : rupiah(voucher.discount);
                    voucherList += `<div class="card mb-3 voucher-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2 border-right">
                                    <i class="fa fa-ticket fa-2x text-primary"></i>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">Diskon ${discountText} S/& ${rupiah(voucher.max_discount)}</h6>
                                    <p class="card-text mb-0">
                                        <span class="text-danger font-weight-bold">Kode: ${voucher.code}</span>
                                    </p>
                                    <small class="text-muted">Min. belanja ${rupiah(voucher.min_purchase)}</small>
                                </div>
                                <div class="col-3 text-right">
                                    <input type="radio" name="selected_voucher" value="${voucher.code}" class="voucher-radio" data-code="${voucher.code}">
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
                $('#voucher-list').html(voucherList);
            }
        });
    }
    fetchVouchers();

    $(document).on('change', '.voucher-radio', function() {
        resetVoucher(); // Reset diskon sebelum menerapkan voucher baru
        let voucherCode = $(this).val();
        applyVoucher(voucherCode);
    });

    function applyVoucher(voucherCode) {
        let subtotal = parseInt($('#total').text().replace(/[^0-9]/g, '')) || 0;
        $.ajax({
            url: "{{ route('apply.voucher') }}",
            method: "POST",
            data: {
                code: voucherCode,
                total: subtotal,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    let discount = response.data.discount;
                    // let totalAkhir = subtotal - discount;
                    $('#voucher_code').val(discoount);
                    $('#voucher').text('-' + rupiah(discount));
                    // $('#total').text(rupiah(totalAkhir));
                } else {
                    resetVoucher();
                    alert(response.message);
                }
            },
            error: function() {
                resetVoucher();
                alert('Voucher tidak valid');
            }
        });
    }

    function resetVoucher() {
        console.log("resetVoucher dipanggil"); // Tambahkan log untuk debugging
        $('#voucher').text(rupiah(0));
        $('#voucher_code').val('');
        let subtotal = parseInt($('#total').text().replace(/[^0-9]/g, '')) || 0;
        $('#total').text(rupiah(subtotal));
    }
});

  

    </script>
@endpush