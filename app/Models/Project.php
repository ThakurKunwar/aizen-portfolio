<?php

namespace App\Models;

use App\Enums\RepoProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'summary', 'description',
        'repo_url', 'provider', 'show_repo_link', 'live_url',
        'language', 'topics', 'cover',
        'is_featured', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'provider'       => RepoProvider::class,
            'topics'         => 'array',
            'show_repo_link' => 'boolean',
            'is_featured'    => 'boolean',
            'is_published'   => 'boolean',
            'sort_order'     => 'integer',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /** asset() follows the address the visitor is on, so it works on any host or port. */
    protected function coverUrl(): Attribute
    {
        return Attribute::get(fn () => $this->cover ? asset('storage/'.$this->cover) : null);
    }

    /** The repository link, only when it is meant to be public. */
    protected function publicRepoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->show_repo_link ? $this->repo_url : null);
    }
}
