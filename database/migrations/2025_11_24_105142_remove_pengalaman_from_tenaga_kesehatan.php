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
        Schema::table('tenaga_kesehatan', function (Blueprint $table) {
            $table->dropColumn('pengalaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenaga_kesehatan', function (Blueprint $table) {
            $table->text('pengalaman')->nullable()->after('sip');
        });
    }
};
