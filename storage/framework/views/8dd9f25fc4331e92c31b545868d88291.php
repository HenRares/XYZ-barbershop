<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'class' => 'h-5 w-5']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'class' => 'h-5 w-5']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<svg <?php echo e($attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '2', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true'])); ?>>
<?php switch($name):
case ('scissors'): ?> <circle cx="6" cy="7" r="3"/><path d="M8.7 8.7 21 21"/><circle cx="6" cy="17" r="3"/><path d="M8.7 15.3 21 3"/> <?php break; ?>
<?php case ('menu'): ?> <path d="M4 6h16M4 12h16M4 18h16"/> <?php break; ?>
<?php case ('x'): ?> <path d="m18 6-12 12M6 6l12 12"/> <?php break; ?>
<?php case ('sparkles'): ?> <path d="m12 3-1.9 4.4L6 9.2l4.1 1.8L12 15l1.9-4 4.1-1.8-4.1-1.8L12 3Z"/><path d="m5 16-.9 2.1L2 19l2.1.9L5 22l.9-2.1L8 19l-2.1-.9L5 16Z"/> <?php break; ?>
<?php case ('arrow-right'): ?> <path d="M5 12h14M13 6l6 6-6 6"/> <?php break; ?>
<?php case ('clock'): ?> <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/> <?php break; ?>
<?php case ('users'): ?> <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/> <?php break; ?>
<?php case ('check-circle'): ?> <circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/> <?php break; ?>
<?php case ('play-circle'): ?> <circle cx="12" cy="12" r="9"/><path d="m10 8 6 4-6 4V8Z"/> <?php break; ?>
<?php case ('x-circle'): ?> <circle cx="12" cy="12" r="9"/><path d="m9 9 6 6M15 9l-6 6"/> <?php break; ?>
<?php case ('list'): ?> <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/> <?php break; ?>
<?php case ('dashboard'): ?> <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/> <?php break; ?>
<?php case ('chart'): ?> <path d="M3 3v18h18"/><path d="m7 16 4-5 4 3 5-8"/> <?php break; ?>
<?php case ('logout'): ?> <path d="M10 17l5-5-5-5M15 12H3"/><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/> <?php break; ?>
<?php case ('plus'): ?> <path d="M12 5v14M5 12h14"/> <?php break; ?>
<?php case ('search'): ?> <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/> <?php break; ?>
<?php case ('trash'): ?> <path d="M3 6h18M8 6V4h8v2M19 6l-1 15H6L5 6M10 11v6M14 11v6"/> <?php break; ?>
<?php case ('pencil'): ?> <path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/> <?php break; ?>
<?php case ('info'): ?> <circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/> <?php break; ?>
<?php default: ?> <circle cx="12" cy="12" r="9"/>
<?php endswitch; ?>
</svg>
<?php /**PATH C:\laragon\www\Booking-Barber-Fixed\resources\views/components/icon.blade.php ENDPATH**/ ?>