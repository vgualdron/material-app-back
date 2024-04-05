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
        Schema::create('ovens', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 150)->collation('utf8_spanish_ci');
            $table->boolean('active')->nullable()->default(1);
            $table->unsignedBigInteger('batterie');
            $table->foreign('batterie')->references('id')->on('batteries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ovens');
    }
};
