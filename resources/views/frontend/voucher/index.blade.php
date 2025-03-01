@extends('layouts.frontend.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Cari dan Klaim Voucher</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Pencarian Voucher -->
    <form action="{{ route('voucher.search') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">Kode Voucher</label>
            <input type="text" id="code" name="code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Cari Voucher</button>
    </form>

    <!-- Menampilkan Hasil Pencarian -->
    @if(session('voucher'))
        @php $voucher = session('voucher'); @endphp
        <div class="card mt-4 border-primary">
            <div class="card-body">
            @if($voucher->type == 'percentage')    
                                <h5 class="card-title mb-1 text-primary">Diskon {{ $voucher->discount }} % S/& {{ rupiah($voucher->max_discount) }}</h5> <!-- Perbaikan di sini -->
                               @else
                               <h5 class="card-title mb-1 text-primary">Diskon {{ rupiah($voucher->discount) }} </h5> <!-- Perbaikan di sini -->
                                @endif
              
                <p class="card-text font-weight-bold">Minimal Belanja: <span class="text-success">{{ rupiah($voucher->min_purchase) }}</span></p>
                <p class="card-text">Kuota: <span class="font-weight-bold">{{ $voucher->used_count }}</span>/<span class="font-weight-bold">{{ $voucher->usage_limit }}</span></p>
                <p class="card-text">Kadaluarsa: <span>{{ \Carbon\Carbon::parse($voucher->valid_from)->format('d-m-Y') }}</span> - <span class="text-danger">{{ \Carbon\Carbon::parse($voucher->valid_until)->format('d-m-Y') }}</span></p>
                <!-- @php $claimedCount = isset($claimedCount) ? $claimedCount : 0; @endphp -->

                @if($voucher->used_count < $voucher->usage_limit && $voucher->valid_until > now() && $claimedCount == false) 
                <form action="{{ route('voucher.claim') }}" method="POST">
                    @csrf
                    <input type="hidden" name="code" value="{{ $voucher->code }}">
                    <button type="submit" class="btn btn-success btn-block">Klaim Voucher</button>
                </form>
                @else
                <button class="btn btn-secondary btn-block" disabled style="opacity: 0.5;">Klaim Voucher</button>                
                @endif
@endif
    <!-- Menampilkan Voucher yang Sudah Diklaim -->
   
    <h6 class="mt-4">Voucher yang Sudah Diklaim</h6>
    <div class="row"> <!-- Tambahkan baris untuk grid -->
        @foreach($claimedVouchers as $claimed_voucher)
        <div class="col-md-4"> <!-- Bagi menjadi 3 kolom -->
        <div class="card mb-3 voucher-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2 border-right">
                                    <i class="fa fa-ticket fa-2x text-primary"></i>
                                </div>
                                <div class="col-7">
                                @if($claimed_voucher->voucher->type == 'percentage')    
                                <h6 class="card-title mb-1">Diskon {{ $claimed_voucher->voucher->discount }} % S/& {{ rupiah($claimed_voucher->voucher->max_discount) }}</h6> <!-- Perbaikan di sini -->
                               @else
                               <h6 class="card-title mb-1">Diskon {{ rupiah($claimed_voucher->voucher->discount) }} </h6> <!-- Perbaikan di sini -->
                                @endif 
                                <p class="card-text mb-0">
                                          <span class="text-danger font-weight-bold">Berakhir pada tanggal: {{ \Carbon\Carbon::parse($claimed_voucher->voucher->valid_until)->format('d-m-Y') }}</span> <!-- Perbaikan di sini -->
                                    </p>
                                    <small class="text-muted">Min. belanja {{ rupiah($claimed_voucher->voucher->min_purchase) }}</small> <!-- Perbaikan di sini -->
                                </div>
                                <!-- <div class="col-3 text-right">
                                    <input type="radio" name="selected_voucher" value="${voucher.code}" class="voucher-radio" data-code="${voucher.code}">
                                </div> -->
                            </div>
                        </div>
                    </div>
        </div> <!-- Tutup kolom -->
        @endforeach
    </div> <!-- Tutup baris -->
 
</div>

@endsection