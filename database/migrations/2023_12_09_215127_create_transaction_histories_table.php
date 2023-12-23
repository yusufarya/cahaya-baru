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
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->char('code', 20)->primary();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->on('products')->references('id');
            $table->string('description', 200)->nullable();
            $table->dateTime('date')->nullable();
            $table->enum('flag', [1, 2, 3]);
            $table->double('qty')->default(0);
            $table->double('price')->default(0);
            $table->double('discount')->default(0);
            $table->double('charge')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_histories');
    }
};
