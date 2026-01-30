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
        Schema::create('requests', function(Blueprint $table){
            $table->id();
            $table->string('request_code')->unique();
            $table->text('description')->nullable();
            $table->dateTime('date_requested');
            $table->dateTime('date_approved')->nullable();
            $table->string('asset_name')->nullable();
            //relations
            $table->foreignId('requested_by')->constrained('users', 'id')->onDelete('restrict');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('restrict');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users', 'id')->onDelete('restrict');
            $table->foreignId('asset_id')->nullable()->constrained('assets')->onDelete('set null');
            //enums
            $table->string('type');
            $table->string('service_type')->nullable(); //if its a service
            $table->string('status')->default('pending');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
