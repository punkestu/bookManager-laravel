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
        Schema::create("book_genres", function (Blueprint $table){
            $table->id();
            $table->foreignId("book_id")->nullable(false);
            $table->foreignId("genre_id")->nullable(false);
            $table->timestamps();
        });
        Schema::table("book_genres", function (Blueprint $table){
            $table->foreign("book_id")->references("id")->on("books")->onDelete("cascade");
            $table->foreign("genre_id")->references("id")->on("genres");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("book_genres");
    }
};
