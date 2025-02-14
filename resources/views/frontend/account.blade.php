@extends('layouts.frontend.app')
@section('content')
    <section class="ftco-section">
        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-2 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5"
                            width="150px"
                            src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span
                            class="font-weight-bold">{{ $profile->name }}</span><span
                            class="text-black-50">{{ $profile->email }}</span><span> </span></div>
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Profile Setting</h4>
                        </div>
                       
                        <form action="{{ route('account.profiles.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="labels" for="first_name">Nama Depan:</label>
                                <input class="form-control" type="text" name="first_name" id="first_name"
                                    value="{{ $profile->first_name }}" required>
                            </div>
                            <div>
                                <label class="labels" for="last_name">Nama Belakang:</label>
                                <input class="form-control" type="text" name="last_name" id="last_name"
                                    value="{{ $profile->last_name }}" required>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="checkout__form__input">
                                    <p>Province <span>*</span></p>
                                    <select name="province" id="province_id" class="select-2" required>
                                        <option selected value="{{$profile->province}}">{{$profile->province}}</option> 
                                        @foreach ($data['provinces'] as $province)
                                            <option value="{{ $province['province'] }}" data-id="{{ $province['province_id'] }}">{{ $province['province'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="checkout__form__input">
                                    <p>City <span>*</span></p>
                                    <select name="city" id="city_id" class="select-2" disabled required>
                                        <option value="" selected disabled>-- Select City --</option>
                                    </select>
                                </div>
                            </div>
                            <input class="form-control" type="text" name="id_province" id="id_province" required>
                            <input class="form-control" type="text" name="id_city" id="id_city" required>
                            {{-- <div>
                                <label class="labels" for="province">Provinsi:</label>
                                <input class="form-control" type="text" name="province" id="province"
                                    value="{{ $profile->province }}" required>
                            </div>
                            <div>
                                <label class="labels" for="city">Kota:</label>
                                <input class="form-control" type="text" name="city" id="city"
                                    value="{{ $profile->city }}" required>
                            </div> --}}
                            <div>
                                <label class="labels" for="phone">Telepon:</label>
                                <input class="form-control" type="text" name="phone" id="phone"
                                    value="{{ $profile->phone }}" required>
                            </div>
                            <div>
                                <label class="labels" for="address">Alamat:</label>
                                <textarea class="form-control" name="address" id="address" rows="5" style="height: 150px;" required>{{ $profile->address }}</textarea>
                            </div>
                            <div>
                                <button class="btn btn-primary profile-button mt-2" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Account Setting</h4>
                        </div>
                       
                        <form action="{{ route('account.profiles.updateaccount') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="labels" for="name">Name:</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ $profile->name }}" required>
                            </div>
                            <div>
                                <label class="labels" for="email">Email:</label>
                                <input class="form-control" type="email" name="email" id="email"
                                    value="{{ $profile->email }}" required>
                            </div>
                            <div>
                                <label class="labels" for="old_password">Password Lama:</label>
                                <input class="form-control" type="password" name="old_password" id="old_password" required>
                            </div>
                            <div>
                                <label class="labels" for="new_password">Password Baru:</label>
                                <input class="form-control" type="password" name="password" id="new_password" required>
                            </div>
                            <div>
                                <label class="labels" for="password_confirmation">Konfirmasi Password Baru:</label>
                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required>
                            </div>
                            <div>
                                <button class="btn btn-primary profile-button mt-2" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>
    <!-- Product Section End -->
    @push('js')
    <script>
   
        $('#province_id').on('change', function() {
            var provinceId = $('#province_id option:selected').data('id');
            $('#id_province').val(provinceId);
            $('#city_id').empty();
            $('#city_id').append('<option value="">-- Loading Data --</option>');
            $.ajax({
                url: '/rajaongkir/province/' + provinceId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data) {
                        $('#city_id').empty();
                        $('#city_id').removeAttr('disabled');
                        $('select[name="city"]').append(
                            'option value="" selected>-- Select City --</option>');
                        $.each(data, function(key, city) {
                            $('select[name="city"]').append('<option value="' + city
                                .city_name + '" data-id="'+city.city_id+'">' + city.type + ' ' + city.city_name +
                                '</option>');
                          
                            });
                            console.log($data.results);
                            $('#id_city').val(data[0].city_id);
                    } else {
                        $('#city_id').empty();
                    }
                }
            });
        });

       
        // $(document).ready(function() {
        //     // ... existing code ...
        //     $('#get_voucher_code').on('input', function() {
        //         var voucherCode = $(this).val();
        //         console.log(voucherCode);
        //         if (voucherCode) {
        //             $.ajax({
        //                 url: "{{ route('apply.voucher') }}",
        //                 method: "POST",
        //                 data: {
        //                     code: voucherCode,
        //                     _token: $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 success: function(response) {
        //                     if (response.success) {
        //                         console.log(response.data.discount);
        //                         $('#voucher_code').val(response.data.discount);
        //                         $('#voucher').text('-' + rupiah(response.data.discount));
        //                         countCost(parseInt($('#shipping_cost').val())); // Panggil countCost untuk memperbarui total
        //                     } else {
        //                         $('#voucher_code').val(0);
        //                         $('#voucher').text(rupiah(0));
        //                         $('#voucher-message').html('<p style="color: red;">' + response.data.discount + '</p>');
        //                     }
        //                 },
        //                 error: function(xhr) {
        //                     let errorMessage = xhr.responseJSON.message || 'An error occurred.';
        //                     $('#voucher-message').html('<p style="color: red;">' + errorMessage + '</p>');
        //                 }
        //             });
        //         } else {
        //             $('#voucher-message').html('<p style="color: red;">Voucher code cannot be empty.</p>'); // Menampilkan pesan jika input kosong
        //             $('#voucher').text(rupiah(0));
        //             $('#voucher_code').val(0);
        //         }
        //     });
        // });
    </script>
@endpush
@endsection
