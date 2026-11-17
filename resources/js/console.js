import { API_BASE, buildCurl, highlight, normalizePath, statusText } from './lib/api-console.js';

const root = document.querySelector('[data-console]');

if (root) {
    const out = root.querySelector('[data-out]');
    const status = root.querySelector('[data-status]');
    const input = root.querySelector('[data-input]');
    const copyButton = root.querySelector('[data-copy]');
    const chips = [...root.querySelectorAll('[data-path]')];
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const history = [];
    let historyIndex = 0;
    let currentPath = '/about';
    let timer;

    // Lines appear a few at a time. This is the page's one animation.
    function paint(html) {
        clearInterval(timer);
        const lines = html.split('\n');

        if (reduceMotion || lines.length > 70) {
            out.innerHTML = html;
            return;
        }

        const step = Math.max(1, Math.ceil(lines.length / 28));
        let shown = 0;
        out.innerHTML = '';

        timer = setInterval(() => {
            shown = Math.min(lines.length, shown + step);
            out.innerHTML = lines.slice(0, shown).join('\n');
            if (shown >= lines.length) clearInterval(timer);
        }, 24);
    }

    function show({ path, code, ms, remaining, body }) {
        const ok = code >= 200 && code < 300;
        const left = remaining !== null && remaining !== undefined ? `, ${remaining} requests left` : '';

        status.className = 'console-status ' + (ok ? 'is-ok' : 'is-err');
        status.innerHTML = `<b>${code} ${statusText(code)}</b> GET ${API_BASE}${path} (${ms} ms${left})`;
        paint(highlight(JSON.stringify(body, null, 2)));
    }

    async function run(raw) {
        const path = normalizePath(raw);

        if (path === null) {
            show({ path: '/', code: 400, ms: 0, body: { error: 'bad_request', message: 'Type a path like /projects or /about, or "help".' } });
            return;
        }

        currentPath = path;
        chips.forEach((chip) => chip.setAttribute('aria-pressed', String(chip.dataset.path === path)));
        status.className = 'console-status';
        status.textContent = `GET ${API_BASE}${path} ...`;

        const started = performance.now();

        try {
            const response = await fetch(API_BASE + path, { headers: { Accept: 'application/json' } });
            const text = await response.text();
            let body;

            try {
                body = JSON.parse(text);
            } catch {
                body = { error: 'not_json', message: 'The server did not answer with JSON.' };
            }

            show({
                path,
                code: response.status,
                ms: Math.round(performance.now() - started),
                remaining: response.headers.get('X-RateLimit-Remaining'),
                body,
            });
        } catch {
            show({ path, code: 0, ms: 0, body: { error: 'network', message: 'Could not reach the server.' } });
        }
    }

    chips.forEach((chip) => chip.addEventListener('click', () => run(chip.dataset.path)));

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            const value = input.value;
            if (value.trim() === '') return;

            history.push(value);
            historyIndex = history.length;
            input.value = '';
            run(value);
        } else if (event.key === 'ArrowUp' && history.length) {
            event.preventDefault();
            historyIndex = Math.max(0, historyIndex - 1);
            input.value = history[historyIndex];
        } else if (event.key === 'ArrowDown' && history.length) {
            event.preventDefault();
            historyIndex = Math.min(history.length, historyIndex + 1);
            input.value = history[historyIndex] ?? '';
        }
    });

    copyButton.addEventListener('click', async () => {
        const label = copyButton.textContent;

        try {
            await navigator.clipboard.writeText(buildCurl(window.location.origin, currentPath));
            copyButton.textContent = 'Copied';
        } catch {
            copyButton.textContent = 'Copy failed';
        }

        setTimeout(() => (copyButton.textContent = label), 1600);
    });

    run('/about');
}
