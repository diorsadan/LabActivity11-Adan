<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // pivot table for many-to-many (1 post has many tags, 1 tag has many posts)
    // name is both models singular + alphabetical (post_tag) so laravel finds it by itself
    // runs after tags because of the timestamp in the file name
    public function up(): void
    {
        Schema::create('post_tag', function (Blueprint $table) {
            // delete a post or a tag = its rows here get deleted too
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            // combo primary key so the same tag can't be attached to a post twice
            $table->primary(['post_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
