@extends('layouts.user')
@section('title', 'Unggah Dokumen - VOXARA')
@section('page-title', 'Unggah Dokumen')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-text-primary">Unggah Dokumen Baru</h2>
            <p class="text-text-secondary mt-1 text-sm">Unggah dokumen PDF atau Word (maksimal 10MB) untuk diproses dan ditanya.</p>
        </div>

        <div class="p-6">
            @if(session('success'))
            <div class="bg-success/10 border border-success/30 text-success rounded-lg p-4 mb-6" role="alert" aria-live="polite">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="bg-danger/10 border border-danger/30 text-danger rounded-lg p-4 mb-6" role="alert" aria-live="polite">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('user.upload.submit') }}" enctype="multipart/form-data" id="upload-form" novalidate>
                @csrf
                <div id="drop-zone"
                     class="border-2 border-dashed border-gray-300 rounded-xl p-12 text-center hover:border-primary/50 hover:bg-light-bg/30 transition-colors cursor-pointer"
                     tabindex="0"
                     role="button"
                     aria-label="Area.Drop.File.Untuk.Unggah"
                     onkeydown="if(event.key==='Enter'||event.key===' ')document.getElementById('document').click()">
                    <div class="flex flex-col items-center gap-4" id="drop-content">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center" aria-hidden="true">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <div>
                            <p class="text-text-primary font-semibold" id="file-label">Seret file ke sini atau klik untuk memilih</p>
                            <p class="text-text-secondary text-sm mt-1">PDF atau DOCX, maksimal 10MB</p>
                        </div>
                    </div>
                    <input type="file" id="document" name="document" accept=".pdf,.docx,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                           class="hidden" required aria-describedby="file-info"
                           onchange="handleFileSelect(this)">
                </div>

                <div id="selected-file" class="hidden mt-4 bg-light-bg rounded-lg p-4 border border-primary/20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center" aria-hidden="true">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-text-primary" id="selected-filename"></p>
                            <p class="text-xs text-text-secondary" id="selected-filesize"></p>
                        </div>
                        <button type="button" onclick="clearFile()" class="text-danger hover:text-danger/80 focus:outline-none focus:ring-2 focus:ring-danger/50 rounded p-1" aria-label="Hapus file yang dipilih">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <p id="file-info" class="text-xs text-text-secondary mt-2">Format yang didukung: PDF, DOCX</p>

                @error('document')
                <p class="text-danger text-sm mt-2">{{ $message }}</p>
                @enderror

                <div class="mt-6" id="upload-actions">
                    <button type="submit" id="submit-btn"
                            class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        Proses Dokumen
                    </button>
                </div>
            </form>

            <div id="progress-section" class="hidden mt-6">
                <div class="bg-light-bg rounded-lg p-6 text-center" role="status" aria-live="polite">
                    <div class="w-12 h-12 border-4 border-primary/30 border-t-primary rounded-full animate-spin mx-auto mb-4" aria-hidden="true"></div>
                    <p class="font-semibold text-text-primary" id="progress-message">Memproses dokumen...</p>
                    <p class="text-text-secondary text-sm mt-1">Mohon tunggu, ini mungkin beberapa saat.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-text-primary mb-3">Tips</h3>
        <ul class="space-y-2 text-sm text-text-secondary">
            <li class="flex items-start gap-2"><span class="text-success font-bold mt-0.5" aria-hidden="true">✓</span> Dokumen STEM/math untuk tingkat SMP-SMA sangat didukung</li>
            <li class="flex items-start gap-2"><span class="text-success font-bold mt-0.5" aria-hidden="true">✓</span> Teks akan otomatis dibersihkan dari header/footer</li>
            <li class="flex items-start gap-2"><span class="text-success font-bold mt-0.5" aria-hidden="true">✓</span> Anda bisa bertanya dalam Bahasa Indonesia setelah dokumen siap</li>
        </ul>
    </div>
</div>

<script>
function handleFileSelect(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('file-label').textContent = file.name;
    document.getElementById('selected-filename').textContent = file.name;
    document.getElementById('selected-filesize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    document.getElementById('selected-file').classList.remove('hidden');
    document.getElementById('drop-content').querySelector('p').textContent = file.name;
}

function clearFile() {
    document.getElementById('document').value = '';
    document.getElementById('selected-file').classList.add('hidden');
    document.getElementById('file-label').textContent = 'Seret file ke sini atau klik untuk memilih';
}

const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('click', () => document.getElementById('document').click());
dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('border-primary'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-primary'));
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary');
    const file = e.dataTransfer.files[0];
    if (file) {
        const input = document.getElementById('document');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        handleFileSelect(input);
    }
});

document.getElementById('upload-form').addEventListener('submit', function() {
    document.getElementById('submit-btn').disabled = true;
    document.getElementById('progress-section').classList.remove('hidden');
});
</script>
@endsection
