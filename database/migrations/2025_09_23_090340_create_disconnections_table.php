<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisconnectionsTable extends Migration
{
    public function up()
    {
        Schema::create('disconnections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id'); 
            $table->unsignedBigInteger('consumer_id');
            $table->date('disconnection_date');
            $table->text('reason')->nullable();
            $table->enum('status', ['disconnected', 'reconnected'])->default('disconnected');
            $table->foreignId('disconnected_by')->constrained('users')->onDelete('cascade');
            $table->date('reconnection_date')->nullable();
            $table->foreignId('reconnected_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disconnections');
    }
}