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
            $table->string('origin', 1)->collation('utf8_spanish_ci');
            $table->string('type', 1)->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('yard')->unsigned();
            $table->unsignedBigInteger('material')->unsigned();
            $table->unsignedBigInteger('oven')->nullable()->default(null);
            $table->decimal('amount', 10,2);
            $table->date('date');
            $table->double('release_time', 10,2)->nullable()->default(null);
            $table->boolean('release_status')->nullable()->default(null); 
            $table->text('observation')->collation('utf8_spanish_ci')->nullable();
            $table->string('uuid', 100)->collation('utf8_spanish_ci')->nullable();
            $table->foreign('yard')->references('id')->on('yards');
            $table->foreign('material')->references('id')->on('materials');
            $table->foreign('oven')->references('id')->on('ovens');
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
