<x-layouts.app title="Booking Antrean — XYZ Barbershop">

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-12 sm:px-6">

        {{-- Header --}}
        <h1 class="font-display text-3xl font-bold sm:text-4xl">
            Booking <span class="gold-text">Antrean</span>
        </h1>

        <p class="mt-2 text-muted-foreground">
            Pilih layanan dan tanggal kunjungan untuk mendapatkan nomor antrean.
        </p>

        <div class="mt-8 grid gap-8 lg:grid-cols-2">

            {{-- Form Booking --}}
            <form method="POST" action="{{ route('booking.store') }}" data-booking-form
                data-estimate-url="{{ route('booking.estimate') }}" class="card-premium space-y-5 rounded-xl p-6">
                @csrf

                {{-- Nama --}}
                <x-field label="Nama Pelanggan" name="customer_name">
                    <input type="text" name="customer_name" required
                        value="{{ old('customer_name', auth()->user()?->name) }}" class="input-control"
                        placeholder="Nama lengkap">
                </x-field>

                {{-- Nomor HP --}}
                <x-field label="Nomor HP" name="phone">
                    <input type="text" name="phone" required value="{{ old('phone', auth()->user()?->phone) }}"
                        class="input-control" placeholder="08xxxxxxxxxx">
                </x-field>

                {{-- Layanan --}}
                <x-field label="Pilih Layanan" name="service_id">
                    <select name="service_id" required class="input-control">
                        <option value="">
                            -- Pilih layanan --
                        </option>

                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected((string) old('service_id', $selectedService) === (string) $service->id)>
                                {{ $service->name }}
                                —
                                {{ $service->duration }} menit
                                —
                                Rp{{ number_format($service->price, 0, ',', '.') }}
                            </option>
                        @endforeach

                    </select>
                </x-field>

                {{-- Tanggal Kunjungan --}}
                <x-field label="Tanggal Kunjungan" name="visit_date">
                    <input type="date" name="visit_date" required min="{{ $today }}"
                        value="{{ old('visit_date', $today) }}" class="input-control">
                </x-field>

                {{-- Submit --}}
                <button type="submit" class="btn-gold btn-gold-hover w-full rounded-md py-3 text-sm font-semibold">
                    Konfirmasi Booking Antrean
                </button>

            </form>

            {{-- Panel Estimasi --}}
            <div class="card-premium rounded-xl p-6">

                <h2 class="font-display text-xl font-bold">
                    Estimasi Antrean
                </h2>

                <p class="mt-1 text-xs text-muted-foreground">
                    Perkiraan otomatis berdasarkan antrean saat ini.
                </p>

                {{-- Empty State --}}
                <div data-estimate-empty
                    class="mt-6 rounded-lg border border-dashed border-border p-8 text-center text-sm text-muted-foreground">
                    Pilih layanan dan tanggal untuk melihat estimasi.
                </div>

                {{-- Estimate Result --}}
                <div data-estimate-panel class="mt-5 hidden space-y-3">

                    {{-- Nomor Antrean --}}
                    <div class="rounded-lg gold-gradient p-5 text-background">

                        <div class="text-xs font-semibold uppercase tracking-wider opacity-80">
                            Nomor Antrean Anda
                        </div>

                        <div data-estimate="nextNumber" class="font-display text-6xl font-bold leading-none">
                            —
                        </div>

                    </div>

                    {{-- Sedang Dilayani --}}
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Sedang Dilayani
                        </span>

                        <span class="text-sm font-semibold">
                            No.
                            <span data-estimate="currentServingNumber">
                                0
                            </span>
                        </span>
                    </div>

                    {{-- Sisa Antrean --}}
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Sisa Antrean Sebelum Anda
                        </span>

                        <span class="text-sm font-semibold">
                            <span data-estimate="waitingBefore">0</span>
                            pelanggan
                        </span>
                    </div>

                    {{-- Barber Aktif --}}
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Barber Aktif
                        </span>

                        <span class="text-sm font-semibold">
                            <span data-estimate="activeBarbers">0</span>
                            barber
                        </span>
                    </div>

                    {{-- Estimasi Tunggu --}}
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Estimasi Waktu Tunggu
                        </span>

                        <span data-estimate="waitingMinutes" class="text-sm font-semibold text-primary">
                            —
                        </span>
                    </div>

                    {{-- Estimasi Jam Dilayani --}}
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Estimasi Jam Dilayani
                        </span>

                        <span data-estimate="serviceTime" class="text-sm font-semibold text-primary">
                            —
                        </span>
                    </div>

                    {{-- Info --}}
                    <div
                        class="mt-4 flex gap-2 rounded-md border border-warning/40 bg-warning/10 p-3 text-xs text-warning">

                        <x-icon name="info" class="mt-0.5 h-4 w-4 shrink-0" />

                        <span>
                            Estimasi waktu dapat berubah sesuai durasi layanan,
                            kedatangan pelanggan, dan ketersediaan barber.
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </main>

</x-layouts.app>