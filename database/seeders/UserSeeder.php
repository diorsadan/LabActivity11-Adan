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
        // resident account, no author row so she can comment but not post
        // Hash::make so the password is never saved as plain text
        User::create([
            'name' => 'Lola Adan',
            'email' => 'lola.adan@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        // login account for my kagawad, saved in a variable to get the id
        $kagawadUser = User::create([
            'name' => 'Kagawad Dion Ren Adan',
            'email' => 'kagawad.adan@bulletin.test',
            'password' => Hash::make('bulletin2026'),
        ]);

        // link the Lab 10 kagawad author to the new user account
        // now auth()->user()->author works for the kagawad
        $author = Author::where('email', 'kagawad.adan@bulletin.test')->first();
        $author->update(['user_id' => $kagawadUser->id]);

        // 8 random users so there are more people commenting
        User::factory()->count(8)->create();
    }
}
