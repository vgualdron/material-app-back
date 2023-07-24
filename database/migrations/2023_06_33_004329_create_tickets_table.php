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
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('type', 1)->collation('utf8_spanish_ci');
            $table->unsignedBigInteger('user')->unsigned();
            $table->unsignedBigInteger('origin_yard')->nullable()->unsigned();
            $table->unsignedBigInteger('destiny_yard')->nullable()->unsigned();
            $table->unsignedBigInteger('supplier')->nullable()->unsigned();
            $table->unsignedBigInteger('customer')->nullable()->unsigned();
            $table->unsignedBigInteger('material')->nullable()->unsigned();
            $table->double('ash_percentage', 4,2)->nullable()->default(0);
            $table->string('receipt_number', 30)->nullable()->collation('utf8_spanish_ci');
            $table->string('referral_number', 30)->nullable()->collation('utf8_spanish_ci');
            $table->date('date');
            $table->time('time');
            $table->string('license_plate', 30)->collation('utf8_spanish_ci');
            $table->string('trailer_number', 30)->nullable()->collation('utf8_spanish_ci');
            $table->string('driver_document', 20)->collation('utf8_spanish_ci');
            $table->string('driver_name', 100)->collation('utf8_spanish_ci');
            $table->double('gross_weight', 10,2);
            $table->double('tare_weight', 10,2);
            $table->double('net_weight', 10,2);
            $table->unsignedBigInteger('conveyor_company')->unsigned();
            $table->text('observation')->nullable()->collation('utf8_spanish_ci')->default(null);
            $table->string('seals', 500)->nullable()->collation('utf8_spanish_ci')->default('[]');
            $table->boolean('round_trip')->nullable()->default(0);
            $table->date('local_created_at')->nullable();
            $table->unsignedBigInteger('freight_settlement')->nullable()->unsigned();
            $table->unsignedBigInteger('material_settlement')->nullable()->unsigned();
            $table->double('freight_settlement_retention_percentage', 4,2)->nullable();
            $table->double('material_settlement_retention_percentage', 4,2)->nullable();
            $table->double('material_settlement_royalties', 15,2)->default(0);
            $table->double('freight_settlement_unit_value', 15,2)->default(0);
            $table->double('material_settlement_unit_value', 15,2)->default(0);
            $table->double('freight_settlement_net_value', 15,2)->default(0);
            $table->double('material_settlement_net_value', 15,2)->default(0);
            $table->boolean('material_settle_receipt_weight')->default(0);
            $table->boolean('freight_settle_receipt_weight')->default(0);
            $table->double('freight_weight_settled', 15,2)->default(0);
            $table->double('material_weight_settled', 15,2)->default(0);
            $table->unsignedBigInteger('ticketmovid')->nullable()->unsigned();
            $table->foreign('user')->references('id')->on('users');
            $table->foreign('origin_yard')->references('id')->on('yards');
            $table->foreign('destiny_yard')->references('id')->on('yards');
            $table->foreign('supplier')->references('id')->on('thirds');
            $table->foreign('customer')->references('id')->on('thirds');
            $table->foreign('material')->references('id')->on('materials');
            $table->foreign('conveyor_company')->references('id')->on('thirds');
            $table->foreign('freight_settlement')->references('id')->on('settlements');
            $table->foreign('material_settlement')->references('id')->on('settlements');
            $table->string('consecutive', 50)->nullable()->collation('utf8_spanish_ci');
            $table->unique(['type', 'referral_number'], 'type_referral_number');
            $table->unique(['type', 'receipt_number'], 'type_receipt_number');
            $table->unique(['consecutive'], 'consecutive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
