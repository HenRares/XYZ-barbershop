# XYZ Barbershop — Laravel

Hasil konversi project Lovable/TanStack TypeScript menjadi aplikasi Laravel + Blade + Tailwind. Semua data yang sebelumnya tersimpan di `localStorage` sekarang tersimpan di database.

## Fitur yang sudah berjalan

- Beranda dan katalog layanan dengan UI hitam–emas.
- Register, login, logout, role `pelanggan` dan `admin`.
- Booking online untuk pelanggan yang sudah login.
- Nomor antrean harian yang aman dari bentrok melalui transaction, row lock, counter harian, dan unique index.
- Preview estimasi antrean sebelum submit.
- Tracking antrean dengan polling otomatis setiap 5 detik.
- Pembatalan booking dengan verifikasi pemilik berdasarkan akun.
- Admin: dashboard, antrean Online/Walk-in, perubahan status, CRUD layanan, kapasitas barber, laporan.
- Seeder layanan, kapasitas, dan akun admin opsional melalui environment variable.
- Pembatasan kapasitas saat admin memulai pelayanan, termasuk jam istirahat.
- Otorisasi kepemilikan booking dan tracking beberapa nomor yang sedang dilayani.
- Scheduler lokal, rekonsiliasi otomatis saat halaman aktif, dan endpoint cron production.
- Feature/unit test untuk kapasitas, privasi, penjadwalan, laporan, dan nomor antrean.

## Instalasi cepat

Persyaratan: PHP 8.2+, Composer, Node.js 20+, extension PDO SQLite/MySQL.

### Linux/macOS

```bash
./setup.sh
php artisan serve
```

### Windows

```bat
setup-windows.bat
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Membuat akun admin dengan aman

Isi variabel berikut di `.env` sebelum menjalankan seeder:

```env
SEED_ADMIN_NAME="Admin XYZ"
SEED_ADMIN_EMAIL="admin@example.com"
SEED_ADMIN_PHONE="081234567890"
SEED_ADMIN_PASSWORD="gunakan-password-kuat-minimal-12-karakter"
```

Kemudian jalankan `php artisan db:seed`. Source code tidak lagi menyertakan password admin bawaan.

## Database

Default menggunakan SQLite (`database/database.sqlite`). Untuk MySQL, ubah `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=booking_barber
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan:

```bash
php artisan migrate --seed
```

## Perintah penting

```bash
php artisan serve
npm run dev
php artisan schedule:work
php artisan test
php artisan queue:recalculate 2026-06-20
```

Atau jalankan `composer run dev` untuk menyalakan server, Vite, queue worker, log viewer, dan scheduler sekaligus.

## Scheduler production

Endpoint berikut tersedia untuk cron production:

```text
GET /api/cron/booking-auto-complete
Authorization: Bearer <CRON_SECRET>
```

Isi `CRON_SECRET` minimal 16 karakter. Pada Vercel Pro, endpoint dapat dijadwalkan setiap menit melalui `vercel.json`. Vercel Hobby hanya mengizinkan cron harian, sehingga aplikasi juga menjalankan rekonsiliasi ringan ketika dashboard, daftar antrean, atau polling customer diakses.

## Keamanan repository

Jangan commit `.env`, database dump berisi data pengguna, atau `storage/logs/*.log`. Database dibuat melalui migration dan data master layanan melalui seeder.

Dokumentasi mapping file dan algoritma ada di `KONVERSI_TYPESCRIPT_KE_LARAVEL.md`.
