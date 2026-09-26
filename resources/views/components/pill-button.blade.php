@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
])

@php
    $baseStyles = 'inline-flex items-center justify-center gap-2 font-semibold tracking-wide transition-all duration-200 select-none active:scale-[0.98] cursor-pointer';
    
    $variants = [
        'primary' => 'bg-linear-to-r from-brand-signal to-brand-darkred hover:opacity-95 text-white shadow-md shadow-brand-darkred/25 border border-transparent',
        'accent' => 'bg-brand-darkred hover:bg-brand-deepred text-white shadow-md shadow-brand-darkred/25 border border-transparent',
        'secondary' => 'bg-white/90 hover:bg-white text-brand-ink border border-brand-ink/20 shadow-xs',
        'ghost' => 'bg-transparent hover:bg-brand-darkred/10 text-brand-ink border border-transparent',
    ];

    $sizes = [
        'sm' => 'text-xs px-4 py-1.5 rounded-full',
        'md' => 'text-sm px-6 py-2.5 rounded-full',
        'lg' => 'text-base px-8 py-3 rounded-full',
    ];

    $classes = implode(' ', [
        $baseStyles,
        $variants[$variant] ?? $variants['primary'],
        $sizes[$size] ?? $sizes['md'],
        $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '',
    ]);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
