<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->decimal('amount', 10, 2);
            $table->uuid('senderId');
            $table->uuid('receiverId')->nullable();
            $table->decimal('feeAmount', 10, 2)->default(0.0);
            $table->string('currency')->default('XOF');
            $table->enum('transactionType', ['DEPOSIT', 'WITHDRAW', 'PURCHASE', 'TRANSFER']);
            $table->enum('status', ['PENDING', 'COMPLETED', 'FAILED', 'CANCELLED'])->default('PENDING');
            $table->timestamps();

            $table->foreign('senderId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiverId')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
