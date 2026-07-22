<x-layouts.admin title="Daftar Antrean">

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold sm:text-3xl">Daftar Antrean</h1>
            <p class="mt-1 text-sm text-muted-foreground">Kelola semua antrean online & walk-in.</p>
        </div><button data-dialog-open="walkin-dialog"
            class="btn-gold btn-gold-hover inline-flex items-center gap-2 rounded-md px-4 py-2 text-sm font-semibold"><x-icon
                name="plus" class="h-4 w-4" />Tambah Walk-in</button>
    </div>
    <form method="GET" action="{{ route('admin.queues') }}"
        class="card-premium mt-6 grid gap-3 rounded-xl p-4 md:grid-cols-4">
        <div class="relative md:col-span-2"><x-icon name="search"
                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" /><input name="search"
                value="{{ $filters['search'] }}" placeholder="Cari nama, HP, atau kode booking…"
                class="input-control pl-9"></div><input type="date" name="date" value="{{ $filters['date'] }}"
            class="input-control">
        <div class="grid grid-cols-2 gap-2"><select name="status" class="input-control">
                <option value="">Semua Status</option>
                @foreach(['Menunggu', 'Sedang Dilayani', 'Selesai', 'Dibatalkan'] as $status)<option
                @selected($filters['status'] === $status)>{{ $status }}</option>@endforeach
            </select><select name="service" class="input-control">
                <option value="">Semua Layanan</option>@foreach($services as $service)<option value="{{ $service->id }}"
                    @selected((string) $filters['service'] === (string) $service->id)>{{ $service->name }}</option>
                @endforeach
            </select></div><button class="hidden">Filter</button>
    </form>
    <div class="mt-3 text-sm text-muted-foreground">
        Barber tersedia: <span class="font-semibold text-primary">{{ max(0, $effectiveCapacity - $servingCount) }}</span>
        dari {{ $effectiveCapacity }} kapasitas efektif.
    </div>
    <div class="card-premium mt-6 overflow-hidden rounded-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-background/40 text-xs uppercase text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 text-left">No.</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">HP</th>
                        <th class="px-4 py-3 text-left">Layanan</th>
                        <th class="px-4 py-3 text-left">Durasi</th>
                        <th class="px-4 py-3 text-left">Est. Jam</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $booking)<tr class="border-t border-border hover:bg-background/30">
                        <td class="px-4 py-3 font-bold text-primary">#{{ $booking->queue_number }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $booking->customer_name }}</div>
                            <div class="text-xs text-muted-foreground">{{ $booking->booking_code }}</div>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">{{ $booking->phone }}</td>
                        <td class="px-4 py-3">{{ $booking->service_name }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ $booking->service_duration }} mnt</td>
                        <td class="px-4 py-3">{{ $booking->estimated_service_time }}</td>
                        <td class="px-4 py-3"><x-type-badge :type="$booking->queue_type" /></td>
                        <td class="px-4 py-3"><x-status-badge :status="$booking->status" /></td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-1">
                                @if($booking->status === 'Menunggu')
                                    <form method="POST" action="{{ route('admin.queues.status', $booking) }}">@csrf
                                        @method('PATCH')<input type="hidden" name="status" value="Sedang Dilayani"><button
                                            title="{{ $canStartServing ? 'Tandai Sedang Dilayani' : 'Kapasitas penuh atau di luar jam operasional' }}"
                                            @disabled(!$canStartServing)
                                            class="rounded p-1.5 text-primary hover:bg-primary/10 disabled:cursor-not-allowed disabled:opacity-30"><x-icon
                                name="play-circle" class="h-4 w-4" /></button></form>@endif
                                @if($booking->status === 'Sedang Dilayani')
                                    <form method="POST" action="{{ route('admin.queues.status', $booking) }}">@csrf
                                        @method('PATCH')<input type="hidden" name="status" value="Selesai"><button
                                            title="Tandai Selesai"
                                            class="rounded p-1.5 text-success hover:bg-success/10"><x-icon
                                name="check-circle" class="h-4 w-4" /></button></form>@endif
                                @if(in_array($booking->status, ['Menunggu', 'Sedang Dilayani']))
                                    <form method="POST" action="{{ route('admin.queues.status', $booking) }}"
                                        data-confirm="Batalkan antrean #{{ $booking->queue_number }}?">@csrf
                                        @method('PATCH')<input type="hidden" name="status" value="Dibatalkan"><button
                                            title="Batalkan"
                                            class="rounded p-1.5 text-destructive hover:bg-destructive/10"><x-icon
                                name="trash" class="h-4 w-4" /></button></form>@endif
                            </div>
                        </td>
                    </tr>@empty<tr>
                        <td colspan="9" class="py-12 text-center text-muted-foreground">Tidak ada antrean.</td>
                    </tr>@endforelse
                </tbody>
            </table>
        </div>
    </div>

    <dialog id="walkin-dialog"
        class="m-auto w-[min(92vw,500px)] rounded-xl border border-border bg-popover p-0 text-foreground shadow-2xl">
        <form method="POST" action="{{ route('admin.queues.walkin') }}" class="p-6">@csrf<div
                class="flex items-start justify-between">
                <div>
                    <h2 class="font-display text-xl font-bold">Tambah Antrean Walk-in</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Tambahkan pelanggan walk-in ke daftar antrean.</p>
                </div><button type="button" data-dialog-close class="text-muted-foreground"><x-icon name="x" /></button>
            </div>
            <div class="mt-5 space-y-4"><x-field label="Nama Pelanggan" name="customer_name"><input name="customer_name"
                        class="input-control" required></x-field><x-field label="Nomor HP" name="phone"><input
                        name="phone" class="input-control" required></x-field><x-field label="Pilih Layanan"
                    name="service_id"><select name="service_id" class="input-control" required>
                        <option value="">-- Pilih layanan --</option>
                        @foreach($services->where('status', 'aktif') as $service)<option value="{{ $service->id }}">
                            {{ $service->name }} — {{ $service->duration }} mnt
                        </option>@endforeach
                    </select></x-field>
                <div class="rounded-md border border-border bg-background/50 px-3 py-2 text-xs text-muted-foreground">
                    Jenis antrean: <span class="font-semibold text-foreground">Walk-in</span></div>
            </div>
            <div class="mt-6 flex justify-end gap-2"><button type="button" data-dialog-close
                    class="rounded-md border border-border px-4 py-2 text-sm">Batal</button><button
                    class="btn-gold rounded-md px-4 py-2 text-sm">Tambahkan</button></div>
        </form>
    </dialog>
    
    <script>document.querySelectorAll('#admin-sidebar select,#admin-sidebar input').forEach(() => { }); document.querySelectorAll('form[action="{{ route('admin.queues') }}"] select, form[action="{{ route('admin.queues') }}"] input[type=date]').forEach(el => el.addEventListener('change', () => el.form.submit()));</script>
</x-layouts.admin>
