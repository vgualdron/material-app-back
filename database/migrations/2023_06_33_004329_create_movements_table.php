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
        Schema::create('movements', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('consecutive', 100)->nullable()->collation('utf8_spanish_ci');
            $table->date('start_date');
            $table->date('final_date');
            $table->unique(['consecutive'], 'consecutive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
