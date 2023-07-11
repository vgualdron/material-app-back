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
            $table->string('consecutive', 30)->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('third')->nullable()->unsigned();
            $table->date('date');
            $table->double('subtotal_amount', 15,2)->default(0);
            $table->double('subtotal_settlement', 15,2)->default(0);
            $table->double('unit_royalties', 5,2)->default(0);
            $table->double('royalties', 15,2)->default(0);
            $table->double('retentions_percentage', 15,2)->default(0);
            $table->double('retentions', 15,2)->default(0);
            $table->double('total_settle', 15,2)->default(0);
            $table->text('observation')->collation('utf8_spanish_ci');
            $table->string('invoice', 30)->nullable()->collation('utf8_spanish_ci');
            $table->date('invoice_date')->nullable();
            $table->string('internal_document', 50)->nullable()->collation('utf8_spanish_ci');
            $table->date('start_date');
            $table->date('final_date');
            $table->foreign('third')->references('id')->on('thirds');
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
