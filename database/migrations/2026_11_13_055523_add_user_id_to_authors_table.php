<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // new migration so the Lab 10 authors migration stays the same
    // (never edit a migration that already ran, just add a new one)
    public function up(): void
    {
        // Schema::table = change an existing table, Schema::create = new table
        Schema::table('authors', function (Blueprint $table) {
            // nullable = old authors without a login account are ok
            // unique = 1 user can only be 1 author (one-to-one)
            // nullOnDelete = delete the user, the author stays but user_id becomes null
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
        });
    }

    // undo in reverse order: foreign key, then the unique index, then the column
    // sqlite won't drop a column that still has an index on it
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
