<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('personal_infos', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('userId')->unique();
            $table->string('documentType');
            $table->string('idCardFrontPhoto')->nullable();
            $table->string('idCardBackPhoto')->nullable();
            $table->enum('verificationStatus', ['PENDING', 'VERIFIED', 'REJECTED'])->default('PENDING');
            $table->dateTime('verifiedAt')->nullable();
            $table->string('verificationMethod')->nullable();
            $table->string('rejectionReason')->nullable();
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
