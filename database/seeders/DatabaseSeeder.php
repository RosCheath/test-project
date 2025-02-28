<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('11112222'),
            'remember_token' => Str::random(10),
        ]);
        User::factory(10)->create();
    }
}
