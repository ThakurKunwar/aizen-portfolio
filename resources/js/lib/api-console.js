// Pure helpers for the API console. No DOM in here, so they can be tested with Node.

export const API_BASE = '/api/v1';

const STATUS_TEXT = {
    200: 'OK',
    404: 'Not Found',
    422: 'Unprocessable Content',
    429: 'Too Many Requests',
    500: 'Server Error',
};

/**
 * Turns whatever was typed ("projects", "GET /projects?featured=1", "/api/v1/about", "help")
 * into a path that starts with "/", or null when it is empty or not allowed.
 */
export function normalizePath(raw) {
    let path = String(raw ?? '').trim().replace(/^get\s+/i, '').trim();

    if (path === '') return null;
    if (/^help$/i.test(path)) return '/';

    path = path.replace(/^\/?api\/v1(?=\/|\?|$)/i, '');
    if (path === '') return '/';
    if (path[0] !== '/') path = '/' + path;

    // Same-origin only, plain characters only. Nothing else is ever sent.
    if (!/^\/[A-Za-z0-9\-_.~%/?=&,+:]*$/.test(path) || path.includes('..')) return null;

    return path;
}

export function escapeHtml(text) {
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

/** Wraps JSON tokens in <span class="tok-*"> for colour. The input is escaped first. */
export function highlight(json) {
    return escapeHtml(json).replace(
        /("(?:\\.|[^"\\])*")(\s*:)?|\b(true|false|null)\b|-?\b\d+(?:\.\d+)?(?:[eE][+-]?\d+)?\b/g,
        (match, string, colon, literal) => {
            if (string) {
                return colon ? `<span class="tok-key">${string}</span>${colon}` : `<span class="tok-str">${string}</span>`;
            }
            if (literal) return `<span class="tok-lit">${match}</span>`;
            return `<span class="tok-num">${match}</span>`;
        },
    );
}

export function statusText(code) {
    return STATUS_TEXT[code] ?? '';
}

export function buildCurl(origin, path) {
    return `curl -s '${origin}${API_BASE}${path}'`;
}
