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
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;500;600;700&family=Azeret+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    {{-- Admin assets loaded via @vite() - separated for lazy loading --}}
    @vite(['admin-css', 'admin-neo'])
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
</head>
<body class="admin-layout" x-data="{ open: false, collapsed: false }">

    <!-- Sidebar -->
    <aside :class="collapsed ? 'admin-sidebar collapsed' : 'admin-sidebar'" class="hidden lg:flex">
        <!-- Logo -->
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="sidebar-brand">SIL</div>
                <div class="brand-mark" class="hidden">
                    
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
            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                <span class="sidebar-nav-icon-wrap cyan">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M219.31,108.68l-80-80a16,16,0,0,0-22.62,0l-80,80A15.87,15.87,0,0,0,32,120v96a8,8,0,0,0,8,8h64a8,8,0,0,0,8-8V160h32v56a8,8,0,0,0,8,8h64a8,8,0,0,0,8-8V120A15.87,15.87,0,0,0,219.31,108.68ZM208,208H160V152a8,8,0,0,0-8-8H104a8,8,0,0,0-8,8v56H48V120l80-80,80,80Z" fill="currentColor"/>
                        <circle cx="100" cy="84" r="8" fill="currentColor" opacity="0.4"/>
                        <circle cx="140" cy="84" r="8" fill="currentColor" opacity="0.4"/>
                        <circle cx="180" cy="84" r="8" fill="currentColor" opacity="0.4"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Dashboard</span>
            </a>

            @if($isAdmin)

            <!-- User Management -->
            <a href="{{ route('admin.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-tooltip="Pengguna">
                <span class="sidebar-nav-icon-wrap violet">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8Z" fill="currentColor"/>
                        <path d="M96,216a24,24,0,1,1,24-24A24,24,0,0,1,96,216Zm88-24a24,24,0,1,0-24,24A24,24,0,0,0,184,192Z" fill="currentColor"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Pengguna</span>
            </a>

            <!-- Services Management -->
            <a href="{{ route('admin.services.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}" data-tooltip="Layanan">
                <span class="sidebar-nav-icon-wrap emerald">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M232,64H208V48a8,8,0,0,0-8-8H56a8,8,0,0,0-8,8V64H24a16,16,0,0,0-16,16v8a40,40,0,0,0,40,40h3.65A80.13,80.13,0,0,0,120,191.61V216H96a8,8,0,0,0,0,16h64a8,8,0,0,0,0-16H136V191.61A80.13,80.13,0,0,0,204.35,128H208a40,40,0,0,0,40-40V80A16,16,0,0,0,232,64Zm-56-8v8a8,8,0,0,1-8,8H88a8,8,0,0,1-8-8V56H176Zm-16,32a64,64,0,1,1-64-64A64.07,64.07,0,0,1,160,88Z" fill="currentColor"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Layanan</span>
            </a>

            <!-- Units Management -->
            <a href="{{ route('admin.units.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.units.*') ? 'active' : '' }}" data-tooltip="Unit Kerja">
                <span class="sidebar-nav-icon-wrap amber">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M240,208H224V96a16,16,0,0,0-16-16H144V48a16,16,0,0,0-24.88-13.32L39.2,90.76A15.86,15.86,0,0,0,32,103.2V208H16a8,8,0,0,0,0,16H240a8,8,0,0,0,0-16ZM48,208,64,104a16,16,0,0,0-4.8-11.84l56-44.8a5.31,5.31,0,0,1,4.16-.88L136,56v44H208V208Zm64,0H176V92.19l32-25.6V192a16,16,0,0,0,16,16Z" fill="currentColor"/>
                        <rect x="152" y="152" width="48" height="16" rx="4" fill="currentColor" opacity="0.4"/>
                        <rect x="152" y="184" width="32" height="16" rx="4" fill="currentColor" opacity="0.4"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Unit Kerja</span>
            </a>

            <!-- Requests -->
            <a href="{{ route('admin.requests.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}" data-tooltip="Pengajuan">
                <span class="sidebar-nav-icon-wrap rose">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Zm-32-80a8,8,0,0,1-8,8H96a8,8,0,0,1,0-16h64A8,8,0,0,1,168,136Zm0,32a8,8,0,0,1-8,8H96a8,8,0,0,1,0-16h64A8,8,0,0,1,168,168Z" fill="currentColor"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Pengajuan</span>
            </a>

            <!-- TPG Verification -->
            <a href="{{ route('admin.tpg.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.tpg.*') ? 'active' : '' }}" data-tooltip="Verif TPG">
                <span class="sidebar-nav-icon-wrap emerald">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="16">
                        <path d="M128,24A104,104,0,1,0,232,128,104,104,0,0,0,128,24Zm45.66,85.66-56,56a8,8,0,0,1-11.32,0l-24-24a8,8,0,0,1,11.32-11.32L112,148.69l50.34-50.35a8,8,0,0,1,11.32,11.32Z"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Verif TPG</span>
            </a>

            <!-- Reports -->
            <a href="{{ route('admin.reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" data-tooltip="Laporan">
                <span class="sidebar-nav-icon-wrap blue">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M224,200h-8V40a8,8,0,0,0-8-8H152a8,8,0,0,0-8,8V80H96a8,8,0,0,0-8,8v40H48a8,8,0,0,0-8,8v64H32a8,8,0,0,0,0,16H224a8,8,0,0,0,0-16ZM160,48h40V200H160ZM104,96h40V200H104ZM56,144H88v56H56Z" fill="currentColor"/>
                        <rect x="64" y="60" width="32" height="12" rx="2" fill="currentColor" opacity="0.4"/>
                        <rect x="64" y="84" width="24" height="12" rx="2" fill="currentColor" opacity="0.4"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Laporan</span>
            </a>
            @endif

            <!-- News Management -->
            <a href="{{ route('admin.news.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}" data-tooltip="Berita">
                <span class="sidebar-nav-icon-wrap indigo">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216ZM184,96a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,96Zm0,32a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,128Zm0,32a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,160Z" fill="currentColor"/>
                    </svg>
                </span>
                <span x-show="!collapsed" x-transition class="sidebar-nav-label">Berita</span>
            </a>

            <!-- Divider -->
            <div class="sidebar-divider"></div>

            <!-- Back to Site -->
            <a href="{{ url('/') }}" target="_blank" class="sidebar-nav-item" data-tooltip="Lihat Website">
                <span class="sidebar-nav-icon-wrap slate">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M224,120v96a8,8,0,0,1-8,8H40a8,8,0,0,1-8-8V120a16,16,0,0,1,4.69-11.31l80-80a16,16,0,0,1,22.62,0l80,80A16,16,0,0,1,224,120ZM93.66,120,120,93.66V120Zm32-48L56,141.66A8,8,0,0,0,68.69,144l72-72a8,8,0,0,0-11.32-11.32ZM56,40a8,8,0,0,0-8,8V72a8,8,0,0,0,16,0V48A8,8,0,0,0,56,40Z" fill="currentColor"/>
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
                    <div class="sidebar-footer-user-avatar">
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
                        <p class="text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-xs">{{ auth()->user()->role }}</p>
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
        class="fixed inset-0 z-40 lg:hidden admin-mobile-overlay"
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
                <div class="brand-mark lg:hidden">
                    <img src="{{ asset('favicon.webp') }}" alt="SILATAR" class="w-full h-full object-cover rounded-full">
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
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M219.31,108.68l-80-80a16,16,0,0,0-22.62,0l-80,80A15.87,15.87,0,0,0,32,120v96a8,8,0,0,0,8,8h64a8,8,0,0,0,8-8V160h32v56a8,8,0,0,0,8,8h64a8,8,0,0,0,8-8V120A15.87,15.87,0,0,0,219.31,108.68ZM208,208H160V152a8,8,0,0,0-8-8H104a8,8,0,0,0-8,8v56H48V120l80-80,80,80Z" fill="currentColor"/>
                        <circle cx="100" cy="84" r="8" fill="currentColor" opacity="0.4"/>
                        <circle cx="140" cy="84" r="8" fill="currentColor" opacity="0.4"/>
                        <circle cx="180" cy="84" r="8" fill="currentColor" opacity="0.4"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Dashboard</span>
            </a>
            @if($isAdmin)
            <a href="{{ route('admin.users.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap violet">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8Z" fill="currentColor"/>
                        <path d="M96,216a24,24,0,1,1,24-24A24,24,0,0,1,96,216Zm88-24a24,24,0,1,0-24,24A24,24,0,0,0,184,192Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Pengguna</span>
            </a>
            <a href="{{ route('admin.services.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap emerald">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M232,64H208V48a8,8,0,0,0-8-8H56a8,8,0,0,0-8,8V64H24a16,16,0,0,0-16,16v8a40,40,0,0,0,40,40h3.65A80.13,80.13,0,0,0,120,191.61V216H96a8,8,0,0,0,0,16h64a8,8,0,0,0,0-16H136V191.61A80.13,80.13,0,0,0,204.35,128H208a40,40,0,0,0,40-40V80A16,16,0,0,0,232,64Zm-56-8v8a8,8,0,0,1-8,8H88a8,8,0,0,1-8-8V56H176Zm-16,32a64,64,0,1,1-64-64A64.07,64.07,0,0,1,160,88Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Layanan</span>
            </a>
            <a href="{{ route('admin.units.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap amber">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M240,208H224V96a16,16,0,0,0-16-16H144V48a16,16,0,0,0-24.88-13.32L39.2,90.76A15.86,15.86,0,0,0,32,103.2V208H16a8,8,0,0,0,0,16H240a8,8,0,0,0,0-16ZM48,208,64,104a16,16,0,0,0-4.8-11.84l56-44.8a5.31,5.31,0,0,1,4.16-.88L136,56v44H208V208Zm64,0H176V92.19l32-25.6V192a16,16,0,0,0,16,16Z" fill="currentColor"/>
                        <rect x="152" y="152" width="48" height="16" rx="4" fill="currentColor" opacity="0.4"/>
                        <rect x="152" y="184" width="32" height="16" rx="4" fill="currentColor" opacity="0.4"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Unit Kerja</span>
            </a>
            <a href="{{ route('admin.requests.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap rose">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,51.31,188.69,80H160ZM200,216H56V40h88V88a8,8,0,0,0,8,8h48V216Zm-32-80a8,8,0,0,1-8,8H96a8,8,0,0,1,0-16h64A8,8,0,0,1,168,136Zm0,32a8,8,0,0,1-8,8H96a8,8,0,0,1,0-16h64A8,8,0,0,1,168,168Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Pengajuan</span>
            </a>
            @endif
            <a href="{{ route('admin.news.index') }}" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap indigo">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216ZM184,96a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,96Zm0,32a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,128Zm0,32a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,160Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Berita</span>
            </a>
            <div class="my-4 h-px" class="sidebar-divider"></div>
            <a href="{{ url('/') }}" target="_blank" class="sidebar-nav-item">
                <span class="sidebar-nav-icon-wrap slate">
                    <svg class="sidebar-nav-icon-inner" viewBox="0 0 256 256" fill="none">
                        <path d="M224,120v96a8,8,0,0,1-8,8H40a8,8,0,0,1-8-8V120a16,16,0,0,1,4.69-11.31l80-80a16,16,0,0,1,22.62,0l80,80A16,16,0,0,1,224,120ZM93.66,120,120,93.66V120Zm32-48L56,141.66A8,8,0,0,0,68.69,144l72-72a8,8,0,0,0-11.32-11.32ZM56,40a8,8,0,0,0-8,8V72a8,8,0,0,0,16,0V48A8,8,0,0,0,56,40Z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="sidebar-nav-label">Lihat Website</span>
            </a>
        </nav>

        <button
            @click="open = false"
            class="absolute right-4 top-4 w-8 h-8 flex items-center justify-center rounded-lg"
            class="icon-btn"
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
                    class="icon-btn"
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
                        <div class="dropdown-header">
                            <p class="dropdown-header-label">Akun</p>
                            <p class="dropdown-header-name">{{ auth()->user()->name }}</p>
                            <p class="dropdown-header-id">{{ auth()->user()->nomor_induk }}</p>
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
                                <button type="submit" class="dropdown-item btn-danger w-full">
                                    <svg class="dropdown-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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
                <p class="toast-message" x-text="toast.message"></p>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="toast-close">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    @stack('scripts')
</body>
</html>
