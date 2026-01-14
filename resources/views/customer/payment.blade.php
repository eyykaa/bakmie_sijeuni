@extends('layouts.app')
@section('back_url', route('cart.show'))
@section('page_title', 'Pilih Metode Pembayaran')

@section('content')
<div class="pay-page">

    <div class="pay-top">
        <div class="pay-total-label">TOTAL PEMBAYARAN</div>
        <div class="pay-total-value">Rp {{ number_format($total,0,',','.') }}</div>
    </div>

    <div class="pay-cards">
        <form method="POST" action="{{ route('pembayaran.store') }}" class="pay-form">
            @csrf

            <div class="pay-card-grid">
                <label class="pay-card {{ $selected==='transfer' ? 'active' : '' }}">
                    <input type="radio" name="method" value="transfer" {{ $selected==='transfer' ? 'checked' : '' }}>
                    <div class="pay-card-icon">🏛️</div>
                    <div class="pay-card-title">Transfer Bank</div>
                    <div class="pay-card-sub">VA, Mobile Banking, ATM</div>
                    <div class="pay-check">✓</div>
                </label>

                <label class="pay-card {{ $selected==='cash' ? 'active' : '' }}">
                    <input type="radio" name="method" value="cash" {{ $selected==='cash' ? 'checked' : '' }}>
                    <div class="pay-card-icon">💵</div>
                    <div class="pay-card-title">Tunai (Cash)</div>
                    <div class="pay-card-sub">Bayar di kasir restoran</div>
                    <div class="pay-check">✓</div>
                </label>
            </div>

            <div class="pay-info">
                <div class="pay-info-tx">
                    Pilih <b>Transfer Bank</b> untuk proses lebih cepat menggunakan Virtual Account.
                    Konfirmasi otomatis akan dilakukan setelah dana diterima.
                </div>
            </div>

            <div class="pay-bottom">
                <div class="pay-bottom-left">
                    <div class="pay-bottom-label">Metode Terpilih</div>
                    <div class="pay-bottom-value" id="selectedText">
                        {{ $selected === 'cash' ? 'Tunai (Cash)' : 'Transfer Bank' }}
                    </div>
                </div>

                <button type="submit" class="pay-save-btn">Simpan Pilihan</button>
            </div>
        </form>

        <a href="{{ route('pembayaran.lanjut') }}" class="pay-next">
            Lanjutkan Pembayaran <span>→</span>
        </a>

        <a class="pay-cancel" href="{{ route('cart.show') }}">Batal dan Kembali ke Pesanan</a>
    </div>

</div>

<script>
(function(){
  const radios = document.querySelectorAll('input[name="method"]');
  const txt = document.getElementById('selectedText');

  function sync(){
    const v = document.querySelector('input[name="method"]:checked')?.value || 'transfer';
    txt.textContent = (v === 'cash') ? 'Tunai (Cash)' : 'Transfer Bank';

    document.querySelectorAll('.pay-card').forEach(card => card.classList.remove('active'));
    document.querySelector('input[name="method"]:checked')?.closest('.pay-card')?.classList.add('active');
  }

  radios.forEach(r => r.addEventListener('change', sync));
  sync();
})();
</script>
@endsection