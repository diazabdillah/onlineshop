<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Feature\Cart;
use App\Models\Voucher;
use App\Models\Setting\ShippingAddress;
use App\Repositories\CrudRepositories;
use App\Services\Feature\CartService;
use App\Services\Feature\CheckoutService;
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
        $data['carts'] = $this->cartService->getUserCart();
        $data['provinces'] = $this->rajaongkirService->getProvince();
        $data['shipping_address'] = ShippingAddress::first();
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
            return redirect()->back()->with('success', 'Voucher applied successfully.');
        }

        return redirect()->back()->with('error', 'Voucher is not valid.');
    }
 
}
