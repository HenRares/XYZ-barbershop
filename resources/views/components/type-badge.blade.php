@props(['type'])
<span {{ $attributes->merge(['class' => 'type-badge '.($type === 'Online' ? 'type-online' : 'type-walkin')]) }}>{{ $type }}</span>
