<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/** The parts of the portfolio that come from config/portfolio.php. */
class ProfileController extends Controller
{
    public function about(): JsonResponse
    {
        return $this->data([
            'name' => config('portfolio.name'),
            'role' => config('portfolio.role'),
            'city' => config('portfolio.city'),
            ...config('portfolio.about'),
        ]);
    }

    public function skills(): JsonResponse
    {
        return $this->data(config('portfolio.skills'));
    }

    public function experience(): JsonResponse
    {
        return $this->data(config('portfolio.experience'));
    }

    public function contact(): JsonResponse
    {
        return $this->data(array_filter([
            'email'    => config('portfolio.email'),
            'github'   => config('portfolio.github'),
            'linkedin' => config('portfolio.linkedin'),
            'form'     => route('home').'#contact',
        ]));
    }

    public function life(): JsonResponse
    {
        return $this->data(config('portfolio.life'));
    }

    /** @param  array<string, mixed>  $data */
    private function data(array $data): JsonResponse
    {
        return response()->json(['data' => $data]);
    }
}
