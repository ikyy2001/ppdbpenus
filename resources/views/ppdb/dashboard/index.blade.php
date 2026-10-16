@extends('layouts.admin')

@section('title', 'Dashboard Utama PPDB - SMK Plus Pelita Nusantara')

@php
    $userName = $authUser['nama_lengkap'] ?? $authUser['username'] ?? 'Panitia PPDB';
    $firstName = \Illuminate\Support\Str::of($userName)->explode(' ')->first();

    $hour = (int) now()->format('H');
    $greet = match (true) {
        $hour < 11 => 'Selamat pagi',
        $hour < 15 => 'Selamat siang',
        $hour < 18 => 'Selamat sore',
        default => 'Selamat malam',
    };

    $kuotaPerJurusan = 72; // 2 rombel x 36 siswa per jurusan
    $verifiedRate = $totalPendaftar > 0 ? (int) round(($totalPendaftar - $totalMenunggu) / $totalPendaftar * 100) : 0;
    $activeKuota = $activeWave?->kuota ?? 0;
    $activeFill = $activeKuota > 0 ? min(100, (int) round(($activeWave->pendaftar_count ?? 0) / $activeKuota * 100)) : 0;

    $statusRows = [
        ['key' => 'menunggu_verifikasi', 'label' => 'Menunggu Verifikasi', 'total' => $totalMenunggu, 'tone' => 'bg-brand-rose'],
        ['key' => 'terverifikasi', 'label' => 'Terverifikasi', 'total' => $totalTerverifikasi, 'tone' => 'bg-brand-warmred'],
        ['key' => 'lulus_seleksi', 'label' => 'Lulus Seleksi', 'total' => $totalLulus, 'tone' => 'bg-brand-darkred'],
        ['key' => 'tidak_lulus', 'label' => 'Tidak Lulus', 'total' => $totalTidakLulus, 'tone' => 'bg-brand-ink/20'],
    ];

    $kpis = [
        ['label' => 'Total Pendaftar', 'value' => $totalPendaftar, 'icon' => 'users', 'note' => '+' . $todayCount . ' hari ini', 'href' => route('ppdb.dashboard.pendaftar')],
        ['label' => 'Menunggu Verifikasi', 'value' => $totalMenunggu, 'icon' => 'hourglass', 'note' => 'Berkas perlu dicek panitia', 'href' => route('ppdb.dashboard.pendaftar', ['status' => 'menunggu_verifikasi']), 'alert' => $totalMenunggu > 0],
        ['label' => 'Terverifikasi', 'value' => $totalTerverifikasi, 'icon' => 'shield-check', 'note' => $verifiedRate . '% berkas sudah diproses', 'href' => route('ppdb.dashboard.pendaftar', ['status' => 'terverifikasi'])],
        ['label' => 'Lulus Seleksi', 'value' => $totalLulus, 'icon' => 'award', 'note' => 'Siap daftar ulang', 'href' => route('ppdb.dashboard.pendaftar', ['status' => 'lulus_seleksi'])],
    ];

    $trendMax = max(1, collect($trend)->max('total'));
    $trendTotal = collect($trend)->sum('total');
    $genderL = (int) ($genderCounts['L'] ?? 0);
    $genderP = (int) ($genderCounts['P'] ?? 0);

    $card = 'bg-white rounded-card border border-brand-ink/10 shadow-[0_4px_20px_-4px_rgb(36_16_18_/_0.08)]';
    $tabs = [
        ['label' => 'Overview', 'route' => 'ppdb.dashboard'],
        ['label' => 'Data Pendaftar', 'route' => 'ppdb.dashboard.pendaftar'],
        ['label' => 'Gelombang', 'route' => 'ppdb.dashboard.gelombang'],
        ['label' => 'Pengumuman', 'route' => 'ppdb.dashboard.pengumuman'],
        ['label' => 'Biaya & Akomodasi', 'route' => 'ppdb.dashboard.akomodasi'],
    ];
@endphp

