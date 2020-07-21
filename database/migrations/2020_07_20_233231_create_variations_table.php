<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('color_product_id');
            $table->unsignedBigInteger('product_size_id');
            $table->unsignedSmallInteger('quantity')->default(0);
            $table->timestamps();

            $table->foreign('color_product_id')
                ->references('id')
                ->on('color_product')
                ->onDelete('cascade');

            $table->foreign('product_size_id')
                ->references('id')
                ->on('product_size')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variations');
    }
}
