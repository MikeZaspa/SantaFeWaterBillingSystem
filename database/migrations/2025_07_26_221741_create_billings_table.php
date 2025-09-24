<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consumer_id'); // Replaced foreignId with unsignedBigInteger
            $table->string('consumer_type');
            $table->string('meter_no');
            $table->decimal('previous_reading', 10, 2)->default(0.00);
            $table->decimal('current_reading', 10, 2);
            $table->decimal('consumption', 10, 2)->default(0.00);
            $table->date('reading_date');
            $table->timestamps();
            
            $table->index('reading_date');
            $table->index('meter_no');
            
            // If you still want the index on consumer_id (recommended)
            $table->index('consumer_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('billings');
    }
};