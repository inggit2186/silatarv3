<?php
use App\Http\Middleware\AdminAccess;
$userAccess = AdminAccess::getUserAccess(auth()->id());
$isAdmin = in_array("admin", $userAccess) || in_array("superadmin", $userAccess);
?>

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
    <title>{{ $title ?? 'Admin Dashboard - SILATAR' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin-new.css'])
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
</head>
<body class="admin-layout">

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Logo -->
        <div class="sidebar-logo">
            <div class="sidebar-brand">SIL</div>
            <div class="sidebar-word">
                <span>SILATAR</span>
                <span>Admin Panel</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="sidebar-nav-icon-wrap cyan">
                    <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span>Dashboard</span>
            </a>

            @if($isAdmin)
            <a href="{{ route('admin.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <div class="sidebar-nav-icon-wrap violet">
                    <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span>Pengguna</span>
            </a>

            <a href="{{ route('admin.services.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <div class="sidebar-nav-icon-wrap emerald">
                    <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <span>Layanan</span>
            </a>

            <a href="{{ route('admin.units.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                <div class="sidebar-nav-icon-wrap amber">
                    <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span>Unit Kerja</span>
            </a>

            <a href="{{ route('admin.requests.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">
                <div class="sidebar-nav-icon-wrap rose">
                    <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span>Pengajuan</span>
            </a>

            <a href="{{ route('admin.tpg.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.tpg.*') ? 'active' : '' }}">
                <div class="sidebar-nav-icon-wrap emerald">
                    <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span>Verif TPG</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <div class="sidebar-nav-icon-wrap blue">
                    <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span>Laporan</span>
            </a>
            @endif

            <a href="{{ route('admin.news.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <div class="sidebar-nav-icon-wrap indigo">
                    <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a5 5 0 01-5-5m5 5v13a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2h-5z"/>
                    </svg>
                </div>
                <span>Berita</span>
            </a>

            <div class="sidebar-divider"></div>

            <a href="{{ url('/') }}" target="_blank" class="sidebar-nav-item">
                <div class="sidebar-nav-icon-wrap">
                    <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 6v6m0-6L10 14"/>
                    </svg>
                </div>
                <span>Lihat Website</span>
            </a>
        </nav>

        <!-- User Profile Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    @if(auth()->user()->pp && auth()->user()->nomor_induk)
                        <img src="{{ asset('assets/img/users/' . auth()->user()->nomor_induk . '/' . auth()->user()->pp) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                    @else
                        {{ substr(auth()->user()->name, 0, 2) }}
                    @endif
                </div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                    <span class="sidebar-user-role">{{ auth()->user()->role }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="header-icon-btn lg:hidden" onclick="toggleSidebar()">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="header-search">
                    <svg class="header-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" class="header-search-input" placeholder="Cari...">
                </div>
            </div>

            <div class="header-right">
                <button class="header-icon-btn">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="header-badge">3</span>
                </button>

                <div class="header-user">
                    <div class="header-user-avatar">
                        @if(auth()->user()->pp && auth()->user()->nomor_induk)
                            <img src="{{ asset('assets/img/users/' . auth()->user()->nomor_induk . '/' . auth()->user()->pp) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 2) }}
                        @endif
                    </div>
                    <div class="header-user-info">
                        <span class="header-user-name">{{ auth()->user()->name }}</span>
                        <span class="header-user-role">{{ auth()->user()->role }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">
            {{ $slot }}
        </div>
    </main>

    <!-- Toast Container -->
    <div class="toast-container" x-data="{ toasts: [] }">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="toast" :class="'toast-' + toast.type">
                <svg x-show="toast.type === 'success'" class="toast-icon text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <svg x-show="toast.type === 'error'" class="toast-icon text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="toast-message" x-text="toast.message"></p>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="toast-close">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
        }
    </script>
    @stack('scripts')
</body>
</html>
