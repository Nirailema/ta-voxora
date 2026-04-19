@extends('layouts.user')
@section('title', $document->title . ' - VOXARA')
@section('page-title', $document->title)

@section('content')
<div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-8rem)]">
    <div class="lg:w-72 flex-shrink-0 bg-white rounded-xl border border-gray-100 shadow-sm p-5 overflow-y-auto">
        <a href="{{ route('user.library') }}" class="inline-flex items-center gap-2 text-secondary hover:underline text-sm font-medium mb-4 focus:outline-none focus:ring-2 focus:ring-secondary/50 rounded">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Pustaka
        </a>
        <h2 class="font-bold text-text-primary mb-2">Info Dokumen</h2>
        <p class="text-sm text-text-secondary mb-1"><strong>Judul:</strong> {{ $document->title }}</p>
        <p class="text-sm text-text-secondary mb-1"><strong>File:</strong> {{ $document->original_filename }}</p>
        <p class="text-sm text-text-secondary mb-3"><strong>Diunggah:</strong> {{ $document->created_at->format('d M Y') }}</p>
        <div class="bg-light-bg rounded-lg p-3 mb-4">
            <h3 class="text-xs font-semibold text-text-secondary uppercase tracking-wider mb-2">Pratinjau</h3>
            <p class="text-sm text-text-primary leading-relaxed">{{ $document->preview_text ?? 'Tidak ada pratinjau.' }}</p>
        </div>
        <div class="space-y-2">
            <a href="{{ route('user.braille', $document->id) }}"
               class="flex items-center gap-2 px-3 py-2 bg-primary/10 text-primary rounded-lg text-sm font-medium hover:bg-primary/20 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 w-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                Kirim ke Braille
            </a>
            <a href="{{ route('user.conversation.export', $document->id) }}"
               class="flex items-center gap-2 px-3 py-2 bg-gray-100 text-text-secondary rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300 w-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export ke Word
            </a>
            @if($messages->count() > 0)
            <form method="POST" action="{{ route('user.conversation.clear', $document->id) }}" onsubmit="return confirm('Hapus semua riwayat percakapan?');">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-3 py-2 bg-danger/10 text-danger rounded-lg text-sm font-medium hover:bg-danger/20 transition-colors focus:outline-none focus:ring-2 focus:ring-danger/50 w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus Riwayat
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="flex-1 flex flex-col bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden min-h-0">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-text-primary">Percakapan</h2>
            <div class="flex items-center gap-3">
                <label for="tts-toggle" class="flex items-center gap-2 text-sm text-text-secondary cursor-pointer">
                    <span>Auto-TTS</span>
                    <input type="checkbox" id="tts-toggle" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                </label>
            </div>
        </div>

        @if(session('success'))
        <div class="px-4 pt-4">
            <div class="bg-success/10 border border-success/30 text-success rounded-lg p-3 text-sm" role="alert" aria-live="polite">{{ session('success') }}</div>
        </div>
        @endif

        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4" role="log" aria-live="polite" aria-label="Riwayat percakapan">
            @forelse($messages as $message)
            <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] rounded-xl px-4 py-3 {{ $message->role === 'user' ? 'bg-primary text-white' : 'bg-light-bg text-text-primary' }}">
                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $message->content }}</p>
                    <p class="text-xs mt-1 opacity-60">{{ $message->created_at->format('H:i') }}</p>
                </div>
            </div>
            @empty
            <div class="flex justify-center py-12 text-center">
                <div>
                    <p class="text-text-secondary font-medium">Belum ada pesan.</p>
                    <p class="text-text-secondary text-sm mt-1">Ajukan pertanyaan tentang dokumen ini di bawah.</p>
                </div>
            </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('user.conversation.ask', $document->id) }}" id="chat-form" class="flex gap-2">
                @csrf
                <div class="flex-1 relative">
                    <label for="message-input" class="sr-only">Ketik pertanyaan Anda</label>
                    <textarea id="message-input" name="message" rows="1" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-colors"
                              placeholder="Ajukan pertanyaan tentang dokumen ini..."
                              aria-label="Ketik pertanyaan Anda"
                              onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault();this.form.submit()}"
                              oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"></textarea>
                </div>
                <button type="button" id="voice-btn"
                        class="flex-shrink-0 w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300"
                        aria-label="Rekam suara" title="Rekam dengan suara">
                    <svg class="w-5 h-5 text-text-secondary" id="mic-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                </button>
                <button type="submit" id="send-btn"
                        class="flex-shrink-0 w-12 h-12 bg-primary hover:bg-primary/90 rounded-xl flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2"
                        aria-label="Kirim pesan">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    const chatForm = document.getElementById('chat-form');
    const chatMessages = document.getElementById('chat-messages');
    const sendBtn = document.getElementById('send-btn');
    const ttsToggle = document.getElementById('tts-toggle');
    const voiceBtn = document.getElementById('voice-btn');
    const messageInput = document.getElementById('message-input');
    let isRecording = false;
    let recognition = null;

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(chatForm);
        const message = formData.get('message');
        if (!message.trim()) return;

        sendBtn.disabled = true;

        // Show user message immediately
        addMessage('user', message);

        try {
            const response = await fetch(chatForm.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData,
            });

            const data = await response.json();

            if (data.error) {
                addMessage('assistant', data.error);
            } else if (data.success && data.message) {
                addMessage('assistant', data.message.content);

                if (ttsToggle.checked && 'speechSynthesis' in window) {
                    setTimeout(() => speak(data.message.content), 500);
                }
            }
        } catch (err) {
            addMessage('assistant', 'Maaf, terjadi kesalahan koneksi. Silakan coba lagi.');
        }

        sendBtn.disabled = false;
        messageInput.value = '';
        messageInput.style.height = 'auto';
    });

    function addMessage(role, content) {
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const div = document.createElement('div');
        div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;
        div.innerHTML = `<div class="max-w-[80%] rounded-xl px-4 py-3 ${role === 'user' ? 'bg-primary text-white' : 'bg-light-bg text-text-primary'}"><p class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(content)}</p><p class="text-xs mt-1 opacity-60">${time}</p></div>`;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    function speak(text) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = 1.0;
        speechSynthesis.cancel();
        speechSynthesis.speak(utterance);
    }

    // Voice input
    const SpeechRecognitionAPI = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (SpeechRecognitionAPI) {
        recognition = new SpeechRecognitionAPI();
        recognition.lang = 'id-ID';
        recognition.continuous = false;
        recognition.interimResults = false;

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            messageInput.value = transcript;
            messageInput.dispatchEvent(new Event('input'));
            isRecording = false;
            updateVoiceBtn();
        };

        recognition.onerror = function(event) {
            isRecording = false;
            updateVoiceBtn();
            if (event.error === 'not-allowed') {
                showVoiceStatus('Izin mikrofon ditolak. Izinkan akses mikrofon di browser.');
            } else if (event.error === 'no-speech') {
                showVoiceStatus('Tidak ada suara terdeteksi. Coba lagi.');
            } else {
                showVoiceStatus('Error voice: ' + event.error);
            }
        };
        recognition.onend = function() { isRecording = false; updateVoiceBtn(); };
    }

    function showVoiceStatus(msg) {
        let status = document.getElementById('voice-status');
        if (!status) {
            status = document.createElement('div');
            status.id = 'voice-status';
            status.className = 'fixed bottom-24 left-1/2 -translate-x-1/2 bg-danger text-white px-4 py-2 rounded-lg text-sm shadow-lg z-50';
            status.setAttribute('role', 'alert');
            status.setAttribute('aria-live', 'polite');
            document.body.appendChild(status);
        }
        status.textContent = msg;
        setTimeout(() => status.remove(), 4000);
    }

    voiceBtn.addEventListener('click', function() {
        if (!recognition) {
            showVoiceStatus('Voice input tidak didukung browser ini. Gunakan Chrome atau Edge.');
            return;
        }
        if (isRecording) {
            recognition.stop();
        } else {
            recognition.start();
            isRecording = true;
            updateVoiceBtn();
            showVoiceStatus('Merekam... Silakan bicara sekarang.');
            setTimeout(() => {
                const s = document.getElementById('voice-status');
                if (s) s.remove();
            }, 3000);
        }
    });

    function updateVoiceBtn() {
        if (isRecording) {
            voiceBtn.classList.add('bg-danger/20');
            voiceBtn.classList.remove('bg-gray-100');
            voiceBtn.setAttribute('aria-label', 'Berhenti merekam');
        } else {
            voiceBtn.classList.remove('bg-danger/20');
            voiceBtn.classList.add('bg-gray-100');
            voiceBtn.setAttribute('aria-label', 'Rekam suara');
        }
    }

    // Scroll to bottom on load
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
})();
</script>
@endsection
