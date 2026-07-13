<x-layouts.app title="Booking Berhasil — XYZ Barbershop">

    <main class="mx-auto w-full max-w-2xl flex-1 px-4 py-12 sm:px-6">

        <div class="card-premium rounded-2xl p-8 text-center">

            {{-- Success Icon --}}
            <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-success/15 text-success">
                <x-icon
                    name="check-circle"
                    class="h-7 w-7"
                />
            </div>

            {{-- Header --}}
            <h1 class="mt-4 font-display text-3xl font-bold">
                Booking Berhasil!
            </h1>

            <p class="mt-2 text-sm text-muted-foreground">
                Simpan ID booking Anda untuk referensi.
            </p>

            {{-- Queue Number --}}
            <div class="mt-6 rounded-xl gold-gradient p-6 text-background">

                <div class="text-xs font-semibold uppercase tracking-widest opacity-80">
                    Nomor Antrean Anda
                </div>

                <div class="font-display text-7xl font-bold leading-none">
                    {{ $booking->queue_number }}
                </div>

                <div class="mt-2 text-sm font-medium">
                    {{ $booking->booking_code }}
                </div>

            </div>

            {{-- Booking Details --}}
            <div class="mt-6 space-y-2 text-left">

                @foreach ([
                    ['Nama', $booking->customer_name],
                    ['Layanan', $booking->service_name],
                    [
                        'Estimasi Waktu Tunggu',
                        $booking->barberLog
                            ? app(\App\Services\QueueEstimator::class)
                                ->formatWait(
                                    max(
                                        0,
                                        now()->diffInMinutes(
                                            $booking->barberLog->service_start_at,
                                            false
                                        )
                                    )
                                )
                            : '-'
                    ],

                    [
                        'Estimasi Jam Dilayani',
                        $booking->barberLog
                            ? $booking->barberLog
                                ->service_start_at
                                ->format('H:i')
                            : '-'
                    ],

                ] as [$label, $value])

                    <div class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-2.5">

                        <span class="text-xs uppercase tracking-wider text-muted-foreground">
                            {{ $label }}
                        </span>

                        <span class="text-sm font-semibold">
                            {{ $value }}
                        </span>

                    </div>

                @endforeach

                {{-- Status --}}
                <div class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-2.5">

                    <span class="text-xs uppercase tracking-wider text-muted-foreground">
                        Status
                    </span>

                    <x-status-badge :status="$booking->status" />

                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 flex flex-wrap justify-center gap-3">

                <a
                    href="{{ route('queue.mine', ['phone' => $booking->phone]) }}"
                    class="btn-gold btn-gold-hover rounded-md px-5 py-2.5 text-sm font-semibold"
                >
                    Lihat Antrean Saya
                </a>

                <a
                    href="{{ route('home') }}"
                    class="rounded-md border border-border px-5 py-2.5 text-sm hover:border-primary/60 hover:text-primary"
                >
                    Kembali ke Beranda
                </a>

            </div>

        </div>

    </main>

</x-layouts.app>
