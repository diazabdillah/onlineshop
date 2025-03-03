<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $data['order'][0]->invoice_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #34495e;
        }
        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 30px;
            margin-bottom: 30px;
            border-bottom: 2px solid #ecf0f1;
        }
        .header img {
            max-width: 180px;
        }
        .header h1 {
            font-size: 40px;
            color: #2c3e50;
            margin: 10px 0;
        }
        .header p {
            font-size: 10px;
            color: #7f8c8d;
        }
        .header .invoice-number {
            font-size: 10px;
            font-weight: bold;
            color: #e74c3c;
        }
        .section {
            margin-bottom: 30px;
        }
        .section table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        .section table th,
        .section table td {
            padding: 15px;
            text-align: left;
            font-size: 10px;
            border-bottom: 1px solid #ecf0f1;
        }
        .section table th {
            background-color: #f9fafb;
            color: #34495e;
            font-weight: bold;
        }
        .section table td {
            color: #7f8c8d;
        }
        .section table tr:hover {
            background-color: #f4f4f9;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .total table {
            width: 100%;
            text-align: right;
        }
        .total table td {
            padding: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .total .final-total td {
            font-size: 10px;
            color: #27ae60;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #7f8c8d;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer .thank-you {
            font-size: 20px;
            font-weight: bold;
            color: #2ecc71;
        }
        .order-info td {
            color: #34495e;
        }
        .order-info th {
            font-weight: bold;
            color: #34495e;
        }

    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <!-- Logo Perusahaan -->
            <img src="{{ public_path('/ashion/img/logo.jpg') }}" width="100px">
            <h4>Invoice</h4> <small class="invoice-number">#{{ $data['order'][0]->invoice_number }}</small>
        </div>

        <!-- Dibayar Oleh dan Dikirim Ke -->
        <div class="section order-info">
            <table>
                <tr>
                    <th>Dibayar Oleh:</th>
                    <td>{{ $data['order'][0]->recipient_name }}</td>
                </tr>
                <tr>
                    <th>Alamat:</th>
                    <td>{{ $data['order'][0]->address_detail }}</td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td>{{ $data['order'][0]->phone_number }}</td>
                </tr>
                <tr>
                    <th>Destination:</th>
                    <td>{{ $data['order'][0]->destination }}</td>
                </tr>
                <tr>
                    <th>Metode Pengiriman:</th>
                    <td>{{ $data['order'][0]->courier }}</td>
                </tr>
                <tr>
                    <th>Estimasi Pengiriman:</th>
                    <td>{{ $data['order'][0]->shipping_method }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pesanan:</th>
                    <td>{{ \Carbon\Carbon::parse($data['order'][0]->created_at)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Status Pesanan:</th>
                    <td>
                       {!! $data['order'][0]->status_name !!}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['order'] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>Rp {{ number_format(floatval($item->price), 0, ',', '.') }}</td>
                        <td>Rp {{ number_format(floatval($item->qty) * floatval($item->price), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="total">
            <table>
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td>Rp {{ number_format($data['order'][0]->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Voucher:</strong></td>
                    <td>- Rp {{ number_format($data['order'][0]->voucher, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Ongkos Kirim:</strong></td>
                    <td>Rp {{ number_format($data['order'][0]->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                <tr class="final-total">
                    <td><strong>Total:</strong></td>
                    <td>Rp {{ number_format($data['order'][0]->total_pay, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="thank-you">Thank you for your business!</p>
            <p>If you have any questions, feel free to contact us.</p>
            <p>www.yourcompany.com | info@yourcompany.com</p>
        </div>
    </div>
</body>
</html>
