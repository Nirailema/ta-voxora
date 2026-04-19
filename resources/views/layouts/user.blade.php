<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="VOXARA - Panel Pengguna">
    <title>@yield('title', 'VOXARA')</title>
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
        <aside class="w-64 flex-shrink-0 bg-primary text-white flex flex-col" role="navigation" aria-label="Menu Utama">
            <div class="p-6 border-b border-white/20">
                <a href="{{ route('user.library') }}" class="text-2xl font-bold tracking-wide" aria-label="VOXARA">VOXARA</a>
                <p class="text-xs text-white/60 mt-1">Platform Dokumen Aksesibel</p>
            </div>
            <nav class="flex-1 p-4 space-y-1" aria-label="Navigasi">
                <h2 class="text-xs uppercase tracking-wider text-white/50 mb-3 font-semibold">Menu</h2>
                <a href="{{ route('user.upload') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50 @if(request()->routeIs('user.upload')) bg-white/15 @endif"
                   aria-current="@if(request()->routeIs('user.upload')) page @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Unggah Dokumen
                </a>
                <a href="{{ route('user.library') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50 @if(request()->routeIs('user.library*') && !request()->routeIs('user.upload')) bg-white/15 @endif"
                   aria-current="@if(request()->routeIs('user.library*') && !request()->routeIs('user.upload')) page @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Pustaka
                </a>
            </nav>
            <div class="p-4 border-t border-white/20 space-y-1">
                <div class="px-4 py-2 text-sm text-white/60">
                    <span>{{ auth()->user()->name }}</span>
                    <br><span class="text-xs">{{ auth()->user()->email }}</span>
                </div>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Panel Admin
                </a>
                @endif
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
                <h1 class="text-xl font-bold text-text-primary">@yield('page-title', 'Pustaka')</h1>
            </header>
            <main class="flex-1 overflow-y-auto p-8" id="main-content" role="main">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
