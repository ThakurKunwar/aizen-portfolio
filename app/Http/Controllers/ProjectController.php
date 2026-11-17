<?php

namespace App\Http\Controllers;

use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $language = trim((string) $request->query('language'));

        return view('projects.index', [
            'projects'  => $this->projects->published($search ?: null, language: $language ?: null),
            'languages' => $this->projects->languages(),
            'search'    => $search,
            'language'  => $language,
        ]);
    }

    public function show(string $slug): View
    {
        $project = $this->projects->findPublished($slug);

        abort_if($project === null, 404);

        return view('projects.show', ['project' => $project]);
    }
}
