<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('water_rates', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['residential', 'commercial', 'institutional']);
            $table->string('range'); // e.g. "0-10", "11", etc.
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_rates');
    }
};