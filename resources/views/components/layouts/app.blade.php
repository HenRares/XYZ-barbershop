@props(['title' => 'XYZ Barbershop', 'description' => 'Booking antrean barbershop online.'])
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'XYZ Barbershop' }}</title>
    <meta name="description" content="{{ $description ?? 'Booking antrean barbershop online.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col"><x-navbar />
    @if(session('success') || session('error') || $errors->any())
        <div class="fixed right-4 top-20 z-50 max-w-sm space-y-2">@if(session('success'))
            <div class="toast rounded-lg border border-success/40 bg-popover px-4 py-3 text-sm text-success shadow-xl">
        {{ session('success') }}</div>@endif @if(session('error'))
                    <div
                        class="toast rounded-lg border border-destructive/40 bg-popover px-4 py-3 text-sm text-destructive shadow-xl">
                {{ session('error') }}</div>@endif @if($errors->any())
                    <div
                        class="toast rounded-lg border border-destructive/40 bg-popover px-4 py-3 text-sm text-destructive shadow-xl">
                {{ $errors->first() }}</div>@endif
    </div>@endif
    {{ $slot }}<x-footer />
</body>

</html>