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
        Schema::create('disposal_workorders', function(Blueprint $table){
            $table->id();
            $table->foreignId('workorder_id')->constrained('workorders')->onDelete('cascade');
            //even if asset gets deleted the disposal record stays
            $table->foreignId('asset_id')->nullable()->constrained('assets')->onDelete('set null');
            $table->string('disposal_method');
            $table->dateTime('disposal_date');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposal_workorders');
    }
};
