<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('nomer_invoice')->nullable(); // Not unique by default
            $table->foreignId('barang_masuks_id')->nullable()->constrained('barang_masuks')->onDelete('cascade'); // Foreign key to barang_masuks table, nullable
            $table->foreignId('barang_keluars_id')->nullable()->constrained('barang_keluars')->onDelete('cascade'); // Foreign key to barang_keluars table, nullable
            $table->date('tanggal_masuk')->nullable();
            $table->integer('diskon')->nullable();
            $table->decimal('harga_total', 15, 2)->nullable();
            $table->integer('total_qty')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
