<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Lola Adan',
            'email' => 'lola.adan@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        $kagawadUser = User::create([
            'name' => 'Kagawad Dion Ren Adan',
            'email' => 'kagawad.adan@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        // link the Lab 10 kagawad author to the new user account
        $author = Author::where('email', 'kagawad.adan@bulletin.test')->first();
        $author->update(['user_id' => $kagawadUser->id]);

        User::factory()->count(8)->create();
    }
}
