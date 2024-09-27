@ -1,45 +0,0 @@
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
            $table->foreignId('type_mobil_id')->constrained('type_mobil')->onDelete('cascade');
            $table->string('nomer_polisi')->nullable();
            $table->string('nomer_container')->nullable();
            $table->timestamps();
        });

        Schema::create('barang_masuk_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuks')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->integer('qty');
            $table->string('unit');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk_items');
        Schema::dropIfExists('barang_masuks');
    }
};