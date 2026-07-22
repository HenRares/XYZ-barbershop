<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Booking Berhasil — XYZ Barbershop']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Booking Berhasil — XYZ Barbershop']); ?>

    <main class="mx-auto w-full max-w-2xl flex-1 px-4 py-12 sm:px-6">

        <div class="card-premium rounded-2xl p-8 text-center">

            
            <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-success/15 text-success">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check-circle','class' => 'h-7 w-7']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check-circle','class' => 'h-7 w-7']); ?>
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
            </div>

            
            <h1 class="mt-4 font-display text-3xl font-bold">
                Booking Berhasil!
            </h1>

            <p class="mt-2 text-sm text-muted-foreground">
                Simpan ID booking Anda untuk referensi.
            </p>

            
            <div class="mt-6 rounded-xl gold-gradient p-6 text-background">

                <div class="text-xs font-semibold uppercase tracking-widest opacity-80">
                    Nomor Antrean Anda
                </div>

                <div class="font-display text-7xl font-bold leading-none">
                    <?php echo e($booking->queue_number); ?>

                </div>

                <div class="mt-2 text-sm font-medium">
                    <?php echo e($booking->booking_code); ?>

                </div>

            </div>

            
            <div class="mt-6 space-y-2 text-left">

                <?php $__currentLoopData = [
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

                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-2.5">

                        <span class="text-xs uppercase tracking-wider text-muted-foreground">
                            <?php echo e($label); ?>

                        </span>

                        <span class="text-sm font-semibold">
                            <?php echo e($value); ?>

                        </span>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <div class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-2.5">

                    <span class="text-xs uppercase tracking-wider text-muted-foreground">
                        Status
                    </span>

                    <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $booking->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($booking->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>

                </div>

            </div>

            
            <div class="mt-8 flex flex-wrap justify-center gap-3">

                <a
                    href="<?php echo e(route('queue.mine', ['booking' => $booking->public_id])); ?>"
                    class="btn-gold btn-gold-hover rounded-md px-5 py-2.5 text-sm font-semibold"
                >
                    Lihat Antrean Saya
                </a>

                <a
                    href="<?php echo e(route('home')); ?>"
                    class="rounded-md border border-border px-5 py-2.5 text-sm hover:border-primary/60 hover:text-primary"
                >
                    Kembali ke Beranda
                </a>

            </div>

        </div>

    </main>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Booking-Barber-Fixed\resources\views/booking/success.blade.php ENDPATH**/ ?>