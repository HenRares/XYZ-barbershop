# Detail Konversi TypeScript → Laravel

Dokumen ini menjelaskan lokasi setiap logika utama dari project Lovable asli dan hasil konversinya.

## 1. Pemetaan file

| TypeScript/Lovable | Laravel hasil konversi | Fungsi |
|---|---|---|
| `src/lib/storage.ts` | migrations, `app/Models/*`, `database/seeders/DatabaseSeeder.php` | Mengganti `localStorage` menjadi database relasional. |
| `src/lib/auth-context.tsx` | `AuthController`, model `User`, session guard Laravel | Login, logout, register, role admin/pelanggan. |
| `src/lib/queue.ts` | `app/Services/QueueEstimator.php` | Port algoritma estimasi antrean dan kapasitas barber. |
| `src/routes/index.tsx` | `HomeController`, `resources/views/home.blade.php` | Beranda, cara kerja, layanan populer. |
| `src/routes/layanan.tsx` | `resources/views/services/index.blade.php` | Katalog layanan aktif. |
| `src/routes/login.tsx` | `AuthController`, `auth/login.blade.php` | Login session Laravel. |
| `src/routes/register.tsx` | `AuthController`, `auth/register.blade.php` | Registrasi dan password hashing. |
| `src/routes/booking.tsx` | `BookingController`, `booking/create.blade.php`, `BookingCreator` | Form booking, preview estimasi, pembuatan nomor antrean. |
| `src/routes/booking-sukses.$id.tsx` | `booking/success.blade.php` | Ringkasan booking berhasil. |
| `src/routes/antrean-saya.tsx` | `QueueTrackingController`, `booking/my-queue.blade.php` | Tracking, polling 5 detik, pembatalan. |
| `src/routes/admin.tsx` | `components/layouts/admin.blade.php`, middleware `EnsureUserIsAdmin` | Layout dan proteksi panel admin. |
| `src/routes/admin.index.tsx` | `Admin/DashboardController`, `admin/dashboard.blade.php` | Statistik dan live queue panel. |
| `src/routes/admin.antrean.tsx` | `Admin/QueueController`, `admin/queues.blade.php` | Filter, Walk-in, status antrean. |
| `src/routes/admin.layanan.tsx` | `Admin/ServiceController`, `admin/services.blade.php` | CRUD layanan. |
| `src/routes/admin.kapasitas.tsx` | `Admin/CapacityController`, `admin/capacities.blade.php` | Kapasitas barber per tanggal. |
| `src/routes/admin.laporan.tsx` | `Admin/ReportController`, `admin/reports.blade.php` | Laporan harian/mingguan/bulanan. |
| `src/components/Navbar.tsx` | `components/navbar.blade.php`, `components/footer.blade.php` | Navbar responsif dan footer. |
| `src/components/StatusBadge.tsx` | `components/status-badge.blade.php`, `type-badge.blade.php` | Badge status dan tipe antrean. |
| `src/styles.css` | `resources/css/app.css` | Tema hitam, glossy gold, card, button, warna status. |

## 2. Struktur database

### `users`
`name`, `phone`, `email`, password hash, dan `role` (`pelanggan`/`admin`).

### `services`
Nama, deskripsi, durasi menit, harga, status aktif/nonaktif.

### `barber_capacities`
Kapasitas per tanggal, jam buka 10:00, jam tutup 21:00.

### `queue_counters`
Counter khusus per tanggal. Baris ini dikunci saat booking dibuat agar dua request bersamaan tidak memperoleh nomor yang sama.

### `bookings`
Menyimpan nomor antrean, kode booking, snapshot nama/durasi layanan, tanggal kunjungan, estimasi, tipe Online/Walk-in, status, dan user opsional.

Index penting:

```php
$table->unique(['visit_date', 'queue_number']);
$table->index(['visit_date', 'status', 'queue_number']);
```

## 3. Algoritma antrean

Port utama ada di `app/Services/QueueEstimator.php`.

1. Tentukan waktu awal simulasi:
   - Hari ini: waktu sekarang, minimal pukul 10:00.
   - Hari lain: pukul 10:00.
2. Ambil antrean dengan nomor lebih kecil dari target dan status `Menunggu`/`Sedang Dilayani`.
3. Tentukan jumlah barber aktif.
4. Buat array slot barber; setiap slot menyimpan menit kapan barber tersebut kosong.
5. Setiap pelanggan dimasukkan ke slot yang paling cepat kosong.
6. Slot target menghasilkan estimasi waktu tunggu dan jam dilayani.

Aturan kapasitas yang sama dengan TypeScript:

- 10:00–12:00: kapasitas normal.
- 12:00–14:00: kapasitas dikurangi 1, minimum 1.
- 14:00–18:00: kapasitas normal.
- 18:00–20:00: kapasitas dikurangi 1, minimum 1.
- 20:00–21:00: kapasitas normal.

## 4. Pencegahan nomor antrean bentrok

`app/Services/BookingCreator.php` memakai transaction:

```php
DB::transaction(function () {
    DB::table('queue_counters')->insertOrIgnore(...);
    $counter = QueueCounter::whereDate('date', $date)->lockForUpdate()->firstOrFail();
    $queueNumber = $counter->last_number + 1;
    $counter->update(['last_number' => $queueNumber]);
    // hitung estimasi dan insert booking
}, attempts: 5);
```

Ini adalah peningkatan penting dibanding versi `localStorage`, yang dapat menghasilkan nomor ganda ketika dua pelanggan menekan booking hampir bersamaan.

## 5. Alur status

- `Menunggu` → `Sedang Dilayani`
- `Menunggu` → `Dibatalkan`
- `Sedang Dilayani` → `Selesai`
- `Sedang Dilayani` → `Dibatalkan`

Sesudah status atau kapasitas berubah, `QueueEstimator::recalculateDay()` memperbarui estimasi semua antrean aktif pada tanggal tersebut.

## 6. Perbedaan yang sengaja diperbaiki

- Password tidak lagi plaintext; Laravel menyimpan hash.
- Halaman “Antrean Saya” tidak menampilkan seluruh booking kepada pengunjung. Guest harus memasukkan nomor HP; user login hanya melihat booking miliknya/nomor HP yang cocok.
- ID publik memakai ULID agar URL booking tidak mudah ditebak.
- Layanan yang sudah dipakai booking tidak dihapus fisik; layanan dinonaktifkan agar histori tidak rusak.
- Validasi server-side diterapkan pada semua form.

## 7. Daftar file inti yang perlu diedit saat pengembangan

- UI publik: `resources/views/home.blade.php`, `resources/views/booking/*`, `resources/views/services/*`.
- UI admin: `resources/views/admin/*`.
- Warna dan style: `resources/css/app.css`.
- Interaksi browser/polling: `resources/js/app.js`.
- Logika antrean: `app/Services/QueueEstimator.php`.
- Pembuatan booking atomik: `app/Services/BookingCreator.php`.
- Route: `routes/web.php`.
- Database: `database/migrations/*`.
- Data awal: `database/seeders/DatabaseSeeder.php`.
