<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing `id` column as the primary key
            $table->string('email', 191); // Limits the length to 191 characters to stay within key length limits
            $table->string('token', 191); // Limits the length to 191 characters to stay within key length limits
            $table->timestamp('created_at')->nullable(); // Creates a `created_at` timestamp column

            // Create indexes with limited column length to avoid key length issues
            $table->unique('email'); // Creates a unique index on the `email` column
            // $table->unique(['email', 'token']); // Uncomment this if you need a unique index on both columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
