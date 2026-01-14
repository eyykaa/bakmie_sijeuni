@extends('layouts.app')
@section('back_url', route('pembayaran.show'))
@section('page_title', 'Pembayaran Transfer')

@section('content')
<div class="transfer-page">

  {{-- TOP BAR --}}
  <div class="transfer-topbar">
    <div class="transfer-title">Pembayaran Transfer</div>
    <div class="transfer-orderid">Order ID: <span class="accent">{{ $orderCode }}</span></div>
  </div>

  {{-- BADGE COUNTDOWN --}}
  <div class="transfer-badge" id="countdownBadge" data-seconds="{{ (int)$deadlineSeconds }}">
    <span class="dot"></span>
    Selesaikan pembayaran dalam <b id="countdownText">00:00</b>
  </div>

  {{-- TOTAL --}}
  <div class="transfer-total">
    <div class="transfer-total-label">Total Tagihan</div>
    <div class="transfer-total-value">Rp {{ number_format($total,0,',','.') }}</div>
  </div>

  {{-- HEAD ROW --}}
  <div class="transfer-headrow">
    <div class="transfer-lefthead">Pilih Metode Transfer</div>
    <div class="transfer-righthead">Verifikasi otomatis 1-3 menit</div>
  </div>

  <form action="{{ route('pembayaran.transfer.confirm') }}" method="POST" id="transferForm">
    @csrf

    <input type="hidden" name="method" id="methodInput" value="{{ $selected }}">

    {{-- METHODS GRID --}}
    <div class="transfer-grid" id="transferGrid">
      @foreach($methods as $m)
        @php
          $active = ($selected === $m['key']) ? 'active' : '';
        @endphp

        <div class="transfer-card {{ $active }}" data-method="{{ $m['key'] }}">
          <div class="transfer-card-top">
            <div class="transfer-logo">
              <img src="{{ asset('images/'.$m['logo']) }}" alt="{{ $m['name'] }}">
            </div>

            <button type="button" class="transfer-card-copy" title="Copy">⧉</button>
          </div>

          <div class="transfer-card-name">{{ $m['name'] }}</div>

          <div class="transfer-card-number" id="num-{{ $m['key'] }}">
            {{ $m['number'] }}
          </div>

          <div class="transfer-card-holder">a/n {{ $m['holder'] }}</div>

          <div class="transfer-card-check">✓</div>
        </div>
      @endforeach
    </div>

    {{-- GUIDE --}}
    <div class="transfer-guide">
      <div class="transfer-guide-title">Petunjuk Pembayaran</div>

      <div class="transfer-guide-grid">
        <div class="guide-item">
          <div class="guide-no">1</div>
          <div class="guide-tx">Pilih salah satu metode pembayaran yang tersedia di atas sesuai kenyamanan Anda.</div>
        </div>
        <div class="guide-item">
          <div class="guide-no">2</div>
          <div class="guide-tx">Salin nomor rekening atau nomor Virtual Account yang tertera pada kartu metode pilihan.</div>
        </div>
        <div class="guide-item">
          <div class="guide-no">3</div>
          <div class="guide-tx">Lakukan transfer melalui M-Banking atau ATM sesuai dengan nominal total tagihan hingga 3 digit terakhir.</div>
        </div>
        <div class="guide-item">
          <div class="guide-no">4</div>
          <div class="guide-tx">Setelah transfer berhasil, tekan tombol <b>"Saya Sudah Bayar"</b> untuk konfirmasi otomatis.</div>
        </div>
      </div>
    </div>

    {{-- CTA --}}
    <button type="submit" class="transfer-pay-btn">
      Saya Sudah Bayar <span>→</span>
    </button>

  </form>
</div>

{{-- SCRIPT ES5 (AMAN) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

  // =============== SELECT METHOD + COPY ===============
  var grid = document.getElementById('transferGrid');
  var methodInput = document.getElementById('methodInput');

  function removeActive() {
    if (!grid) return;
    var cards = grid.querySelectorAll('.transfer-card');
    for (var i = 0; i < cards.length; i++) {
      cards[i].classList.remove('active');
    }
  }

  if (grid && methodInput) {
    grid.addEventListener('click', function (e) {
      var card = e.target.closest ? e.target.closest('.transfer-card') : null;
      if (!card) return;

      var method = card.getAttribute('data-method') || '';
      if (!method) return;

      removeActive();
      card.classList.add('active');
      methodInput.value = method;

      // copy
      var copyBtn = e.target.closest ? e.target.closest('.transfer-card-copy') : null;
      if (copyBtn) {
        var numEl = document.getElementById('num-' + method);
        var text = numEl ? (numEl.textContent || '').trim() : '';

        if (text && navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(text).then(function () {
            copyBtn.textContent = '✓';
            setTimeout(function () { copyBtn.textContent = '⧉'; }, 800);
          }).catch(function () {});
        }
      }
    });
  }

  // =============== COUNTDOWN ===============
  var badge = document.getElementById('countdownBadge');
  var textEl = document.getElementById('countdownText');
  if (!badge || !textEl) return;

  var remaining = parseInt(badge.getAttribute('data-seconds') || '0', 10);
  if (isNaN(remaining)) remaining = 0;

  function pad(n) { n = String(n); return (n.length < 2) ? ('0' + n) : n; }

  function render() {
    var m = Math.floor(remaining / 60);
    var s = remaining % 60;
    textEl.textContent = pad(m) + ':' + pad(s);
    if (remaining <= 0) badge.classList.add('expired');
  }

  render();

  var timer = setInterval(function () {
    if (remaining <= 0) { clearInterval(timer); return; }
    remaining = remaining - 1;
    render();
  }, 1000);

});
</script>
@endsection