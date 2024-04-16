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
            $table->string('payment_reference_number');
            $table->double('total');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('pending');
            $table->dateTime('confirmed_date')->nullable();
            $table->dateTime('preparing_date')->nullable();
            $table->dateTime('completed_date')->nullable();
            $table->timestamps();

            //pending -> created_at
            //confirmed -> new column
            //preparing -> new column
            //completed -> updated_at
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
