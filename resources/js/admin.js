// Small helpers for the project form: picture previews and a "GitHub or GitLab?" hint.

const form = document.querySelector('[data-project-form]');

if (form) {
    const repoInput = form.querySelector('[name="repo_url"]');
    const repoHint = form.querySelector('[data-repo-hint]');
    const gitlabHost = (form.dataset.gitlabHost || 'gitlab.com').toLowerCase();

    function detect(value) {
        try {
            const host = new URL(value).hostname.replace(/^www\./, '').toLowerCase();
            if (host === 'github.com') return 'GitHub link detected.';
            if (host === 'gitlab.com' || host === gitlabHost) return 'GitLab link detected.';
            return 'Link saved as it is. Only github.com and GitLab get a label.';
        } catch {
            return '';
        }
    }

    if (repoInput && repoHint) {
        const update = () => (repoHint.textContent = repoInput.value ? detect(repoInput.value) : '');
        repoInput.addEventListener('input', update);
        update();
    }

    // Show a small preview of the pictures you picked, before saving.
    form.querySelectorAll('[data-preview]').forEach((fileInput) => {
        const target = form.querySelector(fileInput.dataset.preview);
        if (!target) return;

        fileInput.addEventListener('change', () => {
            target.innerHTML = '';

            [...fileInput.files].slice(0, 8).forEach((file) => {
                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.alt = '';
                image.className = 'h-20 w-28 rounded-md border border-line object-cover';
                target.appendChild(image);
            });
        });
    });
}
