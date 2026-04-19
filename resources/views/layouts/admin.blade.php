<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="VOXARA Admin Panel">
    <title>@yield('title', 'Admin - VOXARA')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1B4F72',
                        secondary: '#2E86C1',
                        'light-bg': '#EBF5FB',
                        'text-primary': '#1A1A2E',
                        'text-secondary': '#4A4A6A',
                        success: '#1E8449',
                        danger: '#C0392B',
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full bg-light-bg text-text-primary font-sans">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 flex-shrink-0 bg-primary text-white flex flex-col" role="navigation" aria-label="Menu Admin">
            <div class="p-6 border-b border-white/20">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold tracking-wide" aria-label="VOXARA Admin">VOXARA</a>
                <p class="text-xs text-white/60 mt-1">Panel Admin</p>
            </div>
            <nav class="flex-1 p-4 space-y-1" aria-label="Navigasi">
                <h2 class="text-xs uppercase tracking-wider text-white/50 mb-3 font-semibold">Menu Utama</h2>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50 @if(request()->routeIs('admin.dashboard')) bg-white/15 @endif"
                   aria-current="@if(request()->routeIs('admin.dashboard')) page @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50 @if(request()->routeIs('admin.users.*')) bg-white/15 @endif"
                   aria-current="@if(request()->routeIs('admin.users.*')) page @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Kelola Pengguna
                </a>
                <a href="{{ route('admin.devices.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50 @if(request()->routeIs('admin.devices.*')) bg-white/15 @endif"
                   aria-current="@if(request()->routeIs('admin.devices.*')) page @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    Kelola Perangkat
                </a>
            </nav>
            <div class="p-4 border-t border-white/20 space-y-1">
                <a href="{{ route('user.library') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    Mode Pengguna
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50 text-sm text-left">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b border-gray-200 px-8 py-4">
                <h1 class="text-xl font-bold text-text-primary">@yield('page-title', 'Dashboard')</h1>
            </header>
            <main class="flex-1 overflow-y-auto p-8" id="main-content" role="main">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
