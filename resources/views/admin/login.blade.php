<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Log in | Admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Hanken+Grotesk:wght@400;500;600&display=swap">
    @vite('resources/css/app.css')
</head>
<body class="grid min-h-screen place-items-center px-5">
    <main class="w-full max-w-sm">
        <h1 class="font-serif text-3xl font-semibold">Admin</h1>
        <p class="mt-1 text-slate">{{ config('portfolio.name') }}</p>

        <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8" novalidate>
            @csrf

            <x-form-field label="Email" name="email">
                <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </x-form-field>

            <x-form-field label="Password" name="password">
                <input class="input" id="password" type="password" name="password" required autocomplete="current-password">
            </x-form-field>

            <label class="mb-5 flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1"> Keep me logged in
            </label>

            <button type="submit" class="btn btn-primary w-full">Log in</button>
        </form>

        <p class="mt-6 text-sm"><a href="{{ route('home') }}" class="link">Back to the site</a></p>
    </main>
</body>
</html>
