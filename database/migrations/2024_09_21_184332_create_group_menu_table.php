<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_menu', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('group_id')->constrained()->onDelete('cascade'); // Foreign key for groups
            $table->foreignId('menu_id')->constrained()->onDelete('cascade'); // Foreign key for menus
            $table->timestamps(); // Creates `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_menu');
    }
}
