@extends('layouts.app')

@section('back_url', route('menu.index'))
@section('page_title', 'Terima Kasih')

@section('content')
<div class="ty-page">
  <div class="ty-card">

    <div class="ty-badge">✅ BERHASIL</div>

    <div class="ty-title">{{ $title }}</div>
    <div class="ty-sub">{{ $subtitle }}</div>

    <form method="POST" action="{{ route('thankyou.done') }}">
      @csrf
      <button type="submit" class="ty-btn">
        Kembali ke Menu
      </button>
    </form>

  </div>
</div>
@endsection