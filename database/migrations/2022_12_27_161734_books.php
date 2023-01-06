<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("books", function (Blueprint $table){
            $table->id();
            $table->string("book_name")->nullable(false);
            $table->integer("book_price")->nullable(false);
            $table->foreignId("book_author")->nullable(false);
            $table->timestamps();
        });

        Schema::table("books", function (Blueprint $table){
            $table->foreign("book_author")->references("id")->on("authors");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("books");
    }
};
