<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('verification_code')->nullable()->after('password');
            $table->timestamp('verification_code_sent_at')->nullable()->after('verification_code');
            $table->date('birthdate')->nullable()->after('last_name'); // Also missing in your migration
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['verification_code', 'verification_code_sent_at', 'birthdate']);
        });
    }
};