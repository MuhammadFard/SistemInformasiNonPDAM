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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');

            $table->foreignId('customer_id')
                ->constrained('customers', 'customer_id');

            $table->string('nomor_invoice')->unique();
            $table->decimal('total_tagihan', 12, 2);
            $table->date('tanggal_bayar')->nullable();
            $table->date('tanggal_jatuh_tempo');
            $table->string('catatan')->nullable();

            $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
