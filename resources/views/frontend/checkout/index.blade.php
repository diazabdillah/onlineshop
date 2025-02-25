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
                                <div class="checkout__form__input">
                                    <label for="get_voucher_code">Apply Voucher</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="voucher" id="get_voucher_code" placeholder="Enter Voucher Code">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success btn-sm" id="apply-voucher-btn">Apply</button>
                                        </div>
                                    </div>
                                    <div id="voucher-message"></div>
                                </div>
                                <div class="checkout__order__voucher">
                                    <h5>List Voucher:</h5>
                                    <div id="voucher-list">
                                        @foreach ($data['vouchers'] as $voucher)
                                            <div class="voucher-item">
                                                {{-- <p>Kode Voucher:&nbsp; <strong>{{ $voucher->code }}</strong> - {{ $voucher->discount }}</p>
                                                <button type="button" class="claim-voucher btn btn-primary btn-sm" data-code="{{ $voucher->code }}">Claim</button> --}}
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
                                    <input type="hidden" name="voucher_code" id="voucher_code" value="0">
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
            $('#total').text(rupiah(total));
        }

        $(document).ready(function() {
    // Ambil daftar voucher
    function fetchVouchers() {
        $.ajax({
            url: "{{ route('vouchers.list') }}",
            method: "GET",
            success: function(response) {
                let voucherList = "";
                response.vouchers.forEach(voucher => {
                    voucherList += `<li>Kode Voucher:<span> ${voucher.code} </span>- ${rupiah(voucher.discount)} <button class="claim-voucher btn btn-primary btn-sm" data-code="${voucher.code}">Claim Voucher</button></li>`;
                });
                $('#voucher-list').html(voucherList);
            }
        });
    }
    fetchVouchers();
    
    // Klaim Voucher
    $(document).on('click', '.claim-voucher', function() {
        let voucherCode = $(this).data('code');
        $.ajax({
            url: "{{ route('vouchers.claim') }}",
            method: "POST",
            data: {
                code: voucherCode,
                total: parseInt($('#total').text().replace(/[^0-9]/g, '')) || 0, // Mengambil total belanja
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.message) {
                    alert(response.message); // Menampilkan pesan dari server
                    $('#get_voucher_code').val(voucherCode);
                    applyVoucher(voucherCode); // Terapkan voucher setelah klaim berhasil
                }
            },
            error: function(xhr) {
                alert('Gagal klaim voucher: ' + xhr.responseJSON.message); // Menampilkan pesan kesalahan
            }
        });
    });

    // Terapkan Voucher
    function applyVoucher(voucherCode) {
        var totalBelanja = parseInt($('#total').text().replace(/[^0-9]/g, '')) || 0;
        $.ajax({
            url: "{{ route('apply.voucher') }}",
            method: "POST",
            data: {
                code: voucherCode,
                total: totalBelanja,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    let discount = response.data.discount;
                    let totalAkhir = totalBelanja - discount;
                    $('#voucher_code').val(discount);
                    $('#voucher').text('-' + rupiah(discount));
                    $('#total').text(rupiah(totalAkhir));
                    $('#voucher-message').html('<p style="color: green;">Voucher berhasil diterapkan!</p>');
                } else {
                    resetVoucher();
                    $('#voucher-message').html('<p style="color: red;">' + response.message + '</p>');
                }
            },
            error: function(xhr) {
                resetVoucher();
                $('#voucher-message').html('<p style="color: red;">Voucher tidak valid</p>');
            }
        });
    }

    function resetVoucher() {
        $('#voucher').text(rupiah(0));
        $('#voucher_code').val(0);
    }

    $('#apply-voucher-btn').on('click', function(e) {
        e.preventDefault();
        let voucherCode = $('#get_voucher_code').val();
        if (voucherCode) {
            applyVoucher(voucherCode);
        } else {
            resetVoucher();
            $('#voucher-message').html('<p style="color: red;">Kode voucher tidak boleh kosong.</p>');
        }
    });
});
  

    </script>
@endpush