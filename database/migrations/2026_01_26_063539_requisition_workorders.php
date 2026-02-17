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
        Schema::create('requisition_workorders', function(Blueprint $table){
            $table->id();
            $table->foreignId('workorder_id')->constrained('workorders')->onDelete('cascade');
            $table->date('acquisition_date')->nullable();
            $table->string('asset_name');
            $table->decimal('estimated_cost', 15, 2)->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acquisition_workorders');
    }
};
