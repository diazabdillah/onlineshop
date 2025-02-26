<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Feature\Cart;
use App\Models\Voucher;
use App\Models\ClaimedVoucher;
use App\Models\Setting\ShippingAddress;
use App\Repositories\CrudRepositories;
use App\Services\Feature\CartService;
use App\Services\Feature\CheckoutService;
use Illuminate\Support\Facades\Auth;
use App\Services\Rajaongkir\RajaongkirService;
use Exception;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{

    protected $rajaongkirService,$checkoutService,$cartService;
    public function __construct(RajaongkirService $rajaongkirService,CheckoutService $checkoutService,CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->rajaongkirService = $rajaongkirService;
        $this->checkoutService = $checkoutService;
    }
public function index()
    {
        $user = Auth::user();
        $data['profile'] = $user->profile()
        ->join('users', 'profile.user_id', '=', 'users.id') // Melakukan join dengan tabel users
        ->select('profile.*', 'users.name', 'users.email') // Memilih kolom yang diinginkan
        ->first();
        $data['carts'] = $this->cartService->getUserCart();
        $data['provinces'] = $this->rajaongkirService->getProvince();
        $data['shipping_address'] = ShippingAddress::first();
        $data['vouchers'] = Voucher::where('valid_until', '>=', now())
        ->whereColumn('used_count', '<', 'usage_limit')
        ->get();
    
    // Check if there are any vouchers before accessing the id
    $data['klaimvoucher'] = ClaimedVoucher::where('user_id', Auth::id())
        ->whereIn('voucher_id', $data['vouchers']->pluck('id')) // Mengambil semua voucher yang valid
        ->get();
        return view('frontend.checkout.index',compact('data'));
    }
    

    public function process(Request $request)
    {
        try{
            $this->checkoutService->process($request->all());
            return redirect()->route('transaction.index')->with('success',__('message.order_success'));
        }catch(Exception $e){
            dd($e);
        }
    }
   
    public function apply(Request $request)
    {
        // $request->validate([
        //     'code' => 'required|exists:vouchers,code',
        // ]);
        $voucher = Voucher::where('code', $request->code)->first();

        if ($voucher && $voucher->isValid()) { // Memastikan $voucher tidak null
            // Apply voucher logic here
            return response()->json([
                'success' => 'Voucher applied successfully.',
                'data' => $voucher // Menyertakan data voucher yang diterapkan
            ]);
        }

        return response()->json(['error' => 'Voucher is not valid.']);
    }
    public function listVouchers() {
        $vouchers = Voucher::where('valid_until', '>=', now())
        ->whereColumn('used_count', '<', 'usage_limit')
        // Menghapus kondisi untuk mengecualikan voucher yang sudah diklaim
        ->get();

        return response()->json(['vouchers' => $vouchers]);
    }

    // Klaim voucher (misalnya menyimpannya ke database pengguna jika perlu)
    public function claimVoucher(Request $request)
    {
        $voucher = Voucher::where('code', $request->code)->first();
        if (!$voucher) {
            return response()->json(['message' => 'Voucher tidak ditemukan.'], 404);
        }

        // Cek apakah pengguna sudah klaim sebelumnya
        $isClaimed = UserVoucher::where('user_id', Auth::id())->where('voucher_id', $voucher->id)->exists();
        if ($isClaimed) {
            return response()->json(['message' => 'Anda sudah klaim voucher ini.'], 400);
        }

        // Simpan klaim voucher
        UserVoucher::create([
            'user_id' => Auth::id(),
            'voucher_id' => $voucher->id,
        ]);

        return response()->json(['message' => 'Voucher berhasil diklaim.'], 200);
    }

    // Terapkan Voucher
    public function applyVoucher(Request $request)
    {
        $voucher = Voucher::where('code', $request->code)->first();
        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak ditemukan.']);
        }

        if ($request->total < $voucher->min_purchase) {
            return response()->json(['success' => false, 'message' => 'Minimal pembelian belum terpenuhi.']);
        }

        // Hitung diskon
        if ($voucher->type === 'percentage') {
            $discount = ($voucher->discount / 100) * $request->total;
            if ($discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
        } else {
            $discount = $voucher->discount;
        }

        return response()->json([
            'success' => true,
            'data' => ['discount' => $discount],
        ]);
    }

}
