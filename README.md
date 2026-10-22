<div align="center">
  <img src="public/assets/img/logo.svg" alt="NutriMate logo" width="180" />

  # NutriMate

  **Aplikasi web pengelolaan nutrisi dan rencana makan untuk mahasiswa — kelola database makanan, hitung kebutuhan gizi, susun meal planner, dan cari rekomendasi makanan sesuai budget.**

  <br />

  ![Platform](https://img.shields.io/badge/Platform-Web-4A90E2?style=for-the-badge)
  ![Framework](https://img.shields.io/badge/Framework-Laravel%2012-FF2D20?style=for-the-badge&logo=laravel)
  ![Language](https://img.shields.io/badge/Language-PHP%208.3+-777BB4?style=for-the-badge&logo=php)
  ![Database](https://img.shields.io/badge/Database-PostgreSQL%20(Supabase)-336791?style=for-the-badge&logo=postgresql)
  ![Build](https://img.shields.io/badge/Bundler-Vite-646CFF?style=for-the-badge&logo=vite)

</div>

---

## Table of contents

- [Project overview](#project-overview)
- [Key features](#key-features)
- [Technology stack](#technology-stack)
- [Project structure](#project-structure)
- [Getting Started](#getting-started)
- [Team](#team)

---

## Project overview

| Item | Details |
| --- | --- |
| Application Type | Web Application (Full-Stack MVC) |
| Primary Platform | Desktop & Mobile Browser (Mobile-First UI) |
| Framework | Laravel 13 (PHP 8.3+) |
| Database | PostgreSQL (Supabase Cloud Database) |
| Course / Subject | Pemrograman Web (Praktikum Proyek Akhir) |

NutriMate adalah aplikasi web pengelolaan nutrisi dan pengatur pola makan harian yang dirancang khusus untuk mahasiswa kost. Aplikasi ini membantu pengguna memahami target asupan gizi harian (kalori, protein, karbohidrat, lemak), mencatat dan mengelola katalog makanan lengkap dengan harga & nilai gizi, menyusun rencana makan harian, serta memberikan rekomendasi makanan sehat yang ramah di kantong mahasiswa.

---

## Key features

| Feature | What the user can do |
| --- | --- |
| **Authentication & Protection** | Pendaftaran dan masuk akun melalui modal auth responsif (CSRF protected, hash password bcrypt). Proteksi akses fitur khusus untuk pengguna yang sudah masuk. |
| **User Profile Management** | Halaman profil responsif (mobile-first): melihat kartu identitas akun, memperbarui nama lengkap, serta mengubah password akun dengan toggle visibilitas password. |
| **Food Database (CRUD & Filter)** | Mengelola database makanan secara terpusat: melihat katalog interaktif, pencarian real-time, filter kategori (Makanan Pokok, Lauk Pauk, Sayuran, Buah, Minuman, Camilan), pengurutan, penambahan makanan baru, edit, serta hapus. |
| **Nutrition & Budget Planner** | Mendukung kalkulasi kalori & nutrisi harian serta estimasi pengeluaran per porsi makanan. |
| **Responsive Mobile-First UI** | Desain antarmuka modern bernuansa segar dan earthy (`--green`, `--cream`), dilengkapi navigasi desktop terintegrasi dan mobile drawer sidebar dengan hamburger toggle. |

---

## Technology stack

| Category | Technology | Purpose |
| --- | --- | --- |
| **Backend Framework** | Laravel 13 | Arsitektur MVC, Eloquent ORM, Routing, Authentication, Request Validation, Migrations & Seeders |
| **Language** | PHP 8.3+ | Logika backend modern dengan strict types dan constructor property promotion |
| **Database** | PostgreSQL (Supabase Cloud) | Penyimpanan data relasional aman (Users, Profiles, Foods, Nutrisi) |
| **Frontend UI** | Blade Templating, Vanilla CSS, Vanilla JS | Antarmuka ringan, responsif, komponen modular reusable (`layouts/`, `partials/`) |
| **Design System** | Custom CSS Design Tokens (`rules.md`) | Variabel warna HSL/Tailored, tipografi Poppins, spacing, radius-pill, shadow konsisten |
| **Asset Bundler** | Vite 8 + Laravel Vite Plugin | Hot Module Reloading (HMR) dan kompilasi minifikasi aset produksi |
| **Code Formatter & Quality** | Laravel Pint & PHPUnit | Penyeragaman standar penulisan kode PHP (PSR-12) dan testing reliabilitas |

---

## Project structure

```text
nutrimate13/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # AuthController, FoodController, HomeController, ProfileController
│   │   └── Requests/          # Form Requests: LoginRequest, RegisterRequest, UpdateProfileRequest, dll.
│   └── Models/                # Eloquent Models: User, Profile, Food
├── database/
│   ├── factories/             # Factory testing data
│   ├── migrations/            # Skema tabel database (users, profiles, foods)
│   └── seeders/               # Database seeder awal makanan & user
├── public/
│   ├── assets/img/            # Logo, hero visual, placeholder ikon
│   └── build/                 # Hasil build Vite (CSS, JS, Fonts)
├── resources/
│   ├── css/
│   │   └── app.css            # Desain sistem global, komponen, modal, profil, dan responsivitas
│   ├── js/
│   │   └── app.js             # Skrip interaksi global: sidebar, modal auth, food modal, pw-toggle
│   └── views/
│       ├── layouts/           # Master layout app.blade.php & alerts.blade.php
│       ├── partials/          # Navbar, sidebar mobile drawer, auth-modal, footer
│       ├── auth/              # View login & register
│       ├── foods/             # Halaman index katalog makanan, create, edit, show
│       ├── profile/           # Halaman profil akun pengguna
│       └── home.blade.php     # Halaman landing page utama
├── routes/
│   ├── web.php                # Rute web aplikasi utama
│   └── console.php            # Artisan console commands
├── rules.md                   # Konvensi proyek & design system tokens
├── composer.json              # Dependensi PHP & Laravel
├── package.json               # Dependensi JavaScript & Vite
└── README.md
```

---

## Getting Started

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

### Prerequisites

Pastikan perangkat Anda telah terpasang:
*   [PHP](https://www.php.net/) (minimal versi 8.2 atau 8.3+)
*   [Composer](https://getcomposer.org/)
*   [Node.js](https://nodejs.org/) & npm
*   Akses Database PostgreSQL (atau Supabase)

### Installation & Setup

1. **Clone repositori**
   ```sh
   git clone https://github.com/RRaph67/NUTRIMATE_13_PEMWEB.git
   cd nutrimate13
   ```

2. **Install dependensi PHP**
   ```sh
   composer install
   ```

3. **Install dependensi frontend**
   ```sh
   npm install
   ```

4. **Konfigurasi file environment (`.env`)**
   Salin file template `.env.example`:
   ```sh
   cp .env.example .env
   ```
   Atur koneksi database PostgreSQL / Supabase Anda pada `.env`:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=db.xfmuajsxutpcztljmbmj.supabase.co
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=your_password_here
   ```

5. **Generate Application Key**
   ```sh
   php artisan key:generate
   ```

6. **Jalankan Migrasi & Seeder Database**
   ```sh
   php artisan migrate --seed
   ```

7. **Build Aset Frontend**
   Untuk tahap pengembangan:
   ```sh
   npm run dev
   ```
   Atau untuk build produksi:
   ```sh
   npm run build
   ```

8. **Jalankan Local Development Server**
   ```sh
   php artisan serve
   ```
   Buka browser dan kunjungi: **`http://127.0.0.1:8000`**

---

## Team

| Name | Role | Responsibilities | Contact |
| --- | --- | --- | --- |
| <!-- PLACEHOLDER: Nama 1 --> | Full-stack Developer | Arsitektur Laravel, Database, CRUD & Auth | <!-- Kontak/GitHub --> |
| <!-- PLACEHOLDER: Nama 2 --> | Frontend Designer | UI/UX Design System, Styling & Responsivitas | <!-- Kontak/GitHub --> |
| <!-- PLACEHOLDER: Nama 3 --> | Technical Writer / QA | Testing, Dokumentasi, & Pengujian Alur | <!-- Kontak/GitHub --> |
