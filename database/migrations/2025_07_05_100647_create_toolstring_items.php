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
        Schema::create('toolstring_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('toolstring_type_id')
                ->constrained('toolstring_types')
                ->onDelete('cascade'); // Foreign key to toolstring_types table
            $table->uuid('thread_id')
                ->nullable()
                ->constrained('threads'); // Foreign key to threads table
            $table->uuid('thread_size_id')
                ->nullable()
                ->constrained('thread_sizes'); // Foreign key to thread_sizes table
            $table->string('name');
            $table->text('description');
            $table->string('image');
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
        Schema::dropIfExists('toolstring_items');
    }
};
