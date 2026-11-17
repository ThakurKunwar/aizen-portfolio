<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /** The two switches shown on the list page, and the column each one flips. */
    private const TOGGLES = [
        'published' => 'is_published',
        'featured'  => 'is_featured',
    ];

    public function __construct(
        private readonly ProjectRepository $projects,
        private readonly ProjectService $service,
    ) {}

    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => $this->projects->paginateForAdmin(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new Project(['is_published' => true, 'show_repo_link' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $project = $this->service->create(
            $request->projectData(),
            $request->file('cover'),
            $request->file('images', []),
        );

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('status', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', ['project' => $project->load('images')]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->service->update(
            $project,
            $request->projectData(),
            $request->file('cover'),
            $request->boolean('remove_cover'),
            $request->file('images', []),
        );

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('status', 'Project saved.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->service->delete($project);

        return redirect()
            ->route('admin.projects.index')
            ->with('status', 'Project deleted.');
    }

    public function toggle(Project $project, string $field): RedirectResponse
    {
        $this->service->toggle($project, self::TOGGLES[$field]);

        return back()->with('status', 'Updated.');
    }
}
