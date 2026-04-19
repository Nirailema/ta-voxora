@extends('layouts.admin')
@section('title', 'Kelola Pengguna - VOXARA')
@section('page-title', 'Kelola Pengguna')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-lg font-bold text-text-primary">Daftar Pengguna</h2>
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <label for="search" class="sr-only">Cari pengguna</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau email..."
                       class="flex-1 sm:flex-none px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 text-sm">
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50">Cari</button>
                @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">Reset</a>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="mx-6 mt-4 bg-success/10 border border-success/30 text-success rounded-lg p-4" role="alert" aria-live="polite">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mx-6 mt-4 bg-danger/10 border border-danger/30 text-danger rounded-lg p-4" role="alert" aria-live="polite">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full" aria-label="Tabel daftar pengguna">
            <thead>
                <tr class="border-b border-gray-100 bg-light-bg text-left">
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Nama</th>
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Email</th>
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Peran</th>
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Bergabung</th>
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Dokumen</th>
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-light-bg/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-text-primary">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-text-secondary text-sm">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-text-secondary' }}">
                            {{ $user->role === 'admin' ? 'Admin' : 'Pengguna' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-text-secondary text-sm">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-text-secondary text-sm">{{ $user->documents()->count() }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.show', $user->id) }}"
                               class="text-secondary hover:underline text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/50 rounded px-2 py-1">Lihat</a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-sm font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-secondary/50 rounded px-2 py-1 {{ $user->role === 'admin' ? 'text-danger' : 'text-success' }}">
                                    {{ $user->role === 'admin' ? 'Jadikan User' : 'Jadikan Admin' }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-text-secondary">Tidak ada pengguna ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-6 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
