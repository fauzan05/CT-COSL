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
        Schema::create('thread_sizes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('thread_id')
                ->constrained('threads')
                ->onDelete('cascade'); // Foreign key to threads table
            $table->string('top_connection')->nullable();
            $table->string('bottom_connection')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->uuid('updated_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_sizes');
    }
};
