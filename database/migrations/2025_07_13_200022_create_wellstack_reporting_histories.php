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
        Schema::create('wellstack_reporting_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('client')->nullable();
            $table->string('field')->nullable();
            $table->string('well_name_number')->nullable();
            $table->string('min_restriction')->nullable();
            $table->string('kop')->nullable();
            $table->string('category')->nullable();
            $table->string('bhp')->nullable();
            $table->string('bhst')->nullable();
            $table->string('so')->nullable();
            $table->string('supplier')->nullable();
            $table->date('date_drawn')->nullable();
            $table->string('drawn_by')->nullable();

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
        Schema::dropIfExists('wellstack_reporting_histories');
    }
};
