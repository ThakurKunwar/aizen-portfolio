<x-layouts.app title="CV">
    <div class="cv wrap max-w-3xl pb-10 pt-4">
        <p class="no-print mb-8 flex flex-wrap items-center gap-4">
            <button type="button" class="btn btn-primary" onclick="window.print()">Print or save as PDF</button>
            <span class="hint">Choose "Save as PDF" in the print window.</span>
        </p>

        <h1 class="font-serif text-4xl font-semibold">{{ config('portfolio.name') }}</h1>
        <p class="mt-1 text-lg">{{ config('portfolio.role') }}, {{ config('portfolio.city') }}</p>
        <p class="mt-2 text-sm">
            {{ config('portfolio.email') }}
            | {{ config('portfolio.github') }}
            @if (config('portfolio.linkedin'))
                | {{ config('portfolio.linkedin') }}
            @endif
        </p>

        <section class="mt-8">
            <h2 class="border-b border-line pb-1 font-serif text-xl font-semibold">Summary</h2>
            <p class="mt-3 leading-relaxed">{{ implode(' ', config('portfolio.bio')) }}</p>
        </section>

        <section class="mt-8">
            <h2 class="border-b border-line pb-1 font-serif text-xl font-semibold">Experience</h2>
            @foreach (config('portfolio.experience') as $job)
                <div class="mt-4">
                    <h3 class="font-semibold">{{ $job['title'] }}, {{ $job['place'] }}</h3>
                    <p class="text-sm text-slate">{{ $job['period'] }}</p>
                    @if (! empty($job['summary']))
                        <p class="mt-1 leading-relaxed">{{ $job['summary'] }}</p>
                    @endif
                </div>
            @endforeach
        </section>

        @if ($projects->isNotEmpty())
            <section class="mt-8">
                <h2 class="border-b border-line pb-1 font-serif text-xl font-semibold">Projects</h2>
                @foreach ($projects as $project)
                    <div class="mt-4">
                        <h3 class="font-semibold">{{ $project->title }}</h3>
                        <p class="leading-relaxed">{{ $project->summary }}</p>
                        @if ($project->topics)
                            <p class="text-sm text-slate">{{ implode(', ', $project->topics) }}</p>
                        @endif
                    </div>
                @endforeach
            </section>
        @endif

        <section class="mt-8">
            <h2 class="border-b border-line pb-1 font-serif text-xl font-semibold">Skills</h2>
            @foreach (config('portfolio.skills') as $group => $items)
                <p class="mt-2"><span class="font-semibold capitalize">{{ $group }}:</span> {{ implode(', ', $items) }}</p>
            @endforeach
        </section>
    </div>
</x-layouts.app>
