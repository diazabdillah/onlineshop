<?php

namespace App\Http\Controllers;
use App\Models\Voucher;
use App\Models\ClaimedVoucher;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
   // Menampilkan halaman pencarian voucher
   public function index()
   {
       $user = Auth::user();
       $claimedVouchers = ClaimedVoucher::where('user_id', $user->id)->with('voucher')->get();

       return view('frontend.voucher.index', compact('claimedVouchers'));
   }

   // Mencari voucher berdasarkan kode
   public function search(Request $request)
   {
    $request->validate(['code' => 'required|string']);

    $voucher = Voucher::where('code', $request->code)
        ->where('valid_until', '>', now()) // Menambahkan kondisi untuk memeriksa tanggal kedaluwarsa
        ->first();

    if (!$voucher) {
        return back()->with('error', 'Voucher tidak ditemukan atau sudah kadaluarsa');
    }

    $user = Auth::user(); // Mendapatkan pengguna yang sedang login
    $claimedCount = ClaimedVoucher::where('voucher_id', $voucher->id)
    ->where('user_id', $user->id) // Menambahkan kondisi untuk memeriksa klaim berdasarkan user
    ->exists(); // Menggunakan exists untuk memeriksa apakah klaim ada

    return back()->with(['voucher' => $voucher, 'claimedCount' => $claimedCount]); // Memperbaiki format pengembalian
   }
   // Klaim voucher
   public function claim(Request $request)
   {
       $request->validate(['code' => 'required|string']);

       $voucher = Voucher::where('code', $request->code)->first();

       if (!$voucher) {
           return back()->with('error', 'Voucher tidak ditemukan');
       }

       if ($voucher->valid_until < now()) {
           return back()->with('error', 'Voucher sudah kadaluarsa');
       }

       if ($voucher->usage_limit <= 0) {
           return back()->with('error', 'Voucher sudah habis');
       }

       $user = Auth::user();

       if (ClaimedVoucher::where('user_id', $user->id)->where('voucher_id', $voucher->id)->exists()) {
           return back()->with('error', 'Anda sudah mengklaim voucher ini');
       }

       // Simpan klaim voucher
       ClaimedVoucher::create([
           'user_id' => $user->id,
           'voucher_id' => $voucher->id
       ]);

       // Kurangi kuota voucher
    //    $voucher->increment('used_count');

       return back()->with('success', 'Voucher berhasil diklaim');
   }
}
