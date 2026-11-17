<?php

namespace App\Http\Controllers;

use App\Repositories\ProjectRepository;
use App\Support\ApiCatalogue;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function __invoke(): View
    {
        return view('home', [
            'projects'  => $this->projects->featuredOrLatest(4),
            'apiRoutes' => ApiCatalogue::routes(),
        ]);
    }
}
