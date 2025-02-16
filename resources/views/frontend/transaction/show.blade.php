@extends('layouts.frontend.app')
@section('content')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <a href="{{ route('transaction.index') }}"> Transaction</a>
                        <span>{{ $data['order']->invoice_number }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="shop-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice" style="border-top: 2px solid #6777ef;">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="invoice-title">
                                        <h2>Invoice</h2>
                                        <div class="invoice-number">Order {{ $data['order']->invoice_number }}</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <address>
                                                <strong>{{ __('text.billed_to') }}:</strong><br>
                                                {{ $data['order']->Customer->name }}<br>
                                                {{ $data['order']->Customer->email }}<br>
                                            </address>
                                        </div>
                                        <div class="col-md-6 text-md-right">
                                            <address>
                                                <strong>{{ __('text.shipped_to') }}:</strong><br>
                                                {{ $data['order']->recipient_name }}<br>
                                                {{ $data['order']->address_detail }}<br>
                                                {{ $data['order']->destination }}
                                            </address>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <address>
                                                <strong>{{ __('text.order_status') }}:</strong>
                                                <div class="mt-2">
                                                    {!! $data['order']->status_name !!}
                                                </div>
                                            </address>
                                        </div>
                                        <div class="col-md-6 text-md-right">
                                            <address>
                                                <strong>{{ __('text.order_date') }}:</strong><br>
                                                {{ $data['order']->created_at }}<br><br>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="section-title font-weight-bold">{{ __('text.order_summary') }}</div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-md">
                                            <tbody>
                                                <tr>
                                                    <th data-width="40" style="width: 40px;">#</th>
                                                    <th>{{ __('field.product_name') }}</th>
                                                    <th class="text-center">{{ __('field.price') }}</th>
                                                    <th class="text-center">{{ __('text.quantity') }}</th>
                                                    <th class="text-right">Total</th>
                                                    <th  class="text-right">Action</th>
                                                </tr>
                                                @foreach ($data['order']->orderDetail()->get() as $detail)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><a
                                                                href="{{ route('product.show', ['categoriSlug' => $detail->Product->category->slug, 'productSlug' => $detail->Product->slug]) }}">{{ $detail->product->name }}</a>
                                                        </td>
                                                        <td class="text-center">{{ rupiah($detail->product->price) }}
                                                        </td>
                                                        <td class="text-center">{{ $detail->qty }}</td>
                                                        <td class="text-right">
                                                            {{ rupiah($detail->total_price_per_product) }}</td>
                                                            <td class="text-right">   
                                                                <button class="btn btn-primary" 
                                                                        data-toggle="modal" 
                                                                        data-target="#modal-{{ $detail->product->id }}" 
                                                                        data-product-id="{{ $detail->product->id }}"
                                                                        {{ $data['order']->status == 'selesai' && !$detail->review ? '' : 'disabled' }}
                                                                        style="{{ $data['order']->status == 'selesai' && !$detail->review ? '' : 'cursor: not-allowed;' }}">
                                                                    Review Product
                                                                </button>
                                                            </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @php $details = $data['order']->orderDetail()->get(); @endphp
                                        @foreach ($details as $detail) 
                                        <div class="modal fade" 
                                            id="modal-{{ $detail->product->id }}" 
                                            tabindex="-1" 
                                            role="dialog" 
                                            aria-labelledby="modalLabel-{{ $detail->product->id }}" 
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel-{{ $detail->product->id }}">Review Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reviews.store', $detail->product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Product Info -->
                    <div class="product-info d-flex align-items-center">
                        <img src="{{ asset('storage/' . $detail->product->thumbnails ) }}" 
                             alt="{{ $detail->product->thumbnails }}" 
                             width="50" 
                             height="50" 
                             style="border-radius: 50%;">
                       &nbsp; &nbsp;<h6 class="mr-3">{{ $detail->product->name }}</h6>
                    </div>

                    <!-- Ratings -->
                    <div class="rating-css">
                        <label for="product_rating">Kualitas Produk</label> <br>
                        <div class="star-icon">
                            <input type="radio" value="1" name="rating" checked id="rating1-{{ $detail->product->id }}">
                            <label for="rating1-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="2" name="rating" id="rating2-{{ $detail->product->id }}">
                            <label for="rating2-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="3" name="rating" id="rating3-{{ $detail->product->id }}">
                            <label for="rating3-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="4" name="rating" id="rating4-{{ $detail->product->id }}">
                            <label for="rating4-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="5" name="rating" id="rating5-{{ $detail->product->id }}">
                            <label for="rating5-{{ $detail->product->id }}" class="fa fa-star"></label>
                        </div>
                    </div>

                    <!-- Seller Rating -->
                    <div class="rating-css">
                        <label for="seller_rating">Pelayanan Penjual</label> <br>
                        <div class="star-icon">
                            <input type="radio" value="1" name="rating_pelayanan" checked id="seller_rating1-{{ $detail->product->id }}">
                            <label for="seller_rating1-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="2" name="rating_pelayanan" id="seller_rating2-{{ $detail->product->id }}">
                            <label for="seller_rating2-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="3" name="rating_pelayanan" id="seller_rating3-{{ $detail->product->id }}">
                            <label for="seller_rating3-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="4" name="rating_pelayanan" id="seller_rating4-{{ $detail->product->id }}">
                            <label for="seller_rating4-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="5" name="rating_pelayanan" id="seller_rating5-{{ $detail->product->id }}">
                            <label for="seller_rating5-{{ $detail->product->id }}" class="fa fa-star"></label>
                        </div>
                    </div>

                    <!-- Delivery Rating -->
                    <div class="rating-css">
                        <label for="delivery_rating">Kecepatan Pengiriman</label> <br>
                        <div class="star-icon">
                            <input type="radio" value="1" name="rating_pengiriman" checked id="delivery_rating1-{{ $detail->product->id }}">
                            <label for="delivery_rating1-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="2" name="rating_pengiriman" id="delivery_rating2-{{ $detail->product->id }}">
                            <label for="delivery_rating2-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="3" name="rating_pengiriman" id="delivery_rating3-{{ $detail->product->id }}">
                            <label for="delivery_rating3-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="4" name="rating_pengiriman" id="delivery_rating4-{{ $detail->product->id }}">
                            <label for="delivery_rating4-{{ $detail->product->id }}" class="fa fa-star"></label>
                            <input type="radio" value="5" name="rating_pengiriman" id="delivery_rating5-{{ $detail->product->id }}">
                            <label for="delivery_rating5-{{ $detail->product->id }}" class="fa fa-star"></label>
                        </div>
                    </div>

                    <!-- Review Text -->
                    <div class="form-group">
                        <label for="review">Ulasan</label>
                        <textarea name="review" rows="4" cols="5" class="form-control" required></textarea>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label for="image">Upload Gambar (opsional):</label>
                        <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
                    </div>

                    <!-- Video Upload -->
                    <div class="form-group">
                        <label for="video">Upload Video (opsional):</label>
                        <input type="file" name="video" id="video" class="form-control-file" accept="video/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
   
</div>
@endforeach
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-8">
                                            <address>
                                                <strong>{{ __('text.shipping_method') }}:</strong>
                                                <div class="mt-2">
                                                    <p class="section-lead text-uppercase">{{ $data['order']->courier }}
                                                        {{ $data['order']->shipping_method }}</p>
                                                </div>
                                            </address>
                                            @if ($data['order']->receipt_number != null)
                                                <address>
                                                    <strong>{{ __('text.receipt_number') }}:</strong>
                                                    <div class="mt-2">
                                                        <p class="section-lead text-uppercase">
                                                            {{ $data['order']->receipt_number }}</p>
                                                    </div>
                                                </address>
                                            @endif
                                        </div>
                                        <div class="col-lg-4 text-right">
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Subtotal</div>
                                                <div class="invoice-detail-value">{{ rupiah($data['order']->subtotal) }}
                                                </div>
                                            </div>
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Voucher</div>
                                                <div class="invoice-detail-value">- {{ rupiah($data['order']->voucher) }}
                                                </div>
                                            </div>
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">{{ __('text.shipping_cost') }}</div>
                                                <div class="invoice-detail-value">
                                                    {{ rupiah($data['order']->shipping_cost) }}</div>
                                            </div>
                                            <hr class="mt-2 mb-2">
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Total</div>
                                                <div class="invoice-detail-value invoice-detail-value-lg">
                                                    {{ rupiah($data['order']->total_pay) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-md-right">
                            <div class="float-lg-left mb-lg-0 mb-3">
                                @if ($data['order']->status == 0)
                                    <button class="btn btn-primary btn-icon icon-left" id="pay-button"><i
                                            class="fa fa-credit-card"></i>
                                        Process Payment</button>
                                    <a href="{{ route('transaction.canceled', $data['order']->invoice_number) }}" class="btn btn-danger btn-icon icon-left"><i class="fa fa-times"></i>
                                        Cancel Order</a>
                                @elseif ($data['order']->status == 2)
                                    <a href="{{ route('transaction.received', $data['order']->invoice_number) }}"
                                        class="btn btn-primary text-white btn-icon icon-left"><i
                                            class="fa fa-credit-card"></i>
                                        Order Received</a>
                                @endif
                            </div>
                            <button class="btn btn-warning btn-icon icon-left"><i class="fa fa-print"></i> Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title">Order Track</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="activities">
                                        @foreach ($data['order']->OrderTrack()->get() as $orderTrack)
                                            <div class="activity">
                                                <div class="activity-icon bg-primary text-white shadow-primary">
                                                    <i class="{{ $orderTrack->icon }}"></i>
                                                </div>
                                                <div class="activity-detail bg-primary text-white">
                                                    <div class="mb-2">
                                                        <span class="text-job text-white">{{ $orderTrack->created_at->diffForHumans() }}</span>
                                                        <span class="bullet"></span>
                                                    </div>
                                                    <p>{{ __($orderTrack->description) }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        const payButton = document.querySelector('#pay-button');
        payButton.addEventListener('click', function(e) {
            e.preventDefault();

            snap.pay('{{ $data['order']->snap_token }}', {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                }
            });
        });
    </script>
@endpush
