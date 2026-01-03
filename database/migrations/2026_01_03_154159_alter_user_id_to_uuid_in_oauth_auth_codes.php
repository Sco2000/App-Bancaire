<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. On supprime tous les tokens, car ils sont jetables
        DB::statement('TRUNCATE TABLE oauth_access_tokens CASCADE');

        // 2. On supprime l'ancienne colonne
        DB::statement('ALTER TABLE oauth_access_tokens DROP COLUMN user_id');

        // 3. On ajoute une nouvelle colonne UUID
        DB::statement('ALTER TABLE oauth_access_tokens ADD COLUMN user_id UUID');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE oauth_access_tokens DROP COLUMN user_id');
        DB::statement('ALTER TABLE oauth_access_tokens ADD COLUMN user_id BIGINT');

    }
};
