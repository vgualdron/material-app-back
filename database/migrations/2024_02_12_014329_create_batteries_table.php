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
        Schema::create('batteries', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 150)->collation('utf8_spanish_ci');
            $table->string('description', 150)->nullable()->collation('utf8_spanish_ci');
            $table->boolean('active')->nullable()->default(1);
            $table->unsignedBigInteger('yard');
            $table->foreign('yard')->references('id')->on('yards');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batteries');
    }
};
