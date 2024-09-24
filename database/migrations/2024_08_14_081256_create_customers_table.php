<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_pt');
            $table->string('no_npwp', 191)->nullable();
            $table->string('no_ktp', 191)->nullable();
            $table->string('no_hp');
            $table->string('type_payment_customer');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade'); // Gudang ID
            $table->string('email', 191)->nullable();
            $table->text('address');
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
