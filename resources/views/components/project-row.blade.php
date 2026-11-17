@props(['project'])

<article class="grid gap-6 border-t border-line py-9 md:grid-cols-12 md:gap-10">
    <div class="md:col-span-7">
        <h3 class="font-serif text-2xl font-semibold leading-tight">
            <a href="{{ route('projects.show', $project->slug) }}" class="hover:text-flag">{{ $project->title }}</a>
        </h3>

        <p class="mt-3 max-w-xl leading-relaxed text-slate">{{ $project->summary }}</p>

        <div class="mt-4 flex flex-wrap gap-2">
            @if ($project->language)
                <span class="tag tag-lang">{{ $project->language }}</span>
            @endif
            @foreach (array_slice($project->topics ?? [], 0, 3) as $topic)
                <span class="tag">{{ $topic }}</span>
            @endforeach
        </div>

        <p class="mt-5 flex flex-wrap gap-x-6 gap-y-2 text-sm">
            <a href="{{ route('projects.show', $project->slug) }}" class="link">Read more</a>
            @if ($project->public_repo_url)
                <a href="{{ $project->public_repo_url }}" target="_blank" rel="noopener" class="link">{{ $project->provider?->label() ?? 'Code' }}</a>
            @endif
            @if ($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="link">Live site</a>
            @endif
        </p>
    </div>

    <div class="md:col-span-5">
        <a href="{{ route('projects.show', $project->slug) }}" tabindex="-1" aria-hidden="true" class="block aspect-[16/10] overflow-hidden rounded-xl border border-line">
            <x-cover :project="$project" />
        </a>
    </div>
</article>
