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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id');
            $table->unsignedInteger('categori_id');
            $table->string('product_code')->unique();
            $table->string('product_name')->unique();
            $table->string('merk')->nullable();
            $table->integer('purchase_price');
            $table->tinyInteger('discount')->default(0);
            $table->integer('sale_price');
            $table->integer('stock');
            $table->timestamps();

            $table->foreign('categori_id')->references('categori_id')->on('categories')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
