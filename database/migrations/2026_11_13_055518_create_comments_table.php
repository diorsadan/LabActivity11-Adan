<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // which post the comment is on, delete post = its comments get deleted too
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            // who wrote it, delete user = their comments get deleted too
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body'); // text not string so it can be longer than 255
            $table->timestamps(); // created_at is the date shown under each comment
        });
    }

    // rollback just drops the whole table
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
