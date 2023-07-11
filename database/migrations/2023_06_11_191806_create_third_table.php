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
        Schema::create('thirds', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('nit', 50)->collation('utf8_general_ci')->unique();
            $table->string('name', 200)->collation('utf8_general_ci');
            $table->boolean('customer')->default(0);
            $table->boolean('associated')->default(0);
            $table->boolean('contractor')->default(0);
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thirds');
    }
};
