<x-layouts.app title="Masuk — XYZ Barbershop">
    <main class="flex flex-1 items-center justify-center px-4 py-16">
        <div class="w-full max-w-md">
            <div class="card-premium rounded-2xl p-8">
                <div class="text-center">
                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-lg gold-gradient"><x-icon
                            name="scissors" class="h-6 w-6 text-background" /></div>
                    <h1 class="mt-4 font-display text-2xl font-bold">Masuk ke Akun</h1>
                    <p class="mt-1 text-sm text-muted-foreground">Akses booking antrean Anda.</p>
                </div>
                <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">@csrf<x-field
                        label="Email" name="email"><input class="input-control" type="email" name="email"
                            value="{{ old('email') }}" required placeholder="email@contoh.com"></x-field><x-field
                        label="Password" name="password"><input class="input-control" type="password" name="password"
                            required placeholder="••••••••"></x-field><button
                        class="btn-gold btn-gold-hover w-full rounded-md py-2.5 text-sm font-semibold">Masuk</button>
                </form>
                <p class="mt-5 text-center text-sm text-muted-foreground">Belum punya akun? <a
                        href="{{ route('register') }}" class="text-primary hover:underline">Daftar di sini</a></p>
                <div class="mt-6 rounded-md border border-border bg-background/50 p-3 text-xs text-muted-foreground">
                    <div class="font-semibold text-foreground">Akun Demo:</div>
                    <div>Admin: admin@xyzbarbershop.com / admin123</div>
                    <div>Pelanggan: pelanggan@mail.com / pelanggan123</div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>