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
            $table->id();
            $table->foreignId('toolstring_type_id')
                ->constrained('toolstring_types')
                ->onDelete('cascade');
            $table->foreignId('thread_id')
                ->nullable()
                ->constrained('threads');
            $table->foreignId('thread_size_id')
                ->nullable()
                ->constrained('thread_sizes');
            $table->string('name');
            $table->text('description');
            $table->string('image');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unsignedBigInteger('updated_by')->nullable();
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
