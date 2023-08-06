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
        Schema::create('adjustments', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('type', 1)->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('yard')->unsigned();
            $table->unsignedBigInteger('material')->unsigned();
            $table->decimal('amount', 10,2);
            $table->date('date');
            $table->text('observation')->collation('utf8_spanish_ci')->nullable();
            $table->foreign('yard')->references('id')->on('yards');
            $table->foreign('material')->references('id')->on('materials');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustments');
    }
};
