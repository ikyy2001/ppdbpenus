{{-- Satu baris ringkasan data (label | isi) dengan garis coretan tipis di bawahnya, seperti tabel identitas
     di halaman Tentang landing page. Atribut lain (x-text, :class, dst.) diteruskan ke <dd>. Taruh di dalam <dl>. --}}
@props(['label'])
<div class="relative grid gap-1 py-3.5 sm:grid-cols-[12rem_1fr] sm:gap-6">
    <dt class="text-sm text-brand-ink/60">{{ $label }}</dt>
    <dd {{ $attributes->class('text-sm font-semibold text-brand-ink wrap-anywhere') }}>{{ $slot }}</dd>
    <x-sketch.rule class="text-brand-darkred/25 -left-1 -right-1 -bottom-1.5 h-3" />
</div>
