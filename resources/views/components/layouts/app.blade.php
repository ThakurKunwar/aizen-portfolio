@props(['title' => null, 'description' => null])

@php
    $siteName = config('portfolio.name');
    $pageTitle = $title ? $title.' | '.$siteName : $siteName.' – '.config('portfolio.role').' in Kathmandu';
    $pageDescription = $description ?: config('portfolio.headline');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="theme-color" content="#0c1222">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='7' fill='%230c1222'/%3E%3Ctext x='16' y='23' font-size='19' text-anchor='middle' fill='%23ff7080' font-family='Georgia,serif' font-weight='700'%3E{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($siteName, 0, 1)) }}%3C/text%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Hanken+Grotesk:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap">
    @vite('resources/css/app.css')
    @stack('head')
</head>
<body class="flex min-h-screen flex-col">
    <a href="#main" class="skip">Skip to content</a>

    <header class="site-header wrap flex w-full items-center justify-between py-5">
        <a href="{{ route('home') }}" class="font-serif text-lg font-semibold tracking-tight">{{ $siteName }}</a>
        <nav aria-label="Primary" class="flex items-center gap-5 text-sm font-medium text-slate sm:gap-7">
            <a href="{{ route('projects.index') }}" class="hover:text-flag">Work</a>
            <a href="{{ route('home') }}#about" class="hover:text-flag">About</a>
            <a href="{{ route('home') }}#contact" class="hover:text-flag">Contact</a>
            <a href="{{ route('cv') }}" class="hover:text-flag">CV</a>
        </nav>
    </header>

    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    <footer class="site-footer wrap w-full pb-12 pt-20 text-sm text-slate">
        <p>&copy; {{ now()->year }} {{ $siteName }}, {{ config('portfolio.city') }}.</p>
        <p class="mt-1">This site has a public API. Try <code class="rounded bg-[#e4e8ee] px-1.5 py-0.5 font-mono text-[0.8rem]">curl {{ url('/api/v1/projects') }}</code></p>
    </footer>

    @stack('scripts')
</body>
</html>
