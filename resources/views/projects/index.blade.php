<x-layouts.app title="Work" :description="'Projects by '.config('portfolio.name').': what each one is, who it helps, and the code.'">
    <section class="wrap pb-10 pt-6">
        <h1 class="font-serif text-5xl font-semibold tracking-tight">Work</h1>
        <p class="mt-3 max-w-xl text-slate">What I have built, and the problem behind each one.</p>

        <form method="GET" action="{{ route('projects.index') }}" class="mt-8 flex flex-col gap-3 sm:flex-row" role="search">
            <input type="search" name="q" value="{{ $search }}" placeholder="Search projects" class="input sm:max-w-xs" aria-label="Search projects">

            @if ($languages->isNotEmpty())
                <select name="language" class="input sm:max-w-[11rem]" aria-label="Filter by language">
                    <option value="">All languages</option>
                    @foreach ($languages as $item)
                        <option value="{{ $item }}" @selected($language === $item)>{{ $item }}</option>
                    @endforeach
                </select>
            @endif

            <button type="submit" class="btn">Search</button>
        </form>

        <div class="mt-6">
            @forelse ($projects as $project)
                <x-project-row :project="$project" />
            @empty
                <p class="border-t border-line py-9 text-slate">
                    No projects match.
                    <a href="{{ route('projects.index') }}" class="link">Clear the filters</a>.
                </p>
            @endforelse
        </div>
    </section>
</x-layouts.app>
