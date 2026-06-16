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
        Schema::create('workorders', function(Blueprint $table){
            $table->id();
            $table->string('workorder_code')->unique();
            $table->foreignId('request_id')->constrained('requests')->onDelete('restrict');
            $table->foreignId('completed_by')->nullable()->constrained('users', 'id')->onDelete('restrict');
            $table->string('status')->default('pending');
            $table->string('type')->nullable();

            //in-house
            $table->string('priority_level')->nullable();
            $table->decimal('inhouse_cost', 8, 2)->nullable();
            $table->string('estimated_duration')->nullable();
            $table->text('instructions')->nullable();

            //subcontractor
            $table->string('sub_name')->nullable();
            $table->string('sub_document')->nullable();
            $table->text('sub_details')->nullable();
            $table->decimal('sub_cost', 8, 2)->nullable();
            $table->date('sub_date_released')->nullable();
            $table->date('sub_date_returned')->nullable();

            //accomplisment report
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->text('accomplishment_details')->nullable();

            //spareparts
            $table->boolean('has_spare_parts')->default(false);
            $table->json('spare_parts')->nullable();

            //vehicle stuff
            $table->boolean('has_vehicle')->default(false);
            $table->boolean('has_minor')->default(false);
            $table->string('vehicle_minor_details')->nullable();
            $table->boolean('has_major')->default(false);
            $table->string('vehicle_major_details')->nullable();
            $table->boolean('has_change_oil')->default(false);
            $table->date('last_change_oil_date')->nullable();
            $table->string('meter_reading')->nullable();
            $table->boolean('has_insurance')->default(false);
            $table->date('insurance_date')->nullable();
            $table->boolean('has_registration')->default(false);
            $table->date('registration_date')->nullable();
            $table->boolean('has_other')->default(false);
            $table->text('other_details')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workorders');
    }
};
