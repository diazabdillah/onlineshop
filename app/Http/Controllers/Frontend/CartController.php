<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Feature\Cart;
use App\Models\Master\Product;
use App\Repositories\CrudRepositories;
use App\Services\Feature\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cart;
    protected $cartService;
    public function __construct(Cart $cart,CartService $cartService)
    {
        $this->cart = new CrudRepositories($cart);
        $this->cartService = $cartService;
    }

    public function index()
    {
        $data['carts'] = $this->cart->Query()->where('user_id',auth()->user()->id)->get();
        return view('frontend.cart.index',compact('data'));
    }

    public function store(Request $request)
    {
        try {
           $this->cartService->store($request);
            return redirect()->route('cart.index')->with('success',__('message.cart_success'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function delete($id)
    {
        $cart = $this->cart->hardDelete($id);
        return back()->with('success',__('message.cart_delete'));
    }

    public function update(Request $request)
    {
        try {
            $i = 0;
            foreach($request['cart_id'] as $cart_id)
            {
                $cart = $this->cart->find($cart_id);
                $cart->qty = $request['cart_qty'][$i];
                $cart->save();
                $i++;
            }
            return redirect()->route('cart.index')->with('success',__('message.cart_update'));
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function updateCart(Request $request)
{
    $request->validate([
        'cart_id' => 'required|integer',
        'cart_qty' => 'required|integer|min:1',
    ]);

    // Temukan item keranjang berdasarkan ID
    $cartItem = Cart::find($request->cart_id);
    
    if ($cartItem) {
        // Perbarui kuantitas
        $cartItem->qty = $request->cart_qty;
        $cartItem->save();

        // Hitung total harga per produk
        $totalPricePerProduct = $cartItem->qty * $cartItem->Product->price; // Ganti dengan logika harga yang sesuai
        if ($cartItem->Product->discounted_price) {
            $totalPricePerProduct = $cartItem->qty * $cartItem->Product->discounted_price;
        }

        // Hitung total harga keranjang
        $totalCartPrice = Cart::sum('total_price_per_product'); // Ganti dengan logika yang sesuai jika perlu

        // Kembalikan respons JSON
        return response()->json([
            'total_price_per_product' => $totalPricePerProduct,
            'total_cart_price' => $totalCartPrice,
        ]);
    }
}
}
