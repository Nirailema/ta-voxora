@extends('layouts.user')
@section('title', 'Pustaka - VOXARA')
@section('page-title', 'Pustaka Dokumen')

@section('content')
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-text-secondary"> {{ $documents->total() }} dokumen ditemukan</p>
        <form method="GET" action="{{ route('user.library') }}" class="flex gap-2">
            <label for="search" class="sr-only">Cari dokumen</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul dokumen..."
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 text-sm">
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50">Cari</button>
            @if(request('search'))
            <a href="{{ route('user.library') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">Reset</a>
            @endif
        </form>
    </div>
</div>

@if($documents->isEmpty())
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-16 text-center">
    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4" aria-hidden="true">
        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
    </div>
    <h2 class="text-xl font-bold text-text-primary mb-2">
        @if(request('search'))
        Tidak ada dokumen ditemukan
        @else
        Belum Ada Dokumen
        @endif
    </h2>
    <p class="text-text-secondary mb-6">
        @if(request('search'))
        Coba kata kunci lain atau
        @else
        Unggah dokumen pertama Anda untuk memulai.
        @endif
    </p>
    <a href="{{ route('user.upload') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
        Unggah Dokumen
    </a>
</div>
@else
<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" role="list" aria-label="Daftar dokumen">
    @foreach($documents as $document)
    <article class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-primary/20 transition-all group" role="listitem">
        <div class="p-6">
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-text-primary text-lg leading-snug group-hover:text-primary transition-colors truncate">
                        <a href="{{ route('user.conversation.show', $document->id) }}" class="focus:outline-none focus:underline">
                            {{ $document->title }}
                        </a>
                    </h3>
                </div>
                <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-success/10 text-success" aria-label="Status: siap">Siap</span>
            </div>
            <p class="text-text-secondary text-sm leading-relaxed line-clamp-3 mb-4">
                {{ $document->preview_text ?? 'Tidak ada pratinjau tersedia.' }}
            </p>
            <div class="flex items-center justify-between text-xs text-text-secondary">
                <span>{{ $document->created_at->format('d M Y') }}</span>
                <a href="{{ route('user.conversation.show', $document->id) }}"
                   class="inline-flex items-center gap-1 text-secondary font-semibold hover:underline focus:outline-none focus:ring-2 focus:ring-secondary/50 rounded px-2 py-1"
                   aria-label="Tanya tentang {{ $document->title }}">
                    Tanya AI
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </article>
    @endforeach
</div>

@if($documents->hasPages())
<div class="mt-8">
    {{ $documents->links() }}
</div>
@endif
@endif
@endsection
