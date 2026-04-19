@extends('layouts.guest')
@section('title', 'VOXARA - Platform Pembaca Dokumen Aksesibel')

@section('content')
<header class="bg-primary text-white" role="banner">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ route('landing') }}" class="text-2xl font-bold tracking-wide" aria-label="VOXARA Beranda">VOXARA</a>
        <nav aria-label="Navigasi Utama">
            <ul class="flex items-center gap-6">
                @guest
                <li><a href="{{ route('login') }}" class="hover:underline focus:outline-none focus:ring-2 focus:ring-white/50 px-3 py-1 rounded">Masuk</a></li>
                <li><a href="{{ route('register') }}" class="bg-white text-primary px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50">Daftar</a></li>
                @else
                <li><a href="{{ route('user.library') }}" class="hover:underline focus:outline-none focus:ring-2 focus:ring-white/50 px-3 py-1 rounded">Dashboard</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:underline focus:outline-none focus:ring-2 focus:ring-white/50 px-3 py-1 rounded">Keluar</button>
                    </form>
                </li>
                @endguest
            </ul>
        </nav>
    </div>
</header>

<main id="main-content">
    <section class="bg-primary text-white py-24 px-4" aria-labelledby="hero-heading">
        <div class="max-w-4xl mx-auto text-center">
            <h2 id="hero-heading" class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Platform Pembaca Dokumen yang Aksesibel</h2>
            <p class="text-xl md:text-2xl text-white/80 mb-10 max-w-2xl mx-auto">Dukung pembelajaran bagi pengguna tunanetra dengan antarmuka suara, pertanyaan berbasis AI, dan ekspor Braille</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50">Mulai Sekarang</a>
                <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50">Masuk</a>
                @else
                <a href="{{ route('user.library') }}" class="bg-white text-primary px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50">Buka Pustaka</a>
                @endguest
            </div>
        </div>
    </section>

    <section class="py-20 px-4 bg-white" aria-labelledby="features-heading">
        <div class="max-w-6xl mx-auto">
            <h2 id="features-heading" class="text-3xl font-bold text-center text-text-primary mb-4">Fitur Utama</h2>
            <p class="text-text-secondary text-center mb-12 max-w-xl mx-auto">Tiga pilar utama yang membuat VOXARA menjadi platform pembelajaran yang inklusif</p>
            <div class="grid md:grid-cols-3 gap-8">
                <article class="bg-light-bg rounded-xl p-8 border border-blue-100">
                    <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6" aria-hidden="true">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-text-primary mb-3">Tanya Jawab Berbasis AI</h3>
                    <p class="text-text-secondary leading-relaxed">Unggah dokumen STEM/math SMP-SMA dan tanyakan apa saja. AI akan menjawab berdasarkan isi dokumen Anda.</p>
                </article>
                <article class="bg-light-bg rounded-xl p-8 border border-blue-100">
                    <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6" aria-hidden="true">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-text-primary mb-3">Antarmuka Suara (VUI)</h3>
                    <p class="text-text-secondary leading-relaxed">Gunakan suara Anda untuk bertanya dan mendengarkan jawaban. Mendukung penuh navigasi keyboard dan pembaca layar.</p>
                </article>
                <article class="bg-light-bg rounded-xl p-8 border border-blue-100">
                    <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6" aria-hidden="true">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-text-primary mb-3">Ekspor Braille</h3>
                    <p class="text-text-secondary leading-relaxed">Konversi dokumen ke format Braille dan kirim langsung ke perangkat EduBraille untuk dicetak.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="py-20 px-4 bg-light-bg" aria-labelledby="accessibility-heading">
        <div class="max-w-4xl mx-auto text-center">
            <h2 id="accessibility-heading" class="text-3xl font-bold text-text-primary mb-6">Dirancang untuk Semua</h2>
            <p class="text-text-secondary text-lg mb-10">VOXARA memenuhi standar WCAG 2.1 AA, kompatibel dengan NVDA, JAWS, dan pembaca layar lainnya.</p>
            <ul class="inline-block text-left space-y-3 text-text-secondary">
                <li class="flex items-center gap-3"><span class="text-success font-bold" aria-hidden="true">✓</span> Navigasi keyboard penuh</li>
                <li class="flex items-center gap-3"><span class="text-success font-bold" aria-hidden="true">✓</span> Kontras warna WCAG AA</li>
                <li class="flex items-center gap-3"><span class="text-success font-bold" aria-hidden="true">✓</span> Label ARIA untuk semua elemen interaktif</li>
                <li class="flex items-center gap-3"><span class="text-success font-bold" aria-hidden="true">✓</span> Region semantik (nav, main, aside)</li>
            </ul>
        </div>
    </section>
</main>

<footer class="bg-primary text-white/70 py-8 px-4" role="contentinfo">
    <div class="max-w-6xl mx-auto text-center">
        <p class="text-white font-bold text-xl mb-2">VOXARA</p>
        <p class="text-sm">Platform Pembaca Dokumen Aksesibel &copy; {{ date('Y') }}</p>
    </div>
</footer>
@endsection
