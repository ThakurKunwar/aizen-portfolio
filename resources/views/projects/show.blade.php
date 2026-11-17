<x-layouts.app :title="$project->title" :description="$project->summary">
    <article class="wrap pb-10 pt-6">
        <a href="{{ route('projects.index') }}" class="link text-sm">All projects</a>

        <h1 class="mt-5 max-w-3xl font-serif text-[clamp(2.5rem,7vw,4.5rem)] font-semibold leading-[1.02] tracking-tight">{{ $project->title }}</h1>
        <p class="mt-5 max-w-2xl text-xl leading-relaxed text-slate">{{ $project->summary }}</p>

        <div class="mt-6 flex flex-wrap gap-2">
            @if ($project->language)
                <span class="tag tag-lang">{{ $project->language }}</span>
            @endif
            @foreach ($project->topics ?? [] as $topic)
                <span class="tag">{{ $topic }}</span>
            @endforeach
        </div>

        @if ($project->public_repo_url || $project->live_url)
            <p class="mt-7 flex flex-wrap gap-3">
                @if ($project->live_url)
                    <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn btn-primary">Visit live site</a>
                @endif
                @if ($project->public_repo_url)
                    <a href="{{ $project->public_repo_url }}" target="_blank" rel="noopener" class="btn">See the code on {{ $project->provider?->label() ?? 'the web' }}</a>
                @endif
            </p>
        @endif

        <div class="mt-10 aspect-[16/9] overflow-hidden rounded-2xl border border-line">
            <x-cover :project="$project" />
        </div>

        @if ($project->description)
            <div class="prose-lite mt-12">
                {!! \Illuminate\Support\Str::markdown($project->description, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
            </div>
        @endif

        @if ($project->images->isNotEmpty())
            <h2 class="mt-14 font-serif text-2xl font-semibold">Screenshots</h2>
            <ul class="mt-5 grid gap-5 sm:grid-cols-2">
                @foreach ($project->images as $image)
                    <li>
                        <a href="{{ $image->url }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl border border-line">
                            <img src="{{ $image->url }}" alt="{{ $image->caption ?: 'Screenshot of '.$project->title }}" loading="lazy" class="w-full">
                        </a>
                        @if ($image->caption)
                            <p class="hint">{{ $image->caption }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </article>
</x-layouts.app>
