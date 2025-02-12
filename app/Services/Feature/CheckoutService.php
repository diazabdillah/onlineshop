<?php
namespace App\Services\Feature;

use App\Models\Feature\Order;
use App\Models\Feature\OrderDetail;
use App\Models\Voucher;
use App\Models\Master\Product;
use App\Repositories\CrudRepositories;
use Illuminate\Support\Str;


class CheckoutService{

    protected $order,$ordeDetail;
    protected $cartService;
    public function __construct(Order $order,OrderDetail $orderDetail,CartService $cartService)
    {
        $this->order = new CrudRepositories($order);
        $this->orderDetail = new CrudRepositories($orderDetail);
        $this->cartService = $cartService;
    }

    public function process($request)
    {
        $userCart = $this->cartService->getUserCart();
        $subtotal =  $userCart->sum('total_price_per_product');
        $discount = 0;

     
        $voucher_code = $request['voucher_code']; // Ambil kode voucher dari permintaan
        $voucher = Voucher::where('discount', $voucher_code)->first(); // Temukan voucher berdasarkan kode
     
        if ($voucher) { // Periksa apakah voucher valid
            if ($voucher->used_count < $voucher->usage_limit) { // Periksa apakah voucher masih dapat digunakan
                $discount = $subtotal - $request['voucher_code'];
                $voucher->increment('used_count');
            } else {
                // Tambahkan logika untuk menangani voucher yang sudah tidak dapat digunakan
                $discount = $subtotal; // Jika voucher sudah digunakan maksimal, tidak ada diskon
            }
        } else {
            $discount = $subtotal; // Jika voucher tidak valid, tidak ada diskon
        }

        $total_pay = $discount + $request['shipping_cost']; 
        $dataOrder = [
            'invoice_number' => strtoupper(Str::random('6')),
            'total_pay' => $total_pay,
            'recipient_name' => $request['recipient_name'],
            'destination' =>  $request['city_id'] . ', ' . $request['province_id'] ,
            'address_detail' => $request['address_detail'],
            'courier' => $request['courier'],
            'subtotal' => $subtotal,
            'voucher' => $request['voucher_code'],
            'shipping_cost' => $request['shipping_cost'],
            'shipping_method' => $request['shipping_method'],
            'total_weight' => $request['total_weight'],
            'status' => 0,
            'phone_number' => $request['phone_number'],
            'user_id' => auth()->user()->id
        ];
        $orderStore = $this->order->store($dataOrder);
        foreach($userCart as $cart){
            $this->orderDetail->store([
                'order_id' => $orderStore->id,
                'product_id' => $cart->product_id,
                'qty' => $cart->qty
            ]);
            $product = Product::with('orderDetails')
            ->where('id', $cart->product_id)
            ->first();
            $product->penjualan += $cart->qty; // Menambah jumlah penjualan
            $product->stok -= $cart->qty; // Mengurangi stok
            $product->save(); // Simpan perubahan
        }
        $this->cartService->deleteUserCart();
    }

}