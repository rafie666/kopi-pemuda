<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN payment_method ENUM('cash', 'qris', 'mbanking') NOT NULL");
        
        DB::table('transaksis')->where('payment_method', 'qris')->update(['payment_method' => 'mbanking']);
        
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN payment_method ENUM('cash', 'mbanking') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN payment_method ENUM('cash', 'qris', 'mbanking') NOT NULL");
        
        DB::table('transaksis')->where('payment_method', 'mbanking')->update(['payment_method' => 'qris']);
        
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN payment_method ENUM('cash', 'qris') NOT NULL");
    }
};
