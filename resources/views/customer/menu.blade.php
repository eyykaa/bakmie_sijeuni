@extends('layouts.app')
@section('back_url', route('tables.index'))
@section('page_title', 'Pilih Menu')

@section('content')
<div class="menu-page">

    {{-- Sidebar kategori --}}
    <aside class="menu-sidebar">
        @foreach($categories as $key => $label)
            <a class="side-item {{ $active === $key ? 'active' : '' }}" href="{{ route('menu.index', $key) }}">
                <div class="side-icon">🍽️</div>
                <div class="side-text">{{ strtoupper($label) }}</div>
            </a>
        @endforeach
    </aside>

    {{-- Konten --}}
    <main class="menu-content">
        <div class="menu-header">
            <h2>{{ $categories[$active] }} Populer</h2>
            <p class="text-muted">Pilihan menu terbaik kami hari ini</p>
        </div>

        <div class="menu-grid">
            @foreach($items as $it)
                <div class="menu-card">
                    <div class="menu-img">
                        <img src="{{ asset('images/'.$it['image']) }}" alt="{{ $it['name'] }}">
                    </div>

                    <div class="menu-body">
                        <div class="menu-title">
                            <span>{{ $it['name'] }}</span>
                            <span class="menu-price">Rp {{ number_format($it['price'],0,',','.') }}</span>
                        </div>

                        <form method="POST" action="{{ route('cart.add') }}" class="menu-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $it['id'] }}">
                            <input type="hidden" name="name" value="{{ $it['name'] }}">
                            <input type="hidden" name="price" value="{{ $it['price'] }}">
                            <input type="hidden" name="image" value="{{ $it['image'] }}">

                            <input class="menu-note" name="note" placeholder="Contoh: jangan pedas / extra ayam">

                            <div class="menu-actions">
                                <div class="qty-box" data-id="{{ $it['id'] }}">
                                    <button type="button" class="qty-btn minus">–</button>
                                    <input type="number" class="qty-input" name="qty" value="1" min="1" max="99">
                                    <button type="button" class="qty-btn plus">+</button>
                                </div>

                                <button type="submit" class="btn-add">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Bottom bar --}}
        <a class="cart-bar" href="{{ route('cart.show') }}">
            <div class="cart-left">
                <div class="cart-icon">🛒</div>
                <div>
                    <div class="cart-title">Lihat Pesanan</div>
                    <div class="cart-sub">{{ $cartCount }} item sedang dipilih</div>
                </div>
            </div>
            <div class="cart-right">
                <div class="cart-total-label">TOTAL ESTIMASI</div>
                <div class="cart-total">Rp {{ number_format($cartTotal,0,',','.') }}</div>
            </div>
        </a>
    </main>

</div>

<script>
document.querySelectorAll('.qty-box').forEach(box => {
  const input = box.querySelector('.qty-input');
  box.querySelector('.minus').addEventListener('click', () => {
    input.value = Math.max(1, (parseInt(input.value||'1') - 1));
  });
  box.querySelector('.plus').addEventListener('click', () => {
    input.value = Math.min(99, (parseInt(input.value||'1') + 1));
  });
});
</script>
@endsection