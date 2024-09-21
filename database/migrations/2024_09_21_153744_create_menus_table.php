<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
{
    Schema::create('menus', function (Blueprint $table) {
        $table->id(); // Auto-incrementing ID
        $table->string('name')->nullable(); // Menu name
        $table->string('url')->nullable(); // Menu URL
        $table->string('router')->nullable(); // Menu router
        $table->string('icon')->nullable(); // Icon class
        $table->boolean('is_active')->default(1); // Active status
        $table->unsignedBigInteger('parent_id')->nullable(); // Parent ID for submenu

        $table->integer('priority')->nullable(); // Add the priority column

        // Foreign key constraint for parent_id
        $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');

        $table->timestamps(); // Timestamps for created_at and updated_at
    });
}

public function down()
{
    Schema::dropIfExists('menus');
}

}
