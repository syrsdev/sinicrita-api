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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reported_by');
            $table->unsignedBigInteger('reported_user');
            $table->foreign('reported_by')->references('id')->on('users');
            $table->foreign('reported_user')->references('id')->on('users');
            $table->longText('reasons');
            $table->enum('type', ["user", "post"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
