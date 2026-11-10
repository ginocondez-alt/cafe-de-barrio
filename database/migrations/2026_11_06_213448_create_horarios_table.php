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
    Schema::create('horarios', function (Blueprint $table) {
        $table->id();
        $table->unsignedTinyInteger('dia');          // 1 = Lunes ... 7 = Domingo
        $table->time('apertura')->nullable();
        $table->time('cierre')->nullable();
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
