@extends('layouts.user')
@section('title', 'Kirim ke Braille - VOXARA')
@section('page-title', 'Kirim ke Braille')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('user.conversation.show', $document->id) }}" class="inline-flex items-center gap-2 text-secondary hover:underline focus:outline-none focus:ring-2 focus:ring-secondary/50 rounded font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Percakapan
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-text-primary">Kirim ke EduBraille</h2>
            <p class="text-text-secondary mt-1 text-sm">Dokumen: <strong>{{ $document->title }}</strong></p>
        </div>

        <div class="p-6 space-y-6">
            <div>
                <label for="chunk-size" class="block text-sm font-semibold text-text-primary mb-3">Ukuran Chunk Braille</label>
                <div class="flex gap-4" role="group" aria-label="Pilih ukuran chunk">
                    @foreach($chunkSizes as $size)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="chunk_size" value="{{ $size }}"
                               class="w-4 h-4 text-primary border-gray-300 focus:ring-primary"
                               @if($size === $defaultChunkSize) checked @endif>
                        <span class="text-sm text-text-primary">{{ $size }} karakter</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-text-secondary mt-2">Pilih 5 karakter untuk presisi tinggi atau 20 karakter untuk throughput lebih besar.</p>
            </div>

            <div>
                <label for="device-select" class="block text-sm font-semibold text-text-primary mb-2">Pilih Perangkat EduBraille</label>
                <select id="device-select" name="device_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors">
                    <option value="">-- Pilih Perangkat --</option>
                    @foreach($devices as $device)
                    <option value="{{ $device->id }}">{{ $device->name }} ({{ $device->status }})</option>
                    @endforeach
                </select>
                @if($devices->isEmpty())
                <p class="text-xs text-danger mt-1">Belum ada perangkat aktif. Hubungi admin untuk mendaftarkan perangkat.</p>
                @endif
            </div>

            <div>
                <h3 class="text-sm font-semibold text-text-primary mb-3">Pratinjau Braille</h3>
                <div class="bg-light-bg rounded-lg p-4 border border-gray-200 overflow-x-auto" id="braille-preview" role="region" aria-label="Pratinjau Braille" aria-live="polite">
                    @if(!empty($previewChunks))
                    @foreach($previewChunks as $chunk)
                    <div class="mb-3 pb-3 border-b border-gray-200 last:border-0">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-text-secondary">Chunk {{ $chunk['index'] + 1 }}</span>
                            <span class="text-xs text-text-secondary">{{ $chunk['length'] }} karakter</span>
                        </div>
                        <p class="font-mono text-lg leading-relaxed tracking-wide text-text-primary" dir="ltr">{{ $chunk['braille'] }}</p>
                    </div>
                    @endforeach
                    @else
                    <p class="text-text-secondary text-sm">Tidak ada pratinjau yang tersedia.</p>
                    @endif
                </div>
                <p class="text-xs text-text-secondary mt-2">Menampilkan {{ count($previewChunks) ?? 0 }} chunk dari dokumen.</p>
            </div>

            <div class="flex gap-3">
                <button type="button" id="prev-chunk" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 disabled:opacity-50" aria-label="Chunk sebelumnya">Sebelumnya</button>
                <button type="button" id="next-chunk" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 disabled:opacity-50" aria-label="Chunk berikutnya">Selanjutnya</button>
            </div>

            <button type="button" id="send-device-btn"
                    class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                Kirim ke Perangkat EduBraille
            </button>

            <div id="send-status" class="hidden" role="status" aria-live="polite"></div>
        </div>
    </div>
</div>

<script>
(function() {
    const chunkSizeRadios = document.querySelectorAll('input[name="chunk_size"]');
    const deviceSelect = document.getElementById('device-select');
    const sendBtn = document.getElementById('send-device-btn');
    const statusDiv = document.getElementById('send-status');

    deviceSelect.addEventListener('change', () => {
        sendBtn.disabled = !deviceSelect.value;
    });

    sendBtn.addEventListener('click', async function() {
        if (!deviceSelect.value) return;

        sendBtn.disabled = true;
        statusDiv.className = 'hidden';
        statusDiv.innerHTML = '<div class="bg-primary/10 border border-primary/30 rounded-lg p-4 text-center"><div class="w-8 h-8 border-4 border-primary/30 border-t-primary rounded-full animate-spin mx-auto mb-2" aria-hidden="true"></div><p class="text-sm text-primary font-medium">Mengirim ke perangkat...</p></div>';
        statusDiv.classList.remove('hidden');

        try {
            const previewChunks = window.currentChunks || [];
            const response = await fetch('{{ route('user.braille.send', $document->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    device_id: deviceSelect.value,
                    chunks: previewChunks,
                    chunk_index: 0,
                }),
            });

            const data = await response.json();

            if (data.success) {
                statusDiv.innerHTML = `<div class="bg-success/10 border border-success/30 rounded-lg p-4 text-success text-sm"><strong>Berhasil!</strong> ${data.message}</div>`;
            } else {
                statusDiv.innerHTML = `<div class="bg-danger/10 border border-danger/30 rounded-lg p-4 text-danger text-sm"><strong>Gagal.</strong> ${data.message}</div>`;
            }
        } catch (err) {
            statusDiv.innerHTML = `<div class="bg-danger/10 border border-danger/30 rounded-lg p-4 text-danger text-sm"><strong>Error.</strong> Gagal terhubung ke server.</div>`;
        }

        sendBtn.disabled = !deviceSelect.value;
    });
})();
</script>
@endsection
