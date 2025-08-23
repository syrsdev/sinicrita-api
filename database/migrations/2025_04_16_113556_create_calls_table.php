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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->foreign('chat_id')->references('id')->on('chat_sessions')->onDelete('cascade');
            $table->enum('status', [
                'initiated', // baru dibuat oleh pemanggil
                'ringing',   // sedang berdering di sisi penerima
                'active',    // call sedang berlangsung
                'ended',     // call selesai
                'missed'     // tidak diangkat
            ])->default('initiated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
