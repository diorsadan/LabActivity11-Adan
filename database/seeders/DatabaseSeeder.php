<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // authors first because posts need an author_id
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
