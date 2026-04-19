@extends('layouts.admin')
@section('title', 'Detail Pengguna - VOXARA')
@section('page-title', 'Detail Pengguna')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-secondary hover:underline focus:outline-none focus:ring-2 focus:ring-secondary/50 rounded font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-text-primary">{{ $user->name }}</h2>
            <p class="text-text-secondary mt-1">{{ $user->email }}</p>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold mt-2 {{ $user->role === 'admin' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-text-secondary' }}">
                {{ $user->role === 'admin' ? 'Admin' : 'Pengguna' }}
            </span>
        </div>
        <div class="p-6 grid gap-6">
            <div>
                <h3 class="font-semibold text-text-primary mb-3">Informasi Akun</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex gap-4">
                        <dt class="text-text-secondary w-32">ID:</dt>
                        <dd class="text-text-primary font-medium">{{ $user->id }}</dd>
                    </div>
                    <div class="flex gap-4">
                        <dt class="text-text-secondary w-32">Email:</dt>
                        <dd class="text-text-primary font-medium">{{ $user->email }}</dd>
                    </div>
                    <div class="flex gap-4">
                        <dt class="text-text-secondary w-32">Bergabung:</dt>
                        <dd class="text-text-primary font-medium">{{ $user->created_at->format('d F Y, H:i') }}</dd>
                    </div>
                    <div class="flex gap-4">
                        <dt class="text-text-secondary w-32">Google ID:</dt>
                        <dd class="text-text-primary font-medium">{{ $user->google_id ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="font-semibold text-text-primary mb-3">Statistik</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-light-bg rounded-lg p-4">
                        <dt class="text-text-secondary">Total Dokumen</dt>
                        <dd class="text-2xl font-bold text-primary mt-1">{{ $user->documents()->count() }}</dd>
                    </div>
                    <div class="bg-light-bg rounded-lg p-4">
                        <dt class="text-text-secondary">Total Percakapan</dt>
                        <dd class="text-2xl font-bold text-secondary mt-1">{{ $user->conversations()->count() }}</dd>
                    </div>
                </dl>
            </div>

            @if($user->documents()->count() > 0)
            <div>
                <h3 class="font-semibold text-text-primary mb-3">Dokumen Terbaru</h3>
                <ul class="space-y-2">
                    @foreach($user->documents()->orderBy('created_at', 'desc')->limit(5)->get() as $doc)
                    <li class="flex items-center gap-3 text-sm">
                        <span class="w-2 h-2 rounded-full {{ $doc->status === 'ready' ? 'bg-success' : ($doc->status === 'error' ? 'bg-danger' : 'bg-yellow-400') }}" aria-hidden="true"></span>
                        <span class="text-text-primary">{{ $doc->title }}</span>
                        <span class="text-text-secondary ml-auto text-xs">{{ $doc->created_at->format('d M Y') }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        <div class="p-6 border-t border-gray-100 flex gap-3">
            @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                @csrf
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50">
                    {{ $user->role === 'admin' ? 'Jadikan Pengguna' : 'Jadikan Admin' }}
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
