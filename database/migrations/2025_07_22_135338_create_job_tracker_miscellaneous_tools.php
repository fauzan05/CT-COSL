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
        Schema::create('job_tracker_miscellaneous_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_tracker_id')
                ->constrained('job_trackers')
                ->onDelete('cascade');
            $table->foreignId('miscellaneous_tool_id')
                ->constrained('miscellaneous_tools')
                ->onDelete('cascade');
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
        Schema::dropIfExists('job_tracker_miscellaneous_tools');
    }
};
