<?php if (isset($component)) { $__componentOriginalc8c9fd5d7827a77a31381de67195f0c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8c9fd5d7827a77a31381de67195f0c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.admin','data' => ['title' => 'Kapasitas Barber']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Kapasitas Barber']); ?>



<h1 class="font-display text-2xl font-bold sm:text-3xl">
    Kapasitas Barber
</h1>

<p class="mt-1 text-sm text-muted-foreground">
    Atur jumlah barber aktif per hari.
    Jam operasional: 10:00 - 21:00.
</p>

<div class="mt-6 grid gap-6 lg:grid-cols-2">

    
    <div class="card-premium rounded-xl p-6">

        <h2 class="font-display text-lg font-bold">
            Pengaturan Hari
        </h2>

        <form
            method="POST"
            action="<?php echo e(route('admin.capacities.store')); ?>"
            class="mt-4 space-y-4"
        >
            <?php echo csrf_field(); ?>

            <?php if (isset($component)) { $__componentOriginalae4c123bc9806121d87d234de2f27a3b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalae4c123bc9806121d87d234de2f27a3b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.field','data' => ['label' => 'Tanggal','name' => 'date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tanggal','name' => 'date']); ?>

                <input
                    type="date"
                    name="date"
                    value="<?php echo e($date); ?>"
                    class="input-control"
                >

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $attributes = $__attributesOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $component = $__componentOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__componentOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginalae4c123bc9806121d87d234de2f27a3b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalae4c123bc9806121d87d234de2f27a3b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.field','data' => ['label' => 'Jumlah Barber Aktif','name' => 'active_barbers','hint' => 'Berlaku saat jam normal. Saat jam istirahat otomatis dikurangi 1.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jumlah Barber Aktif','name' => 'active_barbers','hint' => 'Berlaku saat jam normal. Saat jam istirahat otomatis dikurangi 1.']); ?>

                <input
                    type="number"
                    name="active_barbers"
                    min="1"
                    max="20"
                    value="<?php echo e($base); ?>"
                    class="input-control"
                >

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $attributes = $__attributesOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__attributesOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalae4c123bc9806121d87d234de2f27a3b)): ?>
<?php $component = $__componentOriginalae4c123bc9806121d87d234de2f27a3b; ?>
<?php unset($__componentOriginalae4c123bc9806121d87d234de2f27a3b); ?>
<?php endif; ?>

            <button
                class="btn-gold btn-gold-hover w-full rounded-md py-2 text-sm font-semibold"
            >
                Simpan
            </button>

        </form>

    </div>

    
    <div class="card-premium rounded-xl p-6">

        <h2 class="font-display text-lg font-bold">
            Jadwal Kapasitas (<?php echo e($date); ?>)
        </h2>

        <div class="mt-4 overflow-hidden rounded-md border border-border">

            <table class="w-full text-sm">

                <thead class="bg-background/40 text-xs uppercase text-muted-foreground">

                    <tr>
                        <th class="px-3 py-2 text-left">
                            Rentang Waktu
                        </th>

                        <th class="px-3 py-2 text-left">
                            Barber Aktif
                        </th>

                        <th class="px-3 py-2 text-left">
                            Keterangan
                        </th>
                    </tr>

                </thead>

                <tbody>

                    <?php $__currentLoopData = $schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr class="border-t border-border">

                            <td class="px-3 py-2 font-medium">
                                <?php echo e($item['range']); ?>

                            </td>

                            <td class="px-3 py-2 font-semibold text-primary">
                                <?php echo e($item['barbers']); ?> barber
                            </td>

                            <td class="px-3 py-2 text-muted-foreground">
                                <?php echo e($item['note']); ?>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<div class="card-premium mt-6 rounded-xl p-6">

    <h2 class="font-display text-lg font-bold">
        Riwayat Kapasitas
    </h2>

    <div class="mt-4 overflow-hidden rounded-md border border-border">

        <table class="w-full text-sm">

            <thead class="bg-background/40 text-xs uppercase text-muted-foreground">

                <tr>

                    <th class="px-3 py-2 text-left">
                        Tanggal
                    </th>

                    <th class="px-3 py-2 text-left">
                        Barber Aktif
                    </th>

                    <th class="px-3 py-2 text-left">
                        Jam Operasional
                    </th>

                </tr>

            </thead>

            <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $capacities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $capacity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr class="border-t border-border">

                        <td class="px-3 py-2">

                            <a
                                href="<?php echo e(route('admin.capacities', [
                                    'date' => $capacity->date->format('Y-m-d')
                                ])); ?>"
                                class="hover:text-primary"
                            >
                                <?php echo e($capacity->date->format('Y-m-d')); ?>

                            </a>

                        </td>

                        <td class="px-3 py-2 font-semibold text-primary">
                            <?php echo e($capacity->active_barbers); ?>

                        </td>

                        <td class="px-3 py-2 text-muted-foreground">
                            <?php echo e(substr($capacity->opening_time, 0, 5)); ?>

                            -
                            <?php echo e(substr($capacity->closing_time, 0, 5)); ?>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>

                        <td
                            colspan="3"
                            class="py-6 text-center text-muted-foreground"
                        >
                            Belum ada data.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

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
<?php /**PATH C:\laragon\www\Booking-Barber-Fixed\resources\views/admin/capacities.blade.php ENDPATH**/ ?>