<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Booking Antrean — XYZ Barbershop']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Booking Antrean — XYZ Barbershop']); ?>

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-12 sm:px-6">

        
        <h1 class="font-display text-3xl font-bold sm:text-4xl">
            Booking <span class="gold-text">Antrean</span>
        </h1>

        <p class="mt-2 text-muted-foreground">
            Pilih layanan dan tanggal kunjungan untuk mendapatkan nomor antrean.
        </p>

        <div class="mt-8 grid gap-8 lg:grid-cols-2">

            
            <form method="POST" action="<?php echo e(route('booking.store')); ?>" data-booking-form
                data-estimate-url="<?php echo e(route('booking.estimate')); ?>" class="card-premium space-y-5 rounded-xl p-6">
                <?php echo csrf_field(); ?>

                
                <?php if (isset($component)) { $__componentOriginalae4c123bc9806121d87d234de2f27a3b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalae4c123bc9806121d87d234de2f27a3b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.field','data' => ['label' => 'Nama Pelanggan','name' => 'customer_name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nama Pelanggan','name' => 'customer_name']); ?>
                    <input type="text" name="customer_name" required
                        value="<?php echo e(old('customer_name', auth()->user()?->name)); ?>" class="input-control"
                        placeholder="Nama lengkap">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.field','data' => ['label' => 'Nomor HP','name' => 'phone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nomor HP','name' => 'phone']); ?>
                    <input type="text" name="phone" required value="<?php echo e(old('phone', auth()->user()?->phone)); ?>"
                        class="input-control" placeholder="08xxxxxxxxxx">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.field','data' => ['label' => 'Pilih Layanan','name' => 'service_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pilih Layanan','name' => 'service_id']); ?>
                    <select name="service_id" required class="input-control">
                        <option value="">
                            -- Pilih layanan --
                        </option>

                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($service->id); ?>" <?php if((string) old('service_id', $selectedService) === (string) $service->id): echo 'selected'; endif; ?>>
                                <?php echo e($service->name); ?>

                                —
                                <?php echo e($service->duration); ?> menit
                                —
                                Rp<?php echo e(number_format($service->price, 0, ',', '.')); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.field','data' => ['label' => 'Tanggal Kunjungan','name' => 'visit_date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tanggal Kunjungan','name' => 'visit_date']); ?>
                    <input type="date" name="visit_date" required min="<?php echo e($today); ?>"
                        value="<?php echo e(old('visit_date', $today)); ?>" class="input-control">
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

                
                <button type="submit" class="btn-gold btn-gold-hover w-full rounded-md py-3 text-sm font-semibold">
                    Konfirmasi Booking Antrean
                </button>

            </form>

            
            <div class="card-premium rounded-xl p-6">

                <h2 class="font-display text-xl font-bold">
                    Estimasi Antrean
                </h2>

                <p class="mt-1 text-xs text-muted-foreground">
                    Perkiraan otomatis berdasarkan antrean saat ini.
                </p>

                
                <div data-estimate-empty
                    class="mt-6 rounded-lg border border-dashed border-border p-8 text-center text-sm text-muted-foreground">
                    Pilih layanan dan tanggal untuk melihat estimasi.
                </div>

                
                <div data-estimate-panel class="mt-5 hidden space-y-3">

                    
                    <div class="rounded-lg gold-gradient p-5 text-background">

                        <div class="text-xs font-semibold uppercase tracking-wider opacity-80">
                            Nomor Antrean Anda
                        </div>

                        <div data-estimate="nextNumber" class="font-display text-6xl font-bold leading-none">
                            —
                        </div>

                    </div>

                    
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Sedang Dilayani
                        </span>

                        <span data-estimate="currentServingLabel" class="text-sm font-semibold">
                            Belum ada
                        </span>
                    </div>

                    
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Sisa Antrean Sebelum Anda
                        </span>

                        <span class="text-sm font-semibold">
                            <span data-estimate="waitingBefore">0</span>
                            pelanggan
                        </span>
                    </div>

                    
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Barber Aktif
                        </span>

                        <span class="text-sm font-semibold">
                            <span data-estimate="activeBarbers">0</span>
                            barber
                        </span>
                    </div>

                    
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Estimasi Waktu Tunggu
                        </span>

                        <span data-estimate="waitingMinutes" class="text-sm font-semibold text-primary">
                            —
                        </span>
                    </div>

                    
                    <div
                        class="flex items-center justify-between rounded-md border border-border bg-background/50 px-4 py-3">
                        <span class="text-sm text-muted-foreground">
                            Estimasi Jam Dilayani
                        </span>

                        <span data-estimate="serviceTime" class="text-sm font-semibold text-primary">
                            —
                        </span>
                    </div>

                    
                    <div
                        class="mt-4 flex gap-2 rounded-md border border-warning/40 bg-warning/10 p-3 text-xs text-warning">

                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'info','class' => 'mt-0.5 h-4 w-4 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'info','class' => 'mt-0.5 h-4 w-4 shrink-0']); ?>
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

                        <span>
                            Estimasi waktu dapat berubah sesuai durasi layanan,
                            kedatangan pelanggan, dan ketersediaan barber.
                        </span>

                    </div>

                </div>

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
<?php /**PATH C:\laragon\www\Booking-Barber-Fixed\resources\views/booking/create.blade.php ENDPATH**/ ?>