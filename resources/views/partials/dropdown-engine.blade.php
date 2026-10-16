{{-- Logika komponen <x-dropdown>. Didaftarkan sebelum Alpine (CDN, defer) berjalan. Semua properti diawali "dd"
     supaya tidak menutupi data milik halaman (formData, errors, dsb.) yang dipakai <select> di dalamnya. --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ppdbDropdown', (columns = 1) => {
            // Elemen DOM disimpan di closure, bukan di data reaktif (proxy Alpine merusak method DOM)
            let select;
            let button;
            let typed = '';
            let typedAt = 0;

            return {
                ddOpen: false,
                ddOptions: [],
                ddValue: '',
                ddPlaceholder: 'Pilih',
                ddName: '',
                ddActive: -1,
                ddUp: false,
                ddAlignRight: false,
                ddColumns: columns,

                init() {
                    select = this.$el.querySelector('select');
                    button = this.$refs.button;
                    if (!select) return;

                    select.tabIndex = -1;
                    select.addEventListener('focus', () => button.focus());
                    select.addEventListener('change', () => this.ddSync());
                    new MutationObserver(() => this.ddMirror()).observe(select, { attributes: true, attributeFilter: ['class'] });

                    this.ddName = (select.labels?.[0]?.textContent || select.getAttribute('aria-label') || '').replace('*', '').trim();
                    button.setAttribute('aria-label', this.ddName);

                    const expr = select.getAttribute('x-model');
                    if (expr) this.$watch(expr, () => this.$nextTick(() => this.ddSync()));

                    // Opsi dari x-for & nilai x-model baru terpasang setelah anak-anaknya diinisialisasi
                    this.$nextTick(() => this.ddSync());
                },

                get ddLabel() {
                    const opt = this.ddOptions.find(o => o.value === this.ddValue);
                    return opt ? opt.label : this.ddPlaceholder;
                },

                ddSync() {
                    const expr = select.getAttribute('x-model');
                    const value = expr ? Alpine.evaluate(select, expr) : select.value;
                    this.ddValue = value == null ? '' : String(value);

                    const placeholder = [...select.options].find(o => o.value === '' && o.disabled);
                    if (placeholder) this.ddPlaceholder = placeholder.textContent.trim();
                    this.ddOptions = [...select.options]
                        .filter(o => !(o.value === '' && o.disabled))
                        .map(o => ({ value: o.value, label: o.textContent.trim(), disabled: o.disabled }));

                    this.ddMirror();
                },

                // Tombol berpenampilan sama persis dengan <select> aslinya, termasuk kelas error yang berubah-ubah
                ddMirror() {
                    const empty = !this.ddOptions.some(o => o.value === this.ddValue);
                    button.className = select.className.replace(/\bappearance-none\b/g, '')
                        + ' block text-left truncate cursor-pointer' + (empty ? ' text-brand-ink/40' : '');
                },

                ddShow(active) {
                    this.ddSync();
                    const selected = this.ddOptions.findIndex(o => o.value === this.ddValue);
                    this.ddActive = active ?? (selected >= 0 ? selected : this.ddNext(-1, 1));
                    this.ddOpen = true;

                    this.$nextTick(() => {
                        const rect = button.getBoundingClientRect();
                        const panel = this.$refs.panel;
                        const below = window.innerHeight - rect.bottom - 12;
                        this.ddUp = panel.offsetHeight > below && rect.top - 12 > below;
                        this.ddAlignRight = rect.left + panel.offsetWidth > document.documentElement.clientWidth - 16;
                        this.ddScrollTo(this.ddActive, true);
                    });
                },

                ddHide() {
                    this.ddOpen = false;
                },

                ddFocusOut(event) {
                    if (this.$el.contains(event.relatedTarget)) return;
                    this.ddOpen = false;
                    // Teruskan sebagai blur <select> supaya @blur="handleBlur(..)" milik halaman tetap terpanggil
                    select?.dispatchEvent(new Event('blur'));
                },

                ddChoose(i) {
                    const opt = this.ddOptions[i];
                    if (!opt || opt.disabled) return;
                    select.value = opt.value;
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    this.ddValue = opt.value;
                    this.ddOpen = false;
                    button.focus();
                },

                // Indeks opsi aktif berikutnya yang tidak disabled, melangkah "step" dari "from"
                ddNext(from, step) {
                    let i = from + step;
                    while (i >= 0 && i < this.ddOptions.length) {
                        if (!this.ddOptions[i].disabled) return i;
                        i += step;
                    }
                    return from;
                },

                ddScrollTo(i, center = false) {
                    const list = this.$refs.list;
                    const el = list?.querySelector(`[data-index="${i}"]`);
                    if (!el) return;
                    if (center) {
                        list.scrollTop = el.offsetTop - (list.clientHeight - el.offsetHeight) / 2;
                    } else if (el.offsetTop < list.scrollTop) {
                        list.scrollTop = el.offsetTop - 6;
                    } else if (el.offsetTop + el.offsetHeight > list.scrollTop + list.clientHeight) {
                        list.scrollTop = el.offsetTop + el.offsetHeight - list.clientHeight + 6;
                    }
                },

                ddMove(i) {
                    this.ddActive = i;
                    this.ddScrollTo(i);
                },

                // Ketik untuk melompat: "1" "2" -> tanggal 12, "2" "0" "0" "9" -> tahun 2009, "me" -> Mei
                ddTypeAhead(char) {
                    const now = Date.now();
                    typed = now - typedAt > 700 ? char : typed + char;
                    typedAt = now;
                    const q = typed.toLowerCase();
                    const i = this.ddOptions.findIndex(o => !o.disabled && o.label.toLowerCase().startsWith(q));
                    if (i < 0) return;
                    if (this.ddOpen) this.ddMove(i);
                    else this.ddShow(i);
                },

                ddKey(e) {
                    const cols = this.ddColumns;
                    const last = this.ddOptions.length - 1;

                    if (e.key.length === 1 && e.key !== ' ' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                        e.preventDefault();
                        this.ddTypeAhead(e.key);
                        return;
                    }

                    if (!this.ddOpen) {
                        if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(e.key)) {
                            e.preventDefault();
                            this.ddShow();
                        }
                        return;
                    }

                    switch (e.key) {
                        case 'ArrowDown':
                            e.preventDefault();
                            this.ddMove(cols > 1 ? Math.min(this.ddActive + cols, last) : this.ddNext(this.ddActive, 1));
                            break;
                        case 'ArrowUp':
                            e.preventDefault();
                            this.ddMove(cols > 1 ? Math.max(this.ddActive - cols, 0) : this.ddNext(this.ddActive, -1));
                            break;
                        case 'ArrowRight':
                            if (cols > 1) { e.preventDefault(); this.ddMove(this.ddNext(this.ddActive, 1)); }
                            break;
                        case 'ArrowLeft':
                            if (cols > 1) { e.preventDefault(); this.ddMove(this.ddNext(this.ddActive, -1)); }
                            break;
                        case 'Home':
                            e.preventDefault();
                            this.ddMove(this.ddNext(-1, 1));
                            break;
                        case 'End':
                            e.preventDefault();
                            this.ddMove(this.ddNext(last + 1, -1));
                            break;
                        case 'Enter':
                        case ' ':
                            e.preventDefault();
                            this.ddChoose(this.ddActive);
                            break;
                        case 'Escape':
                            e.preventDefault();
                            e.stopPropagation();
                            this.ddOpen = false;
                            break;
                        case 'Tab':
                            this.ddOpen = false;
                            break;
                    }
                },
            };
        });
    });
</script>
