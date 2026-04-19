@extends('layouts.admin')
@section('title', 'Dashboard Admin - VOXARA')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
    <article class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-text-secondary">Total Pengguna</p>
                <p class="text-3xl font-bold text-text-primary mt-1">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center" aria-hidden="true">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
    </article>

    <article class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-text-secondary">Total Dokumen</p>
                <p class="text-3xl font-bold text-text-primary mt-1">{{ number_format($stats['total_documents']) }}</p>
            </div>
            <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center" aria-hidden="true">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </article>

    <article class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-text-secondary">Total Percakapan</p>
                <p class="text-3xl font-bold text-text-primary mt-1">{{ number_format($stats['total_conversations']) }}</p>
            </div>
            <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center" aria-hidden="true">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
        </div>
    </article>

    <article class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-text-secondary">Dokumen Siap</p>
                <p class="text-3xl font-bold text-text-primary mt-1">{{ number_format($stats['ready_documents']) }}</p>
            </div>
            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center" aria-hidden="true">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </article>
</div>

<div class="mt-8 bg-white rounded-xl p-8 border border-gray-100 shadow-sm">
    <h2 class="text-xl font-bold text-text-primary mb-4">Selamat Datang di Panel Admin VOXARA</h2>
    <p class="text-text-secondary">Kelola pengguna, dokumen, dan perangkat EduBraille dari panel ini. Pastikan untuk menjaga keamanan akun Anda.</p>
</div>
@endsection