@section('content')
<div class="space-y-8">

    {{-- SUB NAVIGASI --}}
    <nav class="flex flex-wrap items-center justify-between gap-3 print:hidden" aria-label="Sub menu PPDB">
        <div class="flex flex-wrap gap-1.5">
            @foreach($tabs as $tab)
                <a href="{{ route($tab['route']) }}" class="px-4 h-9 inline-flex items-center rounded-full text-xs font-semibold transition-colors {{ request()->routeIs($tab['route']) ? 'bg-brand-ink text-white' : 'bg-white border border-brand-ink/10 text-brand-ink hover:bg-brand-softmist' }}">{{ $tab['label'] }}</a>
            @endforeach
        </div>
        <div class="flex gap-2">
            <a href="{{ route('ppdb.dashboard.pendaftar.export') }}" class="h-9 px-4 rounded-full bg-white border border-brand-ink/15 text-xs font-semibold text-brand-ink hover:bg-brand-softmist inline-flex items-center gap-1.5 transition-colors">
                <i data-lucide="download" class="w-3.5 h-3.5 text-brand-darkred"></i> Export CSV
            </a>
            <button type="button" onclick="window.print()" class="h-9 px-4 rounded-full bg-white border border-brand-ink/15 text-xs font-semibold text-brand-ink hover:bg-brand-softmist inline-flex items-center gap-1.5 transition-colors cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5 text-brand-darkred"></i> Cetak Rekap
            </button>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="{{ $card }} relative p-6 sm:p-8 overflow-hidden">
        <div class="absolute -right-20 -top-24 w-72 h-72 rounded-full bg-brand-darkred/[0.04] pointer-events-none"></div>
        <div class="relative grid lg:grid-cols-[1fr_340px] gap-8 items-center">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-brand-darkred">Panitia PPDB · T.A {{ $activeWave?->tahun_ajaran ?? '2027/2028' }}</p>
                <h1 class="mt-3 font-display text-3xl sm:text-4xl lg:text-[44px] font-bold uppercase tracking-wide text-brand-ink leading-[1.1]">
                    {{ $greet }}, <x-sketch.underline size="md">{{ $firstName }}</x-sketch.underline>
                </h1>
                <p class="mt-5 text-sm sm:text-[15px] text-brand-ink/70 leading-relaxed max-w-2xl">
                    Sudah ada <b class="text-brand-ink">{{ number_format($totalPendaftar) }} calon siswa</b> yang mendaftar
                    @if($todayCount > 0)(<b class="text-brand-ink">{{ $todayCount }}</b> hari ini)@endif.
                    @if($totalMenunggu > 0)
                        <b class="text-brand-darkred">{{ $totalMenunggu }} berkas</b> masih menunggu verifikasi panitia.
                    @else
                        Semua berkas sudah diverifikasi.
                    @endif
                </p>
                <div class="mt-6 flex flex-wrap gap-2.5">
                    <a href="{{ route('ppdb.dashboard.pendaftar', ['status' => 'menunggu_verifikasi']) }}" class="inline-flex items-center gap-2 h-10 px-5 rounded-full bg-gradient-to-r from-brand-signal to-brand-darkred text-white text-sm font-semibold shadow-[0_4px_14px_0_rgba(122,16,24,0.25)] hover:-translate-y-px transition-transform">
                        Verifikasi Berkas <x-sketch.arrow class="w-6 h-3" />
                    </a>
                    <a href="{{ route('ppdb.dashboard.pengumuman') }}" class="inline-flex items-center gap-2 h-10 px-5 rounded-full border border-brand-ink/15 bg-white text-brand-ink text-sm font-semibold hover:bg-brand-softmist transition-colors">
                        <i data-lucide="megaphone" class="w-4 h-4"></i> Buat Pengumuman
                    </a>
                </div>
            </div>

            {{-- Gelombang aktif --}}
            <div class="relative rounded-card bg-[#F5F4F2] p-5">
                <x-sketch.corner class="-left-2 -top-2 w-20 h-8" />
                <x-sketch.corner :delay="350" class="-right-2 -bottom-2 rotate-180 w-20 h-8" />
                <div class="flex items-center justify-between gap-2">
                    <h2 class="font-display font-bold text-lg uppercase tracking-wide text-brand-ink">Gelombang Aktif</h2>
                    @if($activeWave)
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Dibuka</span>
                    @endif
                </div>
                @if($activeWave)
                    <p class="mt-1 text-sm font-semibold text-brand-ink">{{ $activeWave->nama }}</p>
                    <p class="text-xs text-brand-ink/60">{{ $activeWave->periode_formatted }}</p>
                    <div class="mt-4 flex items-end justify-between">
                        <span class="font-display text-4xl font-bold text-brand-ink leading-none">{{ $activeWave->pendaftar_count }}</span>
                        <span class="text-xs text-brand-ink/60">dari kuota {{ number_format($activeKuota) }}</span>
                    </div>
                    <div class="mt-2 h-2.5 rounded-full bg-white overflow-hidden">
                        <div class="h-full rounded-full bg-brand-darkred" style="width: {{ $activeFill }}%"></div>
                    </div>
                    <p class="mt-1.5 text-[11px] text-brand-ink/50">{{ $activeFill }}% kuota terisi · formulir {{ $activeWave->biaya_formulir_formatted }}</p>
                @else
                    <p class="mt-2 text-sm text-brand-ink/70">Belum ada gelombang yang aktif. Formulir publik tidak bisa dipakai sampai satu gelombang diaktifkan.</p>
                    <a href="{{ route('ppdb.dashboard.gelombang') }}" class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-brand-darkred hover:underline">Atur gelombang <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
                @endif
            </div>
        </div>
    </section>

    {{-- KPI --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4" aria-label="Ringkasan angka PPDB">
        @foreach($kpis as $kpi)
            <a href="{{ $kpi['href'] }}" class="{{ $card }} group p-5 hover:border-brand-darkred/25 hover:shadow-softpill transition-all {{ !empty($kpi['alert']) ? 'ring-1 ring-brand-darkred/20' : '' }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider {{ !empty($kpi['alert']) ? 'text-brand-darkred' : 'text-brand-ink/60' }}">{{ $kpi['label'] }}</span>
                    <span class="w-9 h-9 rounded-full grid place-items-center transition-colors {{ !empty($kpi['alert']) ? 'bg-brand-darkred text-white' : 'bg-brand-darkred/[0.07] text-brand-darkred group-hover:bg-brand-darkred group-hover:text-white' }}">
                        <i data-lucide="{{ $kpi['icon'] }}" class="w-4 h-4"></i>
                    </span>
                </div>
                <div class="mt-3 font-display text-5xl font-bold text-brand-ink leading-none">{{ number_format($kpi['value']) }}</div>
                <div class="mt-3 pt-3 border-t border-brand-ink/10 text-xs text-brand-ink/60">{{ $kpi['note'] }}</div>
            </a>
        @endforeach
    </section>

    {{-- TREN + STATUS --}}
    <section class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="{{ $card }} xl:col-span-7 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="font-display font-bold text-xl uppercase tracking-wide text-brand-ink"><x-sketch.underline size="sm">Pendaftar 14 Hari Terakhir</x-sketch.underline></h2>
                    <p class="text-xs text-brand-ink/60 mt-3">Jumlah formulir masuk per hari.</p>
                </div>
                <span class="font-display font-bold text-2xl text-brand-ink">{{ $trendTotal }}</span>
            </div>
            <div class="mt-6 h-40 flex items-end gap-1.5" role="img" aria-label="Grafik batang pendaftar harian 14 hari terakhir">
                @foreach($trend as $day)
                    <div class="group relative flex-1 h-full flex flex-col justify-end items-center">
                        <span class="absolute -top-6 text-[11px] font-bold text-brand-ink opacity-0 group-hover:opacity-100 transition-opacity">{{ $day['total'] }}</span>
                        <div class="w-full max-w-[28px] rounded-t-md {{ $day['date']->isToday() ? 'bg-brand-darkred' : 'bg-brand-rose/60 group-hover:bg-brand-rose' }} transition-colors" style="height: {{ $day['total'] > 0 ? max(4, $day['total'] / $trendMax * 100) : 2 }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="mt-2 flex gap-1.5 text-[10px] text-brand-ink/50">
                @foreach($trend as $day)
                    <span class="flex-1 text-center">{{ $loop->first || $loop->last || $loop->index % 3 === 0 ? $day['date']->format('d/m') : '' }}</span>
                @endforeach
            </div>
        </div>

        <div class="{{ $card }} xl:col-span-5 p-6">
            <h2 class="font-display font-bold text-xl uppercase tracking-wide text-brand-ink"><x-sketch.underline size="sm">Status Seleksi</x-sketch.underline></h2>
            <div class="mt-6 flex h-3 rounded-full overflow-hidden bg-[#F5F4F2]" role="img" aria-label="Komposisi status seleksi">
                @foreach($statusRows as $row)
                    @if($row['total'] > 0)
                        <div class="{{ $row['tone'] }} h-full border-r-2 border-white last:border-r-0" style="width: {{ $row['total'] / max(1, $totalPendaftar) * 100 }}%"></div>
                    @endif
                @endforeach
            </div>
            <ul class="mt-5 space-y-2.5">
                @foreach($statusRows as $row)
                    <li>
                        <a href="{{ route('ppdb.dashboard.pendaftar', ['status' => $row['key']]) }}" class="flex items-center gap-3 text-sm rounded-lg px-2 py-1 -mx-2 hover:bg-[#F5F4F2] transition-colors">
                            <span class="w-2.5 h-2.5 rounded-full {{ $row['tone'] }}"></span>
                            <span class="flex-1 text-brand-ink/80">{{ $row['label'] }}</span>
                            <span class="font-display font-bold text-base text-brand-ink">{{ $row['total'] }}</span>
                            <span class="w-10 text-right text-xs text-brand-ink/50">{{ $totalPendaftar > 0 ? round($row['total'] / $totalPendaftar * 100) : 0 }}%</span>
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="mt-5 pt-4 border-t border-brand-ink/10 grid grid-cols-2 gap-3 text-xs">
                <div class="rounded-xl bg-[#F5F4F2] p-3">
                    <span class="text-brand-ink/60">Laki-laki</span>
                    <span class="block font-display font-bold text-2xl text-brand-ink">{{ $genderL }}</span>
                </div>
                <div class="rounded-xl bg-[#F5F4F2] p-3">
                    <span class="text-brand-ink/60">Perempuan</span>
                    <span class="block font-display font-bold text-2xl text-brand-ink">{{ $genderP }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- PENDAFTAR TERBARU + JURUSAN --}}
    <section class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="{{ $card }} xl:col-span-7 overflow-hidden flex flex-col">
            <div class="px-6 pt-6 pb-4 flex items-end justify-between gap-4">
                <h2 class="font-display font-bold text-xl uppercase tracking-wide text-brand-ink"><x-sketch.underline size="sm">Pendaftar Terbaru</x-sketch.underline></h2>
                <a href="{{ route('ppdb.dashboard.pendaftar') }}" class="group inline-flex items-center gap-1.5 text-xs font-semibold text-brand-darkred">
                    Semua ({{ number_format($totalPendaftar) }}) <x-sketch.arrow class="w-5 h-2.5 group-hover:translate-x-0.5 transition-transform" />
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[560px]">
                    <thead>
                        <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-brand-ink/50 border-y border-brand-ink/10 bg-[#F5F4F2]/60">
                            <th class="px-6 py-2.5">Calon Siswa</th>
                            <th class="px-3 py-2.5">Jurusan</th>
                            <th class="px-3 py-2.5">Status</th>
                            <th class="px-6 py-2.5 text-right"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-ink/5">
                        @forelse($recentPendaftar as $student)
                            @php $badge = $student->status_badge; @endphp
                            <tr class="hover:bg-[#F5F4F2]/50">
                                <td class="px-6 py-3">
                                    <div class="font-semibold text-brand-ink truncate max-w-[220px]">{{ $student->nama_lengkap }}</div>
                                    <div class="text-xs text-brand-ink/60"><span class="tabular-nums text-brand-darkred">{{ $student->nomor_registrasi }}</span> · {{ \Illuminate\Support\Str::limit($student->asal_sekolah, 24) }}</div>
                                </td>
                                <td class="px-3 py-3 text-xs font-semibold text-brand-ink whitespace-nowrap">{{ $majors[$student->jurusan] ?? $student->jurusan }}</td>
                                <td class="px-3 py-3">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold border whitespace-nowrap {{ $badge['bg'] }}">{{ $badge['label'] }}</span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('ppdb.dashboard.pendaftar.detail', $student->id) }}" class="inline-flex items-center gap-1 h-8 px-3 rounded-full text-xs font-semibold bg-brand-softmist text-brand-ink hover:bg-brand-ink hover:text-white transition-colors">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-sm text-brand-ink/50">Belum ada calon siswa yang mendaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="{{ $card }} xl:col-span-5 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="font-display font-bold text-xl uppercase tracking-wide text-brand-ink"><x-sketch.underline size="sm">Peminat Jurusan</x-sketch.underline></h2>
                    <p class="text-xs text-brand-ink/60 mt-3">Kuota {{ $kuotaPerJurusan }} kursi per jurusan.</p>
                </div>
                <a href="{{ route('ppdb.akomodasi') }}" target="_blank" class="text-xs font-semibold text-brand-darkred hover:underline shrink-0">Info biaya</a>
            </div>
            <ul class="mt-5 space-y-4">
                @foreach($jurusanCounts as $fullName => $count)
                    @php
                        $code = $majors[$fullName] ?? '—';
                        $fill = min(100, (int) round($count / $kuotaPerJurusan * 100));
                    @endphp
                    <li>
                        <a href="{{ $fullName !== 'Lainnya' ? route('ppdb.dashboard.pendaftar', ['jurusan' => $fullName]) : route('ppdb.dashboard.pendaftar') }}" class="block group">
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="flex items-center gap-2 min-w-0">
                                    <span class="w-10 shrink-0 font-display font-bold text-sm text-brand-darkred">{{ $code }}</span>
                                    <span class="text-brand-ink/80 truncate group-hover:text-brand-ink">{{ $fullName }}</span>
                                </span>
                                <span class="shrink-0"><b class="font-display font-bold text-base text-brand-ink">{{ $count }}</b><span class="text-xs text-brand-ink/50"> / {{ $kuotaPerJurusan }}</span></span>
                            </div>
                            <div class="mt-1.5 h-2 rounded-full bg-[#F5F4F2] overflow-hidden">
                                <div class="h-full rounded-full {{ $fill >= 100 ? 'bg-brand-ink' : 'bg-brand-darkred' }}" style="width: {{ $fill }}%"></div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- GELOMBANG + ASAL SEKOLAH --}}
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="{{ $card }} p-6">
            <div class="flex items-end justify-between gap-4">
                <h2 class="font-display font-bold text-xl uppercase tracking-wide text-brand-ink"><x-sketch.underline size="sm">Gelombang</x-sketch.underline></h2>
                <a href="{{ route('ppdb.dashboard.gelombang') }}" class="text-xs font-semibold text-brand-darkred hover:underline">Kelola</a>
            </div>
            <ul class="mt-5 space-y-4">
                @forelse($waves as $wave)
                    @php $fill = $wave->kuota > 0 ? min(100, (int) round($wave->pendaftar_count / $wave->kuota * 100)) : 0; @endphp
                    <li>
                        <div class="flex items-center justify-between gap-2 text-sm">
                            <span class="font-semibold text-brand-ink flex items-center gap-2">
                                {{ $wave->nama }}
                                @if($wave->is_active)<span class="px-1.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase">Aktif</span>@endif
                            </span>
                            <span class="text-xs text-brand-ink/60">{{ $wave->pendaftar_count }} / {{ $wave->kuota }}</span>
                        </div>
                        <div class="mt-1.5 h-2 rounded-full bg-[#F5F4F2] overflow-hidden">
                            <div class="h-full rounded-full {{ $wave->is_active ? 'bg-brand-darkred' : 'bg-brand-ink/25' }}" style="width: {{ $fill }}%"></div>
                        </div>
                        <p class="mt-1 text-[11px] text-brand-ink/50">{{ $wave->periode_formatted }}</p>
                    </li>
                @empty
                    <li class="text-sm text-brand-ink/50 text-center py-6">Belum ada gelombang.</li>
                @endforelse
            </ul>
        </div>

        <div class="{{ $card }} p-6">
            <h2 class="font-display font-bold text-xl uppercase tracking-wide text-brand-ink"><x-sketch.underline size="sm">Asal Sekolah Teratas</x-sketch.underline></h2>
            <ol class="mt-5 space-y-2.5">
                @forelse($topSekolah as $sekolah => $count)
                    <li class="flex items-center gap-3 text-sm">
                        <span class="w-7 h-7 rounded-full grid place-items-center font-display font-bold text-xs {{ $loop->first ? 'bg-brand-darkred text-white' : 'bg-brand-softmist text-brand-ink' }}">{{ $loop->iteration }}</span>
                        <span class="flex-1 text-brand-ink/80 truncate">{{ $sekolah }}</span>
                        <span class="font-display font-bold text-base text-brand-ink">{{ $count }}</span>
                    </li>
                @empty
                    <li class="text-sm text-brand-ink/50 text-center py-6">Belum ada data.</li>
                @endforelse
            </ol>
            <p class="mt-5 pt-4 border-t border-brand-ink/10 text-xs text-brand-ink/60">{{ $totalPengumuman }} pengumuman diterbitkan · <a href="{{ route('ppdb.dashboard.pengumuman') }}" class="font-semibold text-brand-darkred hover:underline">kelola</a></p>
        </div>
    </section>
</div>
@endsection
