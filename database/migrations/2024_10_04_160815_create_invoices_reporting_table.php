<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesReportingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices_reporting', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('nomer_invoice')->nullable();
            $table->foreignId('barang_masuks_id')->nullable()->constrained('barang_masuks')->onDelete('set null'); // Foreign key to barang_masuks table
            $table->foreignId('barang_keluars_id')->nullable()->constrained('barang_keluars')->onDelete('set null'); // Foreign key to barang_keluars table
            $table->string('job_number')->nullable();
            $table->string('nomer_container')->nullable();
            $table->integer('qty')->nullable(); // Changed from int() to integer()
            $table->string('unit')->nullable();
            $table->string('type_mobil')->nullable();
            $table->date('tanggal_masuk_penimbunan')->nullable();
            $table->date('tanggal_keluar_penimbunan')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->integer('diskon')->nullable();
            $table->decimal('harga_lembur', 15, 2)->nullable();
            $table->decimal('harga_kirim_barang', 15, 2)->nullable();
            $table->decimal('harga_simpan_barang', 15, 2)->nullable(); // Ensure this is sufficient for your use case
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices_reporting');
    }
}
