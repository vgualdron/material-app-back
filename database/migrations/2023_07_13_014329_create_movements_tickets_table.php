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
        Schema::create('movements_tickets', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('movement');
            $table->unsignedBigInteger('ticket');
            $table->foreign('ticket')->references('id')->on('tickets');
            $table->foreign('movement')->references('id')->on('movements');
            $table->unique(['movement', 'ticket'], 'movement_ticket');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements_tickets');
    }
};
