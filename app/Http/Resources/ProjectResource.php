<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Project */
class ProjectResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug'     => $this->slug,
            'title'    => $this->title,
            'summary'  => $this->summary,
            'language' => $this->language,
            'topics'   => $this->topics ?? [],
            'featured' => $this->is_featured,
            'provider' => $this->provider?->label(),
            'repo'     => $this->public_repo_url,
            'live'     => $this->live_url,
            'cover'    => $this->cover_url,
            'page'     => route('projects.show', $this->slug),

            // Only on /projects/{slug}, so the list stays small.
            'description' => $this->when(
                $request->routeIs('api.v1.projects.show'),
                fn () => $this->description
            ),
            'images' => $this->whenLoaded('images', fn () => $this->images
                ->map(fn ($image) => ['url' => $image->url, 'caption' => $image->caption])
                ->all()),
        ];
    }
}
