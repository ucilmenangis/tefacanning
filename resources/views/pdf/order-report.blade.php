<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pesanan - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
        }

        .header {
            border-bottom: 3px solid #dc2626;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            vertical-align: top;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #dc2626;
        }

        .company-subtitle {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        .document-title {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }

        .document-number {
            text-align: right;
            font-size: 11px;
            color: #6b7280;
        }

        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-section td {
            vertical-align: top;
            width: 50%;
            padding: 0 10px;
        }

        .info-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px;
        }

        .info-box-neutral {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
        }

        .info-label {
            font-size: 9px;
            font-weight: bold;
            color: #dc2626;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-label-neutral {
            font-size: 9px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-row {
            margin-bottom: 3px;
        }

        .info-key {
            font-weight: bold;
            color: #374151;
            display: inline-block;
            width: 100px;
        }

        .info-value {
            color: #1f2937;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .products-table thead th {
            background-color: #dc2626;
            color: #ffffff;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .products-table thead th:first-child {
            border-radius: 6px 0 0 0;
        }

        .products-table thead th:last-child {
            border-radius: 0 6px 0 0;
            text-align: right;
        }

        .products-table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .products-table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .products-table .text-right {
            text-align: right;
        }

        .products-table .text-center {
            text-align: center;
        }

        .total-section {
            width: 100%;
            margin-bottom: 25px;
        }

        .total-section td {
            vertical-align: top;
        }

        .total-box {
            float: right;
            width: 250px;
        }

        .total-row {
            display: block;
            padding: 5px 10px;
            overflow: hidden;
        }

        .total-label {
            float: left;
            font-weight: bold;
            color: #374151;
        }

        .total-value {
            float: right;
            color: #1f2937;
        }

        .total-grand {
            background-color: #dc2626;
            color: #ffffff;
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 13px;
            overflow: hidden;
        }

        .total-grand .total-label,
        .total-grand .total-value {
            color: #ffffff;
        }

        .pickup-section {
            background-color: #f0fdf4;
            border: 2px dashed #22c55e;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }

        .pickup-code {
            font-size: 28px;
            font-weight: bold;
            color: #16a34a;
            letter-spacing: 5px;
            margin: 5px 0;
        }

        .pickup-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .notes-section {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .notes-label {
            font-size: 9px;
            font-weight: bold;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .notes-text {
            color: #78350f;
            font-style: italic;
        }

        .footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-ready {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-picked_up {
            background-color: #f3f4f6;
            color: #374151;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    <div class="company-name">TEFA Canning SIP</div>
                    <div class="company-subtitle">Teaching Factory Sarden Ikan Perikanan</div>
                    <div class="company-subtitle">Politeknik Negeri Jember</div>
                </td>
                <td style="width: 40%;">
                    <div class="document-title">LAPORAN PESANAN</div>
                    <div class="document-number">{{ $order->order_number }}</div>
                    <div class="document-number">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Customer & Order Info --}}
    <table class="info-section">
        <tr>
            <td style="padding-right: 10px;">
                <div class="info-box">
                    <div class="info-label">Informasi Pelanggan</div>
                    <div class="info-row">
                        <span class="info-key">Nama</span>
                        <span class="info-value">: {{ $order->customer->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Telepon</span>
                        <span class="info-value">: {{ $order->customer->phone ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Email</span>
                        <span class="info-value">: {{ $order->customer->email ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Organisasi</span>
                        <span class="info-value">: {{ $order->customer->organization ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Alamat</span>
                        <span class="info-value">: {{ $order->customer->address ?? '-' }}</span>
                    </div>
                </div>
            </td>
            <td style="padding-left: 10px;">
                <div class="info-box-neutral">
                    <div class="info-label-neutral">Informasi Pesanan</div>
                    <div class="info-row">
                        <span class="info-key">No. Pesanan</span>
                        <span class="info-value">: {{ $order->order_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Batch</span>
                        <span class="info-value">: {{ $order->batch->name ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Event</span>
                        <span class="info-value">: {{ $order->batch->event_name ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Tanggal Event</span>
                        <span class="info-value">: {{ $order->batch->event_date?->format('d M Y') ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Status</span>
                        <span class="info-value">:
                            <span class="status-badge status-{{ $order->status }}">
                                {{ match($order->status) {
                                    'pending' => 'Menunggu',
                                    'processing' => 'Diproses',
                                    'ready' => 'Siap Ambil',
                                    'picked_up' => 'Sudah Diambil',
                                    default => $order->status,
                                } }}
                            </span>
                        </span>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Products Table --}}
    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Produk</th>
                <th style="width: 15%;" class="text-center">Jumlah</th>
                <th style="width: 20%;" class="text-right">Harga Satuan</th>
                <th style="width: 25%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->products as $index => $product)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        @if($product->sku)
                            <br><span style="font-size: 9px; color: #6b7280;">SKU: {{ $product->sku }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($product->pivot->quantity, 0, ',', '.') }} {{ $product->unit ?? 'kaleng' }}</td>
                    <td class="text-right">Rp {{ number_format($product->pivot->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($product->pivot->subtotal, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Total --}}
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 55%;"></td>
            <td style="width: 45%;">
                <div class="total-row" style="border-bottom: 1px solid #e5e7eb;">
                    <span class="total-label">Subtotal</span>
                    <span class="total-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                @if($order->profit > 0)
                <div class="total-row" style="border-bottom: 1px solid #e5e7eb;">
                    <span class="total-label">Profit</span>
                    <span class="total-value">Rp {{ number_format($order->profit, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-grand">
                    <span class="total-label">TOTAL</span>
                    <span class="total-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- Pickup Code --}}
    <div class="pickup-section">
        <div class="pickup-label">Kode Pengambilan</div>
        <div class="pickup-code">{{ $order->pickup_code }}</div>
        <div class="pickup-label">Tunjukkan kode ini saat mengambil pesanan di kampus</div>
    </div>

    {{-- Notes --}}
    @if($order->notes)
        <div class="notes-section">
            <div class="notes-label">Catatan</div>
            <div class="notes-text">{{ $order->notes }}</div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem TEFA Canning SIP — Politeknik Negeri Jember</p>
        <p>Jl. Mastrip PO BOX 164, Jember, Jawa Timur 68121 • Dicetak: {{ now()->format('d M Y, H:i:s') }}</p>
    </div>
</body>
</html>
