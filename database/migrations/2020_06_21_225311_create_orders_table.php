<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('exported_at')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('town_id')->nullable();
            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('orders');
    }
}
