@props(['status'])
@php($class = match($status) { 'Menunggu' => 'status-warning', 'Sedang Dilayani' => 'status-primary', 'Selesai' => 'status-success', default => 'status-danger' })
<span {{ $attributes->merge(['class' => "status-badge {$class}"]) }}>{{ $status }}</span>
