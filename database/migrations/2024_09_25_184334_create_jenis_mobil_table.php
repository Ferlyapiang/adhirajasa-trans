<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisMobilTable extends Migration
{
    public function up()
    {
        Schema::create('type_mobil', function (Blueprint $table) {
            $table->id();
            $table->string('type'); 
            $table->decimal('rental_price', 15, 2)->nullable(); 
            $table->string('status'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_mobil');
    }
}
