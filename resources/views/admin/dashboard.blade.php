<x-layouts.admin title="Dashboard">

{{-- Header --}}
<h1 class="font-display text-2xl font-bold sm:text-3xl">
    Dashboard
</h1>

<p class="mt-1 text-sm text-muted-foreground">
    Ringkasan antrean hari ini ({{ $today }}).
</p>

{{-- Statistik --}}
<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">

    @foreach ([
        ['list', 'Antrean Hari Ini', $stats['total'], ''],
        ['play-circle', 'Sedang Dilayani', $stats['serving'], 'text-primary'],
        ['clock', 'Menunggu', $stats['waiting'], 'text-warning'],
        ['check-circle', 'Selesai', $stats['done'], 'text-success'],
        ['x-circle', 'Dibatalkan', $stats['cancelled'], 'text-destructive'],
    ] as [$icon, $label, $value, $color])

        <div class="card-premium rounded-xl p-5">

            <div class="flex items-center justify-between">

                <span class="text-xs uppercase tracking-wider text-muted-foreground">
                    {{ $label }}
                </span>

                <x-icon
                    :name="$icon"
                    class="h-4 w-4 {{ $color }}"
                />

            </div>

            <div class="mt-3 font-display text-3xl font-bold {{ $color }}">
                {{ $value }}
            </div>

        </div>

    @endforeach

</div>

{{-- Main Content --}}
<div class="mt-6 grid gap-6 lg:grid-cols-2">

    {{-- Live Queue Panel --}}
    <div class="card-premium rounded-xl p-6">

        <h2 class="font-display text-lg font-bold">
            Live Queue Panel
        </h2>

        <div class="mt-4 grid grid-cols-2 gap-4">

            @foreach ([
                ['Sedang Dilayani', $current ? '#' . $current->queue_number : '—', true],
                ['Antrean Berikutnya', $next ? '#' . $next->queue_number : '—', false],
                ['Barber Aktif', $barbers, false],
                ['Rata-rata Tunggu', $avgWait ? $avgWait . ' mnt' : '—', false],
            ] as [$label, $value, $highlight])

                <div class="
                    rounded-lg border p-4
                    {{ $highlight
                        ? 'border-primary/50 bg-primary/5'
                        : 'border-border bg-background/50' }}
                ">

                    <div class="text-xs text-muted-foreground">
                        {{ $label }}
                    </div>

                    <div class="mt-2 font-display text-3xl font-bold {{ $highlight ? 'gold-text' : '' }}">
                        {{ $value }}
                    </div>

                </div>

            @endforeach

        </div>

    </div>

    {{-- Antrean Terbaru --}}
    <div class="card-premium rounded-xl p-6">

        <h2 class="font-display text-lg font-bold">
            Antrean Terbaru
        </h2>

        <div class="mt-4 overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="text-xs uppercase text-muted-foreground">

                    <tr>
                        <th class="px-2 py-2 text-left">No.</th>
                        <th class="px-2 py-2 text-left">Nama</th>
                        <th class="px-2 py-2 text-left">Layanan</th>
                        <th class="px-2 py-2 text-left">Jam</th>
                        <th class="px-2 py-2 text-left">Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($recent as $booking)

                        <tr class="border-t border-border">

                            <td class="px-2 py-2 font-semibold text-primary">
                                #{{ $booking->queue_number }}
                            </td>

                            <td class="px-2 py-2">
                                {{ $booking->customer_name }}
                            </td>

                            <td class="px-2 py-2 text-muted-foreground">
                                {{ $booking->service_name }}
                            </td>

                            <td class="px-2 py-2">
                                {{ $booking->estimated_service_time }}
                            </td>

                            <td class="px-2 py-2">
                                <x-status-badge :status="$booking->status" />
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="py-8 text-center text-muted-foreground"
                            >
                                Belum ada antrean hari ini.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


</x-layouts.admin>
