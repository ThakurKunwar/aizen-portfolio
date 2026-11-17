<x-layouts.app>
    @php
        $schema = json_encode(array_filter([
            '@context' => 'https://schema.org',
            '@type'    => 'Person',
            'name'     => config('portfolio.name'),
            'jobTitle' => config('portfolio.role'),
            'address'  => ['@type' => 'PostalAddress', 'addressLocality' => 'Kathmandu', 'addressCountry' => 'NP'],
            'sameAs'   => array_values(array_filter([config('portfolio.github'), config('portfolio.linkedin')])),
        ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    @endphp

    @push('head')
        <script type="application/ld+json">{!! $schema !!}</script>
    @endpush

    {{-- Hero: the live API is the first thing you see --}}
    <section class="wrap pb-20 pt-10 sm:pt-16">
        <h1 class="font-serif text-[clamp(3.25rem,11vw,7rem)] font-semibold leading-[0.95] tracking-tight">{{ config('portfolio.name') }}</h1>
        <p class="mt-6 max-w-xl text-xl leading-relaxed text-slate">{{ config('portfolio.headline') }}</p>
        <p class="mt-2 max-w-xl text-slate">This page is also an API. Ask it something.</p>

        <div class="mt-10">
            <x-console :routes="$apiRoutes" />
        </div>
        <p class="hint mt-3">These are real requests to this site. Pick a route, or type your own and press Enter. Up arrow repeats earlier ones.</p>
    </section>

    {{-- Work --}}
    <section id="work" class="wrap pb-20">
        <div class="mb-2 flex items-end justify-between gap-4">
            <h2 class="font-serif text-3xl font-semibold">Selected work</h2>
            <a href="{{ route('projects.index') }}" class="link text-sm">All projects</a>
        </div>

        @forelse ($projects as $project)
            <x-project-row :project="$project" />
        @empty
            <p class="border-t border-line py-9 text-slate">No projects yet. Add the first one from the admin.</p>
        @endforelse
    </section>

    {{-- About --}}
    <section id="about" class="wrap pb-20">
        <h2 class="font-serif text-3xl font-semibold">About</h2>

        <div class="mt-6 grid gap-10 md:grid-cols-12">
            <div class="md:col-span-7">
                @foreach (config('portfolio.bio') as $paragraph)
                    <p class="mb-4 max-w-xl text-lg leading-relaxed">{{ $paragraph }}</p>
                @endforeach

                <h3 class="mt-8 font-serif text-xl font-semibold">How I work</h3>
                <ul class="mt-3 max-w-xl space-y-3 leading-relaxed text-slate">
                    @foreach (config('portfolio.principles') as $principle)
                        <li class="border-l-2 border-flag pl-4">{{ $principle }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="md:col-span-5">
                <h3 class="font-serif text-xl font-semibold">Skills</h3>
                <dl class="mt-3 space-y-4">
                    @foreach (config('portfolio.skills') as $group => $items)
                        <div>
                            <dt class="text-sm font-semibold capitalize">{{ $group }}</dt>
                            <dd class="mt-1.5 flex flex-wrap gap-2">
                                @foreach ($items as $item)
                                    <span class="tag">{{ $item }}</span>
                                @endforeach
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </section>

    {{-- Experience --}}
    <section id="experience" class="wrap pb-20">
        <h2 class="font-serif text-3xl font-semibold">Experience</h2>

        <div class="mt-4">
            @foreach (config('portfolio.experience') as $job)
                <article class="grid gap-2 border-t border-line py-6 md:grid-cols-12 md:gap-10">
                    <p class="text-sm text-slate md:col-span-3">{{ $job['period'] }}</p>
                    <div class="md:col-span-9">
                        <h3 class="font-serif text-xl font-semibold">{{ $job['title'] }}</h3>
                        <p class="text-slate">{{ $job['place'] }}</p>
                        @if (! empty($job['summary']))
                            <p class="mt-2 max-w-xl leading-relaxed">{{ $job['summary'] }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Contact --}}
    <section id="contact" class="wrap pb-10">
        <h2 class="font-serif text-3xl font-semibold">Contact</h2>
        <p class="mt-3 max-w-xl text-slate">Open to back-end roles and freelance work. Write to me here, or use the links.</p>

        <p class="mt-4 flex flex-wrap gap-x-6 gap-y-2">
            <a href="mailto:{{ config('portfolio.email') }}" class="link">{{ config('portfolio.email') }}</a>
            <a href="{{ config('portfolio.github') }}" target="_blank" rel="noopener" class="link">GitHub</a>
            @if (config('portfolio.linkedin'))
                <a href="{{ config('portfolio.linkedin') }}" target="_blank" rel="noopener" class="link">LinkedIn</a>
            @endif
        </p>

        @if (session('status'))
            <p class="notice mt-8 max-w-xl" role="status">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="mt-8 max-w-xl" novalidate>
            @csrf
            <div class="hp" aria-hidden="true">
                <label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            <x-form-field label="Your name" name="name">
                <input class="input" id="name" name="name" value="{{ old('name') }}" required minlength="2" maxlength="80" autocomplete="name">
            </x-form-field>

            <x-form-field label="Your email" name="email">
                <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            </x-form-field>

            <x-form-field label="Message" name="message">
                <textarea class="input" id="message" name="message" rows="5" required minlength="10" maxlength="2000">{{ old('message') }}</textarea>
            </x-form-field>

            <button type="submit" class="btn btn-primary">Send message</button>
        </form>
    </section>

    @push('scripts')
        @vite('resources/js/console.js')
    @endpush
</x-layouts.app>
