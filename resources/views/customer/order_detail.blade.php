@extends('layouts.app')

@section('back_url', route('menu.index'))
@section('page_title', 'Detail Pesanan')

@section('content')
<div class="container py-4">

  {{-- HEADER TOP RIGHT: MEJA --}}
  <div class="d-flex justify-content-between align-items-start mb-4">
    <div></div>

    <div class="text-end" style="line-height:1.1;">
      <div style="font-size:12px; letter-spacing:1px; color:#7a7a7a;">MEJA</div>
      <div style="font-weight:800; font-size:14px;">
        {{ $tableNo ? 'MEJA ' . $tableNo : ($tableText ?? 'MEJA ?') }}
      </div>
    </div>
  </div>

  <div class="row g-4">
    {{-- LEFT: LIST ITEM --}}
    <div class="col-lg-8">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div style="font-size:12px; letter-spacing:2px; color:#8a8a8a; font-weight:700;">ITEM ANDA</div>
        <div style="font-size:14px; color:#7a7a7a;">{{ $count }} Item Terpilih</div>
      </div>

      @forelse($items as $it)
        <div class="card mb-3" style="border-radius:16px; border:1px solid #eef0f4;">
          <div class="card-body">
            <div class="d-flex gap-3">
              {{-- IMAGE --}}
              <div style="width:110px; height:80px; overflow:hidden; border-radius:14px; background:#f3f4f6; flex:0 0 auto;">
                @if(!empty($it['image']))
                  <img src="{{ asset($it['image']) }}" style="width:100%; height:100%; object-fit:cover;">
                @endif
              </div>

              {{-- INFO --}}
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <div style="font-weight:800; font-size:18px;">{{ $it['name'] }}</div>
                    <div style="font-weight:800; color:#d18b14;">
                      Rp {{ number_format($it['price'],0,',','.') }}
                      <span style="color:#8a8a8a; font-weight:600;">(Qty: {{ $it['qty'] }})</span>
                    </div>
                  </div>

                  {{-- DELETE (POST) --}}
                  <form action="{{ route('cart.remove') }}" method="POST" style="margin:0;">
                    @csrf
                    <input type="hidden" name="key" value="{{ $it['key'] }}">
                    <button type="submit" class="btn" style="border:none; background:transparent; padding:0;">
                      <span style="color:#e11d48; font-size:22px;">🗑️</span>
                    </button>
                  </form>
                </div>

                {{-- NOTE --}}
                @if(!empty($it['note']))
                  <div class="mt-2" style="background:#f6f7fb; border-radius:10px; padding:10px 12px; color:#6b7280;">
                    <b>Note:</b> {{ $it['note'] }}
                  </div>
                @endif

                <div class="text-end mt-2" style="color:#9aa0a6; font-size:12px; font-weight:700; letter-spacing:1px;">
                  SUBTOTAL
                </div>
                <div class="text-end" style="font-weight:900; font-size:20px;">
                  Rp {{ number_format($it['line_subtotal'],0,',','.') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="alert alert-light" style="border-radius:14px;">
          Keranjang masih kosong.
        </div>
      @endforelse

      {{-- TAMBAH ITEM --}}
      <a href="{{ route('menu.index') }}"
         class="d-flex flex-column justify-content-center align-items-center text-decoration-none"
         style="height:170px; border:2px dashed #e5e7eb; border-radius:18px; color:#9ca3af;">
        <div style="font-size:28px;">➕</div>
        <div style="font-weight:700;">Tambah item lainnya?</div>
      </a>
    </div>

    {{-- RIGHT: SUMMARY --}}
    <div class="col-lg-4">
      <div style="background:#6b4b3f; color:#fff; border-radius:22px; padding:22px;">
        <div class="d-flex align-items-center gap-2 mb-3">
          <div style="width:40px; height:40px; background:rgba(255,255,255,.15); border-radius:12px; display:flex; align-items:center; justify-content:center;">🧾</div>
          <div style="font-weight:900; font-size:18px;">Ringkasan Pesanan</div>
        </div>

        <div class="d-flex justify-content-between mb-2" style="color:rgba(255,255,255,.85);">
          <div>Subtotal</div>
          <div>Rp {{ number_format($subtotal,0,',','.') }}</div>
        </div>
        <div class="d-flex justify-content-between mb-3" style="color:rgba(255,255,255,.85);">
          <div>Pajak (10%)</div>
          <div>Rp {{ number_format($tax,0,',','.') }}</div>
        </div>

        <hr style="border-color: rgba(255,255,255,.25);">

        <div style="color:rgba(255,255,255,.7); font-size:12px; letter-spacing:2px; font-weight:800;">
          TOTAL PEMBAYARAN
        </div>
        <div style="font-weight:1000; font-size:34px; color:#f5a623;">
          Rp {{ number_format($total,0,',','.') }}
        </div>
      </div>

      <a href="{{ route('pembayaran.show') }}"
         class="btn w-100 mt-3"
         style="background:#f5a623; color:#fff; font-weight:900; border-radius:16px; padding:16px 18px;">
        Bayar Sekarang →
      </a>

      <a href="{{ route('menu.index') }}"
         class="btn w-100 mt-2"
         style="background:#fff; border:1px solid #e5e7eb; font-weight:800; border-radius:16px; padding:14px 18px;">
        📖 Kembali ke Menu
      </a>

      <div class="mt-3 p-3" style="background:#fff7ed; border:1px solid #fde68a; border-radius:14px; color:#92400e;">
        <b>Info:</b> Nikmati promo potongan Rp 10.000 untuk pembayaran menggunakan <b>Transfer Bank</b>.
      </div>
    </div>
  </div>

</div>
@endsection