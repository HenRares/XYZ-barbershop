<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Antrean Saya — XYZ Barbershop']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Antrean Saya — XYZ Barbershop']); ?>

    <main class="mx-auto w-full max-w-5xl flex-1 px-4 py-12 sm:px-6">

        <h1 class="font-display text-3xl font-bold sm:text-4xl">
            Antrean <span class="gold-text">Saya</span>
        </h1>

        <p class="mt-2 text-muted-foreground">
            Pantau status antrean Anda secara real-time.
        </p>

        
        <?php if(!$primary): ?>
            <div class="mt-10 rounded-xl border border-dashed border-border p-12 text-center">
                
                <p class="text-lg font-semibold text-muted-foreground">
                    Anda belum memiliki antrean aktif.
                </p>
                <p class="mt-2 text-sm text-muted-foreground">
                    Silakan lakukan booking untuk mendapatkan nomor antrean.
                </p>
                
                <a
                    href="<?php echo e(route('booking.create')); ?>"
                    class="btn-gold btn-gold-hover mt-6 inline-block rounded-md px-5 py-2 text-sm font-semibold"
                >
                    Booking Sekarang
                </a>
            </div>

        <?php else: ?>

            <div
                data-queue-tracker
                data-summary-url="<?php echo e(route('queue.summary', $primary->public_id)); ?>"
                class="mt-8 grid gap-6 lg:grid-cols-3"
            >
                
                

                <div class="card-premium rounded-2xl p-8 lg:col-span-2">

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="text-xs uppercase tracking-widest text-muted-foreground">
                            Booking <?php echo e($primary->booking_code); ?>

                        </span>

                        <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['dataLive' => 'status','status' => $primary->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-live' => 'status','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($primary->status)]); ?>
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

                    
                    <div class="mt-6 rounded-xl gold-gradient p-8 text-background">
                        <div class="text-xs font-bold uppercase tracking-widest opacity-80">
                            Nomor Antrean Anda
                        </div>

                        <div data-live="queueNumber" class="font-display text-8xl font-extrabold leading-none">
                            <?php echo e($primary->queue_number); ?>

                        </div>

                        <div class="mt-3 text-sm font-semibold">
                            <?php echo e($primary->service_name); ?>

                        </div>
                    </div>

                    
                    
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">

                        <div class="rounded-md border border-border bg-background/50 p-4">
                            <div class="text-xs uppercase tracking-wider text-muted-foreground">
                                Sedang Dilayani
                            </div>

                            <div data-live="currentServingLabel" class="mt-1 text-xl font-bold">
                                <?php echo e($estimate['currentServingLabel']); ?>

                            </div>
                        </div>

                        <div class="rounded-md border border-border bg-background/50 p-4">
                            <div class="text-xs uppercase tracking-wider text-muted-foreground">
                                Sisa Antrean
                            </div>

                            <div data-live="waitingBefore" class="mt-1 text-xl font-bold">
                                <?php echo e($estimate['waitingBefore']); ?> pelanggan
                            </div>
                        </div>

                        <div class="rounded-md border border-border bg-background/50 p-4">
                            <div class="text-xs uppercase tracking-wider text-muted-foreground">
                                Estimasi Waktu Tunggu
                            </div>

                            <div data-live="waitingText" class="mt-1 text-xl font-bold gold-text">
                                <?php echo e(app(\App\Services\QueueEstimator::class)->formatWait($estimate['waitingMinutes'])); ?>

                            </div>
                        </div>

                        <div class="rounded-md border border-border bg-background/50 p-4">
                            <div class="text-xs uppercase tracking-wider text-muted-foreground">
                                Estimasi Jam Dilayani
                            </div>

                            <div data-live="serviceTime" class="mt-1 text-xl font-bold gold-text">
                                <?php echo e($estimate['serviceTime']); ?>

                            </div>
                        </div>

                    </div>


                    
                    
                    <div class="mt-6 rounded-md border border-primary/30 bg-primary/5 p-4 text-sm text-muted-foreground">
                        <div class="font-semibold text-primary">
                            Antrean
                            <span data-live="currentServingLabel">
                                <?php echo e($estimate['currentServingLabel']); ?>

                            </span>
                            sedang dilayani
                        </div>

                        <div>
                            Antrean <span data-live="queueNumber"><?php echo e($primary->queue_number); ?></span> adalah antrean Anda
                        </div>
                    </div>

                </div>

                
                
                <div class="card-premium rounded-2xl p-6">

                    <h3 class="font-display text-lg font-bold">
                        Detail Booking
                    </h3>

                    <div class="mt-4 space-y-3 text-sm">

                        <?php $__currentLoopData = [
                            ['Layanan', $primary->service_name],
                            ['Tanggal Kunjungan', $primary->visit_date->format('Y-m-d')],
                            ['Estimasi Durasi', $primary->service_duration . ' menit'],
                            ['Nomor HP', $primary->phone],
                            ['Jenis', $primary->queue_type],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <div class="flex items-center justify-between border-b border-border pb-2 last:border-0">
                                <span class="text-muted-foreground">
                                    <?php echo e($label); ?>

                                </span>

                                <span class="font-medium">
                                    <?php echo e($value); ?>

                                </span>
                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                    
                    
                    <?php if($primary->isActive()): ?>

                        <form
                            method="POST"
                            action="<?php echo e(route('queue.cancel', $primary->public_id)); ?>"
                            data-confirm="Batalkan booking nomor antrean <?php echo e($primary->queue_number); ?>?"
                            class="mt-6"
                        >
                            <?php echo csrf_field(); ?>

                            <button
                                class="w-full rounded-md border border-destructive/50 px-4 py-2 text-sm font-semibold text-destructive hover:bg-destructive/10"
                            >
                                Batalkan Booking
                            </button>

                        </form>

                    <?php endif; ?>

                </div>

            </div>

        <?php endif; ?>

    
        
        <?php if($bookings->count() > 0): ?>

            <div class="mt-10">

                <h2 class="font-display text-xl font-bold">
                    Riwayat Booking
                </h2>

                <div class="mt-4 overflow-x-auto rounded-xl border border-border">

                    <table class="w-full text-sm">

                        <thead class="bg-popover text-muted-foreground">
                            <tr>
                                <th class="px-4 py-3 text-left">No. Antrean</th>
                                <th class="px-4 py-3 text-left">Kode</th>
                                <th class="px-4 py-3 text-left">Layanan</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            

                            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php if($primary && $booking->id == $primary->id) continue; ?>

                                <tr class="border-t border-border">

                                    <td class="px-4 py-3 font-semibold text-primary">
                                        <?php echo e($booking->queue_number); ?>

                                    </td>

                                    <td class="px-4 py-3">
                                        <?php echo e($booking->booking_code); ?>

                                    </td>

                                    <td class="px-4 py-3">
                                        <?php echo e($booking->service_name); ?>

                                    </td>

                                    <td class="px-4 py-3">
                                        <?php echo e($booking->visit_date->format('Y-m-d')); ?>

                                    </td>

                                    <td class="px-4 py-3">
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
                                    </td>

                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>

                    </table>

                </div>

            </div>

        <?php endif; ?>

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
<?php /**PATH C:\laragon\www\Booking-Barber-Fixed\resources\views/booking/my-queue.blade.php ENDPATH**/ ?>