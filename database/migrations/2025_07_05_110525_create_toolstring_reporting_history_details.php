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
        Schema::create('toolstring_reporting_history_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Berikan nama constraint yang lebih pendek
            $table->uuid('toolstring_reporting_history_id')
                ->constrained('toolstring_reporting_histories')
                ->onDelete('cascade')
                ->name('fk_trhd_history_id'); // Custom constraint name

            $table->uuid('toolstring_type_id')
                ->constrained('toolstring_types')
                ->onDelete('cascade')
                ->name('fk_trhd_type_id'); // Custom constraint name

            $table->uuid('toolstring_item_id')
                ->constrained('toolstring_items')
                ->onDelete('cascade')
                ->name('fk_trhd_item_id'); // Custom constraint name

            $table->uuid('toolstring_item_dimension_id')
                ->constrained('toolstring_item_dimensions')
                ->onDelete('cascade')
                ->name('fk_trhd_dimension_id'); // Custom constraint name
                
            $table->integer('position')->nullable();
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
        Schema::dropIfExists('toolstring_reporting_history_details');
    }
};
