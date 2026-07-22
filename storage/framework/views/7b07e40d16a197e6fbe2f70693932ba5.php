<?php if (isset($component)) { $__componentOriginalc8c9fd5d7827a77a31381de67195f0c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8c9fd5d7827a77a31381de67195f0c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.admin','data' => ['title' => 'Dashboard']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dashboard']); ?>


<h1 class="font-display text-2xl font-bold sm:text-3xl">
    Dashboard
</h1>

<p class="mt-1 text-sm text-muted-foreground">
    Ringkasan antrean hari ini (<?php echo e($today); ?>).
</p>


<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">

    <?php $__currentLoopData = [
        ['list', 'Antrean Hari Ini', $stats['total'], ''],
        ['play-circle', 'Sedang Dilayani', $stats['serving'], 'text-primary'],
        ['clock', 'Menunggu', $stats['waiting'], 'text-warning'],
        ['check-circle', 'Selesai', $stats['done'], 'text-success'],
        ['x-circle', 'Dibatalkan', $stats['cancelled'], 'text-destructive'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $label, $value, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div class="card-premium rounded-xl p-5">

            <div class="flex items-center justify-between">

                <span class="text-xs uppercase tracking-wider text-muted-foreground">
                    <?php echo e($label); ?>

                </span>

                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $icon,'class' => 'h-4 w-4 '.e($color).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => 'h-4 w-4 '.e($color).'']); ?>
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

            <div class="mt-3 font-display text-3xl font-bold <?php echo e($color); ?>">
                <?php echo e($value); ?>

            </div>

        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>


<div class="mt-6 grid gap-6 lg:grid-cols-2">

    
    <div class="card-premium rounded-xl p-6">

        <h2 class="font-display text-lg font-bold">
            Live Queue Panel
        </h2>

        <div class="mt-4 grid grid-cols-2 gap-4">

            <?php $__currentLoopData = [
                ['Sedang Dilayani', $currentLabel, true],
                ['Antrean Berikutnya', $next ? '#' . $next->queue_number : '—', false],
                ['Barber Aktif', $barbers, false],
                ['Rata-rata Tunggu', $avgWait ? $avgWait . ' mnt' : '—', false],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $highlight]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="
                    rounded-lg border p-4
                    <?php echo e($highlight
                        ? 'border-primary/50 bg-primary/5'
                        : 'border-border bg-background/50'); ?>

                ">

                    <div class="text-xs text-muted-foreground">
                        <?php echo e($label); ?>

                    </div>

                    <div class="mt-2 font-display text-3xl font-bold <?php echo e($highlight ? 'gold-text' : ''); ?>">
                        <?php echo e($value); ?>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    </div>

    
    <div class="card-premium rounded-xl p-6">

        <h2 class="font-display text-lg font-bold">
            Antrean Terbaru
        </h2>

        <div class="mt-4 overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="text-xs uppercase text-muted-foreground">

                    <tr>
                        <th class="px-2 py-2 text-left">No.</th>
                        <th class="px-2 py-2 text-left">Nama</th>
                        <th class="px-2 py-2 text-left">Layanan</th>
                        <th class="px-2 py-2 text-left">Jam</th>
                        <th class="px-2 py-2 text-left">Status</th>
                    </tr>

                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr class="border-t border-border">

                            <td class="px-2 py-2 font-semibold text-primary">
                                #<?php echo e($booking->queue_number); ?>

                            </td>

                            <td class="px-2 py-2">
                                <?php echo e($booking->customer_name); ?>

                            </td>

                            <td class="px-2 py-2 text-muted-foreground">
                                <?php echo e($booking->service_name); ?>

                            </td>

                            <td class="px-2 py-2">
                                <?php echo e($booking->estimated_service_time); ?>

                            </td>

                            <td class="px-2 py-2">
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

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td
                                colspan="5"
                                class="py-8 text-center text-muted-foreground"
                            >
                                Belum ada antrean hari ini.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8c9fd5d7827a77a31381de67195f0c3)): ?>
<?php $attributes = $__attributesOriginalc8c9fd5d7827a77a31381de67195f0c3; ?>
<?php unset($__attributesOriginalc8c9fd5d7827a77a31381de67195f0c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8c9fd5d7827a77a31381de67195f0c3)): ?>
<?php $component = $__componentOriginalc8c9fd5d7827a77a31381de67195f0c3; ?>
<?php unset($__componentOriginalc8c9fd5d7827a77a31381de67195f0c3); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\Booking-Barber-Fixed\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>