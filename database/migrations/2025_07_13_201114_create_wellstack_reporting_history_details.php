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
        Schema::create('wellstack_reporting_history_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->uuid('wellstack_reporting_history_id')
                ->constrained('wellstack_reporting_histories')
                ->onDelete('cascade')
                ->name('fk_wrhd_history_id'); // Custom constraint name
            $table->uuid('wellstack_type_id')
                ->constrained('wellstack_types')
                ->onDelete('cascade')
                ->name('fk_wrhd_type_id'); // Custom constraint name
            $table->uuid('wellstack_item_id')
                ->constrained('wellstack_items')
                ->onDelete('cascade')
                ->name('fk_wrhd_item_id'); // Custom constraint name

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
        Schema::dropIfExists('wellstack_reporting_history_details');
    }
};
