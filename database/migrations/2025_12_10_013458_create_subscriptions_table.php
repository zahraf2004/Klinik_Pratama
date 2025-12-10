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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('plan_name'); // 'monthly', 'yearly'
            $table->decimal('price', 10, 2);
            $table->string('status')->default('active'); // 'active', 'expired', 'cancelled'
            $table->timestamp('starts_at');
            $table->timestamp('expires_at')->nullable();
            $table->string('transaction_id')->nullable(); // Link ke transaction
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
