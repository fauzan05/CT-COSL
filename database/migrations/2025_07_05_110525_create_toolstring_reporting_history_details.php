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
            $table->id();
            $table->unsignedBigInteger('toolstring_reporting_history_id');
            $table->foreign('toolstring_reporting_history_id', 'trh_detail_fk')
                ->references('id')
                ->on('toolstring_reporting_histories')
                ->onDelete('cascade');

            $table->foreignId('toolstring_type_id')
                ->constrained('toolstring_types')
                ->onDelete('cascade');

            $table->foreignId('toolstring_item_id')
                ->constrained('toolstring_items')
                ->onDelete('cascade');

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
        Schema::dropIfExists('toolstring_reporting_history_details');
    }
};
