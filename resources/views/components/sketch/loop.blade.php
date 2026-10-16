{{-- Versi besar lingkaran coretan untuk dipasang di belakang gambar, goresannya lebih tebal.
     class wajib berisi posisi, ukuran & warna. Taruh sebelum gambarnya dan beri gambar "relative" supaya di atas. --}}
@props(['delay' => 0])
<span data-sketch="circle" data-scale="1.5" data-duration="1200" data-delay="{{ $delay }}" aria-hidden="true" {{ $attributes->class('pointer-events-none absolute') }}></span>
