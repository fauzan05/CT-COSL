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
            $table->string('description');
            $table->string('image');
            $table->string('manufacturer');
            $table->decimal('outer_diameter', 8, 2)->nullable();
            $table->decimal('inner_diameter', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->string('comment');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
