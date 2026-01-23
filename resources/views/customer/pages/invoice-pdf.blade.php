<!DOCTYPE html>
<html>

<head>
    <title>Invoice {{ $transaction->invoice }}</title>
    <style>
        /* Simple CSS yang work di DomPDF */
        body {
            font-family: "DejaVu Sans", "Helvetica", Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #3b82f6;
            font-size: 24px;
            margin: 0;
        }

        .info-section {
            display: block;
            margin-bottom: 30px;
        }

        .info-box {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        .info-title {
            background-color: #3b82f6;
            color: white;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            background-color: #3b82f6;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-section {
            float: right;
            width: 300px;
            margin-top: 30px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .grand-total {
            border-top: 2px solid #3b82f6;
            padding-top: 10px;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 100px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }

        .status {
            display: inline-block;
            padding: 4px 12px;
            background-color: {{ $statusInfo['color'] }};
            color: white;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>INVOICE</h1>
        <div style="margin: 10px 0;">
            <strong>No. Invoice:</strong> {{ $transaction->invoice }} |
            <strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y') }} |
            <strong>Status:</strong> <span class="status">{{ $statusInfo['label'] }}</span>
        </div>
    </div>

    <!-- Company & Customer Info -->
    <div class="info-section">
        <div class="info-box">
            <div class="info-title">DARI</div>
            <div>
                <strong>{{ $company['name'] }}</strong><br>
                {{ $company['address'] }}<br>
                Email: {{ $company['email'] }}<br>
                Telp: {{ $company['phone'] }}
            </div>
        </div>

        <div class="info-box">
            <div class="info-title">KEPADA</div>
            <div>
                <strong>{{ $transaction->user->name }}</strong><br>
                Email: {{ $transaction->user->email }}<br>
                @if ($transaction->user->phone)
                    Telp: {{ $transaction->user->phone }}<br>
                @endif
                Customer ID: {{ $transaction->user_id }}
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <!-- Order Items -->
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th style="width: 100px;" class="text-right">Harga</th>
                <th style="width: 50px;" class="text-center">Qty</th>
                <th style="width: 120px;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name ?? 'Produk' }}
                        @if ($item->nominal)
                            <br><small>{{ $item->nominal->name }}</small>
                        @endif
                        @if ($item->phone)
                            <br><small>No: {{ $item->phone }}</small>
                        @endif
                        @if ($item->voucher_code)
                            <br><small>Voucher: {{ $item->voucher_code }}</small>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Section -->
    <div class="total-section">
        <div class="total-row">
            <span>Subtotal Produk:</span>
            <span>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>Biaya Admin:</span>
            <span>Rp {{ number_format($transaction->fee ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="total-row grand-total">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($total_amount, 0, ',', '.') }}</span>
        </div>
    </div>
    <div class="clearfix"></div>

    <!-- Payment Info -->
    <div style="margin-top: 50px;">
        <div style="background-color: #f0f9ff; padding: 15px; border-radius: 5px;">
            <div style="font-weight: bold; color: #3b82f6; margin-bottom: 10px;">Informasi Pembayaran</div>
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <div style="font-size: 11px; color: #666;">Metode Pembayaran</div>
                    <div style="font-weight: bold;">{{ $paymentLabel }}</div>
                </div>
                <div>
                    <div style="font-size: 11px; color: #666;">Tanggal Pesanan</div>
                    <div>{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @if ($transaction->paid_at)
                    <div>
                        <div style="font-size: 11px; color: #666;">Dibayar Pada</div>
                        <div>{{ $transaction->paid_at->format('d/m/Y H:i') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div style="text-align: center;">
            Invoice ini sah sebagai bukti pembayaran digital.<br>
            Simpan invoice untuk keperluan klaim atau garansi.<br>
            <strong>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</strong>
        </div>
    </div>
</body>

</html>
