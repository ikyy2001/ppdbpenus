{{-- Footer publik PPDB, port dari lp/lpfe/src/components/Footer.tsx. Isi kontak diambil dari pengaturan PPDB (AppServiceProvider). --}}
@php
    $mainLinks = [
        ['label' => 'Formulir PPDB Online', 'href' => url('/ppdb')],
        ['label' => 'Info Biaya', 'href' => url('/ppdb/akomodasi')],
        ['label' => 'Daftar Pengumuman Seleksi', 'href' => url('/ppdb/pengumuman')],
        ['label' => 'Cek Status Pendaftar (NISN)', 'href' => url('/ppdb/cek-status')],
        ['label' => 'Profil Sekolah Resmi', 'href' => 'https://smkpluspnb.sch.id', 'external' => true],
    ];

    $socials = [
        ['label' => 'Website Resmi', 'href' => 'https://smkpluspnb.sch.id', 'icon' => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>'],
        ['label' => 'Instagram', 'href' => 'https://instagram.com/smkpelitanusantara', 'icon' => '<rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/>'],
        ['label' => 'Facebook', 'href' => 'https://facebook.com/smkpelitanusantara', 'icon' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>'],
        ['label' => 'WhatsApp', 'href' => 'https://wa.me/'.$kontakWa, 'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>'],
        ['label' => 'YouTube', 'href' => 'https://youtube.com/@smkpelitanusantara', 'icon' => '<path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/>'],
    ];

    $ppdbInfo = [
        ['label' => 'Pendaftar Terdata', 'value' => number_format($sharedTotalPendaftar ?? 0, 0, ',', '.').' Calon Siswa'],
        ['label' => 'Gelombang Aktif', 'value' => $sharedActiveWave?->nama ?? 'Gelombang Utama'],
        ['label' => 'Tahun Ajaran', 'value' => $sharedActiveWave?->tahun_ajaran ?? '2027/2028'],
    ];
@endphp
<footer class="relative z-10 bg-white pt-20 sm:pt-28 text-left no-print">
    {{-- Banner ajakan daftar: teks di kiri, foto siswa di kanan (di HP & tablet di bawah teks) --}}
    <div class="px-6 mb-14">
        <div class="relative max-w-6xl mx-auto overflow-hidden rounded-4xl border border-white/10 bg-linear-135 from-brand-darkred to-brand-deepred p-8 sm:p-12 lg:p-14 text-white shadow-softpill">
            <div class="relative grid gap-8 lg:grid-cols-2 lg:gap-6">
                <div class="lg:self-center">
                    <p class="mb-3 text-xs sm:text-sm font-bold uppercase tracking-[0.25em] text-[#F5C2C7]">
                        Siap Menjadi Talenta Vokasi <x-sketch.sparks tone="text-[#F5C2C7]">Terbaik?</x-sketch.sparks>
                    </p>
                    <h2 class="font-display text-xl sm:text-2xl md:text-3xl lg:text-[2rem] font-bold uppercase tracking-wide leading-snug text-white drop-shadow-sm text-left">
                        Langkah Nyata Membangun Masa Depan Gemilang Bersama SMK Plus Pelita Nusantara.
                    </h2>

                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a
                            href="https://wa.me/{{ $kontakWa }}"
                            target="_blank"
                            rel="noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-linear-to-r from-brand-signal to-brand-darkred px-7 py-3 text-sm font-bold text-white shadow-md shadow-brand-darkred/25 transition-all duration-200 hover:from-brand-warmred hover:to-brand-deepred active:scale-95"
                        >
                            Hubungi CS PPDB
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </a>
                        <a
                            href="https://images.lekar.co.id/file/pelita/infografis_pelita_nusantara.pdf"
                            target="_blank"
                            rel="noreferrer"
                            class="group inline-flex items-center justify-center gap-2 rounded-full border-2 border-white/30 px-7 py-3 text-sm font-semibold text-white transition-all hover:border-white hover:bg-white/10 active:scale-95"
                        >
                            Unduh Brosur
                            <x-sketch.arrow class="w-7 h-3.5 transition-transform group-hover:translate-x-1" />
                        </a>
                    </div>
                </div>

                {{-- Margin negatif = padding banner, supaya foto menempel ke tepi bawah banner --}}
                <div class="relative self-end -mx-8 -mb-8 sm:-mx-12 sm:-mb-12 lg:ml-0 lg:-mr-14 lg:-mb-14">
                    <img
                        src="{{ asset('assets/TalentaVokasi.png') }}"
                        alt="Siswa SMK Plus Pelita Nusantara dari berbagai jurusan"
                        class="relative w-full aspect-1326/588 object-cover object-bottom lg:mask-[linear-gradient(to_right,transparent,black_12%)]"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-brand-ink/10 px-6 pt-16 pb-12 text-brand-ink">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 items-start">
            {{-- Identitas sekolah, kontak, media sosial --}}
            <div class="lg:col-span-4 space-y-4">
                <a href="/" class="group flex w-fit items-center gap-3">
                    <img src="{{ asset('assets/logosmkpenus.png') }}" alt="" class="h-12 w-12 shrink-0 object-contain transition-transform group-hover:scale-105" loading="lazy" />
                    <span class="flex flex-col">
                        <span class="font-display text-base font-bold uppercase tracking-wide leading-tight text-brand-ink transition-colors group-hover:text-brand-darkred">
                            SMK PLUS PELITA NUSANTARA
                        </span>
                        <span class="mt-0.5 text-[10px] uppercase font-semibold tracking-wider text-brand-darkred">
                            We Are Different
                        </span>
                    </span>
                </a>

                <p class="max-w-sm text-xs sm:text-[13px] leading-relaxed text-brand-ink/75">
                    Bersama SMK Plus Pelita Nusantara, jadilah generasi tangguh, berakhlak, dan berwawasan teknologi vokasi unggul.
                </p>

                <address class="space-y-2.5 pt-1 text-xs not-italic text-brand-ink/80">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 shrink-0 text-brand-signal" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <a href="mailto:{{ $emailCs }}" class="transition-colors hover:text-brand-darkred hover:underline">
                            <span class="sr-only">Email: </span>{{ $emailCs }}
                        </a>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 shrink-0 text-brand-signal" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        <a href="tel:{{ $kontakWa }}" class="transition-colors hover:text-brand-darkred hover:underline">
                            <span class="sr-only">Telepon: </span>{{ $kontakWaFormatted }} / {{ $teleponKantor }}
                        </a>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 shrink-0 text-brand-signal" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <p class="leading-snug"><span class="sr-only">Alamat: </span>{{ $alamatKampus }}</p>
                    </div>
                </address>

                <ul class="flex items-center gap-4 pt-2" aria-label="Media sosial">
                    @foreach ($socials as $social)
                        <li>
                            <a href="{{ $social['href'] }}" target="_blank" rel="noreferrer" aria-label="{{ $social['label'] }}" title="{{ $social['label'] }}" class="block text-brand-signal transition-colors hover:text-brand-darkred">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">{!! $social['icon'] !!}</svg>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <img
                    src="{{ asset('assets/logofooter.webp') }}"
                    alt="Partner & kolaborator: Jagoan Hosting Infra Competition, Jagoan Hosting, Komdigi, Maspion IT, Garuda Spark Innovation Hub"
                    width="3727"
                    height="592"
                    class="h-auto w-full max-w-xs opacity-90 transition-opacity hover:opacity-100"
                    loading="lazy"
                />

                <p class="pt-2 text-[11px] sm:text-xs font-medium text-brand-ink/60">
                    Copyright &copy; {{ date('Y') }} All right reserved | PENUS
                </p>
            </div>

            <div class="lg:col-span-2">
                <h2 class="font-display text-sm sm:text-base font-bold uppercase tracking-wide text-brand-ink">Menu Utama</h2>
                <ul class="mt-3.5 space-y-2 text-xs sm:text-[13px] font-medium text-brand-ink/75">
                    @foreach ($mainLinks as $link)
                        <li>
                            <a
                                href="{{ $link['href'] }}"
                                @if (! empty($link['external'])) target="_blank" rel="noreferrer" @endif
                                class="transition-colors hover:text-brand-darkred"
                            >{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h2 class="font-display text-sm sm:text-base font-bold uppercase tracking-wide text-brand-ink">Informasi PPDB Terkini</h2>
                <dl class="mt-3.5 space-y-1.5 text-xs sm:text-[13px] text-brand-ink/75">
                    @foreach ($ppdbInfo as $info)
                        <div class="flex gap-1">
                            <dt>{{ $info['label'] }}:</dt>
                            <dd class="font-medium text-brand-ink">{{ $info['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="lg:col-span-3">
                <h2 class="font-display text-sm sm:text-base font-bold uppercase tracking-wide text-brand-ink">Lokasi Sekolah</h2>

                <div class="relative mt-3 h-55 w-full overflow-hidden rounded-card border border-brand-ink/15 shadow-sm">
                    <a
                        href="https://maps.google.com/?q=SMK+Plus+Pelita+Nusantara+Cibinong+Bogor"
                        target="_blank"
                        rel="noreferrer"
                        class="absolute top-2.5 left-2.5 z-10 inline-flex items-center gap-1.5 rounded-md border border-brand-ink/10 bg-white px-3 py-1.5 text-xs font-semibold text-brand-darkred shadow-sm transition-all hover:bg-brand-softmist hover:shadow-md"
                    >
                        Buka di Maps
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>

                    <iframe
                        title="Peta Lokasi SMK Plus Pelita Nusantara"
                        src="https://maps.google.com/maps?q=SMK+Plus+Pelita+Nusantara+Cibinong+Bogor&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        class="h-full w-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>

                <p class="mt-3 text-[11px] leading-tight text-brand-ink/60">
                    Lokasi strategis dekat pusat pemerintahan Cibinong, Kabupaten Bogor.
                </p>
            </div>
        </div>
    </div>
</footer>
