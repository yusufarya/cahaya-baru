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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->char('code', 20)->primary();
            $table->char('customer_code');
            $table->foreign('customer_code')->on('customers')->references('code');
            $table->string('description', 200)->nullable();
            $table->dateTime('date')->nullable();
            $table->double('qty')->default(0);
            $table->double('total_price')->default(0);
            $table->double('discount')->default(0);
            $table->double('charge')->default(0);
            $table->double('nett')->default(0);
            $table->enum('status', ['Y', 'N'])->default('N'); // Y terjual N masih dalam pesanan
            // 0 pesanan 1 persiapan 2 pengiriman 3 selesai / pesanan sampai tujuan 4 acc_order
            $table->enum('delivery', ['0','1', '2', '3', '4'])->default('0'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
