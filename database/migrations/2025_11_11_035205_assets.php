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
        Schema::create('assets', function(Blueprint $table){
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->string('serial_name')->nullable();
            $table->string('status')->default('active'); //enum values handles in the model
            $table->text('description')->nullable();
            $table->boolean('is_depreciable')->default(false);
            $table->string('image_path')->nullable();
            //foreign keys
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('custodian_id')->nullable()->constrained('employees')->onDelete('set null');
            
            //finance
            $table->date('acquisition_date')->nullable();
            $table->decimal('cost', 15, 2)->default(0);
            $table->decimal('salvage_value', 15, 2)->default(0);
            $table->integer('useful_life_in_years')->nullable();
            $table->date('end_of_life_date')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
