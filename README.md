# VOXARA

Platform Pembaca Dokumen Aksesibel untuk Pengguna Tunanetra — menggunakan RAG (Retrieval-Augmented Generation), ChromaDB, dan LLM API.

## Fitur

- **Upload Dokumen PDF/DOCX** (STEM/math SMP-SMA)
- **Tanya Jawab Berbasis AI** dengan RAG pipeline (ChromaDB + LLM API)
- **Antarmuka Suara (VUI)** via Web Speech API (STT + TTS)
- **Ekspor ke Braille** dan kirim ke perangkat EduBraille (RBD)
- **WCAG 2.1 AA** — fully accessible, screen reader/NVDA-friendly
- **Google OAuth** authentication
- **Admin Panel** untuk kelola pengguna dan perangkat

## Tech Stack

- Backend: Laravel 12
- Frontend: Blade + Tailwind CSS (CDN, no build)
- Database: MySQL
- Vector DB: ChromaDB (HTTP API)
- LLM: OpenAI-compatible API
- File parsing: smalot/pdfparser + phpoffice/phpword
- Voice: Web Speech API (browser-native)

---

## Setup Instructions

### 1. Install Dependencies

```bash
cd voxora
composer install
```

Packages yang diinstal:
- `smalot/pdfparser` — PDF text extraction
- `phpoffice/phpword` — DOCX text extraction
- `laravel/socialite` — Google OAuth
- `guzzlehttp/guzzle` — HTTP client

### 2. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan isi:

```env
# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=voxara
DB_USERNAME=root
DB_PASSWORD=

# LLM (OpenAI-compatible)
LLM_API_KEY=sk-...
LLM_API_URL=https://api.openai.com/v1/chat/completions
LLM_MODEL=gpt-4o-mini

# Embedding
EMBEDDING_API_URL=https://api.openai.com/v1/embeddings
EMBEDDING_MODEL=text-embedding-3-small

# ChromaDB
CHROMA_HOST=http://localhost:8000

# Google OAuth (optional)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

Buat database MySQL:

```sql
CREATE DATABASE voxara CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Run Migrations

```bash
php artisan migrate
php artisan db:seed   # Creates admin@voxara.local / password
php artisan storage:link
```

### 4. Start ChromaDB (Vector Database)

```bash
docker run -p 8000:8000 chromadb/chroma
```

ChromaDB akan berjalan di `http://localhost:8000`.

### 5. Start the App

```bash
php artisan serve
```

Buka `http://localhost:8000`

### Default Admin Account

```
Email:    admin@voxara.local
Password: password
```

---

## Koneksi LLM API

VOXARA mendukung API LLM OpenAI-compatible. Edit di `.env`:

### OpenAI
```env
LLM_API_URL=https://api.openai.com/v1/chat/completions
LLM_MODEL=gpt-4o-mini
LLM_API_KEY=sk-your-key
EMBEDDING_API_URL=https://api.openai.com/v1/embeddings
EMBEDDING_MODEL=text-embedding-3-small
```

### Google Gemini (via proxy)
Jika menggunakan Gemini, gunakan proxy/server yang mengkonversi ke format OpenAI:

```env
LLM_API_URL=https://your-gemini-proxy.com/v1/chat/completions
LLM_MODEL=gemini-pro
LLM_API_KEY=your-gemini-key
```

---

## Struktur Proyek

```
app/
├── Http/Controllers/
│   ├── AuthController.php
│   ├── LandingController.php
│   ├── Admin/
│   │   ├── DashboardController.php
│   │   ├── UserController.php
│   │   └── DeviceController.php
│   └── User/
│       ├── DocumentController.php
│       ├── LibraryController.php
│       ├── ConversationController.php
│       └── BrailleController.php
├── Services/
│   ├── ChromaService.php       # ChromaDB HTTP wrapper
│   ├── RagService.php          # RAG pipeline (embed → retrieve → generate)
│   ├── DocumentProcessor.php   # PDF/DOCX extract + sanitize + chunk
│   ├── BrailleConverter.php    # Text → Braille (stub)
│   └── EduBrailleService.php  # Send to RBD device
└── Models/
    ├── User.php, Document.php, Conversation.php, Message.php, Device.php
```

---

## Placeholder Notes

- **BrailleConverter**: Stub implementation. Full Braille grade 1/2 conversion to be provided later.
- **EduBraille endpoint**: `EduBrailleService::sendToDevice()` — works with RBD-compliant endpoints.
- **MathReader**: `DocumentProcessor` includes a comment for future MathReader integration (https://github.com/AIDASLab/MathReader) for math notation (LaTeX/MathML) support.

## Aksesibilitas

Semua halaman memenuhi WCAG 2.1 AA:
- Heading hierarchy (`h1` → `h2` → `h3`, tidak ada level yang dilewati)
- Landmarks semantik (`<nav>`, `<main>`, `<aside>`, `<header>`, `<footer>`)
- Semua form input memiliki `<label>` terkait
- ARIA roles (`role="status"`, `aria-live="polite"`) untuk region dinamis
- Keyboard navigation penuh
- Kontras warna 4.5:1+
- Focus visible pada semua elemen interaktif
