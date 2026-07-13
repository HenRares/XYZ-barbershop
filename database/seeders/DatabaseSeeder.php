<?php

namespace Database\Seeders;

use App\Models\BarberCapacity;
use App\Models\Booking;
use App\Models\QueueCounter;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@xyzbarbershop.com'], [
            'name' => 'Admin XYZ', 'phone' => '081200000000', 'password' => Hash::make('admin123'), 'role' => 'admin',
        ]);
        $customer = User::updateOrCreate(['email' => 'pelanggan@mail.com'], [
            'name' => 'Budi Santoso', 'phone' => '081234567890', 'password' => Hash::make('pelanggan123'), 'role' => 'pelanggan',
        ]);

        $services = collect([
            ['name' => 'Basic Cut', 'description' => 'Potong rambut klasik rapi dan cepat.', 'duration' => 45, 'price' => 35000, 'status' => 'aktif'],
            ['name' => 'Haircut + Wash', 'description' => 'Potong rambut plus cuci rambut menyegarkan.', 'duration' => 60, 'price' => 50000, 'status' => 'aktif'],
            ['name' => 'Haircut + Beard', 'description' => 'Potong rambut dengan perapian jenggot premium.', 'duration' => 60, 'price' => 60000, 'status' => 'aktif'],
            ['name' => 'Full Treatment', 'description' => 'Paket lengkap potong, cuci, jenggot, dan styling.', 'duration' => 75, 'price' => 90000, 'status' => 'aktif'],
        ])->map(fn ($data) => Service::updateOrCreate(['name' => $data['name']], $data));

        $today = now()->toDateString();
        BarberCapacity::updateOrCreate(['date' => $today], ['active_barbers' => 4, 'opening_time' => '10:00', 'closing_time' => '21:00']);

        if (Booking::forDate($today)->doesntExist()) {
            $samples = [
                ['Andi', '0811', 0, 1, 0, '10:00', 'Walk-in', 'Selesai', null],
                ['Rian', '0812', 1, 2, 10, '10:45', 'Online', 'Selesai', null],
                ['Dewi', '0813', 2, 3, 20, '11:45', 'Online', 'Sedang Dilayani', null],
                ['Joko', '0814', 0, 4, 35, '12:45', 'Walk-in', 'Menunggu', null],
                ['Lia', '0815', 3, 5, 50, '13:30', 'Online', 'Menunggu', $customer->id],
            ];
            foreach ($samples as $sample) {
                [$name, $phone, $serviceIndex, $queueNumber, $wait, $time, $type, $status, $userId] = $sample;
                $service = $services[$serviceIndex];
                $booking = Booking::create([
                    'public_id' => (string) Str::ulid(), 'booking_code' => null, 'queue_number' => $queueNumber,
                    'customer_name' => $name, 'phone' => $phone, 'service_id' => $service->id, 'service_name' => $service->name,
                    'service_duration' => $service->duration, 'visit_date' => $today, 'estimated_waiting_time' => $wait,
                    'estimated_service_time' => $time, 'queue_type' => $type, 'status' => $status, 'user_id' => $userId,
                ]);
                $booking->update(['booking_code' => 'XYZ-'.str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT)]);
            }
            QueueCounter::updateOrCreate(['date' => $today], ['last_number' => 5]);
        }
    }
}
