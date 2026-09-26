@props(['text' => '', 'variant' => 'dark'])

<div
    {{ $attributes->merge([
        'class' => 'font-sans font-semibold uppercase tracking-tighter pointer-events-none select-none ' . 
                   ($variant === 'light' ? 'ghost-typography-white' : 'ghost-typography')
    ]) }}
>
    {{ $text }}
</div>
