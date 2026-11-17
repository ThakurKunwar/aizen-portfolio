<?php

/*
|--------------------------------------------------------------------------
| Portfolio content
|--------------------------------------------------------------------------
| Everything about YOU that is not a project lives here. Projects are added
| from the admin panel. Search for "EDIT" and make it yours; the same data
| feeds the website, the CV page and the public API (/api/v1/about, ...).
*/

return [

    'name'     => env('PORTFOLIO_NAME', 'Aizen Kunwar'),
    'role'     => env('PORTFOLIO_ROLE', 'Back-end developer'),
    'city'     => 'Kathmandu, Nepal',
    'headline' => 'I build Laravel back-ends for problems people in Nepal deal with every day.',

    // EDIT: contact details
    'email'    => env('PORTFOLIO_EMAIL', 'you@example.com'),
    'github'   => env('PORTFOLIO_GITHUB', 'https://github.com/ThakurKunwar'),
    'linkedin' => env('PORTFOLIO_LINKEDIN'),

    'admin' => [
        'name'     => env('ADMIN_NAME', 'Aizen'),
        'email'    => env('ADMIN_EMAIL', 'admin@example.com'),
        'password' => env('ADMIN_PASSWORD', 'password'),
    ],

    // EDIT: two honest sentences. They show on the home page.
    'bio' => [
        'I am a back-end developer from Butwal, now working in Kathmandu. I build Laravel and Livewire apps for problems people here deal with every day.',
        'I learn by reading how experienced developers structure real projects, then rebuilding what works until I can explain every line.',
    ],

    // EDIT: what the /api/v1/about route answers with.
    'about' => [
        'from'   => 'Butwal',
        'focus'  => 'Laravel back-ends for everyday problems',
        'status' => 'open to back-end roles',
    ],

    'skills' => [
        'backend'   => ['PHP', 'Laravel', 'Livewire', 'REST APIs', 'MySQL', 'SQLite'],
        'documents' => ['DomPDF', 'Excel import'],
        'tools'     => ['Git', 'GitHub', 'GitLab', 'WSL'],
    ],

    // EDIT: your real dates and what you actually did.
    'experience' => [
        [
            'title'   => 'Junior back-end developer',
            'place'   => 'Tech101, Kathmandu',
            'period'  => '2026 to now',
            'summary' => 'Build and maintain back-end features and APIs in a team codebase, working through GitLab merge requests and code review.',
        ],
        [
            'title'   => 'BSc CSIT',
            'place'   => 'Tribhuvan University',
            'period'  => 'Completed 2026',
            'summary' => null,
        ],
    ],

    // EDIT: make these sound like you.
    'principles' => [
        'I start from a real problem. I have dropped ideas that sounded good but solved nothing, and kept the ones people would use.',
        'I learn from good code. I read how experienced developers structure their projects, then reuse what works.',
        'I rebuild when it is wrong. I restarted shop-shop from scratch instead of patching a weak base.',
    ],

    'life' => [
        'futsal'      => 'weekends',
        'mountains'   => ['Mustang', 'Annapurna Base Camp'],
        'collects'    => 'foreign currencies',
        'club'        => 'Real Madrid',
        'inspiration' => 'Cristiano Ronaldo',
    ],

];
