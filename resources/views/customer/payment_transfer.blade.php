@extends('layouts.app')

@section('back_url', route('payment')) {{-- atau route('pembayaran.index') kalau itu halaman pilih metode --}}
@section('page_title', 'Pembayaran Transfer')

@section('content')
<div class="transfer-wrap">

  {{-- Header --}}
  <div class="transfer-header">
    <div class="transfer-order">Order ID: <span>#{{ $orderId ?? 'SJ-0001' }}</span></div>
  </div>

  {{-- Timer --}}
  <div class="transfer-timer">
    <i class="bi bi-clock"></i>
    <span>Selesaikan pembayaran dalam</span>
    <b id="timer">14:59</b>
  </div>

  {{-- Total --}}
  <div class="transfer-total">
    <div class="transfer-total-label">Total Tagihan</div>
    <div class="transfer-total-amount">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</div>
  </div>

  {{-- Metode transfer (contoh 3 kartu) --}}
  <div class="transfer-row">
    <div class="transfer-row-left">
      <div class="transfer-row-title">Pilih Metode Transfer</div>
    </div>
    <div class="transfer-row-right">
      <div class="transfer-row-note">Verifikasi otomatis 1-3 menit</div>
    </div>
  </div>

  <div class="transfer-cards">
    <div class="transfer-card active" data-bank="BCA">
      <div class="transfer-card-top">
        <div class="bank-pill">BCA</div>
        <button type="button" class="copy-btn" data-copy="{{ $bcaVa ?? '1234567890' }}">
          <i class="bi bi-copy"></i>
        </button>
      </div>
      <div class="transfer-card-name">BCA Transfer</div>
      <div class="transfer-card-va">{{ $bcaVa ?? '1234567890' }}</div>
      <div class="transfer-card-an">a/n Sijeuni</div>
    </div>

    <div class="transfer-card" data-bank="Mandiri">
      <div class="transfer-card-top">
        <div class="bank-pill">Mandiri</div>
        <button type="button" class="copy-btn" data-copy="{{ $mandiriVa ?? '0987654321' }}">
          <i class="bi bi-copy"></i>
        </button>
      </div>
      <div class="transfer-card-name">Mandiri Transfer</div>
      <div class="transfer-card-va">{{ $mandiriVa ?? '0987654321' }}</div>
      <div class="transfer-card-an">a/n Bakmie Sijeuni</div>
    </div>

    <div class="transfer-card" data-bank="DANA">
      <div class="transfer-card-top">
        <div class="bank-pill">DANA</div>
        <button type="button" class="copy-btn" data-copy="{{ $danaNo ?? '081234567890' }}">
          <i class="bi bi-copy"></i>
        </button>
      </div>
      <div class="transfer-card-name">DANA (E-Wallet)</div>
      <div class="transfer-card-va">{{ $danaNo ?? '081234567890' }}</div>
      <div class="transfer-card-an">a/n Bakmie Sijeuni</div>
    </div>
  </div>

  {{-- Instruksi --}}
  <div class="transfer-instr">
    <div class="transfer-instr-title">Petunjuk Pembayaran</div>
    <div class="transfer-steps">
      <div class="step">
        <div class="step-no">1</div>
        <div class="step-txt">Pilih salah satu metode pembayaran yang tersedia di atas sesuai kenyamanan Anda.</div>
      </div>
      <div class="step">
        <div class="step-no">2</div>
        <div class="step-txt">Salin nomor rekening / nomor VA yang tertera pada kartu metode pilihan.</div>
      </div>
      <div class="step">
        <div class="step-no">3</div>
        <div class="step-txt">Lakukan transfer melalui M-Banking/ATM sesuai nominal total tagihan.</div>
      </div>
      <div class="step">
        <div class="step-no">4</div>
        <div class="step-txt">Setelah transfer berhasil, tekan tombol <b>"Saya Sudah Bayar"</b> untuk konfirmasi.</div>
      </div>
    </div>
  </div>

  {{-- Button --}}
  <form method="POST" action="{{ route('payment.transfer.confirm') }}">
    @csrf
    <input type="hidden" name="bank" id="selectedBank" value="BCA">
    <button class="btn-transfer-confirm" type="submit">
      Saya Sudah Bayar <i class="bi bi-arrow-right ms-2"></i>
    </button>
  </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // ====== COUNTDOWN (aman, tidak pakai ${} ) ======
  var sec = 14 * 60 + 59;
  var el = document.getElementById('timer');

  function tick() {
    if (!el) return;
    var m = String(Math.floor(sec / 60)).padStart(2, '0');
    var s = String(sec % 60).padStart(2, '0');
    el.textContent = m + ':' + s;
    if (sec > 0) sec--;
  }
  tick();
  setInterval(tick, 1000);

  // ====== PILIH CARD ======
  var cards = document.querySelectorAll('.transfer-card');
  var inputBank = document.getElementById('selectedBank');

  cards.forEach(function(card){
    card.addEventListener('click', function(){
      cards.forEach(function(c){ c.classList.remove('active'); });
      card.classList.add('active');
      if (inputBank) inputBank.value = card.getAttribute('data-bank') || 'BCA';
    });
  });

  // ====== COPY ======
  var copyBtns = document.querySelectorAll('.copy-btn');
  copyBtns.forEach(function(btn){
    btn.addEventListener('click', function(e){
      e.stopPropagation();
      var text = btn.getAttribute('data-copy') || '';
      if (!text) return;

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function(){
          alert('Disalin: ' + text);
        });
      } else {
        // fallback
        var ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        alert('Disalin: ' + text);
      }
    });
  });
});
</script>
@endsection