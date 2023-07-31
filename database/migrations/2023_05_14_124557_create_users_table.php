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
            $table->string('document_number', 15)->collation('utf8_general_ci');
            $table->string('name', 50)->collation('utf8_general_ci');
            $table->string('phone', 15)->collation('utf8_general_ci');
            $table->string('password', 255)->collation('utf8_general_ci');
            $table->unsignedBigInteger('yard')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('editable')->default(1);
            $table->boolean('change_yard')->default(0);
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
