<?php

// database/migrations/[timestamp]_create_accountant_billing_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accountant_billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consumer_id');
            $table->string('consumer_type');
            $table->string('meter_no');
            $table->date('due_date');
            $table->decimal('previous_reading', 10, 2)->default(0.00);
            $table->decimal('current_reading', 10, 2);
            $table->decimal('consumption', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['paid', 'unpaid', 'overdue'])->default('unpaid');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accountant_billings');
    }
};