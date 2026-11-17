<x-layouts.admin title="Projects">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="font-serif text-3xl font-semibold">Projects</h1>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">New project</a>
    </div>

    @if ($projects->isEmpty())
        <p class="text-slate">Nothing here yet. Add your first project.</p>
    @else
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Project</th>
                        <th scope="col">Visible</th>
                        <th scope="col">Featured</th>
                        <th scope="col"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td>
                                <a href="{{ route('admin.projects.edit', $project) }}" class="font-semibold hover:text-flag">{{ $project->title }}</a>
                                <p class="text-sm text-slate">{{ $project->language ?? 'No language' }}@if ($project->provider), {{ $project->provider->label() }}@endif</p>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.projects.toggle', [$project, 'published']) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="pill {{ $project->is_published ? 'pill-on' : 'pill-off' }}" title="Click to switch">
                                        {{ $project->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.projects.toggle', [$project, 'featured']) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="pill {{ $project->is_featured ? 'pill-on' : 'pill-off' }}" title="Click to switch">
                                        {{ $project->is_featured ? 'Featured' : 'Not featured' }}
                                    </button>
                                </form>
                            </td>
                            <td class="whitespace-nowrap text-right">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-small">Edit</a>
                                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="inline" onsubmit="return confirm('Delete this project and its pictures?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($projects->hasPages())
            <nav class="mt-6 flex items-center justify-between text-sm" aria-label="Pages">
                @if ($projects->onFirstPage())
                    <span class="text-slate">Previous</span>
                @else
                    <a href="{{ $projects->previousPageUrl() }}" class="link">Previous</a>
                @endif

                <span class="text-slate">Page {{ $projects->currentPage() }} of {{ $projects->lastPage() }}</span>

                @if ($projects->hasMorePages())
                    <a href="{{ $projects->nextPageUrl() }}" class="link">Next</a>
                @else
                    <span class="text-slate">Next</span>
                @endif
            </nav>
        @endif
    @endif
</x-layouts.admin>
