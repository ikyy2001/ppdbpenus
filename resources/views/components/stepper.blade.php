{{-- Timeline langkah pendaftaran. Dipakai di dalam scope Alpine ppdbForm() (steps, currentStep, goToStep).
     Langkah yang sudah dicapai diberi garis coretan yang "digambar" saat langkah itu tercapai. --}}
<div class="rounded-card bg-brand-softmist px-5 py-4 sm:px-6 sm:py-5">
    <ol class="grid grid-cols-4 gap-3 sm:gap-5" aria-label="Langkah pendaftaran">
        <template x-for="step in steps" :key="step.n">
            <li>
                <button type="button" @click="goToStep(step.n)"
                        class="group block w-full text-left rounded-lg focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand-darkred"
                        :aria-current="currentStep === step.n ? 'step' : null"
                        :aria-label="'Langkah ' + step.n + ': ' + step.label">
                    {{-- Jalur tipis = belum dicapai, coretan tebal di atasnya = sudah dicapai --}}
                    <span class="relative block h-3">
                        <span aria-hidden="true" class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-brand-ink/15"></span>
                        <template x-if="currentStep >= step.n">
                            <x-sketch.rule bold class="text-brand-darkred inset-x-0 top-0 h-3" />
                        </template>
                    </span>
                    <span class="mt-2 flex items-baseline gap-2">
                        <span class="font-display text-lg font-bold tracking-wide transition-colors"
                              :class="currentStep >= step.n ? 'text-brand-darkred' : 'text-brand-ink/30 group-hover:text-brand-ink/60'"
                              x-text="String(step.n).padStart(2, '0')"></span>
                        <span class="hidden sm:inline text-sm font-semibold leading-snug transition-colors"
                              :class="currentStep === step.n ? 'text-brand-ink' : 'text-brand-ink/50 group-hover:text-brand-darkred'"
                              x-text="step.label"></span>
                    </span>
                </button>
            </li>
        </template>
    </ol>

    {{-- Di HP label langkah disembunyikan, jadi langkah aktif ditulis di bawah --}}
    <p class="sm:hidden mt-3 text-sm font-semibold text-brand-ink"
       x-text="'Langkah ' + currentStep + ' dari ' + steps.length + ' · ' + steps[currentStep - 1].label"></p>
</div>
