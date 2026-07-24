let container = null;

function getContainer() {
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-[99999] flex flex-col gap-2 pointer-events-none';
        document.body.appendChild(container);
    }
    return container;
}

const TYPE_CLASSES = {
    success: 'bg-green-600',
    error: 'bg-red-600',
    info: 'bg-primary',
    warning: 'bg-yellow-500',
};

/**
 * Show a toast notification.
 * @param {string} message
 * @param {'success'|'error'|'info'|'warning'} type
 * @param {number} duration  ms before auto-dismiss
 */
export function showToast(message, type = 'info', duration = 3000) {
    const colorClass = TYPE_CLASSES[type] ?? TYPE_CLASSES.info;

    const toast = document.createElement('div');
    toast.className = [
        colorClass,
        'text-white px-5 py-3 rounded-xl shadow-lg text-sm font-semibold',
        'transition-all duration-300 opacity-100 pointer-events-auto',
    ].join(' ');
    toast.textContent = message;

    getContainer().appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(16px)';
        setTimeout(() => toast.remove(), 320);
    }, duration);
}
