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
                            <div>
                                <label class="labels" for="province">Provinsi:</label>
                                <input class="form-control" type="text" name="province" id="province"
                                    value="{{ $profile->province }}" required>
                            </div>
                            <div>
                                <label class="labels" for="city">Kota:</label>
                                <input class="form-control" type="text" name="city" id="city"
                                    value="{{ $profile->city }}" required>
                            </div>
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
@endsection
