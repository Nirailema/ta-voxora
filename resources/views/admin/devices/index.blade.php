@extends('layouts.admin')
@section('title', 'Kelola Perangkat - VOXARA')
@section('page-title', 'Kelola Perangkat EduBraille')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-text-primary">Perangkat EduBraille</h2>
                <p class="text-sm text-text-secondary mt-1">Kelola endpoint perangkat Braille untuk ekspor dokumen</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mx-6 mt-4 bg-success/10 border border-success/30 text-success rounded-lg p-4" role="alert" aria-live="polite">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full" aria-label="Tabel daftar perangkat EduBraille">
            <thead>
                <tr class="border-b border-gray-100 bg-light-bg text-left">
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Nama</th>
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Endpoint URL</th>
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Status</th>
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Ditambahkan</th>
                    <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-text-secondary">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($devices as $device)
                <tr class="hover:bg-light-bg/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-text-primary">{{ $device->name }}</td>
                    <td class="px-6 py-4 text-text-secondary text-sm font-mono max-w-xs truncate">{{ $device->endpoint_url }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $device->status === 'active' ? 'bg-success/10 text-success' : 'bg-gray-100 text-text-secondary' }}">
                            {{ $device->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-text-secondary text-sm">{{ $device->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.devices.toggle', $device) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-secondary hover:underline focus:outline-none focus:ring-2 focus:ring-secondary/50 rounded px-2 py-1">
                                    {{ $device->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.devices.destroy', $device) }}" class="inline" onsubmit="return confirm('Hapus perangkat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-danger hover:underline focus:outline-none focus:ring-2 focus:ring-danger/50 rounded px-2 py-1">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-text-secondary">
                        <p>Belum ada perangkat EduBraille.</p>
                        <p class="text-sm mt-1">Gunakan form di bawah untuk menambahkan perangkat pertama.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($devices->hasPages())
    <div class="p-6 border-t border-gray-100">
        {{ $devices->links() }}
    </div>
    @endif
</div>

<div class="mt-8 bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-bold text-text-primary">Tambah Perangkat Baru</h2>
    </div>
    <div class="p-6">
        <p class="text-text-secondary text-sm mb-4">Fitur CRUD perangkat dalam pengembangan. Endpoint URL harus mengarah ke server EduBraille yang valid.</p>
        <form method="POST" action="{{ route('admin.devices.store') }}" class="grid gap-4 max-w-lg">
            @csrf
            <div>
                <label for="name" class="block text-sm font-semibold text-text-primary mb-2">Nama Perangkat</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                       placeholder="Contoh: Printer Braille Ruang 1">
            </div>
            <div>
                <label for="endpoint_url" class="block text-sm font-semibold text-text-primary mb-2">Endpoint URL</label>
                <input type="url" id="endpoint_url" name="endpoint_url" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                       placeholder="https://edubraille.example.com/api/send">
            </div>
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 self-start">
                Tambah Perangkat
            </button>
        </form>
    </div>
</div>
@endsection
