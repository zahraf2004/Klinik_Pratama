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
        // Foreign keys untuk tabel appointment
        Schema::table('appointment', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('set null');
        });

        // Foreign keys untuk tabel tenaga_kesehatan
        Schema::table('tenaga_kesehatan', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Foreign keys untuk tabel profil_pasien
        Schema::table('profil_pasien', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Foreign keys untuk tabel chat_sessions
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Foreign keys untuk tabel ch_messages
        Schema::table('ch_messages', function (Blueprint $table) {
            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Foreign keys untuk tabel ch_favorites
        Schema::table('ch_favorites', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('favorite_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Foreign keys untuk tabel subscriptions (jika ada)
        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Foreign keys untuk tabel transactions (jika ada)
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys
        Schema::table('appointment', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['confirmed_by']);
        });

        Schema::table('tenaga_kesehatan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('profil_pasien', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
        });

        Schema::table('ch_messages', function (Blueprint $table) {
            $table->dropForeign(['from_id']);
            $table->dropForeign(['to_id']);
        });

        Schema::table('ch_favorites', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['favorite_id']);
        });

        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }
    }
};
