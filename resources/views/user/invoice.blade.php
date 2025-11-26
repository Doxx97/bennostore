<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Font ala struk */
            color: #333;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background: #f5f5f5; /* Abu-abu background luar */
        }
        .invoice-box {
            max-width: 400px; /* Lebar struk standar */
            margin: 0 auto;
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header { text-align: center; margin-bottom: 20px; }
        .store-name { font-size: 24px; font-weight: bold; text-transform: uppercase; margin: 0;}
        .store-address { font-size: 12px; margin-top: 5px; }
        
        .divider { border-bottom: 2px dashed #333; margin: 10px 0; }
        .divider-light { border-bottom: 1px dashed #ccc; margin: 10px 0; }

        .info-table { width: 100%; font-size: 12px; }
        .info-table td { padding: 2px 0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }

        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { text-align: left; font-size: 12px; border-bottom: 1px dashed #000; padding-bottom: 5px;}
        .items-table td { padding: 5px 0; font-size: 13px; vertical-align: top; }

        .total-section { margin-top: 10px; }
        
        .footer { text-align: center; margin-top: 20px; font-size: 11px; color: #555; }

        /* Tombol Print (Hilang saat diprint) */
        .btn-print {
            display: block;
            width: 100%;
            background: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-family: sans-serif;
            font-weight: bold;
        }
        
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-box { border: none; box-shadow: none; width: 100%; max-width: 100%; padding: 0; }
            .btn-print { display: none; } /* Sembunyikan tombol saat print */
        }
    </style>
</head>
<body onload="window.print()"> <div class="invoice-box">
        
        <div class="header">
            <div class="store-name">BENNO STORE</div>
            <div class="store-address">
                RT 31 RW 11 Desa Gembleb Kecamatan Pogalan<br>
                Kabupaten Trenggalek, Indonesia<br>
                Telp: 0812-3456-7890
            </div>
        </div>

        <div class="divider"></div>

        <table class="info-table">
            <tr>
                <td>No. Inv</td>
                <td class="text-right fw-bold">{{ $order->invoice_number }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="text-right">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td class="text-right">{{ $order->recipient_name }}</td>
            </tr>
            <tr>
                <td>Metode</td>
                <td class="text-right">{{ strtoupper($order->payment_method) }} / {{ strtoupper($order->delivery_method) }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">ITEM</th>
                    <th width="15%" class="text-center">QTY</th>
                    <th width="35%" class="text-right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}<br>
                        <span style="font-size: 11px; color: #777;">@ Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <table class="info-table total-section">
            <tr>
                <td class="fw-bold" style="font-size: 16px;">TOTAL BAYAR</td>
                <td class="text-right fw-bold" style="font-size: 16px;">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="footer">
            Terima Kasih telah berbelanja di Benno Store.<br>
            Simpan struk ini sebagai bukti pembayaran.<br>
            <i>*Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</i>
        </div>

        <a href="javascript:window.print()" class="btn-print">Cetak Struk</a>
        <a href="{{ route('my.orders') }}" class="btn-print" style="background: #6c757d; margin-top: 10px;">Kembali</a>

    </div>

</body>
</html>