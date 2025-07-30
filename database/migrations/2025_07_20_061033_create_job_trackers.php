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
            
            // General Information
            $table->string('well_name')->nullable();
            $table->string('company_man')->nullable();
            $table->string('cosl_ocd_representative')->nullable();
            $table->date('job_start_date')->nullable();
            $table->date('job_finish_date')->nullable();
            $table->integer('job_days')->nullable();
            $table->string('customer')->nullable();
            $table->string('cosl_base')->nullable();

            // Well Information
            $table->string('field_type')->nullable();
            $table->string('wellhead_x_over')->nullable();
            $table->string('field_location')->nullable();
            $table->string('well_status')->nullable();
            $table->string('well_type')->nullable();
            $table->decimal('max_deviation', 8, 2)->nullable();
            $table->decimal('depth_md', 10, 2)->nullable();
            $table->string('depth_md_unit')->default('ft');
            $table->decimal('depth_tvd', 10, 2)->nullable();
            $table->string('depth_tvd_unit')->default('ft');
            $table->decimal('casing_liner_size', 10, 2)->nullable();
            $table->string('casing_liner_size_unit')->default('in');
            $table->decimal('completion_size', 10, 2)->nullable();
            $table->string('completion_size_unit')->default('in');
            $table->decimal('bh_pressure', 10, 2)->nullable();
            $table->string('bh_pressure_unit')->default('psi');
            $table->decimal('bh_temp', 10, 2)->nullable();
            $table->string('bh_temp_unit')->default('°F');

            // Equipment & Tools
            $table->string('nozzle_type')->nullable();
            $table->decimal('max_bha_od', 10, 2)->nullable();
            $table->string('max_bha_od_unit')->default('in');
            $table->string('control_cabin')->nullable();
            $table->string('power_pack')->nullable();
            $table->string('power_reel')->nullable();
            $table->string('cj_injector')->nullable();
            $table->string('bop')->nullable();
            $table->decimal('ct_size', 10, 2)->nullable();
            $table->string('ct_size_unit')->default('in');
            $table->string('ct_grade')->nullable();
            $table->decimal('wt', 10, 2)->nullable();
            $table->string('wt_unit')->default('lb/ft');
            $table->string('ct_string')->nullable();
            $table->string('ct_converter')->nullable();
            $table->string('n2_converter')->nullable();

            // Personnel
            $table->string('ct_supervisor')->nullable();
            $table->string('nitrogen_supervisor')->nullable();
            $table->string('pump_supervisor')->nullable();

            // Treatment
            $table->decimal('nitrogen_volume', 10, 2)->nullable();
            $table->string('nitrogen_volume_unit')->default('Gals');
            $table->decimal('cement_volume', 10, 2)->nullable();
            $table->string('cement_volume_unit')->default('Bbls');
            
            // Revenue - Fix the field names to match controller
            $table->string('revenue_currency')->default('USD');
            $table->decimal('revenue_coiled_tubing', 10, 2)->nullable();
            $table->decimal('revenue_pumping', 10, 2)->nullable();
            $table->decimal('revenue_special_tools', 10, 2)->nullable();
            $table->decimal('revenue_acid', 10, 2)->nullable();
            $table->decimal('revenue_nitrogen_equipment', 10, 2)->nullable(); // Added
            $table->decimal('revenue_nitrogen_product', 10, 2)->nullable();   // Added
            $table->decimal('revenue_cement', 10, 2)->nullable();
            $table->decimal('personnel_charges', 10, 2)->nullable();
            $table->decimal('service_charges', 10, 2)->nullable();
            $table->decimal('other_charges', 10, 2)->nullable();
            $table->decimal('total_revenue', 10, 2)->nullable();
            $table->decimal('material_charges', 10, 2)->nullable();
            $table->decimal('mobilization_charges', 10, 2)->nullable();

            // Timestamps and audit fields
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