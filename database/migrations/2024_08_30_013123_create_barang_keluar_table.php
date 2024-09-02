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
        // Creating the barang_keluars table
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_keluar'); // Tanggal Keluar
            $table->foreignId('gudang_id')->constrained('warehouses')->onDelete('cascade'); // Gudang ID
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // Pemilik Barang (Customer ID)
            $table->string('nomer_container')->nullable(); // Nomor Container (Opsional)
            $table->string('nomer_polisi')->nullable(); // Nomor Polisi (Opsional)
            $table->foreignId('bank_transfer_id')->constrained('bank_datas')->onDelete('cascade'); // Transfer (ID Bank)
            $table->timestamps();
        });

        // Creating the barang_keluar_items table
        Schema::create('barang_keluar_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_keluar_id')->constrained('barang_keluars')->onDelete('cascade'); // Relasi ke tabel barang_keluars
            $table->foreignId('barang_masuk_id')->constrained('barang_masuks')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade'); // Barang ID (Nama Barang)
            $table->string('no_ref')->nullable(); // Nomor Referensi (Opsional)
            $table->integer('qty'); // Jumlah Barang Keluar
            $table->string('unit'); // Satuan Barang Keluar
            $table->decimal('harga', 15, 2)->nullable(); // Harga Barang (Opsional)
            $table->decimal('total_harga', 15, 2)->nullable(); // Total Harga (Opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar_items');
        Schema::dropIfExists('barang_keluars');
    }
};
