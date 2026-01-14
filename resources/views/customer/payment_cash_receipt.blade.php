@extends('layouts.app')

@section('back_url', route('payment')) {{-- balik ke pilih metode --}}
@section('page_title', 'Struk Pembayaran')

@section('content')
<div class="receipt-page">

    {{-- STATUS BOX (atas) --}}
    <div class="receipt-alert">
        <div class="receipt-alert-icon">
            <i class="bi bi-receipt"></i>
        </div>
        <div class="receipt-alert-text">
            <div class="receipt-alert-title">MENUNGGU KONFIRMASI KASIR</div>
            <div class="receipt-alert-sub">Segera menuju ke kasir untuk menyelesaikan transaksi Anda secara tunai.</div>
        </div>
    </div>

    {{-- STRUK CARD --}}
    <div class="receipt-card">
        <div class="receipt-brand">
            <div class="receipt-brand-name">BAKMIE SIJEUNI</div>
            <div class="receipt-brand-sub">GRAND INDONESIA, BALI</div>
        </div>

        <div class="receipt-meta">
            <div class="receipt-meta-row">
                <div class="receipt-meta-label">ORDER ID</div>
                <div class="receipt-meta-value">{{ $orderId ?? '-' }}</div>
            </div>
            <div class="receipt-meta-row">
                <div class="receipt-meta-label">TABLE</div>
                <div class="receipt-meta-value">{{ $meja ?? '-' }}</div>
                
            </div>
            <div class="receipt-meta-row">
                <div class="receipt-meta-label">DATE</div>
                <div class="receipt-meta-value">
                    {{ isset($createdAt) ? \Carbon\Carbon::parse($createdAt)->format('d M Y, H:i') : now()->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        <hr class="receipt-divider">

        {{-- ITEMS --}}
        <div class="receipt-items">
            @forelse(($items ?? []) as $it)
                <div class="receipt-item">
                    <div class="receipt-item-left">
                        <div class="receipt-item-name">{{ $it['name'] ?? '-' }}</div>
                        <div class="receipt-item-qty">Qty: {{ $it['qty'] ?? 0 }}</div>
                    </div>
                    <div class="receipt-item-price">
                        Rp {{ number_format(($it['price'] ?? 0) * ($it['qty'] ?? 0), 0, ',', '.') }}
                    </div>
                </div>
            @empty
                <div class="text-muted">Tidak ada item.</div>
            @endforelse
        </div>

        <hr class="receipt-divider">

        {{-- TOTALS --}}
        <div class="receipt-totals">
            <div class="receipt-total-row">
                <div class="receipt-total-label">Subtotal</div>
                <div class="receipt-total-value">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="receipt-total-row">
                <div class="receipt-total-label">Pajak (10%)</div>
                <div class="receipt-total-value">Rp {{ number_format($tax ?? 0, 0, ',', '.') }}</div>
            </div>

            <div class="receipt-grand">
                <div class="receipt-grand-label">TOTAL</div>
                <div class="receipt-grand-value">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- BARCODE (opsional) --}}
        <div class="receipt-barcode">
            <div class="receipt-barcode-line"></div>
            <div class="receipt-barcode-code">
                {{ $barcode ?? 'SJ-' . strtoupper(substr(md5(($orderId ?? 'ORDER')), 0, 12)) }}
            </div>
        </div>
    </div>

    {{-- BUTTON SELESAI --}}
    <a href="{{ route('menu.index') }}" class="btn receipt-done">
        Selesai
    </a>

    <div class="receipt-note">
        <i class="bi bi-info-circle"></i>
        Silakan tunjukkan struk ini ke kasir untuk melakukan pembayaran.
    </div>
</div>
@endsection