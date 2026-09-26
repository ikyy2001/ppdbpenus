@props([
    'variant' => 's-curve',
    'color' => '#D04A43',
    'opacity' => 0.35,
])

<svg
    {{ $attributes->merge(['class' => 'pointer-events-none select-none']) }}
    viewBox="0 0 1000 400"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    preserveAspectRatio="none"
>
    <path
        d="M-50 200 C 300 50, 700 350, 1050 200"
        stroke="{{ $color }}"
        stroke-width="2"
        stroke-dasharray="8 8"
        stroke-opacity="{{ $opacity }}"
    />
    <path
        d="M-50 220 C 320 70, 680 330, 1050 220"
        stroke="{{ $color }}"
        stroke-width="1.5"
        stroke-opacity="{{ (float)$opacity * 0.7 }}"
    />
</svg>
