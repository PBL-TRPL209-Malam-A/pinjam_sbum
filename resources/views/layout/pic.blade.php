@extends('layout.app')

@section('content')
<style>
    :root {
        --page-bg: #f5f2ec;
        --panel-bg: #fcfbf8;
        --soft-bg: #edf2ea;
        --line: #e7ddd1;
        --text-main: #33403b;
        --text-muted: #7b8681;
        --primary-main: #5d7d6b;
        --primary-dark: #496454;
        --soft-green: #dfe9df;
    }

    body {
        background: var(--page-bg);
        color: var(--text-main);
        font-family: Arial, Helvetica, sans-serif;
    }

    .app-shell {
        background: var(--panel-bg);
        border: 1px solid var(--line);
        border-radius: 2rem;
        overflow: hidden;
        min-height: calc(100vh - 3rem);
    }

    .sidebar-panel {
        min-height: 100%;
        border-right: 1px solid var(--line);
        background: rgba(255, 255, 255, 0.25);
    }

    .logo-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .logo-box img {
        width: 48px;
        height: auto;
        object-fit: contain;
    }

    .logo-text {
        font-size: 1.2rem;
        font-weight: 700;
        color: #55615b;
    }

    .sidebar-link {
        color: #55615b;
        border-radius: 1rem;
        padding: 0.8rem 1rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: 0.2s ease;
        font-size: 0.95rem;
    }

    .sidebar-link:hover {
        background: #f3f6f3;
        color: var(--primary-dark);
    }

    .sidebar-link.active {
        background: #edf3ee;
        color: var(--primary-dark);
        font-weight: 600;
    }

    .sidebar-dot {
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #dfe7df;
        flex-shrink: 0;
    }

    .sidebar-link.active .sidebar-dot {
        background: var(--primary-main);
    }

    .page-caption {
        color: var(--text-muted);
        font-size: 1.1rem;
        margin-bottom: 0.8rem;
    }

    .page-heading {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0;
    }

    .search-input {
        height: 3rem;
        border-radius: 1rem;
        border: 1px solid #ddd2c5;
        background: #fffdfa;
    }

    .search-dot {
        width: 2.25rem;
        height: 2.25rem;
        background: #cfdacd;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .btn-main {
        background: var(--primary-main);
        border: 0;
        border-radius: 1rem;
        min-width: 140px;
        height: 44px;
        color: #fff;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-main:hover {
        background: var(--primary-dark);
        color: #fff;
    }

    .logout-btn {
        background: transparent;
        border: 0;
        color: #8b3c3c;
        padding: 0.5rem 1rem;
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .logout-btn:hover {
        color: #c14444;
    }

    @media (max-width: 991.98px) {
        .sidebar-panel {
            border-right: 0;
            border-bottom: 1px solid var(--line);
        }
    }
</style>

<div class="container-fluid py-3 py-lg-4 px-2 px-lg-4">
    <div class="app-shell">
        <div class="row g-0">
            <!-- Sidebar Panel -->
            <aside class="col-lg-3 col-xl-2 sidebar-panel p-3 p-lg-4 d-flex flex-column">
                <div class="logo-box mb-4">
                    <img src="{{ asset('assets/images/logo-sbum-icon.png') }}" onerror="this.src='https://placehold.co/48x48/5d7d6b/white?text=SBUM'" alt="SBUM">
                    <div class="logo-text">SBUM</div>
                </div>

                <nav class="nav flex-column gap-1">
                    <a href="{{ route('pic.dashboard') }}" class="sidebar-link {{ request()->routeIs('pic.dashboard') ? 'active' : '' }}">
                        <span class="sidebar-dot"></span><span>Dashboard</span>
                    </a>
                    <a href="{{ route('pic.kesiapan') }}" class="sidebar-link {{ request()->routeIs('pic.kesiapan*') ? 'active' : '' }}">
                        <span class="sidebar-dot"></span><span>Konfirmasi Kesiapan</span>
                    </a>
                    <a href="{{ route('pic.pengembalian') }}" class="sidebar-link {{ request()->routeIs('pic.pengembalian*') ? 'active' : '' }}">
                        <span class="sidebar-dot"></span><span>Verifikasi Pengembalian</span>
                    </a>
                    <a href="{{ route('pic.profil') }}" class="sidebar-link {{ request()->routeIs('pic.profil*') ? 'active' : '' }}">
                        <span class="sidebar-dot"></span><span>Profil</span>
                    </a>
                </nav>

                <div class="mt-auto pt-5 pt-lg-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="col-lg-9 col-xl-10 p-3 p-md-4 p-xl-4 d-flex flex-column">
                <!-- Header block -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
                    <div>
                        <div class="page-caption">@yield('page_caption', 'PIC Ruangan')</div>
                        <h1 class="page-heading">@yield('page_heading')</h1>
                    </div>

                    <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                        <input type="text" class="form-control search-input" placeholder="Cari data">
                        <div class="search-dot d-flex align-items-center justify-content-center">
                            <i class="bi bi-search text-white"></i>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 rounded-4 mb-4" style="background-color: #dfeedd; color: #466454;">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-4 mb-4" style="background-color: #fcebeb; color: #8a3c3c;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('pic_content')
            </main>
        </div>
    </div>
</div>
@endsection
