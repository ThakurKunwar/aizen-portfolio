<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/** Every query about projects lives here, so controllers never build queries. */
class ProjectRepository
{
    /**
     * @return Collection<int, Project>
     */
    public function published(
        ?string $search = null,
        bool $featuredOnly = false,
        ?string $language = null,
        ?int $limit = null,
    ): Collection {
        return Project::query()
            ->published()
            ->when($featuredOnly, fn (Builder $query) => $query->where('is_featured', true))
            ->when($language, fn (Builder $query) => $query->where('language', $language))
            ->when($search, fn (Builder $query) => $query->where(
                fn (Builder $inner) => $inner
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
            ))
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->when($limit, fn (Builder $query) => $query->limit($limit))
            ->get();
    }

    /**
     * Featured projects for the home page; falls back to the newest ones.
     *
     * @return Collection<int, Project>
     */
    public function featuredOrLatest(int $limit = 4): Collection
    {
        $featured = $this->published(featuredOnly: true, limit: $limit);

        return $featured->isNotEmpty() ? $featured : $this->published(limit: $limit);
    }

    public function findPublished(string $slug): ?Project
    {
        return Project::query()
            ->published()
            ->with('images')
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @return \Illuminate\Support\Collection<int, string>
     */
    public function languages(): \Illuminate\Support\Collection
    {
        return Project::query()
            ->published()
            ->whereNotNull('language')
            ->distinct()
            ->orderBy('language')
            ->pluck('language');
    }

    public function paginateForAdmin(int $perPage = 15): LengthAwarePaginator
    {
        return Project::query()
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
