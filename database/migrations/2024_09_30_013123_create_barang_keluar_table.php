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
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_keluar');
            $table->foreignId('gudang_id')->constrained('warehouses')->onDelete('cascade'); 
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('type_mobil_id')->nullable()->constrained('type_mobil')->onDelete('cascade');
            $table->string('nomer_surat_jalan')->nullable();
            $table->string('nomer_invoice')->nullable();
            $table->string('nomer_polisi')->nullable();
            $table->string('nomer_container')->nullable();
            $table->decimal('harga_kirim_barang', 15, 2)->nullable();
            $table->foreignId('bank_transfer_id')->constrained('bank_datas')->onDelete('cascade');
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
