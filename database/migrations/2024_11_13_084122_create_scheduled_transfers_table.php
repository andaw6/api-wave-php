<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledTransfersTable extends Migration
{
    public function up()
    {
        Schema::create('scheduled_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->string('receiver_phone_number');
            $table->decimal('amount', 10, 2);
            $table->enum('frequency', ['daily', 'weekly', 'monthly']);
            $table->timestamp('next_execution');
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->string('currency')->default('XOF');
            $table->enum('status', ['active', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scheduled_transfers');
    }
}
