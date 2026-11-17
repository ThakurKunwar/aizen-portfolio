<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = (string) config('portfolio.admin.password');

        if (app()->isProduction() && $password === 'password') {
            throw new RuntimeException('Set ADMIN_PASSWORD in .env before seeding in production.');
        }

        User::updateOrCreate(
            ['email' => config('portfolio.admin.email')],
            ['name' => config('portfolio.admin.name'), 'password' => $password],
        );
    }
}
