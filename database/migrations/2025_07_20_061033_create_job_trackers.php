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
        Schema::create('job_trackers', function (Blueprint $table) {
            $table->id();
            $table->string('well_name')->nullable();
            $table->string('company_man')->nullable();
            $table->string('bj_representative')->nullable();
            $table->date('job_start_date')->nullable();
            $table->date('job_finish_date')->nullable();
            $table->integer('job_days')->nullable();
            $table->decimal('max_deviation', 8, 2)->nullable();
            $table->decimal('depth_md', 10, 2)->nullable();
            $table->string('depth_md_unit')->default('ft');
            $table->decimal('depth_tvd', 10, 2)->nullable();
            $table->string('depth_tvd_unit')->default('ft');
            $table->decimal('bh_pressure', 10, 2)->nullable();
            $table->string('bh_pressure_unit')->default('psi');
            $table->decimal('bh_temp', 10, 2)->nullable();
            $table->string('bh_temp_unit')->default('°F');
            $table->decimal('nitrogen_volume', 10, 2)->nullable();
            $table->string('nitrogen_volume_unit')->default('Gals');
            $table->decimal('cement_volume', 10, 2)->nullable();
            $table->string('cement_volume_unit')->default('Bbls');
            
            $table->string('revenue_currency')->default('USD');
            $table->decimal('revenue_coiled_tubing', 10, 2)->nullable();
            $table->decimal('revenue_pumping', 10, 2)->nullable();
            $table->decimal('revenue_special_tools', 10, 2)->nullable();
            $table->decimal('revenue_acid', 10, 2)->nullable();
            $table->decimal('revenue_nitrogen', 10, 2)->nullable();
            $table->decimal('revenue_cement', 10, 2)->nullable();
            $table->decimal('personnel_charges', 10, 2)->nullable();
            $table->decimal('service_charges', 10, 2)->nullable();
            $table->decimal('other_charges', 10, 2)->nullable();
            $table->decimal('total_revenue', 10, 2)->nullable();

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
        Schema::dropIfExists('job_trackers');
    }
};
