<header class="sticky top-0 z-40 border-b border-border bg-background/85 backdrop-blur-md">
  <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
    <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2">
      <div class="grid h-9 w-9 place-items-center rounded-lg gold-gradient"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'scissors','class' => 'h-5 w-5 text-background']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'scissors','class' => 'h-5 w-5 text-background']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></div>
      <div class="leading-tight">
        <div class="font-display text-lg font-bold gold-text">XYZ</div>
        <div class="-mt-1 text-[10px] uppercase tracking-widest text-muted-foreground">Barbershop</div>
      </div>
    </a>
    <nav class="hidden items-center gap-7 md:flex">
      <?php $__currentLoopData = [['home', 'Beranda'], ['services.index', 'Layanan'], ['booking.create', 'Booking Antrean'], ['queue.mine', 'Antrean Saya']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$routeName, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route($routeName)); ?>"
          class="text-sm font-medium transition-colors hover:text-primary <?php echo e(request()->routeIs($routeName) ? 'text-primary' : 'text-muted-foreground'); ?>"><?php echo e($label); ?></a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>
    <div class="hidden items-center gap-3 md:flex">
      <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->isAdmin()): ?><a href="<?php echo e(route('admin.dashboard')); ?>"
        class="rounded-md border border-primary/40 px-3 py-1.5 text-sm font-medium text-primary hover:bg-primary/10">Dashboard</a><?php endif; ?>
        <span class="text-sm text-muted-foreground">Hai, <?php echo e(str(auth()->user()->name)->before(' ')); ?></span>
        <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button
            class="rounded-md border border-border px-3 py-1.5 text-sm hover:border-primary/60 hover:text-primary">Keluar</button>
        </form>
      <?php else: ?>
        <a href="<?php echo e(route('login')); ?>"
          class="rounded-md border border-border px-3 py-1.5 text-sm hover:border-primary/60 hover:text-primary">Masuk</a>
        <a href="<?php echo e(route('register')); ?>" class="btn-gold btn-gold-hover rounded-md px-4 py-1.5 text-sm">Daftar</a>
      <?php endif; ?>
    </div>
    <button class="text-foreground md:hidden" data-menu-toggle="mobile-nav" aria-label="menu"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'menu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'menu']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></button>
  </div>
  <div id="mobile-nav" class="hidden border-t border-border bg-popover px-4 py-4 md:hidden">
    <nav class="flex flex-col gap-3">
      <a href="<?php echo e(route('home')); ?>" class="text-sm hover:text-primary">Beranda</a><a
        href="<?php echo e(route('services.index')); ?>" class="text-sm hover:text-primary">Layanan</a><a
        href="<?php echo e(route('booking.create')); ?>" class="text-sm hover:text-primary">Booking Antrean</a><a
        href="<?php echo e(route('queue.mine')); ?>" class="text-sm hover:text-primary">Antrean Saya</a>
      <div class="mt-2 flex gap-2">
        <?php if(auth()->guard()->check()): ?>
          <?php if(auth()->user()->isAdmin()): ?><a href="<?php echo e(route('admin.dashboard')); ?>"
          class="flex-1 rounded-md border border-primary/40 px-3 py-2 text-center text-sm text-primary">Dashboard</a><?php endif; ?>
          <form class="flex-1" method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button
              class="w-full rounded-md border border-border px-3 py-2 text-sm">Keluar</button></form>
        <?php else: ?>
          <a href="<?php echo e(route('login')); ?>"
            class="flex-1 rounded-md border border-border px-3 py-2 text-center text-sm">Masuk</a><a
            href="<?php echo e(route('register')); ?>" class="btn-gold flex-1 rounded-md px-3 py-2 text-center text-sm">Daftar</a>
        <?php endif; ?>
      </div>
    </nav>
  </div>
</header><?php /**PATH C:\laragon\www\Booking-Barber-Fixed\resources\views/components/navbar.blade.php ENDPATH**/ ?>