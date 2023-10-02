<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->char('tokenable_id', 36)->change();
        });
    }

    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // If you need to revert the change, you can do so here.
            // However, in most cases, changing the data type of a primary key column is not recommended.
        });
    }
};
