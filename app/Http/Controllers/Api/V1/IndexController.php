<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\ApiCatalogue;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'name'      => config('portfolio.name').' portfolio API',
            'version'   => 'v1',
            'note'      => 'Read-only. Every route is a GET and answers in JSON.',
            'endpoints' => array_map(fn (array $route) => [
                'method'      => 'GET',
                'path'        => '/api/v1'.$route['path'],
                'description' => $route['description'],
            ], ApiCatalogue::routes()),
        ]);
    }
}
