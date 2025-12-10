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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('transaction_id')->nullable();
            $table->decimal('gross_amount', 10, 2);
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->default('pending');
            $table->string('fraud_status')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['order_id', 'transaction_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
