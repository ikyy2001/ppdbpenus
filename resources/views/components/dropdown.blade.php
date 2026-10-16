{{-- Pengganti tampilan <select> bawaan browser (daftar pilihannya tidak bisa diberi gaya). Bungkus <select> biasa:
       <x-dropdown><select x-model="..." class="ppdb-input appearance-none pr-11">...</select></x-dropdown>
     <select>-nya tetap ada (tersembunyi) sebagai sumber pilihan & nilai, jadi x-model, @change, @blur, :class error,
     <label for> dan el.focus() tetap jalan. Tombol pemicu menyalin class <select>.
     columns > 1 = pilihan berbentuk grid (cocok untuk tanggal, bulan, tahun). Logika: partials/dropdown-engine. --}}
@props(['columns' => 1])
<div x-data="ppdbDropdown({{ (int) $columns }})" x-id="['dd-list', 'dd-opt']"
     @click.outside="ddOpen = false" @focusout="ddFocusOut($event)"
     {{ $attributes->class('relative') }}>
    <div class="sr-only">{{ $slot }}</div>

    <button type="button" x-ref="button" role="combobox" aria-haspopup="listbox"
            :aria-expanded="ddOpen" :aria-controls="$id('dd-list')"
            :aria-activedescendant="ddOpen && ddActive >= 0 ? $id('dd-opt') + '-' + ddActive : null"
            @click="ddOpen ? ddHide() : ddShow()" @keydown="ddKey($event)">
        <span x-text="ddLabel"></span>
    </button>

    <svg class="pointer-events-none absolute right-3 sm:right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-brand-ink/50 transition-transform duration-200"
         :class="ddOpen && 'rotate-180 text-brand-darkred'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>

    <div x-ref="panel" x-show="ddOpen" style="display: none;"
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="absolute z-40 max-w-[calc(100vw-2rem)] rounded-card bg-white p-1.5 shadow-softpill ring-1 ring-brand-ink/10"
         :class="[
             ddUp ? 'bottom-full mb-2 origin-bottom' : 'top-full mt-2 origin-top',
             ddAlignRight ? 'right-0' : 'left-0',
             ddColumns > 1 ? 'w-72' : 'w-max min-w-full'
         ]">
        <p x-show="ddColumns > 1" class="eyebrow px-2.5 pt-2 pb-2.5" x-text="'Pilih ' + ddName"></p>

        <ul x-ref="list" role="listbox" :id="$id('dd-list')" :aria-label="ddName"
            class="dd-scroll relative max-h-72 overflow-y-auto overscroll-contain"
            :class="ddColumns > 1 && 'grid gap-1'"
            :style="ddColumns > 1 && `grid-template-columns: repeat(${ddColumns}, minmax(0, 1fr))`">
            <template x-for="(opt, i) in ddOptions" :key="i + '|' + opt.value">
                <li role="option" :id="$id('dd-opt') + '-' + i" :data-index="i"
                    :aria-selected="opt.value === ddValue" :aria-disabled="opt.disabled"
                    @mousedown.prevent @click="ddChoose(i)" @mousemove="ddActive = i"
                    class="cursor-pointer select-none text-sm transition-colors duration-100"
                    :class="[
                        ddColumns > 1
                            ? 'rounded-lg py-2 text-center tabular-nums'
                            : 'flex items-center justify-between gap-4 rounded-xl px-3.5 py-2.5',
                        opt.disabled ? 'cursor-not-allowed opacity-40' : '',
                        opt.value === ddValue
                            ? (ddColumns > 1 ? 'bg-brand-darkred font-semibold text-white' : (i === ddActive ? 'bg-brand-darkred/15' : 'bg-brand-darkred/10') + ' font-semibold text-brand-darkred')
                            : (i === ddActive ? 'bg-brand-softmist text-brand-ink' : 'text-brand-ink/80'),
                        opt.value === ddValue && i === ddActive && ddColumns > 1 ? 'ring-2 ring-brand-signal ring-offset-1' : ''
                    ]">
                    <span x-text="opt.label"></span>
                    {{-- Centang bergaya coretan tangan untuk pilihan aktif --}}
                    <svg x-show="ddColumns === 1 && opt.value === ddValue" class="h-4 w-5 shrink-0" viewBox="0 0 24 18" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2.5 9.8c1.6 1.1 3.4 2.9 5.2 5.4C11 9.6 15.4 5 21.6 2.2" />
                    </svg>
                </li>
            </template>
        </ul>
    </div>
</div>
