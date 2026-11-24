<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('pedidos')
            ->where('estado', 'finalizado')
            ->update(['estado' => 'en_caja']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pedidos')
            ->where('estado', 'en_caja')
            ->update(['estado' => 'finalizado']);
    }
};
