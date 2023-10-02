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
        Schema::create('commission_process', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('short_code');
            $table->string('organization_id');
            $table->decimal('amount', 10, 2);
            $table->boolean('status')->default(1);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_process');
    }
};
