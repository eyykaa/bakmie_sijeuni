<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Backoffice Sijeuni')</title>
  <link rel="stylesheet" href="{{ asset('css/backoffice.css') }}">
</head>
<body>

  <div class="bo-shell">

    <aside class="bo-sidebar">
      <div class="bo-brand">
        <div class="bo-logo">🍴</div>
        <div class="bo-brand-tx">
          <div class="bo-brand-name">Bakmie Sijeuni</div>
        </div>
      </div>

      <nav class="bo-nav">
        <a class="bo-nav-item {{ request()->routeIs('backoffice.dashboard') ? 'active' : '' }}"
           href="{{ route('backoffice.dashboard') }}">
          <span class="ic">🏠</span> <span>Dashboard</span>
        </a>

        <a class="bo-nav-item {{ request()->routeIs('backoffice.orders.*') ? 'active' : '' }}"
           href="{{ route('backoffice.orders.index') }}">
          <span class="ic">📦</span> <span>Orders</span>
        </a>

        <a class="bo-nav-item {{ request()->routeIs('backoffice.tables.*') ? 'active' : '' }}"
            href="{{ route('backoffice.tables.index') }}">
            <span class="ic">🪑</span> <span>Tables</span>
        </a>

        <a class="bo-nav-item {{ request()->routeIs('backoffice.menu.*') ? 'active' : '' }}"
           href="{{ route('backoffice.menu.index') }}">
          <span class="ic">🍜</span> <span>Menu</span>
        </a>

        <a class="bo-nav-item {{ request()->routeIs('backoffice.reports.*') ? 'active' : '' }}"
           href="{{ route('backoffice.reports.index') }}">
          <span class="ic">📊</span> <span>Reports</span>
        </a>
      </nav>

      <div class="bo-side-bottom">
        <form method="POST" action="{{ route('backoffice.logout') }}">
          @csrf
          <button class="bo-logout" type="submit">Logout</button>
        </form>
      </div>
    </aside>

    <main class="bo-main">
      @yield('content')
    </main>

  </div>

</body>
</html>