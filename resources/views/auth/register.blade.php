{{-- <x-layouts.app title="Daftar — XYZ Barbershop"><main class="flex flex-1 items-center justify-center px-4 py-16"><div class="w-full max-w-md"><div class="card-premium rounded-2xl p-8"><div class="text-center"><div class="mx-auto grid h-12 w-12 place-items-center rounded-lg gold-gradient"><x-icon name="scissors" class="h-6 w-6 text-background"/></div><h1 class="mt-4 font-display text-2xl font-bold">Daftar Akun</h1><p class="mt-1 text-sm text-muted-foreground">Buat akun pelanggan baru.</p></div>
<form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4" novalidate>@csrf
<x-field label="Nama Lengkap" name="name"><input class="input-control" name="name" value="{{ old('name') }}" required placeholder="Nama lengkap"></x-field>
<x-field label="Nomor HP" name="phone"><input class="input-control" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx"></x-field>
<x-field label="Email" name="email"><input class="input-control" type="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com"></x-field>
<x-field label="Password" name="password"><input class="input-control" type="password" name="password" required placeholder="Min. 6 karakter"></x-field>
<x-field label="Konfirmasi Password" name="password_confirmation"><input class="input-control" type="password" name="password_confirmation" required placeholder="Ulangi password"></x-field>
<button class="btn-gold btn-gold-hover w-full rounded-md py-2.5 text-sm font-semibold">Daftar</button></form><p class="mt-5 text-center text-sm text-muted-foreground">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary hover:underline">Masuk</a></p></div></div></main></x-layouts.app> --}}


<x-layouts.app title="Daftar — XYZ Barbershop">

    <main class="flex flex-1 items-center justify-center px-4 py-16">

        <div class="w-full max-w-md">

            <div class="card-premium rounded-2xl p-8">

                <div class="text-center">

                    <div class="mx-auto grid h-12 w-12 place-items-center rounded-lg gold-gradient">
                        <x-icon name="scissors" class="h-6 w-6 text-background" />
                    </div>

                    <h1 class="mt-4 font-display text-2xl font-bold">
                        Daftar Akun
                    </h1>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Buat akun pelanggan baru.
                    </p>

                </div>

                <form
                    method="POST"
                    action="{{ route('register.store') }}"
                    class="mt-6 space-y-4"
                    novalidate
                >
                    @csrf

                    {{-- Nama --}}
                    <x-field label="Nama Lengkap" name="name">
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkap"
                            autocomplete="name"
                            class="input-control @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                        >
                    </x-field>

                    {{-- Nomor HP --}}
                    <x-field label="Nomor HP" name="phone">
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="08xxxxxxxxxx"
                            autocomplete="tel"
                            class="input-control @error('phone') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                        >
                    </x-field>

                    {{-- Email --}}
                    <x-field label="Email" name="email">
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="email@contoh.com"
                            autocomplete="email"
                            class="input-control @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                        >
                    </x-field>

                    {{-- Password --}}
                    <x-field label="Password" name="password">
                        <input
                            type="password"
                            name="password"
                            placeholder="Minimal 6 karakter"
                            autocomplete="new-password"
                            class="input-control @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                        >
                    </x-field>

                    {{-- Konfirmasi Password --}}
                    <x-field
                        label="Konfirmasi Password"
                        name="password_confirmation"
                    >
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            autocomplete="new-password"
                            class="input-control @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                        >
                    </x-field>

                    <button
                        type="submit"
                        class="btn-gold btn-gold-hover w-full rounded-md py-2.5 text-sm font-semibold"
                    >
                        Daftar
                    </button>

                </form>

                <p class="mt-5 text-center text-sm text-muted-foreground">
                    Sudah punya akun?

                    <a
                        href="{{ route('login') }}"
                        class="text-primary hover:underline"
                    >
                        Masuk
                    </a>

                </p>

            </div>

        </div>

    </main>

</x-layouts.app>