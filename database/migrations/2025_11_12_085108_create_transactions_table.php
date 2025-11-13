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
        Schema::create('transactions', function (Blueprint $table) {
            
            $table->string('id', 70)->primary(); 
            $table->string('reference', 70)->unique()->nullable();
            $table->string('payment_method', 30)->nullable();
            $table->string('payment_name', 100)->nullable();
            $table->integer('amount');
            $table->string('pay_code', 100)->nullable();
            $table->string('status', 50)->default('UNPAID')->nullable(); 
            $table->timestamp('expired_time')->nullable();
            $table->string('instruction_title', 255)->nullable();
            $table->text('instruction_step')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable(); 
            $table->softDeletes('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
