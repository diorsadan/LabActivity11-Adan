<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Purok Adan', 'Urgent', 'Libre', 'Schedule', 'Bakuna'];

        foreach ($names as $name) {
            Tag::create(['name' => $name]);
        }

        $tags = Tag::all();

        // 1 to 3 random tags per post
        foreach (Post::all() as $post) {
            $post->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        }

        // sync replaces the random tags so the pinned post only has these 2
        $pinned = Post::where('is_pinned', true)->first();
        $pinned->tags()->sync(
            Tag::whereIn('name', ['Purok Adan', 'Urgent'])->pluck('id')
        );
    }
}
