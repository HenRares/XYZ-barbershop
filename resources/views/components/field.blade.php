{{-- @props(['label', 'name' => null, 'hint' => null])
<label class="block">
  <span class="mb-1.5 block text-sm font-medium text-foreground">{{ $label }}</span>
  {{ $slot }}
  @if($hint)<span class="mt-1 block text-xs text-muted-foreground">{{ $hint }}</span>@endif
  @if($name)@error($name)<span class="mt-1 block text-xs text-destructive">{{ $message }}</span>@enderror@endif
</label> --}}
@props([
    'label',
    'name' => null,
    'hint' => null,
])

<label class="block">

    {{-- Label --}}
    <span class="mb-1.5 block text-sm font-medium text-foreground">
        {{ $label }}
    </span>

    {{-- Input --}}
    {{ $slot }}

    {{-- Hint --}}
    @if ($hint)
        <p class="mt-1 text-xs text-muted-foreground">
            {{ $hint }}
        </p>
    @endif

    {{-- Validation Error --}}
    @if ($name)
        @error($name)
            <p
                class="mt-2 flex items-center gap-1 text-sm font-medium text-red-500"
                role="alert"
            >
                ❌ {{ $message }}
            </p>
        @enderror
    @endif

</label>