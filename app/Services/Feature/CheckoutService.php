<?php
namespace App\Services\Feature;

use App\Models\Feature\Order;
use App\Models\Feature\OrderDetail;
use App\Models\Voucher;
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

        if ($request->has('code')) {
            $voucher = Voucher::where('code', $request->voucher_code)->first();
    
            if ($voucher && $voucher->isValid()) {
                $discount = $subtotal - $voucher->discount;
                $voucher->increment('used_count');
            }
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
            'shipping_cost' => $request['shipping_cost'],
            'shipping_method' => $request['shipping_method'],
            'total_weight' => $request['total_weight'],
            'status' => 0,
            'user_id' => auth()->user()->id
        ];
        $orderStore = $this->order->store($dataOrder);
        foreach($userCart as $cart){
            $this->orderDetail->store([
                'order_id' => $orderStore->id,
                'product_id' => $cart->product_id,
                'qty' => $cart->qty
            ]);
        }
        $this->cartService->deleteUserCart();
    }

}