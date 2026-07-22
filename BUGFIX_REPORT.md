# Laporan Perbaikan Bug

## Temuan utama

1. **Kapasitas barber dapat dilewati oleh admin**
   - Perubahan ke status `Sedang Dilayani` kini divalidasi di backend, bukan hanya di tombol UI.
   - Booking dan counter tanggal terkait dikunci dalam transaction agar dua request bersamaan tidak melewati kapasitas.
   - Jumlah pelanggan yang sedang dilayani dihitung langsung, termasuk perlindungan untuk data log lama yang tidak lengkap.
   - Jam operasional, jam istirahat, durasi layanan, dan jam tutup ikut diperiksa.

2. **Nomor antrean pada “Lihat Antrean Saya” tidak sesuai**
   - Tautan setelah booking sekarang membawa `public_id` booking yang baru dibuat.
   - Halaman memilih booking tersebut sebagai kartu utama selama masih aktif.
   - Nomor yang sedang dilayani ditampilkan sebagai daftar karena beberapa barber dapat melayani bersamaan.
   - Generator nomor antrean memakai nilai terbesar antara daily counter dan data booking aktual untuk memperbaiki counter yang tertinggal.

## Temuan tambahan yang diperbaiki

- Booking pelanggan sekarang hanya dicari berdasarkan `user_id`; nomor HP tidak lagi dapat membuka booking akun lain.
- Halaman sukses, API summary, dan pembatalan booking memverifikasi pemilik booking.
- Jadwal booking masa depan dimulai pada tanggal kunjungan dan jam buka, bukan waktu server saat booking dibuat.
- Penjadwalan ulang memperbarui `barber_logs` dan estimasi booking secara konsisten.
- Pelayanan yang melewati waktu selesai direkonsiliasi melalui scheduler, endpoint cron ber-secret, serta akses halaman/polling aktif.
- Laporan harian, mingguan, dan bulanan tidak lagi memasukkan booking masa depan.
- Registrasi menolak nomor HP duplikat; endpoint login/register diberi rate limit.
- Akun admin demo dan password bawaan dihapus. Admin hanya dibuat jika `SEED_ADMIN_*` diisi.
- Migrasi kompatibilitas ditambahkan untuk database lama yang belum memiliki `barber_logs.status`.
- Database dump dan application log berisi data pengguna tidak disertakan dalam source hasil perbaikan.

## Menjalankan hasil perbaikan

Salin `.env.example` menjadi `.env`, atur database dan `SEED_ADMIN_*`, lalu jalankan:

```bash
composer install
npm ci
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan test
php artisan serve
```

Untuk proses otomatis lokal, jalankan `php artisan schedule:work`. Di production, panggil endpoint berikut dengan header bearer yang sama dengan `CRON_SECRET`:

```text
GET /api/cron/booking-auto-complete
Authorization: Bearer <CRON_SECRET>
```

Arsip source sengaja tidak memuat `.env`, `vendor`, `node_modules`, database SQLite, atau log runtime. Dependensi perlu dipasang ulang melalui Composer dan npm.
