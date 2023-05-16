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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 255)->collation('utf8_general_ci');
            $table->string('document_number', 50)->collation('utf8_general_ci');
            $table->string('phone', 50)->collation('utf8_general_ci');            
            $table->string('password', 255)->collation('utf8_general_ci');
            $table->unsignedBigInteger('yard')->nullable();
            $table->foreign('yard')->references('id')->on('yards');
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
