# Aizen Kunwar: portfolio

A Laravel 13 portfolio with three ideas:

1. **The site is an API.** The console on the home page makes real requests to `/api/v1/...`. Anyone can `curl` the same routes.
2. **A small admin** for the one thing that changes: your projects (title, story, repo link, pictures, featured/published) and the contact inbox.
3. **Everything else lives in one file**: `config/portfolio.php` (bio, skills, experience, contact details).

No GitHub or GitLab API is called. You paste a repo link; the site only reads its host to show a "GitHub" or "GitLab" label.

## Run it

Needs PHP 8.3+ and Composer. Node is optional (the built CSS/JS is included).

```bash
composer setup          # installs, creates .env + key, migrates, seeds, links storage
php artisan serve       # http://localhost:8000
```

Admin: http://localhost:8000/admin/login
Default login: `admin@example.com` / `password` (set `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env` **before** the first seed, and change it before going online).

Changing a view or the CSS? Run `npm install` once, then `npm run dev` while you work (or `npm run build`).

## Make it yours (do this first)

1. `config/portfolio.php`: search for `EDIT`. Bio, experience, principles, skills.
2. `.env`: `PORTFOLIO_EMAIL`, `PORTFOLIO_LINKEDIN`, `ADMIN_*`.
3. Admin: replace the four starter projects with real ones (link, pictures, the full story).

## Adding a project

Admin, New project: fill title and one-line summary, paste the repo link, add a cover picture and screenshots, tick Published (and Featured for the home page). For a private or company repository, untick **Show the repository link**: visitors and the API never see it.

## How the code is organised

```
Request -> Route -> Controller -> Repository (reads) / Service (writes) -> Model
                         |
                    FormRequest (validation)
```

| Folder | Job |
|---|---|
| `routes/web.php`, `routes/api.php` | URLs. Site and admin in web, the JSON API in api |
| `app/Http/Controllers` | Thin. Read the request, call a repository or service, return a view or JSON |
| `app/Http/Requests` | Validation (`ProjectRequest`, `ContactRequest`) |
| `app/Repositories/ProjectRepository.php` | Every project **query** lives here |
| `app/Services/ProjectService.php` | Everything that **changes** a project: slug, pictures, switches |
| `app/Support/RepositoryUrl.php` | Pure PHP: link in, "which service, which repo" out |
| `app/Http/Resources/ProjectResource.php` | The JSON shape of a project |
| `app/Http/Middleware/SecurityHeaders.php` | Security headers on every response |
| `resources/views` | Blade: `components/` (layout, cards, console), pages, `admin/` |
| `resources/js/lib/api-console.js` | Console logic with no DOM (tested with Node) |

Design choices worth knowing:
- Projects are the only database content, plus contact messages and the one admin user.
- Contact form: honeypot field, 2 messages/minute and 10/day per IP, saved to the database first and emailed second, so a mail problem never loses a message.
- Admin login: 5 wrong passwords per email+IP, then a short lockout. There is no sign-up route.
- Descriptions are Markdown with raw HTML stripped.
- `/cv` is a print-friendly CV from the same data. Print it and choose Save as PDF.

## The API

All `GET`, all JSON, 60 requests/minute per IP.

| Route | What |
|---|---|
| `/api/v1` | List of routes |
| `/api/v1/about`, `/skills`, `/experience`, `/contact`, `/life` | From `config/portfolio.php` |
| `/api/v1/projects` | Published projects. Filters: `featured=1`, `language=PHP`, `q=text`, `limit=10` |
| `/api/v1/projects/{slug}` | One project with its full description and screenshots |

## Tests

```bash
php artisan test
```

Covers the link parser, the public pages, every API route, the admin (login, create, update, toggle, delete, validation) and the contact form (saved, honeypot, validation, header-injection attempt, rate limit).

## Going online

- Point the web server's document root at `public/`, never at the project folder.
- `.env`: `APP_ENV=production`, `APP_DEBUG=false`, real `APP_URL`, strong `ADMIN_PASSWORD`, real `MAIL_*` (SMTP) so contact messages reach your inbox.
- `composer install --no-dev --optimize-autoloader`, `php artisan migrate --seed --force`, `php artisan storage:link`
- `php artisan config:cache route:cache view:cache`
- Back up `database/database.sqlite` and `storage/app/public` (your pictures).

## Left out on purpose

Registration, roles, a blog, a CMS for the bio, GitHub/GitLab fetching, image resizing. Each can be added later without changing what is here.
