@extends('layouts.app')

@section('content')
<div class="bo-login-page">
  <div class="bo-login-top">
    <div class="bo-logo">🍴</div>
    <div class="bo-brand">Sijeuni</div>
  </div>

  <div class="bo-card">
    <div class="bo-title">Backoffice Sijeuni</div>
    <div class="bo-sub">Selamat Datang</div>

    <form method="POST" action="{{ route('backoffice.login.post') }}">
      @csrf

      <div class="bo-group">
        <label>Username atau Email</label>
        <input type="text" name="identity" value="{{ old('identity') }}" placeholder="Masukkan username atau email">
        @error('identity')
          <div class="bo-error">{{ $message }}</div>
        @enderror
      </div>

      <div class="bo-group">
        <label>Kata Sandi</label>
        <input type="password" name="password" placeholder="••••••••">
        @error('password')
          <div class="bo-error">{{ $message }}</div>
        @enderror
      </div>

      <button class="bo-btn" type="submit">
        Masuk <span>→</span>
      </button>
    </form>

    <div class="bo-footer">©2026 Bakmie Sijeuni Group.</div>
  </div>
</div>
@endsection