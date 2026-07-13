<x-layouts.admin title="Kapasitas Barber">


{{-- Header --}}
<h1 class="font-display text-2xl font-bold sm:text-3xl">
    Kapasitas Barber
</h1>

<p class="mt-1 text-sm text-muted-foreground">
    Atur jumlah barber aktif per hari.
    Jam operasional: 10:00 - 21:00.
</p>

<div class="mt-6 grid gap-6 lg:grid-cols-2">

    {{-- Form Pengaturan --}}
    <div class="card-premium rounded-xl p-6">

        <h2 class="font-display text-lg font-bold">
            Pengaturan Hari
        </h2>

        <form
            method="POST"
            action="{{ route('admin.capacities.store') }}"
            class="mt-4 space-y-4"
        >
            @csrf

            <x-field label="Tanggal" name="date">

                <input
                    type="date"
                    name="date"
                    value="{{ $date }}"
                    class="input-control"
                >

            </x-field>

            <x-field
                label="Jumlah Barber Aktif"
                name="active_barbers"
                hint="Berlaku saat jam normal. Saat jam istirahat otomatis dikurangi 1."
            >

                <input
                    type="number"
                    name="active_barbers"
                    min="1"
                    max="20"
                    value="{{ $base }}"
                    class="input-control"
                >

            </x-field>

            <button
                class="btn-gold btn-gold-hover w-full rounded-md py-2 text-sm font-semibold"
            >
                Simpan
            </button>

        </form>

    </div>

    {{-- Jadwal Kapasitas --}}
    <div class="card-premium rounded-xl p-6">

        <h2 class="font-display text-lg font-bold">
            Jadwal Kapasitas ({{ $date }})
        </h2>

        <div class="mt-4 overflow-hidden rounded-md border border-border">

            <table class="w-full text-sm">

                <thead class="bg-background/40 text-xs uppercase text-muted-foreground">

                    <tr>
                        <th class="px-3 py-2 text-left">
                            Rentang Waktu
                        </th>

                        <th class="px-3 py-2 text-left">
                            Barber Aktif
                        </th>

                        <th class="px-3 py-2 text-left">
                            Keterangan
                        </th>
                    </tr>

                </thead>

                <tbody>

                    @foreach ($schedule as $item)

                        <tr class="border-t border-border">

                            <td class="px-3 py-2 font-medium">
                                {{ $item['range'] }}
                            </td>

                            <td class="px-3 py-2 font-semibold text-primary">
                                {{ $item['barbers'] }} barber
                            </td>

                            <td class="px-3 py-2 text-muted-foreground">
                                {{ $item['note'] }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- Riwayat Kapasitas --}}
<div class="card-premium mt-6 rounded-xl p-6">

    <h2 class="font-display text-lg font-bold">
        Riwayat Kapasitas
    </h2>

    <div class="mt-4 overflow-hidden rounded-md border border-border">

        <table class="w-full text-sm">

            <thead class="bg-background/40 text-xs uppercase text-muted-foreground">

                <tr>

                    <th class="px-3 py-2 text-left">
                        Tanggal
                    </th>

                    <th class="px-3 py-2 text-left">
                        Barber Aktif
                    </th>

                    <th class="px-3 py-2 text-left">
                        Jam Operasional
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse ($capacities as $capacity)

                    <tr class="border-t border-border">

                        <td class="px-3 py-2">

                            <a
                                href="{{ route('admin.capacities', [
                                    'date' => $capacity->date->format('Y-m-d')
                                ]) }}"
                                class="hover:text-primary"
                            >
                                {{ $capacity->date->format('Y-m-d') }}
                            </a>

                        </td>

                        <td class="px-3 py-2 font-semibold text-primary">
                            {{ $capacity->active_barbers }}
                        </td>

                        <td class="px-3 py-2 text-muted-foreground">
                            {{ substr($capacity->opening_time, 0, 5) }}
                            -
                            {{ substr($capacity->closing_time, 0, 5) }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="3"
                            class="py-6 text-center text-muted-foreground"
                        >
                            Belum ada data.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


</x-layouts.admin>
