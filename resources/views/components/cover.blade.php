@props(['project'])

@if ($project->cover_url)
    <img src="{{ $project->cover_url }}" alt="Screenshot of {{ $project->title }}" loading="lazy" class="h-full w-full object-cover">
@else
    @php $tone = ['blue', 'green', 'yellow', 'red'][crc32($project->slug) % 4]; @endphp
    <div class="cover-fallback cover-{{ $tone }}" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($project->title, 0, 2)) }}</div>
@endif
