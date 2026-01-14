@extends('layouts.app')

@section('back_url', route('cart.show'))
@section('page_title', 'Pilih Metode Pembayaran')

@section('content')
<div class="pay-page">

  <div class="pay-top text-center">
    <div class="pay-label">TOTAL PEMBAYARAN</div>
    <div class="pay-total">Rp {{ number_format($total ?? 0,0,',','.') }}</div>
  </div>

  {{-- FORM PILIH METODE (POST) --}}
  <form action="{{ route('pembayaran.lanjut') }}" method="POST" class="pay-form">
  @csrf
  ...
</form>

    <div class="pay-grid">

      {{-- TRANSFER --}}
      <input class="pay-radio" type="radio" name="method" id="pay_transfer" value="transfer"
        {{ ($selected ?? 'transfer') === 'transfer' ? 'checked' : '' }}>
      <label class="pay-card" for="pay_transfer">
        <div class="pay-icon-circle">
          <i class="bi bi-bank"></i>
        </div>
        <div>
          <div class="pay-card-title">Transfer Bank</div>
          <div class="pay-card-sub">VA, Mobile Banking, ATM</div>
        </div>
        <div class="pay-check"><i class="bi bi-check-lg"></i></div>
      </label>

      {{-- CASH --}}
      <input class="pay-radio" type="radio" name="method" id="pay_cash" value="cash"
        {{ ($selected ?? '') === 'cash' ? 'checked' : '' }}>
      <label class="pay-card" for="pay_cash">
        <div class="pay-icon-circle">
          <i class="bi bi-cash-stack"></i>
        </div>
        <div>
          <div class="pay-card-title">Tunai (Cash)</div>
          <div class="pay-card-sub">Bayar di kasir</div>
        </div>
        <div class="pay-check"><i class="bi bi-check-lg"></i></div>
      </label>

    </div>

    {{-- INFO BOX --}}
    <div class="pay-info">
      <div class="pay-info-ic"><i class="bi bi-info-circle"></i></div>
      <div class="pay-info-txt">
        Pilih <b id="infoMethod">Transfer Bank</b> untuk proses lebih cepat menggunakan Virtual Account.
        Konfirmasi otomatis akan dilakukan setelah dana diterima.
      </div>
    </div>

    {{-- FOOTER BOX --}}
    <div class="pay-footer">
      <div class="pay-footer-left">
        <div class="pay-footer-small">Metode Terpilih</div>
        <div class="pay-footer-method" id="chosenMethod">Transfer Bank</div>
      </div>

      {{-- tombol ini cuma untuk simpan pilihan (optional) --}}
      <button type="submit" class="btn pay-btn" id="saveBtn">
        Simpan Pilihan <i class="bi bi-check2 ms-2"></i>
      </button>
    </div>

  </form>

  {{-- LANJUT (GET) --}}
  <div class="pay-footer" style="margin-top:14px;">
    <div class="pay-footer-left">
      <div class="pay-footer-small text-muted">Jika sudah benar</div>
      <div class="pay-footer-method">Lanjut ke halaman berikutnya</div>
    </div>

    <a href="{{ route('pembayaran.lanjut') }}" class="btn pay-btn">
      Lanjutkan Pembayaran <i class="bi bi-arrow-right ms-2"></i>
    </a>
  </div>

  <div class="pay-cancel text-center">
    <a href="{{ route('cart.show') }}">Batal dan Kembali ke Pesanan</a>
  </div>

</div>

<script>
  const transfer = document.getElementById('pay_transfer');
  const cash = document.getElementById('pay_cash');
  const chosen = document.getElementById('chosenMethod');
  const infoMethod = document.getElementById('infoMethod');

  function sync() {
    if (cash.checked) {
      chosen.textContent = 'Tunai (Cash)';
      infoMethod.textContent = 'Tunai (Cash)';
    } else {
      chosen.textContent = 'Transfer Bank';
      infoMethod.textContent = 'Transfer Bank';
    }
  }

  transfer.addEventListener('change', () => {
    sync();
    // auto-submit biar pilihan langsung tersimpan (opsional)
    document.getElementById('payForm').submit();
  });

  cash.addEventListener('change', () => {
    sync();
    document.getElementById('payForm').submit();
  });

  sync();
</script>

<style>
  /* pastikan label bisa diklik */
  .pay-card { cursor:pointer; position:relative; }
  .pay-check { pointer-events:none; } /* biar klik tidak ketahan icon */
</style>
@endsection