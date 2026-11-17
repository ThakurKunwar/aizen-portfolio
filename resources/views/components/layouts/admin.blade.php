@props(['title' => 'Admin'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Hanken+Grotesk:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap">
    @vite('resources/css/app.css')
    @stack('head')
</head>
<body>
    <a href="#main" class="skip">Skip to content</a>

    <header class="border-b border-line bg-paper">
        <div class="wrap flex flex-wrap items-center justify-between gap-3 py-3">
            <a href="{{ route('admin.projects.index') }}" class="font-serif text-lg font-semibold">Admin</a>
            <nav aria-label="Admin" class="flex flex-wrap items-center gap-5 text-sm font-medium">
                <a href="{{ route('admin.projects.index') }}" class="hover:text-flag">Projects</a>
                <a href="{{ route('admin.projects.create') }}" class="hover:text-flag">New project</a>
                <a href="{{ route('admin.messages.index') }}" class="hover:text-flag">Messages</a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="hover:text-flag">View site</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-small">Log out</button>
                </form>
            </nav>
        </div>
    </header>

    <main id="main" class="wrap py-10">
        @if (session('status'))
            <p class="notice mb-6" role="status">{{ session('status') }}</p>
        @endif

        {{ $slot }}
    </main>

    @stack('scripts')
</body>
</html>
