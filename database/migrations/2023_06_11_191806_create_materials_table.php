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
        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('code', 10)->unique()->collation('utf8_general_ci');
            $table->string('name', 70)->unique()->collation('utf8_general_ci');
            $table->string('unit', 2)->collation('utf8_general_ci');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
