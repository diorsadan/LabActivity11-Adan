<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // runs when I do php artisan migrate:fresh --seed
    public function run(): void
    {
        // order matters because of the foreign keys:
        // authors first because posts need an author_id
        // UserSeeder needs the kagawad author to already exist to link it
        // tags need posts to attach to
        // users before tags and comments because comments need a user_id
        $this->call([
            AuthorSeeder::class,
            PostSeeder::class,
            UserSeeder::class,
            TagSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
