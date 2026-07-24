const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.content ?? '';

/**
 * JSON POST with CSRF token.
 * Throws on non-2xx responses.
 */
export async function post(url, data = {}) {
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'Accept': 'application/json',
        },
        body: JSON.stringify(data),
    });

    if (!res.ok) {
        const err = await res.json().catch(() => ({ message: 'Request failed' }));
        const error = new Error(err.message ?? 'Request failed');
        error.status = res.status;
        error.data = err;
        throw error;
    }

    return res.json();
}

/**
 * Submit a form POST that follows server-side redirects naturally.
 * Use this when the endpoint returns a redirect (302) instead of JSON.
 */
export function submitForm(url, extraFields = {}) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;

    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = csrfToken();
    form.appendChild(token);

    for (const [name, value] of Object.entries(extraFields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}
