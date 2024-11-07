<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('credit_purchase_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('transactionId');
            $table->string('receiverName');
            $table->string('receiverPhoneNumber');
            $table->string('receiverEmail')->nullable();
            $table->timestamps();

            $table->foreign('transactionId')->references('id')->on('transactions')->onDelete('cascade');
        });
    }
  
    public function down(): void
    {
        Schema::dropIfExists('credit_purchase_transactions');
    }
};
