<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $search = trim((string) $request->query('q'));
        $language = trim((string) $request->query('language'));
        $limit = min(max($request->integer('limit', 50), 1), 100);

        $projects = $this->projects->published(
            search: $search ?: null,
            featuredOnly: $request->boolean('featured'),
            language: $language ?: null,
            limit: $limit,
        );

        return ProjectResource::collection($projects)
            ->additional(['meta' => ['count' => $projects->count()]]);
    }

    public function show(string $slug): ProjectResource
    {
        $project = $this->projects->findPublished($slug);

        abort_if($project === null, 404);

        return new ProjectResource($project);
    }
}
