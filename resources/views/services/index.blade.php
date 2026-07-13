<x-layouts.app title="Layanan — XYZ Barbershop">
    <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-12 sm:px-6">
        <div class="text-center">
            <h1 class="font-display text-4xl font-bold sm:text-5xl">Layanan <span class="gold-text">Premium</span></h1>
            <p class="mt-3 text-muted-foreground">Pilih layanan grooming terbaik untuk Anda.</p>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($services as $service)<div class="card-premium flex flex-col rounded-xl p-6">
                <div class="grid h-12 w-12 place-items-center rounded-lg gold-gradient"><x-icon name="scissors"
                        class="h-6 w-6 text-background" /></div>
                <h3 class="mt-4 font-display text-xl font-bold">{{ $service->name }}</h3>
                <p class="mt-1 text-sm text-muted-foreground">{{ $service->description }}</p>
                <div class="mt-4 flex items-center gap-2 text-sm text-muted-foreground"><x-icon name="clock"
                        class="h-4 w-4" />{{ $service->duration }} menit</div>
                <div class="mt-2 text-2xl font-bold gold-text">Rp{{ number_format($service->price, 0, ',', '.') }}</div><a
                    href="{{ route('booking.create', ['service' => $service->id]) }}"
                    class="btn-gold btn-gold-hover mt-5 rounded-md py-2 text-center text-sm">Booking</a>
            </div>@endforeach
        </div>
    </main>
</x-layouts.app>