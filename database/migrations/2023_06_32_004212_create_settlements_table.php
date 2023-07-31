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
        Schema::create('settlements', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('type', 1)->collation('utf8_spanish_ci');
            $table->string('consecutive', 100)->nullable()->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('third')->unsigned();
            $table->date('date');
            $table->double('subtotal_amount', 15,2)->default(0);
            $table->double('subtotal_settlement', 15,2)->default(0);
            $table->double('unit_royalties', 15,2)->default(0);
            $table->double('royalties', 15,2)->default(0);
            $table->double('retentions_percentage', 4,2)->default(0);
            $table->double('retentions', 15,2)->default(0);
            $table->double('total_settle', 15,2)->default(0);
            $table->text('observation')->nullable()->collation('utf8_spanish_ci')->default(null);
            $table->string('invoice', 50)->nullable()->collation('utf8_spanish_ci')->default(null);
            $table->date('invoice_date')->nullable()->default(null);
            $table->string('internal_document', 50)->nullable()->collation('utf8_spanish_ci')->default(null);
            $table->date('start_date');
            $table->date('final_date');
            $table->foreign('third')->references('id')->on('thirds');
            $table->unique(['consecutive'], 'consecutive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
