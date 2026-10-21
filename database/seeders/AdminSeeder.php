<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => config('auth.super_admin.email'),
        ],
        [
            'name' => config('auth.super_admin.name'),
            'email' => config('auth.super_admin.email'),
            'password' => Hash::make(config('auth.super_admin.password')),
        ]);
    }
}
