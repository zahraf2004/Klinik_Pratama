<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::rename('profil__pasien', 'profil_pasien');
    }

    public function down()
    {
        Schema::rename('profil_pasien', 'profil__pasien');
    }
};
