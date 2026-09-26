@props(['currentStep' => 1])

<div class="rounded-full bg-white/95 backdrop-blur-md shadow-softpill border border-brand-ink/10 px-5 py-2.5 sm:px-8 sm:py-3 inline-flex items-center gap-6 sm:gap-10 select-none overflow-x-auto">
    <!-- Step 1: Informasi Pendaftar -->
    <button 
        type="button" 
        @click="goToStep(1)"
        class="flex items-center gap-2.5 sm:gap-3 cursor-pointer group focus:outline-none">
        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center font-bold text-xs transition-colors duration-200"
             :class="currentStep === 1 || currentStep === 2 ? 'bg-brand-darkred text-brand-mist shadow-xs' : 'bg-brand-mist text-brand-ink/60'">
            1
        </div>
        <span class="text-xs sm:text-sm tracking-tight transition-colors duration-200 font-sans"
              :class="currentStep === 1 ? 'font-bold text-brand-darkred' : 'font-semibold text-brand-ink/80 group-hover:text-brand-darkred'">
            Informasi Pendaftar
        </span>
    </button>

    <!-- Step 2: Validasi Data -->
    <button 
        type="button" 
        @click="goToStep(2)"
        class="flex items-center gap-2.5 sm:gap-3 cursor-pointer group focus:outline-none">
        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center font-bold text-xs transition-colors duration-200"
             :class="currentStep === 2 ? 'bg-brand-darkred text-brand-mist shadow-xs' : 'bg-brand-mist text-brand-ink/50'">
            2
        </div>
        <span class="text-xs sm:text-sm tracking-tight transition-colors duration-200 font-sans"
              :class="currentStep === 2 ? 'font-bold text-brand-darkred' : 'font-semibold text-brand-ink/50 group-hover:text-brand-darkred'">
            Validasi Data
        </span>
    </button>
</div>
