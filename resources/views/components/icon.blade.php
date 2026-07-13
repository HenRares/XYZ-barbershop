@props(['name', 'class' => 'h-5 w-5'])
<svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '2', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
@switch($name)
@case('scissors') <circle cx="6" cy="7" r="3"/><path d="M8.7 8.7 21 21"/><circle cx="6" cy="17" r="3"/><path d="M8.7 15.3 21 3"/> @break
@case('menu') <path d="M4 6h16M4 12h16M4 18h16"/> @break
@case('x') <path d="m18 6-12 12M6 6l12 12"/> @break
@case('sparkles') <path d="m12 3-1.9 4.4L6 9.2l4.1 1.8L12 15l1.9-4 4.1-1.8-4.1-1.8L12 3Z"/><path d="m5 16-.9 2.1L2 19l2.1.9L5 22l.9-2.1L8 19l-2.1-.9L5 16Z"/> @break
@case('arrow-right') <path d="M5 12h14M13 6l6 6-6 6"/> @break
@case('clock') <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/> @break
@case('users') <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/> @break
@case('check-circle') <circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/> @break
@case('play-circle') <circle cx="12" cy="12" r="9"/><path d="m10 8 6 4-6 4V8Z"/> @break
@case('x-circle') <circle cx="12" cy="12" r="9"/><path d="m9 9 6 6M15 9l-6 6"/> @break
@case('list') <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/> @break
@case('dashboard') <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/> @break
@case('chart') <path d="M3 3v18h18"/><path d="m7 16 4-5 4 3 5-8"/> @break
@case('logout') <path d="M10 17l5-5-5-5M15 12H3"/><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/> @break
@case('plus') <path d="M12 5v14M5 12h14"/> @break
@case('search') <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/> @break
@case('trash') <path d="M3 6h18M8 6V4h8v2M19 6l-1 15H6L5 6M10 11v6M14 11v6"/> @break
@case('pencil') <path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/> @break
@case('info') <circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/> @break
@default <circle cx="12" cy="12" r="9"/>
@endswitch
</svg>
