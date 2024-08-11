<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_customers_table.php
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
            $table->string('no_npwp_ktp', 191)->unique();
            $table->string('no_hp');
            $table->string('email', 191)->unique();
            $table->text('address');
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps(); // This will add 'created_at' and 'updated_at'
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
