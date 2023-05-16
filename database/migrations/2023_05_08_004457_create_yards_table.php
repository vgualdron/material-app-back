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
        Schema::create('yards', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('code', 30)->collation('utf8_general_ci');
            $table->string('name', 100)->collation('utf8_general_ci');
            $table->unsignedBigInteger('zone')->nullable();
            $table->decimal('longitude', 12,6)->nullable();
            $table->decimal('latitude', 12,6)->nullable();
            $table->foreign('zone')->references('id')->on('zones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yards');
    }
};
