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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('table_id');
            $table->foreign('table_id')->references('id')->on('dining_tables');
            $table->string('order_number', 100);
            $table->string('customer_name', 100);
            $table->string('customer_hp',20);
            $table->double('total_price', 10, 2);
            $table->double('discount', 10, 2)->nullable();
            $table->double('tax', 10, 2)->nullable();
            $table->double('total_payment', 10, 2)->nullable();
            $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending');
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'gopay', 'ovo', 'linkaja', 'shopeepay', 'dana'])->default('cash');
            $table->string('payment_number')->nullable();
            $table->string('note')->nullable();
            $table->uuid('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
