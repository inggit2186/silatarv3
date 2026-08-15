<?php
use App\Http\Middleware\AdminAccess;

// Get user data
$user = auth()->user();
$userRole = $user->role ?? '';
$userId = $user->id ?? 0;
$userDeptId = $user->dept_id ?? 0;

// Check if user is admin or superadmin (full access)
$isAdmin = in_array($userRole, ['admin', 'superadmin', 'kepala']);

// Check if user can access admin panel
$canAccessAdminPanel = in_array($userRole, ['petugas', 'kasi', 'kasubbag', 'admin', 'superadmin', 'kepala']);

// Check if user has humas access
$isHumas = AdminAccess::isHumas($userId);
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
            <div class="sidebar-brand">
                <img src="{{ asset('favicon.webp') }}" alt="SILATAR Logo" class="sidebar-logo-img">
            </div>
            <div class="sidebar-word">
                <span>SILATAR</span>
                <span>Admin Panel</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            @if($canAccessAdminPanel)
            <div class="menu-group" data-group="main" id="menuGroupMain">
                <div class="menu-group-header" onclick="toggleMenuGroup('main')">
                    <div class="menu-group-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="menu-group-header-text">Main</span>
                    <span class="menu-group-count">1</span>
                    <svg class="menu-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="menu-group-items">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <div class="sidebar-nav-icon-wrap cyan">
                            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2v10m10-10a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>
            @endif

            @if($canAccessAdminPanel)
            <div class="menu-group {{ request()->routeIs('admin.users.*', 'admin.services.*', 'admin.units.*', 'admin.requests.*', 'admin.tpg.*', 'admin.reports.*', 'admin.madrasah.laporan.*') ? 'has-active' : '' }}" data-group="kelola" id="menuGroupKelola">
                <div class="menu-group-header" onclick="toggleMenuGroup('kelola')">
                    <div class="menu-group-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="menu-group-header-text">Kelola</span>
                    <span class="menu-group-count">6</span>
                    <svg class="menu-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="menu-group-items">
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

                    @if($isAdmin)
                    <a href="{{ route('admin.units.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                        <div class="sidebar-nav-icon-wrap amber">
                            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span>Unit Kerja</span>
                    </a>
                    @endif

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

                    @if($isAdmin || $userDeptId == 7)
                    <a href="{{ route('admin.madrasah.laporan.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.madrasah.laporan.*') ? 'active' : '' }}">
                        <div class="sidebar-nav-icon-wrap emerald">
                            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span>Laporan Madrasah</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <div class="menu-group {{ request()->routeIs('admin.news.*', 'admin.janji-temu*', 'admin.acara*') ? 'has-active' : '' }}" data-group="publikasi" id="menuGroupPublikasi">
                <div class="menu-group-header" onclick="toggleMenuGroup('publikasi')">
                    <div class="menu-group-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a5 5 0 01-5-5m5 5v13a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2h-5z"/>
                        </svg>
                    </div>
                    <span class="menu-group-header-text">Publikasi</span>
                    <span class="menu-group-count">3</span>
                    <svg class="menu-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="menu-group-items">
                    @if($isAdmin || $isHumas)
                    <a href="{{ route('admin.news.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                        <div class="sidebar-nav-icon-wrap indigo">
                            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a5 5 0 01-5-5m5 5v13a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2h-5z"/>
                            </svg>
                        </div>
                        <span>Berita</span>
                    </a>
                    @endif

                    <a href="{{ route('admin.janji-temu') }}" class="sidebar-nav-item {{ request()->routeIs('admin.janji-temu*') ? 'active' : '' }}">
                        <div class="sidebar-nav-icon-wrap purple">
                            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span>Janji Temu</span>
                    </a>

                    <a href="{{ route('admin.acara') }}" class="sidebar-nav-item {{ request()->routeIs('admin.acara*') ? 'active' : '' }}">
                        <div class="sidebar-nav-icon-wrap amber">
                            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v4m-2-2h4"/>
                            </svg>
                        </div>
                        <span>Acara</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-divider"></div>

            <div class="menu-group system-group" data-group="system" id="menuGroupSystem">
                <div class="menu-group-items">
                    <a href="#" onclick="openPasswordModal(); return false;" class="sidebar-nav-item">
                        <div class="sidebar-nav-icon-wrap amber">
                            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <span>Ubah Password</span>
                    </a>

                    <a href="{{ url('/') }}" target="_blank" class="sidebar-nav-item">
                        <div class="sidebar-nav-icon-wrap">
                            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 6v6m0-6L10 14"/>
                            </svg>
                        </div>
                        <span>Lihat Website</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Profile Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    @if(auth()->user()->pp && auth()->user()->nomor_induk)
                        <img src="{{ asset('storage/users_berkas/' . auth()->user()->nomor_induk . '/' . auth()->user()->pp) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
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
                            <img src="{{ asset('storage/users_berkas/' . auth()->user()->nomor_induk . '/' . auth()->user()->pp) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
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

    <!-- Change Password Modal (Global) -->
    <div id="globalPasswordModal" class="modal-backdrop">
        <div class="modal" style="max-width: 440px;">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title">Ubah Password</h2>
                    <p class="text-sm text-muted">Ubah password akun Anda</p>
                </div>
                <button onclick="closeGlobalPasswordModal()" class="modal-close">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="globalPasswordForm" method="POST" action="{{ route('admin.users.change-password-own') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Password Lama</label>
                        <div class="relative">
                            <input type="password" id="oldPassword" name="current_password" class="form-input" placeholder="Masukkan password lama" required>
                            <button type="button" onclick="togglePasswordVisibility('oldPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-foreground">
                                <svg id="eyeOldPassword" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <div class="relative">
                            <input type="password" id="globalNewPassword" name="password" class="form-input" placeholder="Minimal 6 karakter" required minlength="6">
                            <button type="button" onclick="togglePasswordVisibility('globalNewPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-foreground">
                                <svg id="eyeGlobalNewPassword" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input type="password" id="globalConfirmPassword" name="password_confirmation" class="form-input" placeholder="Ulangi password baru" required minlength="6">
                            <button type="button" onclick="togglePasswordVisibility('globalConfirmPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-foreground">
                                <svg id="eyeGlobalConfirmPassword" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div id="globalPasswordError" class="alert alert-danger hidden mt-3">
                        <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="alert-message" id="globalPasswordErrorMessage"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeGlobalPasswordModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
        }

        // Menu Group Toggle
        function toggleMenuGroup(groupId) {
            const group = document.getElementById('menuGroup' + groupId.charAt(0).toUpperCase() + groupId.slice(1));
            if (group) {
                group.classList.toggle('collapsed');
                // Save state to localStorage
                const isCollapsed = group.classList.contains('collapsed');
                localStorage.setItem('sidebar-group-' + groupId, isCollapsed);
            }
        }

        // Initialize menu groups on page load
        document.addEventListener('DOMContentLoaded', function() {
            const groups = ['main', 'kelola', 'publikasi'];

            groups.forEach(function(groupId) {
                const group = document.getElementById('menuGroup' + groupId.charAt(0).toUpperCase() + groupId.slice(1));
                if (group) {
                    // Check if this group has an active item
                    const hasActiveItem = group.querySelector('.sidebar-nav-item.active');

                    if (hasActiveItem) {
                        // Auto-expand group with active item
                        group.classList.remove('collapsed');
                        group.classList.add('has-active');
                    } else {
                        // Default: collapsed state
                        const isCollapsed = localStorage.getItem('sidebar-group-' + groupId) === 'true';
                        if (isCollapsed || !isCollapsed) {
                            // Default to collapsed unless user explicitly opened it
                            group.classList.add('collapsed');
                        }
                    }
                }
            });
        });

        // Global Password Modal Functions
        const globalPasswordModal = document.getElementById('globalPasswordModal');
        const globalPasswordForm = document.getElementById('globalPasswordForm');
        const globalPasswordError = document.getElementById('globalPasswordError');
        const globalPasswordErrorMessage = document.getElementById('globalPasswordErrorMessage');

        function openPasswordModal() {
            globalPasswordError.classList.add('hidden');
            document.getElementById('oldPassword').value = '';
            document.getElementById('globalNewPassword').value = '';
            document.getElementById('globalConfirmPassword').value = '';
            globalPasswordModal.classList.add('active');
        }

        function closeGlobalPasswordModal() {
            globalPasswordModal.classList.remove('active');
            globalPasswordError.classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeGlobalPasswordModal();
            }
        });

        globalPasswordModal.addEventListener('click', function(e) {
            if (e.target === this) closeGlobalPasswordModal();
        });

        globalPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const oldPassword = document.getElementById('oldPassword').value;
            const newPassword = document.getElementById('globalNewPassword').value;
            const confirmPassword = document.getElementById('globalConfirmPassword').value;

            if (newPassword.length < 8) {
                globalPasswordErrorMessage.textContent = 'Password baru minimal 6 karakter.';
                globalPasswordError.classList.remove('hidden');
                return;
            }

            if (newPassword !== confirmPassword) {
                globalPasswordErrorMessage.textContent = 'Konfirmasi password baru tidak cocok.';
                globalPasswordError.classList.remove('hidden');
                return;
            }

            const formData = new FormData(globalPasswordForm);

            fetch(globalPasswordForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeGlobalPasswordModal();
                    showGlobalToast('success', data.message);
                } else {
                    globalPasswordErrorMessage.textContent = data.message || 'Terjadi kesalahan.';
                    globalPasswordError.classList.remove('hidden');
                }
            })
            .catch(error => {
                globalPasswordErrorMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                globalPasswordError.classList.remove('hidden');
            });
        });

        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                } else {
                    input.type = 'password';
                }
            }
        }

        function showGlobalToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' : '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
                </svg>
                <span class="toast-message">${message}</span>
                <button onclick="this.parentElement.remove()" class="toast-close">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
    </script>
    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
