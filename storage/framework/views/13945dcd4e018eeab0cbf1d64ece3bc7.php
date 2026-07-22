<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'XYZ Barbershop — Booking Antrean Online Tanpa Menunggu Lama','description' => 'Booking antrean barbershop online. Dapatkan nomor antrean dan estimasi waktu layanan sebelum datang.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'XYZ Barbershop — Booking Antrean Online Tanpa Menunggu Lama','description' => 'Booking antrean barbershop online. Dapatkan nomor antrean dan estimasi waktu layanan sebelum datang.']); ?>
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div
                class="absolute left-1/2 top-0 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-primary/10 blur-[120px]">
            </div>
        </div>
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 md:py-32">
            <div class="mx-auto max-w-3xl text-center">
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-primary/40 bg-primary/5 px-4 py-1.5 text-xs font-medium uppercase tracking-widest text-primary"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'sparkles','class' => 'h-3.5 w-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'sparkles','class' => 'h-3.5 w-3.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Premium Grooming Experience</span>
                <h1 class="mt-6 font-display text-4xl font-bold leading-tight sm:text-5xl md:text-6xl">Booking Antrean
                    <span class="gold-text">Tanpa Menunggu Lama</span></h1>
                <p class="mx-auto mt-6 max-w-2xl text-base text-muted-foreground sm:text-lg">Dapatkan nomor antrean
                    secara online dan lihat estimasi waktu layanan sebelum datang ke barbershop.</p>
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4"><a
                        href="<?php echo e(route('booking.create')); ?>"
                        class="btn-gold btn-gold-hover inline-flex items-center gap-2 rounded-lg px-6 py-3 text-sm font-semibold">Booking
                        Antrean <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-right','class' => 'h-4 w-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></a><a href="<?php echo e(route('queue.mine')); ?>"
                        class="inline-flex items-center gap-2 rounded-lg border border-primary/50 px-6 py-3 text-sm font-semibold text-primary hover:bg-primary/10">Cek
                        Antrean Saya</a></div>
            </div>
        </div>
    </section>
    <?php ($steps = [['scissors', 'Pilih Layanan', 'Pilih jenis potongan yang Anda inginkan.'], ['users', 'Dapatkan Nomor Antrean', 'Sistem otomatis memberi nomor antrean Anda.'], ['clock', 'Lihat Estimasi Waktu', 'Pantau perkiraan jam dilayani secara real-time.'], ['check-circle', 'Datang Saat Antrean Dekat', 'Tidak perlu menunggu lama di tempat.']]); ?>
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
        <div class="text-center">
            <h2 class="font-display text-3xl font-bold sm:text-4xl">Cara Kerja</h2>
            <p class="mt-3 text-muted-foreground">Empat langkah mudah untuk booking antrean Anda.</p>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="card-premium relative rounded-xl p-6">
                <div class="absolute -top-3 right-4 font-display text-5xl font-bold opacity-10 gold-text"><?php echo e($i + 1); ?>

                </div>
                <div class="grid h-12 w-12 place-items-center rounded-lg gold-gradient"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $step[0],'class' => 'h-6 w-6 text-background']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($step[0]),'class' => 'h-6 w-6 text-background']); ?>
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
                <h3 class="mt-4 text-lg font-semibold"><?php echo e($step[1]); ?></h3>
                <p class="mt-2 text-sm text-muted-foreground"><?php echo e($step[2]); ?></p>
            </div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="font-display text-3xl font-bold sm:text-4xl">Layanan Populer</h2>
                <p class="mt-2 text-muted-foreground">Pilih layanan terbaik untuk gaya Anda.</p>
            </div><a href="<?php echo e(route('services.index')); ?>" class="text-sm font-medium text-primary hover:underline">Lihat
                semua →</a>
        </div>
        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4"><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div
            class="card-premium group flex flex-col rounded-xl p-6 transition hover:border-primary/60"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'scissors','class' => 'h-7 w-7 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'scissors','class' => 'h-7 w-7 text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
            <h3 class="mt-4 font-display text-xl font-bold"><?php echo e($service->name); ?></h3>
            <div class="mt-2 text-xs text-muted-foreground"><?php echo e($service->duration); ?> menit</div>
            <div class="mt-3 text-2xl font-bold gold-text">Rp<?php echo e(number_format($service->price, 0, ',', '.')); ?></div><a
                href="<?php echo e(route('booking.create', ['service' => $service->id])); ?>"
                class="btn-gold btn-gold-hover mt-5 rounded-md py-2 text-center text-sm">Booking</a>
        </div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    </section>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\Booking-Barber-Fixed\resources\views/home.blade.php ENDPATH**/ ?>