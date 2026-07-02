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
    <link rel="stylesheet" href="{{ asset('build/assets/fonts-DkuEHybc.css') }}">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/css/neo-mirai-home.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="admin-layout" x-data="{ open: false, collapsed: false }">

    <!-- Sidebar -->
    <aside :class="collapsed ? 'admin-sidebar collapsed' : 'admin-sidebar'" class="hidden lg:flex">
        <!-- Logo -->
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="sidebar-brand">SIL</div>
                <div class="brand-mark" style="display:none;">
                    
                </div>
                <div x-show="!collapsed" x-transition class="sidebar-word">
                    <span>SILATAR</span>
                    <span>Admin Panel</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="sidebar-nav-icon-wrap cyan">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Dashboard</span>
            </a>

            @if($isAdmin)

            <!-- User Management -->
            <a href="{{ route('admin.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon-wrap violet">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Pengguna</span>
            </a>

            <!-- Services Management -->
            <a href="{{ route('admin.services.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon-wrap emerald">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Layanan</span>
            </a>

            <!-- Units Management -->
            <a href="{{ route('admin.units.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon-wrap amber">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Unit Kerja</span>
            </a>

            <!-- Requests -->
            <a href="{{ route('admin.requests.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon-wrap rose">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Pengajuan</span>
            </a>

            <!-- Reports -->
            <a href="{{ route('admin.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon-wrap blue">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Laporan</span>
            </a>
            @endif

            <!-- News Management -->
            <a href="{{ route('admin.news.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <span class="sidebar-nav-icon-wrap indigo">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Berita</span>
            </a>

            <!-- Divider -->
            <div class="sidebar-divider"></div>

            <!-- Back to Site -->
            <a href="{{ url('/') }}" target="_blank" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap slate">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Lihat Website</span>
            </a>
        </nav>

        <!-- Collapse Button -->
        <button
            @click="collapsed = !collapsed"
            class="sidebar-collapse-btn"
            :title="collapsed ? 'Expand' : 'Collapse'"
        >
            <svg :class="collapsed ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <!-- User Profile at Bottom -->
        <div class="sidebar-footer">
            <div x-show="!collapsed" x-transition class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="header-user-avatar">
                        @if(auth()->user()->pp && auth()->user()->nomor_induk)
                            <img
                                src="{{ asset('assets/img/users/' . auth()->user()->nomor_induk . '/' . auth()->user()->pp) }}"
                                alt="{{ auth()->user()->name }}"
                                class="h-full w-full object-cover"
                                onerror="this.style.display='none'; this.parentElement.textContent='{{ substr(auth()->user()->name, 0, 2) }}';"
                            >
                        @else
                            {{ substr(auth()->user()->name, 0, 2) }}
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold" style="color: var(--rice);">{{ auth()->user()->name }}</p>
                        <p class="text-xs" style="color: var(--ink-soft);">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="neo-btn-secondary w-full justify-center">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div
        x-show="open"
        x-cloak
        @click="open = false"
        class="fixed inset-0 z-40 lg:hidden"
        style="background: oklch(20% 0.015 80 / 0.5);"
    ></div>
    <!-- Mobile Sidebar -->
    <aside
        x-show="open"
        x-cloak
        class="admin-sidebar fixed left-0 top-0 z-50 lg:hidden"
        :class="open ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="brand-mark">
                    <img src="{{ asset('favicon.webp') }}" alt="SILATAR" class="w-full h-full object-cover" style="border-radius: 50%;">
                </div>
                <div class="sidebar-word">
                    <span>SILATAR</span>
                    <span>Admin Panel</span>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap cyan">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Dashboard</span>
            </a>
            @if($isAdmin)
            <a href="{{ route('admin.users.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap violet">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Pengguna</span>
            </a>
            <a href="{{ route('admin.services.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap emerald">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Layanan</span>
            </a>
            <a href="{{ route('admin.units.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap amber">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Unit Kerja</span>
            </a>
            <a href="{{ route('admin.requests.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap rose">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Pengajuan</span>
            </a>
            @endif
            <a href="{{ route('admin.news.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap indigo">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v9a2 2 0 01-2 2h-2z"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Berita</span>
            </a>
            <div class="my-4 h-px" style="background: var(--line);"></div>
            <a href="{{ url('/') }}" target="_blank" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap slate">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Lihat Website</span>
            </a>
        </nav>

        <button
            @click="open = false"
            class="absolute right-4 top-4 w-8 h-8 flex items-center justify-center rounded-lg"
            style="background: var(--paper); border: 1px solid var(--line);"
        >
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </aside>
    <!-- Main Content Area -->
    <main class="admin-main" :class="collapsed ? 'sidebar-collapsed' : ''">
        <!-- Header -->
        <header class="admin-header">
            <div class="flex items-center gap-4">
                <!-- Mobile Menu Button -->
                <button
                    @click="open = true"
                    class="flex h-10 w-10 items-center justify-center rounded-lg lg:hidden"
                    style="background: var(--paper); border: 1px solid var(--line);"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Breadcrumb -->
                <div class="header-breadcrumb">
                    <a href="{{ route('admin.dashboard') }}" class="header-breadcrumb-item">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </a>
                    @if(isset($breadcrumbs) && count($breadcrumbs))
                        @foreach($breadcrumbs as $breadcrumb)
                            <svg class="header-breadcrumb-separator" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                            @if($breadcrumb['url'])
                                <a href="{{ $breadcrumb['url'] }}" class="header-breadcrumb-item">{{ $breadcrumb['label'] }}</a>
                            @else
                                <span class="header-breadcrumb-current">{{ $breadcrumb['label'] }}</span>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="header-actions">
                <!-- Search -->
                <div class="header-search hidden md:block">
                    <svg class="header-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" class="header-search-input" placeholder="Cari...">
                </div>

                <!-- Notifications -->
                <button class="header-notification">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="header-notification-badge">3</span>
                </button>

                <!-- User Menu -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button
                        @click="userMenuOpen = !userMenuOpen"
                        @click.away="userMenuOpen = false"
                        class="header-user"
                    >
                        <div class="header-user-avatar">
                            @if(auth()->user()->pp && auth()->user()->nomor_induk)
                                <img
                                    src="{{ asset('assets/img/users/' . auth()->user()->nomor_induk . '/' . auth()->user()->pp) }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="h-full w-full object-cover"
                                    onerror="this.style.display='none'; this.parentElement.textContent='{{ substr(auth()->user()->name, 0, 2) }}';"
                                >
                            @else
                                {{ substr(auth()->user()->name, 0, 2) }}
                            @endif
                        </div>
                        <div class="header-user-info hidden sm:block">
                            <p class="header-user-name">{{ auth()->user()->name }}</p>
                            <p class="header-user-role">{{ auth()->user()->role }}</p>
                        </div>
                        <svg :class="userMenuOpen ? 'rotate-180' : ''" class="h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="userMenuOpen" x-cloak class="dropdown-menu">
                        <div class="border-b px-4 py-3" style="border-color: var(--line); background: var(--paper-soft);">
                            <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--ink-soft);">Akun</p>
                            <p class="text-sm font-medium" style="color: var(--ink);">{{ auth()->user()->name }}</p>
                            <p class="text-xs" style="color: var(--ink-soft);">{{ auth()->user()->nomor_induk }}</p>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <svg class="dropdown-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item w-full" style="color: #e11d48;">
                                    <svg class="dropdown-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="color: #e11d48;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="admin-content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="neo-alert neo-alert-success">
                    <svg class="neo-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="neo-alert-content">
                        <p class="neo-alert-title">Berhasil</p>
                        <p class="neo-alert-message">{{ session('success') }}</p>
                    </div>
                    <button @click="this.parentElement.remove()" class="neo-alert-dismiss">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="neo-alert neo-alert-danger">
                    <svg class="neo-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="neo-alert-content">
                        <p class="neo-alert-title">Error</p>
                        <p class="neo-alert-message">{{ session('error') }}</p>
                    </div>
                    <button @click="this.parentElement.remove()" class="neo-alert-dismiss">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div class="neo-alert neo-alert-warning">
                    <svg class="neo-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="neo-alert-content">
                        <p class="neo-alert-title">Peringatan</p>
                        <p class="neo-alert-message">{{ session('warning') }}</p>
                    </div>
                    <button @click="this.parentElement.remove()" class="neo-alert-dismiss">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Page Content Slot -->
            {{ $slot }}
        </div>
    </main>

    <!-- Toast Container -->
    <div class="toast-container" x-data="{ toasts: [] }">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="toast animate-slide-up" :class="'toast-' + toast.type">
                <svg x-show="toast.type === 'success'" class="toast-icon text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <svg x-show="toast.type === 'error'" class="toast-icon text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="flex-1 text-sm font-medium" style="color: var(--ink);" x-text="toast.message"></p>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" style="color: var(--ink-soft);">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>
</body>
</html>
