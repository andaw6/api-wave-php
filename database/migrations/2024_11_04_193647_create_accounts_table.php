<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('userId'); // Supprimez `unique()` si cela cause un conflit de clé étrangère
            $table->decimal('balance', 10, 2)->default(0.0);
            $table->string('currency')->default('XOF');
            $table->text('qrCode')->unique();
            $table->boolean('isActive')->default(true);
            $table->decimal('plafond', 15, 2)->default(500000);
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade'); // Définition de la clé étrangère sans redéclaration
        });
    }

    public function down(): void
    {   
        Schema::dropIfExists('accounts');
    }
};
