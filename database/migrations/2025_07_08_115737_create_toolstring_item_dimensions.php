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
        Schema::create('toolstring_item_dimensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toolstring_item_id')
                ->constrained('toolstring_items')
                ->onDelete('cascade');
            $table->decimal('outer_diameter', 8, 2)->nullable();
            $table->string('outer_diameter_unit')->default('inch');
            $table->decimal('inner_diameter', 8, 2)->nullable();
            $table->string('inner_diameter_unit')->default('inch');
            $table->decimal('length', 8, 2)->nullable();
            $table->string('length_unit')->default('inch');
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
        Schema::dropIfExists('toolstring_item_dimensions');
    }
};
