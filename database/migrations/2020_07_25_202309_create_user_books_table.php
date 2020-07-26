<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_books', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->constrained();
            $table->foreignId('book_id')->nullable(false)->constrained();
            $table->unsignedInteger('priority')->nullable();
            $table->boolean('read')->default(false)->nullable(false);
            $table->timestamps();
            $table->primary(['user_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_books');
    }
}
