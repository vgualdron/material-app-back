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
        Schema::create('rates', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('movement', 1)->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('origin_yard')->nullable()->unsigned();
            $table->unsignedBigInteger('destiny_yard')->nullable()->unsigned();
            $table->unsignedBigInteger('supplier')->nullable()->unsigned();
            $table->unsignedBigInteger('customer')->nullable()->unsigned();
            $table->date('start_date');
            $table->date('final_date');
            $table->unsignedBigInteger('material')->unsigned();
            $table->unsignedBigInteger('conveyor_company')->nullable()->unsigned();
            $table->double('material_price', 10,2)->nullable();
            $table->double('freight_price', 10,2);
            $table->double('total_price', 10,2)->nullable();
            $table->text('observation')->nullable()->collation('utf8_spanish_ci');
            $table->boolean('round_trip')->default(0);
            $table->foreign('origin_yard')->references('id')->on('yards');
            $table->foreign('destiny_yard')->references('id')->on('yards');
            $table->foreign('supplier')->references('id')->on('thirds');
            $table->foreign('customer')->references('id')->on('thirds');
            $table->foreign('material')->references('id')->on('materials');
            $table->foreign('conveyor_company')->references('id')->on('thirds');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
