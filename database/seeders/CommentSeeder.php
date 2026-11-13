<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        // get users once, then pick random ones from this list
        $users = User::all();

        // 0 to 5 comments per published post, using users that already exist
        // drafts are skipped, pinned post is done separately below
        $posts = Post::where('is_published', true)->where('is_pinned', false)->get();

        foreach ($posts as $post) {
            // sequence gives each comment a different random user
            // passing post_id in create() stops the factory from making new posts
            Comment::factory()
                ->count(rand(0, 5))
                ->sequence(fn ($seq) => ['user_id' => $users->random()->id])
                ->create(['post_id' => $post->id]);
        }

        // pinned post: 1 comment from Lola Adan plus 2 to 4 more, so at least 3
        $pinned = Post::where('is_pinned', true)->first();
        $lola = User::where('email', 'lola.adan@bulletin.test')->first();

        Comment::factory()->create([
            'post_id' => $pinned->id,
            'user_id' => $lola->id,
        ]);

        Comment::factory()
            ->count(rand(2, 4))
            ->sequence(fn ($seq) => ['user_id' => $users->random()->id])
            ->create(['post_id' => $pinned->id]);
    }
}
