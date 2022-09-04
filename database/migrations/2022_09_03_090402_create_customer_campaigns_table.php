<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('status', 1);
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->datetime('start_timestamp');
            $table->datetime('end_timestamp')->nullable();
            //$table->timestamps();
            $table->index('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');

            $table->index('voucher_id');
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_campaigns');
    }
};
