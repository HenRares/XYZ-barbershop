# XYZ Barbershop — Laravel

Hasil konversi project Lovable/TanStack TypeScript menjadi aplikasi Laravel + Blade + Tailwind. Semua data yang sebelumnya tersimpan di `localStorage` sekarang tersimpan di database.

## Fitur yang sudah berjalan

- Beranda dan katalog layanan dengan UI hitam–emas.
- Register, login, logout, role `pelanggan` dan `admin`.
- Booking online tanpa wajib login.
- Nomor antrean harian yang aman dari bentrok melalui transaction, row lock, counter harian, dan unique index.
- Preview estimasi antrean sebelum submit.
- Tracking antrean dengan polling otomatis setiap 5 detik.
- Pembatalan booking dengan verifikasi pemilik/nomor HP.
- Admin: dashboard, antrean Online/Walk-in, perubahan status, CRUD layanan, kapasitas barber, laporan.
- Seeder akun demo, layanan, kapasitas, dan antrean contoh.
- Feature/unit test dasar untuk antrean.

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

## Akun demo

- Admin: `admin@xyzbarbershop.com` / `admin123`
- Pelanggan: `pelanggan@mail.com` / `pelanggan123`

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
php artisan migrate:fresh --seed
```

## Perintah penting

```bash
php artisan serve
npm run dev
php artisan test
php artisan queue:recalculate 2026-06-20
```

Dokumentasi mapping file dan algoritma ada di `KONVERSI_TYPESCRIPT_KE_LARAVEL.md`.
