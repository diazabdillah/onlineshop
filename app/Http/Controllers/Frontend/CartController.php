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
    
        // Find the cart item by ID
        $cartItem = Cart::find($request->cart_id);
    
        if ($cartItem) {
            // Update quantity
            $cartItem->qty = $request->cart_qty;
            $cartItem->save();
    
            // Calculate total price per product based on quantity and discounted price (if any)
            $totalPricePerProduct = $cartItem->qty * $cartItem->Product->price; // Default price logic
            if ($cartItem->Product->discounted_price) {
                $totalPricePerProduct = $cartItem->qty * $cartItem->Product->discounted_price;
            }
    
            // Calculate the total price of the cart (across all items)
            $totalCartPrice = 0;
            $cartItems = Cart::all(); // Get all cart items
            foreach ($cartItems as $item) {
                $totalCartPrice += $item->qty * ($item->Product->discounted_price ?: $item->Product->price);
            }
    
            // Return updated data to the AJAX response
            return response()->json([
                'total_price_per_product' => $totalPricePerProduct,
                'total_cart_price' => $totalCartPrice,
            ]);
        }
    
        return response()->json(['error' => 'Cart item not found'], 404);
    }
    
    public function getCartData()
    {
        // Assuming you want to get the cart items for a user
        $cartItems = Cart::with('Product')->get(); // Adjust this query to filter by user if needed
    
        // Calculate the total cart price
        $totalCartPrice = $cartItems->sum(function($item) {
            return $item->qty * ($item->Product->discounted_price ?: $item->Product->price);
        });
    
        // Return the data as JSON
        return response()->json([
            'cart_items' => $cartItems,
            'total_cart_price' => $totalCartPrice,
        ]);
    }
}
