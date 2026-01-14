@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('menu.index') }}" class="icon-btn">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Detail Pesanan</h4>
    </div>

    <div class="row g-4">

        {{-- KIRI: ITEM --}}
        <div class="col-lg-8">

            <div class="text-uppercase small text-muted mb-2">Item Anda</div>

            @if(empty($cart))
                <div class="panel text-center py-5">
                    <p class="mb-3 text-muted">Keranjang masih kosong</p>
                    <a href="{{ route('menu.index') }}" class="btn btn-brand">
                        Pilih Menu
                    </a>
                </div>
            @else
                @foreach($cart as $c)
                <div class="panel mb-3">
                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="fw-bold">{{ $c['name'] }}</div>
                            <div class="text-muted small">
                                Rp {{ number_format($c['price'],0,',','.') }} (Qty: {{ $c['qty'] }})
                            </div>

                            @if(!empty($c['catatan']))
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-chat-left-text"></i>
                                    {{ $c['catatan'] }}
                                </div>
                            @endif
                        </div>

                        <div class="text-end">
                            <div class="small text-muted">Subtotal</div>
                            <div class="fw-bold">
                                Rp {{ number_format($c['price'] * $c['qty'],0,',','.') }}
                            </div>

                            <a href="{{ route('cart.remove', $c['id']) }}"
                               class="text-danger small d-block mt-2">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </div>

                    </div>
                </div>
                @endforeach
            @endif

        </div>

        {{-- KANAN: RINGKASAN --}}
        <div class="col-lg-4">

            <div class="panel" style="background:#3b2f2a;color:white">

                {{-- MEJA PINDAH KE SINI --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="fw-bold">Ringkasan Pesanan</div>
                    <div class="badge bg-warning text-dark">
                        MEJA {{ session('table') }}
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal,0,',','.') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>Pajak (10%)</span>
                    <span>Rp {{ number_format($tax,0,',','.') }}</span>
                </div>

                <hr class="border-light">

                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total</span>
                    <span class="text-warning">
                        Rp {{ number_format($total,0,',','.') }}
                    </span>
                </div>
            </div>

            <a href="{{ route('checkout') }}" class="btn btn-brand w-100 mt-3">
                Bayar Sekarang →
            </a>

            <a href="{{ route('menu.index') }}" class="btn btn-outline-brand w-100 mt-2">
                <i class="bi bi-book"></i> Kembali ke Menu
            </a>

        </div>

    </div>

</div>
@endsection