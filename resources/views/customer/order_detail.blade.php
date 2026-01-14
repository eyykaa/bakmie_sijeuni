@extends('layouts.app')
@section('back_url', route('menu.index'))
@section('page_title', 'Detail Pesanan')

@section('content')
@php
    $cart = session('cart', []);
    $count = 0;
    $subtotal = 0;

    foreach ($cart as $row) {
        $qty = (int)($row['qty'] ?? 0);
        $price = (int)($row['price'] ?? 0);
        $count += $qty;
        $subtotal += ($qty * $price);
    }

    $tax = (int) round($subtotal * 0.10);
    $total = $subtotal + $tax;

    // ✅ ambil meja dari 1 sumber utama (table_no)
    $tableNo = session('table_no');

    // fallback: kalau masih ada session('meja') dari route lama
    $mejaText = session('meja'); // contoh: "MEJA 7"
    if (!$tableNo && $mejaText) {
        if (preg_match('/\d+/', $mejaText, $m)) {
            $tableNo = (int) $m[0];
        }
    }
@endphp

<div class="order-page">

    {{-- TOP RIGHT: MEJA --}}
    <div class="order-top-right">
        <div class="order-meja-box">
            <div class="order-meja-label">MEJA</div>
            <div class="order-meja-value">MEJA {{ $tableNo ?? '-' }}</div>
        </div>

        <div class="order-meja-icon" title="Meja">🪑</div>
    </div>

    <div class="order-body">
        <div class="order-grid">

            {{-- LEFT: LIST ITEM --}}
            <div class="order-left">

                <div class="order-left-head">
                    <div class="order-left-title">ITEM ANDA</div>
                    <div class="order-left-count">{{ $count }} Item Terpilih</div>
                </div>

                @if(empty($cart))
                    <div class="order-empty">
                        <div class="order-empty-plus">+</div>
                        <div class="order-empty-text">Tambah item lainnya?</div>
                        <a class="order-empty-btn" href="{{ route('menu.index') }}">Kembali ke Menu</a>
                    </div>
                @else
                    @foreach($cart as $row)
                        @php
                            $qty = (int)($row['qty'] ?? 0);
                            $price = (int)($row['price'] ?? 0);
                            $line = $qty * $price;
                            $img = $row['image'] ?? '';
                            $name = $row['name'] ?? '-';
                            $note = $row['note'] ?? '';
                            $id = $row['id'] ?? null;
                        @endphp

                        <div class="order-item">
                            <div class="order-item-img">
                                <img src="{{ $img ? asset('images/'.$img) : asset('images/placeholder.jpg') }}" alt="{{ $name }}">
                            </div>

                            <div class="order-item-mid">
                                <div class="order-item-name">{{ $name }}</div>
                                <div class="order-item-price">
                                    Rp {{ number_format($price,0,',','.') }}
                                    <span class="order-item-qty">(Qty: {{ $qty }})</span>
                                </div>

                                @if($note)
                                    <div class="order-item-note">
                                        <span class="order-item-note-ic">📝</span>
                                        <span><i>Note:</i> {{ $note }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="order-item-right">
                                <form action="{{ route('cart.remove') }}" method="POST" class="order-remove-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <button type="submit" class="order-remove-btn">
                                        <span class="order-remove-ic">🗑</span>
                                        <span class="order-remove-tx">Hapus</span>
                                    </button>
                                </form>
                                <div class="order-item-subtotal-label">SUBTOTAL</div>
                                <div class="order-item-subtotal">
                                    Rp {{ number_format($line,0,',','.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <a class="order-addmore" href="{{ route('menu.index') }}">
                        <div class="order-addmore-plus">+</div>
                        <div class="order-addmore-text">Tambah item lainnya?</div>
                    </a>
                @endif
            </div>

            {{-- RIGHT: SUMMARY --}}
            <div class="order-right">
                <div class="order-summary">
                    <div class="order-summary-head">
                        <div class="order-summary-ic">🧾</div>
                        <div class="order-summary-title">Ringkasan Pesanan</div>
                    </div>

                    <div class="order-summary-row">
                        <div class="order-summary-label">Subtotal</div>
                        <div class="order-summary-val">Rp {{ number_format($subtotal,0,',','.') }}</div>
                    </div>

                    <div class="order-summary-row">
                        <div class="order-summary-label">Pajak (10%)</div>
                        <div class="order-summary-val">Rp {{ number_format($tax,0,',','.') }}</div>
                    </div>

                    <div class="order-summary-total">
                        <div class="order-summary-total-label">TOTAL PEMBAYARAN</div>
                        <div class="order-summary-total-value">Rp {{ number_format($total,0,',','.') }}</div>
                    </div>
                </div>

                <a class="order-pay" href="{{ route('pembayaran.show') }}">
                    Bayar Sekarang <span>→</span>
                </a>

                <a class="order-back" href="{{ route('menu.index') }}">
                    📖 Kembali ke Menu
                </a>

                <div class="order-promo">
                    <div class="order-promo-text">
                        Nikmati promo potongan Rp 10.000 untuk pembayaran menggunakan <b>Transfer Bank</b>.
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection