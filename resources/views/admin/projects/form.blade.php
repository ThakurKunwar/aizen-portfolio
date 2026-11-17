@php
    $editing = $project->exists;

    // After a failed save, "topics" comes back from old() as an array.
    $topics = old('topics', $project->topics ?? []);
    $topics = is_array($topics) ? implode(', ', $topics) : $topics;

    $gitlabHost = parse_url((string) config('services.gitlab.url'), PHP_URL_HOST) ?: 'gitlab.com';
@endphp

<x-layouts.admin :title="$editing ? 'Edit '.$project->title : 'New project'">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="font-serif text-3xl font-semibold">{{ $editing ? 'Edit project' : 'New project' }}</h1>
        @if ($editing && $project->is_published)
            <a href="{{ route('projects.show', $project->slug) }}" target="_blank" rel="noopener" class="link text-sm">View on the site</a>
        @endif
    </div>

    <form
        method="POST"
        action="{{ $editing ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
        enctype="multipart/form-data"
        class="max-w-3xl"
        data-project-form
        data-gitlab-host="{{ $gitlabHost }}"
        novalidate
    >
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <x-form-field label="Title" name="title">
            <input class="input" id="title" name="title" value="{{ old('title', $project->title) }}" required maxlength="120">
        </x-form-field>

        @if ($editing)
            <x-form-field label="Address (slug)" name="slug" hint="The page lives at /projects/this-text. Change it only if you must.">
                <input class="input font-mono" id="slug" name="slug" value="{{ old('slug', $project->slug) }}" maxlength="140">
            </x-form-field>
        @endif

        <x-form-field label="One-line summary" name="summary" hint="Shown on cards and in the API. Up to 220 characters.">
            <textarea class="input" id="summary" name="summary" rows="2" required maxlength="220">{{ old('summary', $project->summary) }}</textarea>
        </x-form-field>

        <x-form-field label="Full story" name="description" hint="Optional. Markdown works. Suggested: the problem, what you built, what was hard, what you would change.">
            <textarea class="input" id="description" name="description" rows="10" maxlength="10000">{{ old('description', $project->description) }}</textarea>
        </x-form-field>

        <div class="grid gap-x-6 sm:grid-cols-2">
            <x-form-field label="Repository link" name="repo_url" hint="GitHub or GitLab.">
                <input class="input" id="repo_url" type="url" name="repo_url" value="{{ old('repo_url', $project->repo_url) }}" placeholder="https://github.com/you/project">
                <p class="hint font-medium" data-repo-hint></p>
            </x-form-field>

            <x-form-field label="Live site" name="live_url" hint="Leave empty if there is none.">
                <input class="input" id="live_url" type="url" name="live_url" value="{{ old('live_url', $project->live_url) }}" placeholder="https://">
            </x-form-field>
        </div>

        <div class="field">
            <label class="flex items-start gap-2 font-normal">
                <input type="checkbox" name="show_repo_link" value="1" class="mt-1.5" @checked(old('show_repo_link', $project->show_repo_link))>
                <span>Show the repository link on the site<br><span class="hint">Turn this off for private or company repositories. Visitors never see the link or the API field.</span></span>
            </label>
        </div>

        <div class="grid gap-x-6 sm:grid-cols-2">
            <x-form-field label="Main language" name="language">
                <input class="input" id="language" name="language" value="{{ old('language', $project->language) }}" maxlength="40" placeholder="PHP">
            </x-form-field>

            <x-form-field label="Topics" name="topics" hint="Separate with commas. Up to 8.">
                <input class="input" id="topics" name="topics" value="{{ $topics }}" placeholder="laravel, livewire, api">
            </x-form-field>
        </div>

        <fieldset class="field">
            <legend class="label">Cover picture</legend>

            @if ($editing && $project->cover_url)
                <img src="{{ $project->cover_url }}" alt="Current cover" class="mb-3 h-32 rounded-lg border border-line object-cover">
                <label class="mb-3 flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remove_cover" value="1"> Remove the current cover
                </label>
            @endif

            <input class="input" id="cover" type="file" name="cover" accept="image/jpeg,image/png,image/webp" data-preview="#cover-preview">
            <div id="cover-preview" class="mt-3 flex flex-wrap gap-3"></div>
            <p class="hint">JPG, PNG or WebP, up to 4 MB. Wide pictures (16:10) look best. Without one, a coloured card is used.</p>
            @error('cover')
                <p class="error">{{ $message }}</p>
            @enderror
        </fieldset>

        <fieldset class="field">
            <legend class="label">More screenshots</legend>
            <input class="input" id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple data-preview="#images-preview">
            <div id="images-preview" class="mt-3 flex flex-wrap gap-3"></div>
            <p class="hint">Up to 8 at a time, 4 MB each. Add more later by editing the project.</p>
            @error('images')
                <p class="error">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="error">{{ $message }}</p>
            @enderror
        </fieldset>

        <div class="grid gap-x-6 sm:grid-cols-3">
            <x-form-field label="Order" name="sort_order" hint="Lower numbers come first.">
                <input class="input" id="sort_order" type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $project->sort_order ?? 0) }}">
            </x-form-field>

            <div class="field">
                <span class="label">Visibility</span>
                <label class="mb-2 flex items-center gap-2">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $project->is_published))> Published
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))> Featured on the home page
                </label>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn btn-primary">{{ $editing ? 'Save changes' : 'Create project' }}</button>
            <a href="{{ route('admin.projects.index') }}" class="btn">Cancel</a>
        </div>
    </form>

    {{-- Outside the main form: HTML does not allow a form inside a form. --}}
    @if ($editing && $project->images->isNotEmpty())
        <section class="mt-12 max-w-3xl">
            <h2 class="font-serif text-xl font-semibold">Current screenshots</h2>
            <ul class="mt-4 grid gap-4 sm:grid-cols-3">
                @foreach ($project->images as $image)
                    <li>
                        <img src="{{ $image->url }}" alt="Screenshot {{ $loop->iteration }}" class="aspect-[4/3] w-full rounded-lg border border-line object-cover">
                        <form method="POST" action="{{ route('admin.projects.images.destroy', [$project, $image]) }}" class="mt-2" onsubmit="return confirm('Remove this picture?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-small btn-danger">Remove</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    @push('scripts')
        @vite('resources/js/admin.js')
    @endpush
</x-layouts.admin>
