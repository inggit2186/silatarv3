import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('siteNav', () => ({
        open: false,
        userMenuOpen: false,
        toggle() {
            this.open = !this.open;
        },
        toggleUserMenu() {
            this.userMenuOpen = !this.userMenuOpen;
        },
        closeUserMenu() {
            this.userMenuOpen = false;
        },
    }));

    // Stats Counter for Cyberpunk Theme
    Alpine.data('statsCounter', (config = {}) => ({
        target: config.target || 0,
        current: 0,
        suffix: config.suffix || '',
        duration: config.duration || 2000,
        animated: false,

        animate() {
            const startTime = performance.now();
            const animate = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / this.duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                this.current = Math.floor(easeOut * this.target);

                if (progress < 1) {
                    requestAnimationFrame(animate.bind(this));
                }
            };
            requestAnimationFrame(animate.bind(this));
        },

        init() {
            if (this.animated) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !this.animated) {
                        this.animate();
                        this.animated = true;
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.5 });

            observer.observe(this.$el);
        }
    }));

    Alpine.data('globalLoading', (config = {}) => ({
        active: false,
        enabled: !!window.silatarLoadingPageEnabled,
        title: config.title || 'Memuat data',
        message: config.message || 'Mohon tunggu sebentar.',
        submitLabel: config.submit || 'Mengirim permintaan...',
        navigateLabel: config.navigate || 'Membuka halaman...',
        hideDelay: Number(config.delay || 150),
        variants: config.variants || {},
        variant: 'navigate',
        showTimer: null,
        hideTimer: null,
        pendingStateKey: 'silatar-loading-pending',
        variantTone() {
            return this.variants?.[this.variant]?.tone || 'cyan';
        },
        get variantBarClass() {
            const toneMap = {
                cyan: 'bg-gradient-to-r from-cyan-500 via-sky-400 to-cyan-300',
                amber: 'bg-gradient-to-r from-amber-500 via-orange-400 to-rose-400',
                violet: 'bg-gradient-to-r from-violet-500 via-fuchsia-400 to-cyan-400',
            };

            return toneMap[this.variantTone()] || toneMap.cyan;
        },
        get variantToastClass() {
            const toneMap = {
                cyan: 'border-cyan-100 bg-white/90',
                amber: 'border-amber-100 bg-amber-50/90',
                violet: 'border-violet-100 bg-violet-50/90',
            };

            return toneMap[this.variantTone()] || toneMap.cyan;
        },
        get variantSpinnerClass() {
            const toneMap = {
                cyan: 'border-cyan-600',
                amber: 'border-amber-600',
                violet: 'border-violet-600',
            };

            return toneMap[this.variantTone()] || toneMap.cyan;
        },
        setPendingState(variant, payload = {}) {
            const state = {
                variant,
                title: payload.title || this.title,
                message: payload.message || this.message,
                timestamp: Date.now(),
            };

            sessionStorage.setItem(this.pendingStateKey, JSON.stringify(state));
        },
        consumePendingState() {
            const rawState = sessionStorage.getItem(this.pendingStateKey);

            if (!rawState) {
                return null;
            }

            sessionStorage.removeItem(this.pendingStateKey);

            try {
                return JSON.parse(rawState);
            } catch {
                return null;
            }
        },
        show(payload = {}, persist = true) {
            clearTimeout(this.showTimer);
            clearTimeout(this.hideTimer);

            this.variant = payload.variant || 'navigate';
            this.title = payload.title || this.variants?.[this.variant]?.title || this.title;
            this.message = payload.message || this.variants?.[this.variant]?.message || this.message;

            this.showTimer = setTimeout(() => {
                this.active = true;
            }, this.hideDelay);

            if (!persist) {
                this.hideTimer = setTimeout(() => {
                    this.active = false;
                }, this.hideDelay + 1100);
            }
        },
        hide() {
            clearTimeout(this.showTimer);
            clearTimeout(this.hideTimer);
            this.hideTimer = setTimeout(() => {
                this.active = false;
            }, 120);
        },
        trigger(variant, payload = {}, persist = true) {
            if (!this.enabled) {
                return;
            }

            if (persist) {
                this.setPendingState(variant, payload);
            }

            this.show({ variant, ...payload }, persist);
        },
        isInternalLink(anchor) {
            const href = anchor.getAttribute('href') || '';

            if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                return false;
            }

            if (anchor.target === '_blank' && anchor.dataset.loadingVariant !== 'print') {
                return false;
            }

            if (anchor.hasAttribute('download') || anchor.dataset.noGlobalLoading === 'true') {
                return false;
            }

            try {
                const url = new URL(anchor.href, window.location.href);

                if (url.origin !== window.location.origin) {
                    return false;
                }

                if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) {
                    return false;
                }

                return true;
            } catch {
                return false;
            }
        },
        init() {
            const pending = this.consumePendingState();

            if (!this.enabled) {
                return;
            }

            if (pending) {
                this.variant = pending.variant || 'navigate';
                this.title = pending.title || this.title;
                this.message = pending.message || this.message;
                this.active = true;

                window.addEventListener('load', () => {
                    this.hide();
                }, { once: true });
            }

            document.addEventListener('submit', (event) => {
                const form = event.target;

                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                if (form.dataset.noGlobalLoading === 'true') {
                    return;
                }

                const submitter = event.submitter;
                const variant = submitter?.dataset?.loadingVariant || form.dataset.loadingVariant || 'submit';
                const title = submitter?.dataset?.loadingTitle || form.dataset.loadingTitle || this.variants?.[variant]?.title || this.submitLabel;
                const message = submitter?.dataset?.loadingMessage || form.dataset.loadingMessage || this.variants?.[variant]?.message || this.message;

                const persist = variant !== 'print' || form.target !== '_blank';

                this.trigger(variant, { title, message }, persist);
            });

            document.addEventListener('click', (event) => {
                if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                    return;
                }

                const anchor = event.target.closest?.('a[href]');

                if (!anchor || !this.isInternalLink(anchor)) {
                    return;
                }

                const variant = anchor.dataset.loadingVariant || 'navigate';
                const title = anchor.dataset.loadingTitle || this.variants?.[variant]?.title || this.navigateLabel;
                const message = anchor.dataset.loadingMessage || this.variants?.[variant]?.message || this.message;

                const persist = variant !== 'print' || anchor.target !== '_blank';

                this.trigger(variant, { title, message }, persist);
            }, true);

            window.addEventListener('pageshow', () => {
                this.hide();
            });

            window.addEventListener('beforeunload', () => {
                if (this.active) {
                    return;
                }

                this.active = false;
            });
        },
    }));

    Alpine.data('silatarDatepicker', (config = {}) => ({
        open: false,
        value: config.value || '',
        placeholder: config.placeholder || 'Pilih tanggal',
        todayLabel: config.todayLabel || 'Hari ini',
        clearLabel: config.clearLabel || 'Hapus',
        locale: config.locale || 'en-US',
        months: Array.isArray(config.months) && config.months.length
            ? config.months
            : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        weekdays: Array.isArray(config.weekdays) && config.weekdays.length
            ? config.weekdays
            : ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
        min: config.min || '',
        max: config.max || '',
        monthCursor: null,
        popoverStyle: '',
        isDatepicker: true,
        monthNames() {
            return Array.isArray(this.months) ? this.months : [];
        },
        yearInputValue() {
            return this.monthCursor?.getFullYear?.() || new Date().getFullYear();
        },
        updatePlacement() {
            const trigger = this.$refs.trigger;

            if (!(trigger instanceof HTMLElement)) {
                return;
            }

            const rect = trigger.getBoundingClientRect();
            const width = 304;
            const height = 356;
            const margin = 12;
            const left = Math.min(
                Math.max(rect.left, margin),
                Math.max(margin, window.innerWidth - width - margin)
            );
            const shouldOpenAbove = window.innerHeight - rect.bottom < height + margin;
            const top = shouldOpenAbove
                ? Math.max(margin, rect.top - height - margin)
                : Math.min(window.innerHeight - height - margin, rect.bottom + margin);

            this.popoverStyle = `position:fixed;left:${Math.round(left)}px;top:${Math.round(top)}px;width:${width}px;z-index:110;`;
        },
        init() {
            this.monthCursor = this.value ? this.parseValue(this.value) : new Date();
            this.monthCursor = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth(), 1);
        },
        parseValue(value) {
            const [year, month, day] = String(value).split('-').map((part) => parseInt(part, 10));

            if (!year || !month || !day) {
                return new Date();
            }

            return new Date(year, month - 1, day, 12, 0, 0, 0);
        },
        pad(number) {
            return String(number).padStart(2, '0');
        },
        formatValue(date) {
            return [
                date.getFullYear(),
                this.pad(date.getMonth() + 1),
                this.pad(date.getDate()),
            ].join('-');
        },
        formatDisplay(value) {
            if (!value) {
                return this.placeholder;
            }

            return new Intl.DateTimeFormat(this.locale, {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            }).format(this.parseValue(value));
        },
        get monthLabel() {
            return new Intl.DateTimeFormat(this.locale, {
                month: 'long',
                year: 'numeric',
            }).format(this.monthCursor);
        },
        get currentMonthIndex() {
            return this.monthCursor.getMonth();
        },
        setMonth(index) {
            this.monthCursor = new Date(this.monthCursor.getFullYear(), index, 1);
            this.updatePlacement();
        },
        setYear(year) {
            const parsedYear = Number.parseInt(year, 10);

            if (Number.isNaN(parsedYear)) {
                return;
            }

            this.monthCursor = new Date(parsedYear, this.monthCursor.getMonth(), 1);
            this.updatePlacement();
        },
        get days() {
            const year = this.monthCursor.getFullYear();
            const month = this.monthCursor.getMonth();
            const firstDay = new Date(year, month, 1);
            const offset = (firstDay.getDay() + 6) % 7;
            const totalDays = new Date(year, month + 1, 0).getDate();
            const cells = [];

            for (let index = 0; index < offset; index += 1) {
                cells.push({ blank: true, key: `blank-${index}` });
            }

            for (let day = 1; day <= totalDays; day += 1) {
                cells.push({
                    blank: false,
                    key: `${year}-${this.pad(month + 1)}-${this.pad(day)}`,
                    date: new Date(year, month, day, 12, 0, 0, 0),
                });
            }

            return cells;
        },
        inRange(date) {
            const value = this.formatValue(date);

            if (this.min && value < this.min) {
                return false;
            }

            if (this.max && value > this.max) {
                return false;
            }

            return true;
        },
        isSelected(date) {
            return this.value === this.formatValue(date);
        },
        isToday(date) {
            const today = new Date();

            return date.getFullYear() === today.getFullYear()
                && date.getMonth() === today.getMonth()
                && date.getDate() === today.getDate();
        },
        openPicker() {
            this.open = true;
            this.$nextTick(() => {
                this.updatePlacement();
            });
        },
        closePicker() {
            this.open = false;
            this.popoverStyle = '';
        },
        togglePicker() {
            this.open = !this.open;

            if (this.open) {
                this.$nextTick(() => {
                    this.updatePlacement();
                });
            } else {
                this.popoverStyle = '';
            }
        },
        prevMonth() {
            this.monthCursor = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth() - 1, 1);
        },
        nextMonth() {
            this.monthCursor = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth() + 1, 1);
        },
        selectDay(date) {
            if (!this.inRange(date)) {
                return;
            }

            this.value = this.formatValue(date);
            this.monthCursor = new Date(date.getFullYear(), date.getMonth(), 1);
            this.open = false;
        },
        clearValue() {
            this.value = '';
            this.open = false;
        },
        setToday() {
            const today = new Date();
            this.monthCursor = new Date(today.getFullYear(), today.getMonth(), 1);
            this.value = this.formatValue(today);
            this.open = false;
        },
        displayText() {
            return this.value ? this.formatDisplay(this.value) : this.placeholder;
        },
    }));

    Alpine.data('silatarMonthpicker', (config = {}) => ({
        open: false,
        name: config.name || '',
        value: config.value || '',
        placeholder: config.placeholder || 'Pilih bulan',
        clearLabel: config.clearLabel || 'Hapus',
        applyLabel: config.applyLabel || 'Pilih',
        locale: config.locale || 'en-US',
        months: Array.isArray(config.months) && config.months.length
            ? config.months
            : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        yearCursor: null,
        popoverStyle: '',
        yearInputValue() {
            return this.yearCursor || new Date().getFullYear();
        },
        updatePlacement() {
            const trigger = this.$refs.trigger;

            if (!(trigger instanceof HTMLElement)) {
                return;
            }

            const rect = trigger.getBoundingClientRect();
            const width = 320;
            const height = 356;
            const margin = 12;
            const left = Math.min(
                Math.max(rect.left, margin),
                Math.max(margin, window.innerWidth - width - margin)
            );
            const shouldOpenAbove = window.innerHeight - rect.bottom < height + margin;
            const top = shouldOpenAbove
                ? Math.max(margin, rect.top - height - margin)
                : Math.min(window.innerHeight - height - margin, rect.bottom + margin);

            this.popoverStyle = `position:fixed;left:${Math.round(left)}px;top:${Math.round(top)}px;width:${width}px;z-index:110;`;
        },
        init() {
            const sourceDate = this.value ? this.parseValue(this.value) : new Date();
            this.yearCursor = sourceDate.getFullYear();
        },
        parseValue(value) {
            const [year, month] = String(value).split('-').map((part) => parseInt(part, 10));

            if (!year || !month) {
                return new Date();
            }

            return new Date(year, month - 1, 1, 12, 0, 0, 0);
        },
        get monthNameMap() {
            return Array.isArray(this.months) ? this.months : [];
        },
        pad(number) {
            return String(number).padStart(2, '0');
        },
        formatValue(year, monthIndex) {
            return `${year}-${this.pad(monthIndex + 1)}`;
        },
        formatDisplay(value) {
            if (!value) {
                return this.placeholder;
            }

            const parsed = this.parseValue(value);
            const monthName = this.monthNameMap[parsed.getMonth()] || new Intl.DateTimeFormat(this.locale, {
                month: 'long',
            }).format(parsed);

            return `${monthName} ${parsed.getFullYear()}`;
        },
        get monthLabel() {
            return new Intl.DateTimeFormat(this.locale, {
                year: 'numeric',
            }).format(new Date(this.yearCursor, 0, 1));
        },
        get monthItems() {
            return this.months.map((month, index) => ({
                label: month,
                key: `${this.yearCursor}-${index}`,
                value: this.formatValue(this.yearCursor, index),
                index,
            }));
        },
        isSelected(index) {
            if (!this.value) {
                return false;
            }

            const parsed = this.parseValue(this.value);
            return parsed.getFullYear() === this.yearCursor && parsed.getMonth() === index;
        },
        openPicker() {
            this.open = true;
            this.$nextTick(() => {
                this.updatePlacement();
            });
        },
        closePicker() {
            this.open = false;
            this.popoverStyle = '';
        },
        togglePicker() {
            this.open = !this.open;

            if (this.open) {
                this.$nextTick(() => {
                    this.updatePlacement();
                });
            } else {
                this.popoverStyle = '';
            }
        },
        prevYear() {
            this.yearCursor -= 1;
            this.updatePlacement();
        },
        nextYear() {
            this.yearCursor += 1;
            this.updatePlacement();
        },
        setYear(year) {
            const parsedYear = Number.parseInt(year, 10);

            if (Number.isNaN(parsedYear)) {
                return;
            }

            this.yearCursor = parsedYear;
            this.updatePlacement();
        },
        selectMonth(index) {
            this.value = this.formatValue(this.yearCursor, index);
            this.open = false;
            this.submitForm();
        },
        clearValue() {
            this.value = '';
            this.open = false;
            this.submitForm();
        },
        submitForm() {
            const form = this.$refs.trigger?.closest?.('form') || this.$el.closest('form');

            if (form instanceof HTMLFormElement) {
                if (String(form.method || '').toLowerCase() === 'get') {
                    const params = new URLSearchParams(new FormData(form));

                    if (this.name) {
                        params.set(this.name, this.value || '');
                    }

                    const url = new URL(form.action || window.location.href, window.location.href);
                    url.search = params.toString();
                    window.location.assign(url.toString());
                    return;
                }

                setTimeout(() => {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                        return;
                    }

                    form.submit();
                }, 0);
            }
        },
        displayText() {
            return this.value ? this.formatDisplay(this.value) : this.placeholder;
        },
    }));

    Alpine.data('silatarYearpicker', (config = {}) => ({
<<<<<<< HEAD
        name: config.name || '',
        value: config.value || '',
        placeholder: config.placeholder || 'Pilih tahun',
        submitForm() {
            const form = this.$el.closest('form');
=======
        open: false,
        name: config.name || '',
        value: config.value || '',
        placeholder: config.placeholder || 'Pilih tahun',
        clearLabel: config.clearLabel || 'Hapus',
        applyLabel: config.applyLabel || 'Pilih',
        locale: config.locale || 'en-US',
        yearCursor: null,
        popoverStyle: '',
        updatePlacement() {
            const trigger = this.$refs.trigger;

            if (!(trigger instanceof HTMLElement)) {
                return;
            }

            const rect = trigger.getBoundingClientRect();
            const width = 320;
            const height = 314;
            const margin = 12;
            const left = Math.min(
                Math.max(rect.left, margin),
                Math.max(margin, window.innerWidth - width - margin)
            );
            const shouldOpenAbove = window.innerHeight - rect.bottom < height + margin;
            const top = shouldOpenAbove
                ? Math.max(margin, rect.top - height - margin)
                : Math.min(window.innerHeight - height - margin, rect.bottom + margin);

            this.popoverStyle = `position:fixed;left:${Math.round(left)}px;top:${Math.round(top)}px;width:${width}px;z-index:70;`;
        },
        init() {
            const parsed = Number.parseInt(String(this.value || ''), 10);
            this.yearCursor = Number.isNaN(parsed) ? new Date().getFullYear() : parsed;
        },
        formatValue(year) {
            return String(year);
        },
        formatDisplay(value) {
            if (!value) {
                return this.placeholder;
            }

            const parsed = Number.parseInt(String(value), 10);

            if (Number.isNaN(parsed)) {
                return this.placeholder;
            }

            return new Intl.DateTimeFormat(this.locale, {
                year: 'numeric',
            }).format(new Date(parsed, 0, 1));
        },
        get yearItems() {
            const startYear = this.yearCursor - 5;

            return Array.from({ length: 12 }, (_, index) => {
                const year = startYear + index;

                return {
                    key: String(year),
                    label: String(year),
                    year,
                };
            });
        },
        isSelected(year) {
            return String(this.value) === String(year);
        },
        openPicker() {
            this.open = true;
            this.$nextTick(() => {
                this.updatePlacement();
            });
        },
        closePicker() {
            this.open = false;
            this.popoverStyle = '';
        },
        togglePicker() {
            this.open = !this.open;

            if (this.open) {
                this.$nextTick(() => {
                    this.updatePlacement();
                });
            } else {
                this.popoverStyle = '';
            }
        },
        prevYear() {
            this.yearCursor -= 12;
            this.updatePlacement();
        },
        nextYear() {
            this.yearCursor += 12;
            this.updatePlacement();
        },
        setYear(year) {
            const parsedYear = Number.parseInt(year, 10);

            if (Number.isNaN(parsedYear)) {
                return;
            }

            this.yearCursor = parsedYear;
            this.updatePlacement();
        },
        selectYear(year) {
            this.value = this.formatValue(year);
            this.yearCursor = year;
            this.open = false;
            this.submitForm();
        },
        clearValue() {
            this.value = '';
            this.open = false;
            this.submitForm();
        },
        submitForm() {
            const form = this.$refs.trigger?.closest?.('form') || this.$el.closest('form');
>>>>>>> 1cdcd39f051e5cf74502037ab3e117ad5b143f87

            if (form instanceof HTMLFormElement) {
                if (String(form.method || '').toLowerCase() === 'get') {
                    const params = new URLSearchParams(new FormData(form));

                    if (this.name) {
                        params.set(this.name, this.value || '');
                    }

                    const url = new URL(form.action || window.location.href, window.location.href);
                    url.search = params.toString();
                    window.location.assign(url.toString());
                    return;
                }

                setTimeout(() => {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                        return;
                    }

                    form.submit();
                }, 0);
            }
        },
<<<<<<< HEAD
=======
        displayText() {
            return this.value ? this.formatDisplay(this.value) : this.placeholder;
        },
>>>>>>> 1cdcd39f051e5cf74502037ab3e117ad5b143f87
    }));

    Alpine.data('requestForm', (requiredIds = []) => ({
        requiredIds: Array.isArray(requiredIds) ? requiredIds : [],
        requiredState: {},
        previewOpen: false,
        previewTitle: '',
        previewKind: 'file',
        previewSrc: '',
        openRequirementPreview(payload = {}) {
            this.previewTitle = payload.title || '';
            this.previewKind = payload.kind || 'file';
            this.previewSrc = payload.src || '';
            this.previewOpen = true;
        },
        closeRequirementPreview() {
            this.previewOpen = false;
        },
        markRequirement(id, hasValue) {
            this.requiredState[id] = !!hasValue;
        },
        get canSubmit() {
            return this.requiredIds.every((id) => this.requiredState[id]);
        },
    }));

    Alpine.data('requestRequirementCard', ({
        kind = 'file',
        emptySrc = '',
        filledSrc = '',
        initialValue = '',
        initialFileName = '',
        initialFileType = '',
        initialPreviewSrc = '',
    } = {}) => ({
        kind,
        fileName: initialFileName ?? '',
        value: initialValue ?? '',
        imageSrc: initialFileName
            ? (kind === 'image' && initialPreviewSrc ? initialPreviewSrc : filledSrc)
            : emptySrc,
        fileType: initialFileType ?? '',
        previewUrl: initialPreviewSrc || '',
        previewOpen: false,
        previewOpenTimer: null,
        justUpdated: false,
        uploadedTimer: null,
        get hasValue() {
            if (this.kind === 'file' || this.kind === 'image') {
                return !!this.fileName;
            }

            return String(this.value ?? '').trim().length > 0;
        },
        syncRequirement(requirementId, required) {
            this.$dispatch('requirement-changed', {
                id: requirementId,
                hasValue: this.hasValue,
                required,
            });
        },
        initRequirement(requirementId, required) {
            if (this.kind === 'file' || this.kind === 'image') {
                this.$dispatch('requirement-changed', {
                    id: requirementId,
                    hasValue: this.hasValue,
                    required,
                });
                return;
            }

            this.syncRequirement(requirementId, required);
        },
        syncText(event, requirementId, required) {
            this.value = event.target.value;
            this.syncRequirement(requirementId, required);
        },
        openPreview() {
            if (!this.hasValue) {
                return;
            }

        },
        selectFile(event, requirementId, required) {
            const file = event.target.files?.[0] || null;

            this.fileName = file?.name || '';
            this.imageSrc = emptySrc;
            this.fileType = file?.type || this.fileType;

            if (this.previewUrl) {
                if (String(this.previewUrl).startsWith('blob:')) {
                    URL.revokeObjectURL(this.previewUrl);
                }
                this.previewUrl = null;
            }

            if (this.fileName && this.kind === 'image') {
                this.previewUrl = URL.createObjectURL(file);
                this.imageSrc = this.previewUrl;
            } else if (this.fileName) {
                this.previewUrl = URL.createObjectURL(file);
                this.imageSrc = filledSrc;
            }

            this.justUpdated = !!this.fileName;

            clearTimeout(this.uploadedTimer);

            if (this.fileName) {
                this.uploadedTimer = setTimeout(() => {
                    this.justUpdated = false;
                }, 1200);
            }

            this.$dispatch('requirement-changed', {
                id: requirementId,
                hasValue: !!file,
                required,
            });
        },
    }));

    Alpine.data('reportKinerjaPage', (config = {}) => ({
        addModalOpen: !!config.addModalOpen,
        editModalOpen: !!config.editModalOpen,
        addDate: config.defaultDate || '',
        humasData: config.humasData || [],
        rows: Array.isArray(config.initialRows) && config.initialRows.length
            ? config.initialRows.map((row) => ({
                kegiatan: row?.kegiatan || '',
                volume: row?.volume || '',
                satuan: row?.satuan || (config.defaultUnit || 'Kegiatan'),
            }))
            : [{
                kegiatan: '',
                volume: '',
                satuan: config.defaultUnit || 'Kegiatan',
            }],
        editRows: Array.isArray(config.editInitialRows) && config.editInitialRows.length
            ? config.editInitialRows.map((row) => ({
                kegiatan: row?.kegiatan || '',
                volume: row?.volume || '',
                satuan: row?.satuan || (config.defaultUnit || 'Kegiatan'),
            }))
            : [{
                kegiatan: '',
                volume: '',
                satuan: config.defaultUnit || 'Kegiatan',
            }],
        unitOptions: Array.isArray(config.unitOptions) && config.unitOptions.length
            ? config.unitOptions
            : ['Kegiatan', 'Dokumen', 'Jam'],
        // PDF Preview Modal
        pdfPreviewOpen: false,
        pdfPreviewUrl: '',
        pdfPreviewTitle: '',
        openPdfPreview(url, title) {
            this.pdfPreviewUrl = url;
            this.pdfPreviewTitle = title || 'Preview PDF';
            this.pdfPreviewOpen = true;
        },
        closePdfPreview() {
            this.pdfPreviewOpen = false;
            this.pdfPreviewUrl = '';
            this.pdfPreviewTitle = '';
        },
        openAddModal() {
            this.addModalOpen = true;

            if (!this.rows.length) {
                this.rows = [{
                    kegiatan: '',
                    volume: '',
                    satuan: this.unitOptions[0] || 'Kegiatan',
                }];
            }
        },
        closeAddModal() {
            this.addModalOpen = false;
        },
        openEditModal() {
            this.editModalOpen = true;
        },
        closeEditModal() {
            this.editModalOpen = false;
        },
        addRow() {
            this.rows.push({
                kegiatan: '',
                volume: '',
                satuan: this.unitOptions[0] || 'Kegiatan',
            });
        },
        removeRow(index) {
            if (this.rows.length === 1) {
                this.rows[0] = {
                    kegiatan: '',
                    volume: '',
                    satuan: this.unitOptions[0] || 'Kegiatan',
                };
                return;
            }

            this.rows.splice(index, 1);
        },
        addEditRow() {
            this.editRows.push({
                kegiatan: '',
                volume: '',
                satuan: this.unitOptions[0] || 'Kegiatan',
            });
        },
        removeEditRow(index) {
            if (this.editRows.length === 1) {
                this.editRows[0] = {
                    kegiatan: '',
                    volume: '',
                    satuan: this.unitOptions[0] || 'Kegiatan',
                };
                return;
            }

            this.editRows.splice(index, 1);
        },
    }));

    Alpine.data('humasPage', () => ({
        modalOpen: false,
        isEdit: false,
        editingId: null,
        selectedMonth: '',
        comment: '',
        platforms: ['Facebook', 'Instagram', 'TikTok', 'Website', 'Youtube'],
        platformData: {
            Facebook: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
            Instagram: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
            TikTok: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
            Website: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
            Youtube: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
        },
        // Platform Detail Modal
        platformDetailOpen: false,
        platformDetailData: null,
        platformDetailName: '',
        openPlatformDetail(reportId, platformName) {
            const report = this.humasData.find(r => r.id === reportId);
            if (report) {
                const platform = report.platforms.find(p => p.name.toLowerCase() === platformName.toLowerCase());
                if (platform) {
                    // Map platform names to display names
                    const displayNames = {
                        'facebook': 'Facebook',
                        'instagram': 'Instagram',
                        'tiktok': 'TikTok',
                        'website': 'Website',
                        'youtube': 'YouTube'
                    };
                    const lowerName = platformName.toLowerCase();
                    this.platformDetailName = displayNames[lowerName] || platformName;
                    this.platformDetailData = platform;
                    this.platformDetailOpen = true;
                }
            }
        },
        closePlatformDetail() {
            this.platformDetailOpen = false;
            this.platformDetailData = null;
            this.platformDetailName = '';
        },
        openAddModal() {
            this.isEdit = false;
            this.editingId = null;
            this.selectedMonth = '';
            this.comment = '';
            this.resetPlatformData();
            this.modalOpen = true;
        },
        openEditModal(id) {
            this.isEdit = true;
            this.editingId = id;

            // Find the data for this id
            const report = this.humasData.find(r => r.id === id);
            if (report) {
                this.selectedMonth = report.bulan_full || '';
                this.comment = report.comment || '';
                this.resetPlatformData();

                // Populate platform data from report
                report.platforms.forEach(p => {
                    const platformName = p.name.charAt(0).toUpperCase() + p.name.slice(1);
                    if (this.platformData[platformName]) {
                        this.platformData[platformName] = {
                            first: {
                                date: p.first_date || '',
                                content: p.first_content || '',
                                link: p.first_link || '',
                            },
                            last: {
                                date: p.last_date || '',
                                content: p.last_content || '',
                                link: p.last_link || '',
                            }
                        };
                    }
                });
            }

            this.modalOpen = true;
        },
        closeModal() {
            this.modalOpen = false;
            this.resetPlatformData();
        },
        resetPlatformData() {
            this.platformData = {
                Facebook: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
                Instagram: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
                TikTok: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
                Website: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
                Youtube: { first: { date: '', content: '', link: '' }, last: { date: '', content: '', link: '' } },
            };
        },
    }));
});

Livewire.start();
