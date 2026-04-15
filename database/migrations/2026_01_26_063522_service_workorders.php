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
        Schema::create('service_workorders', function(Blueprint $table){
            $table->id();
            $table->foreignId('workorder_id')->constrained('workorders')->onDelete('cascade');
            $table->foreignId('asset_id')->nullable()->constrained('assets')->onDelete('set null');
            $table->string('service_type');
            $table->string('maintenance_type')->nullable();
            $table->decimal('cost', 15, 2)->default(0);

            //subcontractor
            $table->string('subcontractor_name')->nullable();
            $table->text('subcontractor_details')->nullable();

            //in house
            $table->foreignId('assigned_to')->nullable()->constrained('employees')->onDelete('set null');
            $table->integer('estimated_hours')->nullable();

            $table->text('instructions')->nullable();
            $table->text('accomplishment_report')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_workorders');
    }
};
