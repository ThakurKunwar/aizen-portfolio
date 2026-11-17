<?php

namespace App\Http\Controllers;

use App\Repositories\ProjectRepository;
use Illuminate\View\View;

/** A plain, printable CV built from the same data as the site. Print it and choose "Save as PDF". */
class CvController extends Controller
{
    public function __construct(private readonly ProjectRepository $projects) {}

    public function __invoke(): View
    {
        return view('cv.show', ['projects' => $this->projects->published(featuredOnly: true, limit: 6)]);
    }
}
