<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Feature\Order;
use App\Models\Master\Product;
use App\Models\Feature\OrderDetail;
use App\Repositories\CrudRepositories;
use App\Services\Feature\OrderService;
use App\Services\Midtrans\CreateSnapTokenService;
use Illuminate\Http\Request;
use PDF;

class TransacationController extends Controller
{   
    protected $orderService;
    protected $order;
    public function __construct(OrderService $orderService,Order $order)
    {
        $this->orderService = $orderService;
        $this->order = new CrudRepositories($order);
    }

    public function index()
    {
        $data['orders'] = $this->orderService->getUserOrder(auth()->user()->id);
        return view('frontend.transaction.index',compact('data'));
    }

    public function show($invoice_number)
    {
        $data['order'] = $this->order->Query()->where('invoice_number',$invoice_number)->first();
        $snapToken = $data['order']->snap_token;
        if (empty($snapToken)) {
            // Jika snap token masih NULL, buat token snap dan simpan ke database
            $midtrans = new CreateSnapTokenService($data['order']);
            $snapToken = $midtrans->getSnapToken();
            $data['order']->snap_token = $snapToken;
            $data['order']->save();
        }
        return view('frontend.transaction.show',compact('data'));
    }

    public function received($invoice_number)
    {
        $this->order->Query()->where('invoice_number',$invoice_number)->first()->update(['status' => 3]);
        return back()->with('success',__('message.order_received'));
    }

    public function canceled($invoice_number)
    {
        $order = $this->order->Query()->where('invoice_number', $invoice_number)->first();
        $order->update(['status' => 4]);

        // Assuming you have a relationship set up for order details
        foreach ($order->orderDetail as $orderDetail) {
            $product = Product::find($orderDetail->product_id);
            if ($product) {
                $product->penjualan -= $orderDetail->qty; // Decrease sales
                $product->stok += $orderDetail->qty; // Increase stock
                $product->save();
            }
        }
        return back()->with('success',__('message.order_canceled'));
    }
    public function download($invoice_number)
    {
        // Ambil data invoice berdasarkan invoice_number
        $data['order'] = $this->order->Query()
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('orders.invoice_number', $invoice_number)
            ->select('orders.*', 'order_details.*', 'products.*') // Pilih kolom yang diinginkan
            ->get();
    
        // Pastikan untuk mengirimkan 'order' dalam bentuk array jika lebih dari satu item
        $pdf = PDF::loadView('frontend.transaction.invoice', compact('data'));
    
        // Kembalikan PDF sebagai unduhan
        return $pdf->download('invoice_' . $invoice_number . '.pdf');
    }
}
