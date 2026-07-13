<header class="sticky top-0 z-40 border-b border-border bg-background/85 backdrop-blur-md">
  <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
    <a href="{{ route('home') }}" class="flex items-center gap-2">
      <div class="grid h-9 w-9 place-items-center rounded-lg gold-gradient"><x-icon name="scissors"
          class="h-5 w-5 text-background" /></div>
      <div class="leading-tight">
        <div class="font-display text-lg font-bold gold-text">XYZ</div>
        <div class="-mt-1 text-[10px] uppercase tracking-widest text-muted-foreground">Barbershop</div>
      </div>
    </a>
    <nav class="hidden items-center gap-7 md:flex">
      @foreach([['home', 'Beranda'], ['services.index', 'Layanan'], ['booking.create', 'Booking Antrean'], ['queue.mine', 'Antrean Saya']] as [$routeName, $label])
        <a href="{{ route($routeName) }}"
          class="text-sm font-medium transition-colors hover:text-primary {{ request()->routeIs($routeName) ? 'text-primary' : 'text-muted-foreground' }}">{{ $label }}</a>
      @endforeach
    </nav>
    <div class="hidden items-center gap-3 md:flex">
      @auth
        @if(auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}"
        class="rounded-md border border-primary/40 px-3 py-1.5 text-sm font-medium text-primary hover:bg-primary/10">Dashboard</a>@endif
        <span class="text-sm text-muted-foreground">Hai, {{ str(auth()->user()->name)->before(' ') }}</span>
        <form method="POST" action="{{ route('logout') }}">@csrf<button
            class="rounded-md border border-border px-3 py-1.5 text-sm hover:border-primary/60 hover:text-primary">Keluar</button>
        </form>
      @else
        <a href="{{ route('login') }}"
          class="rounded-md border border-border px-3 py-1.5 text-sm hover:border-primary/60 hover:text-primary">Masuk</a>
        <a href="{{ route('register') }}" class="btn-gold btn-gold-hover rounded-md px-4 py-1.5 text-sm">Daftar</a>
      @endauth
    </div>
    <button class="text-foreground md:hidden" data-menu-toggle="mobile-nav" aria-label="menu"><x-icon
        name="menu" /></button>
  </div>
  <div id="mobile-nav" class="hidden border-t border-border bg-popover px-4 py-4 md:hidden">
    <nav class="flex flex-col gap-3">
      <a href="{{ route('home') }}" class="text-sm hover:text-primary">Beranda</a><a
        href="{{ route('services.index') }}" class="text-sm hover:text-primary">Layanan</a><a
        href="{{ route('booking.create') }}" class="text-sm hover:text-primary">Booking Antrean</a><a
        href="{{ route('queue.mine') }}" class="text-sm hover:text-primary">Antrean Saya</a>
      <div class="mt-2 flex gap-2">
        @auth
          @if(auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}"
          class="flex-1 rounded-md border border-primary/40 px-3 py-2 text-center text-sm text-primary">Dashboard</a>@endif
          <form class="flex-1" method="POST" action="{{ route('logout') }}">@csrf<button
              class="w-full rounded-md border border-border px-3 py-2 text-sm">Keluar</button></form>
        @else
          <a href="{{ route('login') }}"
            class="flex-1 rounded-md border border-border px-3 py-2 text-center text-sm">Masuk</a><a
            href="{{ route('register') }}" class="btn-gold flex-1 rounded-md px-3 py-2 text-center text-sm">Daftar</a>
        @endauth
      </div>
    </nav>
  </div>
</header>