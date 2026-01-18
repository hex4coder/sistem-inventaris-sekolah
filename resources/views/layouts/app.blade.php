<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body>
    <div id="app">
        @auth
            <div class="layout-wrapper">
                <aside class="sidebar">
                    <div class="sidebar-header">
                        <a href="{{ route('dashboard') }}" class="sidebar-brand">
                            <i class="ph ph-package" style="font-size: 1.5rem;"></i>
                            Inventaris Sekolah
                        </a>
                    </div>

                    <nav class="sidebar-nav">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="ph ph-squares-four" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                            Dashboard
                        </a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('categories.index') }}"
                                class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <i class="ph ph-tag" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                                Kategori
                            </a>
                            <a href="{{ route('locations.index') }}"
                                class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                                <i class="ph ph-map-pin" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                                Lokasi
                            </a>
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="ph ph-users" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                                Pengguna
                            </a>
                        @endif
                        <a href="{{ route('items.index') }}"
                            class="nav-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                            <i class="ph ph-cube" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                            Sarana & Prasarana
                        </a>
                        <a href="{{ route('borrowings.index') }}"
                            class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                            <i class="ph ph-hand-palm" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                            Peminjaman
                        </a>
                    </nav>

                    <div class="sidebar-footer">
                        <div class="user-info">{{ Auth::user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn"
                                style="width: 100%; background: #fee2e2; color: #991b1b; padding: 0.5rem;">
                                <i class="ph ph-sign-out" style="font-size: 1.25rem; margin-right: 0.5rem;"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </aside>

                <main class="main-content">
                    @yield('content')
                </main>
            </div>
        @else
            @yield('content')
        @endauth
    </div>
</body>

</html>