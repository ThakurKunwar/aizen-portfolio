<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;

class ProjectImageController extends Controller
{
    public function __construct(private readonly ProjectService $service) {}

    public function destroy(Project $project, ProjectImage $image): RedirectResponse
    {
        $this->service->deleteImage($image);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('status', 'Picture removed.');
    }
}
