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
        Schema::create('barang_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('joc_number', 191)->unique(); // Add JOC Number
            $table->date('tanggal_masuk');
            $table->foreignId('gudang_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('jenis_mobil')->nullable();
            $table->string('nomer_polisi')->nullable();
            $table->string('nomer_container');
            $table->integer('fifo_in')->default(0);
            $table->integer('fifo_out')->default(0);
            $table->integer('fifo_sisa')->default(0);
            $table->timestamps();
        });

        Schema::create('barang_masuks_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuks')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->integer('qty');
            $table->string('unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuks_items');
        Schema::dropIfExists('barang_masuks');
    }
};