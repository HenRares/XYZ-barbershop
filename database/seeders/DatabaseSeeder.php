<?php

namespace Database\Seeders;

use App\Models\BarberCapacity;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = trim((string) config('app.seed_admin.email', ''));
        $adminPassword = (string) config('app.seed_admin.password', '');

        if ($adminEmail !== '') {
            if (strlen($adminPassword) < 12) {
                throw new RuntimeException(
                    'SEED_ADMIN_PASSWORD wajib diisi minimal 12 karakter ketika SEED_ADMIN_EMAIL digunakan.'
                );
            }

            User::updateOrCreate(
                ['email' => strtolower($adminEmail)],
                [
                    'name' => config('app.seed_admin.name', 'Admin XYZ'),
                    'phone' => config('app.seed_admin.phone', '080000000000'),
                    'password' => Hash::make($adminPassword),
                    'role' => 'admin',
                ]
            );
        }

        collect([
            ['name' => 'Basic Cut', 'description' => 'Potong rambut klasik rapi dan cepat.', 'duration' => 45, 'price' => 35000, 'status' => 'aktif'],
            ['name' => 'Haircut + Wash', 'description' => 'Potong rambut plus cuci rambut menyegarkan.', 'duration' => 60, 'price' => 50000, 'status' => 'aktif'],
            ['name' => 'Haircut + Beard', 'description' => 'Potong rambut dengan perapian jenggot premium.', 'duration' => 60, 'price' => 60000, 'status' => 'aktif'],
            ['name' => 'Full Treatment', 'description' => 'Paket lengkap potong, cuci, jenggot, dan styling.', 'duration' => 75, 'price' => 90000, 'status' => 'aktif'],
        ])->each(fn (array $data) => Service::updateOrCreate(['name' => $data['name']], $data));

        BarberCapacity::updateOrCreate(
            ['date' => now()->toDateString()],
            ['active_barbers' => 4, 'opening_time' => '10:00', 'closing_time' => '21:00']
        );
    }
}
