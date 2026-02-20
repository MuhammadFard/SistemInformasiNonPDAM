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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customer_id');
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->onDelete('cascade');

            $table->text('alamat')->nullable;
            $table->text('rt')->nullable;
            $table->text('rw')->nullable;
            $table->string('nomor_telepon')->nullable;

            $table->foreignId('kwh_category_id')
                ->constrained('kwh_categories', 'kwh_category_id');

            $table->enum('status', ['pending', 'active'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
