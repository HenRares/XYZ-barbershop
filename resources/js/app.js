import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
if (csrf) window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;

document.querySelectorAll('[data-menu-toggle]').forEach(button => {
    button.addEventListener('click', () => document.getElementById(button.dataset.menuToggle)?.classList.toggle('hidden'));
});

document.querySelectorAll('[data-dialog-open]').forEach(button => {
    button.addEventListener('click', () => document.getElementById(button.dataset.dialogOpen)?.showModal());
});
document.querySelectorAll('[data-dialog-close]').forEach(button => {
    button.addEventListener('click', () => button.closest('dialog')?.close());
});

document.querySelectorAll('[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
        if (!confirm(form.dataset.confirm)) event.preventDefault();
    });
});

setTimeout(() => document.querySelectorAll('.toast').forEach(el => el.remove()), 4500);

const bookingForm = document.querySelector('[data-booking-form]');
if (bookingForm) {
    const service = bookingForm.querySelector('[name="service_id"]');
    const date = bookingForm.querySelector('[name="visit_date"]');
    const empty = document.querySelector('[data-estimate-empty]');
    const panel = document.querySelector('[data-estimate-panel]');
    let requestId = 0;
    const updateEstimate = async () => {
        if (!service.value || !date.value) { panel?.classList.add('hidden'); empty?.classList.remove('hidden'); return; }
        const id = ++requestId;
        try {
            const { data } = await axios.post(bookingForm.dataset.estimateUrl, { service_id: service.value, visit_date: date.value });
            if (id !== requestId) return;
            for (const [key, value] of Object.entries(data)) {
                const el = document.querySelector(`[data-estimate="${key}"]`);
                if (el) el.textContent = key === 'waitingMinutes' ? formatWait(value) : value;
            }
            panel?.classList.remove('hidden'); empty?.classList.add('hidden');
        } catch (error) {
            panel?.classList.add('hidden'); empty?.classList.remove('hidden');
        }
    };
    service.addEventListener('change', updateEstimate); date.addEventListener('change', updateEstimate); updateEstimate();
}

function formatWait(minutes) {
    if (minutes <= 0) return 'Segera dilayani';
    const h = Math.floor(minutes / 60), m = minutes % 60;
    if (!h) return `± ${m} menit`; if (!m) return `± ${h} jam`; return `± ${h} jam ${m} menit`;
}

const tracker = document.querySelector('[data-queue-tracker]');
if (tracker) {
    const refresh = async () => {
        try {
            const { data } = await axios.get(tracker.dataset.summaryUrl);
            const map = { currentServingNumber: `No. ${data.currentServingNumber}`, waitingBefore: `${data.waitingBefore} pelanggan`, waitingText: data.waitingText, serviceTime: data.serviceTime };
            Object.entries(map).forEach(([key, value]) => document.querySelectorAll(`[data-live="${key}"]`).forEach(el => el.textContent = value));
            document.querySelectorAll('[data-live="status"]').forEach(el => { el.textContent = data.status; el.className = `status-badge ${data.statusClass}`; });
        } catch (_) { /* Keep last known state when connection is temporarily unavailable. */ }
    };
    setInterval(refresh, 5000);
}
