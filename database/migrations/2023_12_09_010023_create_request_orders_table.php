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
        Schema::create('request_orders', function (Blueprint $table) {
            $table->char('code', 20)->primary();
            $table->char('customer_code');
            $table->foreign('customer_code')->on('customers')->references('code');
            $table->unsignedBigInteger('size_id');
            $table->foreign('size_id')->on('sizes')->references('id');
            $table->dateTime('date')->nullable()->default();
            $table->string('description', 200);
            $table->double('qty')->default(0);
            $table->double('price')->default(0);
            $table->double('charge')->default(0);
            $table->double('nett')->default(0);
            $table->text('image')->nullable();
            $table->enum('status', ['Y', 'N'])->default('N'); // Y terjual N masih dalam pesanan
            // 0 pesanan 1 persiapan 2 pengiriman 3 selesai / pesanan sampai tujuan
            $table->enum('delivery', ['0','1', '2', '3'])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_orders');
    }
};
