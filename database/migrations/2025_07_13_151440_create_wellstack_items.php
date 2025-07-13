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
        Schema::create('wellstack_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wellstack_type_id')
                ->constrained('wellstack_types')
                ->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->text('serial_number');
            $table->decimal('height', 8, 2)->nullable();
            $table->string('height_unit')->default('ft');
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('weight_unit')->default('lbs');
            $table->decimal('pressure_rating', 8, 2)->nullable();
            $table->string('pressure_rating_unit')->default('psi');
            $table->string('owner')->nullable();
            $table->decimal('shear_ram_dist_from_bottom', 8, 2)->nullable();
            $table->string('shear_ram_dist_from_bottom_unit')->default('ft');
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
        Schema::dropIfExists('wellstack_items');
    }
};