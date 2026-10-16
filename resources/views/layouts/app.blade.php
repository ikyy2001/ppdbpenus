<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PPDB 2027/2028 - SMK Plus Pelita Nusantara Bogor')</title>
    <meta name="description" content="@yield('meta_description', 'Portal Resmi Pendaftaran Peserta Didik Baru (PPDB) SMK Plus Pelita Nusantara Bogor. Pilihan Jurusan Vokasi Unggulan & Fasilitas Terlengkap.')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/logosmkpenus.png') }}">

    <!-- Google Fonts: Oswald & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for lightweight reactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @stack('styles')
</head>
{{-- Latar putih seperti landing page; jarak untuk navbar fixed diatur oleh hero tiap halaman (pt-32 md:pt-40) --}}
<body class="bg-white text-brand-ink antialiased min-h-screen flex flex-col font-sans selection:bg-brand-darkred selection:text-white">
    @include('partials.dropdown-engine')
    <x-header />

    <div class="flex-1">
        @yield('content')
    </div>

    <x-footer />

    @include('partials.sketch-engine')
    @stack('scripts')
</body>
</html>
