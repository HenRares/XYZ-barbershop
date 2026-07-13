<x-layouts.app title="Antrean Saya — XYZ Barbershop">

    <main class="mx-auto w-full max-w-5xl flex-1 px-4 py-12 sm:px-6">

        <h1 class="font-display text-3xl font-bold sm:text-4xl">
            Antrean <span class="gold-text">Saya</span>
        </h1>

        <p class="mt-2 text-muted-foreground">
            Pantau status antrean Anda secara real-time.
        </p>

        {{-- Guest Search Form --}}
        @guest
            <form
                method="GET"
                action="{{ route('queue.mine') }}"
                class="mt-6 flex max-w-xl gap-2"
            >
                <input
                    type="text"
                    name="phone"
                    value="{{ $phone }}"
                    placeholder="Masukkan nomor HP booking"
                    class="input-control"
                >

                <button class="btn-gold rounded-md px-4 py-2 text-sm">
                    Cari
                </button>
            </form>
        @endguest

        {{-- =========================================================
     TAMPILKAN JIKA TIDAK ADA ANTREAN AKTIF HARI INI
     ========================================================= --}}
        @if (!$primary)
            <div class="mt-10 rounded-xl border border-dashed border-border p-12 text-center">
                {{-- Pesan kepada pelanggan --}}
                <p class="text-lg font-semibold text-muted-foreground">
                    Anda belum memiliki antrean aktif hari ini.
                </p>
                <p class="mt-2 text-sm text-muted-foreground">
                    Silakan lakukan booking untuk mendapatkan nomor antrean.
                </p>
                {{-- Tombol menuju halaman booking --}}
                <a
                    href="{{ route('booking.create') }}"
                    class="btn-gold btn-gold-hover mt-6 inline-block rounded-md px-5 py-2 text-sm font-semibold"
                >
                    Booking Sekarang
                </a>
            </div>

        @else

            <div
                data-queue-tracker
                data-summary-url="{{ route('queue.summary', $primary->public_id) }}"
                class="mt-8 grid gap-6 lg:grid-cols-3"
            >
                {{-- =========================================================
                    KARTU ANTREAN AKTIF
                    Hanya muncul jika pelanggan mempunyai booking aktif
                    pada tanggal hari ini.
                ========================================================= --}}
                {{-- Queue Information --}}

                <div class="card-premium rounded-2xl p-8 lg:col-span-2">

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="text-xs uppercase tracking-widest text-muted-foreground">
                            Booking {{ $primary->booking_code }}
                        </span>

                        <x-status-badge
                            data-live="status"
                            :status="$primary->status"
                        />
                    </div>

                    {{-- Queue Number --}}
                    <div class="mt-6 rounded-xl gold-gradient p-8 text-background">
                        <div class="text-xs font-bold uppercase tracking-widest opacity-80">
                            Nomor Antrean Anda
                        </div>

                        <div class="font-display text-8xl font-extrabold leading-none">
                            {{ $primary->queue_number }}
                        </div>

                        <div class="mt-3 text-sm font-semibold">
                            {{ $primary->service_name }}
                        </div>
                    </div>

                    {{-- =========================================================
                        INFORMASI ANTREAN REAL-TIME

                        Sedang Dilayani
                        Sisa Antrean
                        Estimasi Tunggu
                        Estimasi Dilayani

                        Data akan diperbarui melalui AJAX.
                    ========================================================= --}}
                    {{-- Queue Stats --}}
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">

                        <div class="rounded-md border border-border bg-background/50 p-4">
                            <div class="text-xs uppercase tracking-wider text-muted-foreground">
                                Sedang Dilayani
                            </div>

                            <div data-live="currentServingNumber" class="mt-1 text-xl font-bold">
                                No. {{ $estimate['currentServingNumber'] }}
                            </div>
                        </div>

                        <div class="rounded-md border border-border bg-background/50 p-4">
                            <div class="text-xs uppercase tracking-wider text-muted-foreground">
                                Sisa Antrean
                            </div>

                            <div data-live="waitingBefore" class="mt-1 text-xl font-bold">
                                {{ $estimate['waitingBefore'] }} pelanggan
                            </div>
                        </div>

                        <div class="rounded-md border border-border bg-background/50 p-4">
                            <div class="text-xs uppercase tracking-wider text-muted-foreground">
                                Estimasi Waktu Tunggu
                            </div>

                            <div data-live="waitingText" class="mt-1 text-xl font-bold gold-text">
                                {{ app(\App\Services\QueueEstimator::class)->formatWait($estimate['waitingMinutes']) }}
                            </div>
                        </div>

                        <div class="rounded-md border border-border bg-background/50 p-4">
                            <div class="text-xs uppercase tracking-wider text-muted-foreground">
                                Estimasi Jam Dilayani
                            </div>

                            <div data-live="serviceTime" class="mt-1 text-xl font-bold gold-text">
                                {{ $estimate['serviceTime'] }}
                            </div>
                        </div>

                    </div>


                    {{-- =========================================================
                        INFORMASI STATUS ANTREAN
                        Memberikan informasi antrean yang sedang dipanggil.
                        ========================================================= --}}
                    {{-- Current Queue Info --}}
                    <div class="mt-6 rounded-md border border-primary/30 bg-primary/5 p-4 text-sm text-muted-foreground">
                        <div class="font-semibold text-primary">
                            Antrean
                            <span data-live="currentServingNumber">
                                No. {{ $estimate['currentServingNumber'] }}
                            </span>
                            sedang dilayani
                        </div>

                        <div>
                            Antrean {{ $primary->queue_number }} adalah antrean Anda
                        </div>
                    </div>

                </div>

                {{-- =========================================================
                    DETAIL BOOKING AKTIF
                    Menampilkan informasi booking yang sedang berjalan.
                ========================================================= --}}
                {{-- Booking Detail --}}
                <div class="card-premium rounded-2xl p-6">

                    <h3 class="font-display text-lg font-bold">
                        Detail Booking
                    </h3>

                    <div class="mt-4 space-y-3 text-sm">

                        @foreach ([
                            ['Layanan', $primary->service_name],
                            ['Tanggal Kunjungan', $primary->visit_date->format('Y-m-d')],
                            ['Estimasi Durasi', $primary->service_duration . ' menit'],
                            ['Nomor HP', $primary->phone],
                            ['Jenis', $primary->queue_type],
                        ] as [$label, $value])

                            <div class="flex items-center justify-between border-b border-border pb-2 last:border-0">
                                <span class="text-muted-foreground">
                                    {{ $label }}
                                </span>

                                <span class="font-medium">
                                    {{ $value }}
                                </span>
                            </div>

                        @endforeach

                    </div>

                    {{-- =========================================================
                        PEMBATALAN BOOKING
                        Tombol hanya muncul jika status booking masih aktif
                        (Waiting / Serving)
                    ========================================================= --}}
                    {{-- Cancel Booking --}}
                    @if ($primary->isActive())

                        <form
                            method="POST"
                            action="{{ route('queue.cancel', $primary->public_id) }}"
                            data-confirm="Batalkan booking nomor antrean {{ $primary->queue_number }}?"
                            class="mt-6"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="phone"
                                value="{{ $phone ?: $primary->phone }}"
                            >

                            <button
                                class="w-full rounded-md border border-destructive/50 px-4 py-2 text-sm font-semibold text-destructive hover:bg-destructive/10"
                            >
                                Batalkan Booking
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        @endif

    
        {{-- =========================================================
            RIWAYAT BOOKING PELANGGAN
            Tetap tampil walaupun tidak ada antrean aktif.
        ========================================================= --}}
        @if ($bookings->count() > 0)

            <div class="mt-10">

                <h2 class="font-display text-xl font-bold">
                    Riwayat Booking
                </h2>

                <div class="mt-4 overflow-x-auto rounded-xl border border-border">

                    <table class="w-full text-sm">

                        <thead class="bg-popover text-muted-foreground">
                            <tr>
                                <th class="px-4 py-3 text-left">No. Antrean</th>
                                <th class="px-4 py-3 text-left">Kode</th>
                                <th class="px-4 py-3 text-left">Layanan</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- Tampilkan semua riwayat booking
                                kecuali booking yang sedang aktif
                                karena sudah ditampilkan pada kartu Antrean Saya.
                            --}}

                            @foreach ($bookings as $booking)

                                @continue($primary && $booking->id == $primary->id)

                                <tr class="border-t border-border">

                                    <td class="px-4 py-3 font-semibold text-primary">
                                        {{ $booking->queue_number }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $booking->booking_code }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $booking->service_name }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $booking->visit_date->format('Y-m-d') }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <x-status-badge :status="$booking->status" />
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        @endif

    </main>

</x-layouts.app>