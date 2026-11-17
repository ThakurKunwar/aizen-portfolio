<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // the admin routes already sit behind the "auth" middleware
    }

    protected function prepareForValidation(): void
    {
        $topics = collect(explode(',', (string) $this->input('topics')))
            ->map(fn (string $topic) => trim($topic))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->merge([
            'topics'         => $topics,
            'is_featured'    => $this->boolean('is_featured'),
            'is_published'   => $this->boolean('is_published'),
            'show_repo_link' => $this->boolean('show_repo_link'),
            'remove_cover'   => $this->boolean('remove_cover'),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:120'],
            'slug'           => ['nullable', 'string', 'max:140', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'summary'        => ['required', 'string', 'max:220'],
            'description'    => ['nullable', 'string', 'max:10000'],
            'repo_url'       => ['nullable', 'url:http,https', 'max:255'],
            'live_url'       => ['nullable', 'url:http,https', 'max:255'],
            'language'       => ['nullable', 'string', 'max:40'],
            'topics'         => ['array', 'max:8'],
            'topics.*'       => ['string', 'max:30'],
            'sort_order'     => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_featured'    => ['boolean'],
            'is_published'   => ['boolean'],
            'show_repo_link' => ['boolean'],
            'remove_cover'   => ['boolean'],
            'cover'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'images'         => ['nullable', 'array', 'max:8'],
            'images.*'       => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    /**
     * The fields that belong on the projects table (files are handled separately).
     *
     * @return array<string, mixed>
     */
    public function projectData(): array
    {
        return collect($this->validated())
            ->except(['cover', 'images', 'remove_cover'])
            ->all();
    }
}
