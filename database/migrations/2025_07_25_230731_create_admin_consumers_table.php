<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_consumers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix', 20)->nullable();
            $table->string('contact_number', 20);
            $table->string('meter_no')->unique();
            $table->string('address', 500);
            $table->text('address_information')->nullable();
            $table->date('connection_date');
            $table->enum('consumer_type', ['residential', 'commercial', 'institutional']);
            $table->enum('status', ['active', 'inactive', 'disconnected', 'cut']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_consumers');
    }
};
