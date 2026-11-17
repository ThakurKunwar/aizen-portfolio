<?php

namespace App\Services;

use App\Enums\RepoProvider;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Support\RepositoryUrl;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Everything that changes a project: the row, its slug and its pictures.
 * Controllers stay thin and call this.
 */
class ProjectService
{
    /**
     * @param  array<string, mixed>  $data
     * @param  UploadedFile[]  $images
     */
    public function create(array $data, ?UploadedFile $cover = null, array $images = []): Project
    {
        return DB::transaction(function () use ($data, $cover, $images) {
            $data = $this->prepare($data);
            $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: $data['title']);
            $data['cover'] = $cover?->store('projects/covers', 'public');

            $project = Project::create($data);
            $this->addImages($project, $images);

            return $project;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  UploadedFile[]  $images
     */
    public function update(
        Project $project,
        array $data,
        ?UploadedFile $cover = null,
        bool $removeCover = false,
        array $images = [],
    ): Project {
        $oldCover = null;

        DB::transaction(function () use ($project, $data, $cover, $removeCover, $images, &$oldCover) {
            $data = $this->prepare($data);

            $slug = $data['slug'] ?? null;
            $data['slug'] = ($slug && $slug !== $project->slug)
                ? $this->uniqueSlug($slug, $project->id)
                : $project->slug;

            if ($cover) {
                $oldCover = $project->cover;
                $data['cover'] = $cover->store('projects/covers', 'public');
            } elseif ($removeCover) {
                $oldCover = $project->cover;
                $data['cover'] = null;
            }

            $project->update($data);
            $this->addImages($project, $images);
        });

        // Only after the database change succeeded, so a failure never loses a file we still need.
        $this->deleteFile($oldCover);

        return $project->refresh();
    }

    public function delete(Project $project): void
    {
        $paths = $project->images()->pluck('path')->push($project->cover)->filter()->all();

        DB::transaction(function () use ($project) {
            $project->images()->delete();
            $project->delete();
        });

        foreach ($paths as $path) {
            $this->deleteFile($path);
        }
    }

    public function deleteImage(ProjectImage $image): void
    {
        $path = $image->path;

        $image->delete();

        $this->deleteFile($path);
    }

    public function toggle(Project $project, string $column): Project
    {
        $project->update([$column => ! $project->{$column}]);

        return $project;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepare(array $data): array
    {
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['provider'] = $this->detectProvider($data['repo_url'] ?? null)?->value;

        return $data;
    }

    /** Reads the host of the pasted link ("github.com", "gitlab.com", your own GitLab). No network call. */
    private function detectProvider(?string $url): ?RepoProvider
    {
        if (! $url) {
            return null;
        }

        try {
            return RepositoryUrl::parse($url, $this->gitlabHost())->provider;
        } catch (InvalidArgumentException) {
            return null; // a link we do not recognise is still saved and shown, just without a label
        }
    }

    private function gitlabHost(): string
    {
        return (string) (parse_url((string) config('services.gitlab.url'), PHP_URL_HOST) ?: 'gitlab.com');
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: 'project';
        $candidate = $slug;
        $suffix = 2;

        while (Project::query()
            ->where('slug', $candidate)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $slug.'-'.$suffix++;
        }

        return $candidate;
    }

    /** @param  UploadedFile[]  $files */
    private function addImages(Project $project, array $files): void
    {
        $order = (int) ProjectImage::where('project_id', $project->id)->max('sort_order');

        foreach ($files as $file) {
            $project->images()->create([
                'path'       => $file->store('projects/gallery', 'public'),
                'sort_order' => ++$order,
            ]);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
