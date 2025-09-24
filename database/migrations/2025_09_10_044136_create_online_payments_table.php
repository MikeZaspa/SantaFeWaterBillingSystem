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
        // Migration for online_payments table
        Schema::create('online_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id'); 
            $table->unsignedBigInteger('consumer_id');
            $table->string('payment_method'); // gcash, maya, etc.
            $table->decimal('amount', 10, 2);
            $table->string('reference_number')->nullable();
            $table->string('proof_image')->nullable(); // path to uploaded proof
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_payments');
    }
};
