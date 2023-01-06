<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function seed()
    {
        DB::table("books")->insert([
            "book_name" => "Salvation of a Saint",
            "book_price" => 79200,
            "book_author" => 1
        ]);
        DB::table("books")->insert([
            "book_name" => 'Pembunuhan di Nihonbashi', 
            "book_price" => 93000, 
            "book_author" => 1
        ]);
        DB::table("books")->insert([
            "book_name" => 'Haru Mahameru', 
            "book_price" => 69000, 
            "book_author" => 2
        ]);
        DB::table("books")->insert([
            "book_name" => 'Trouvaille', 
            "book_price" => 99500, 
            "book_author" => 3
        ]);
        DB::table("books")->insert([
            "book_name" => 'Midwinter Murder', 
            "book_price" => 179000, 
            "book_author" => 4
        ]);
        DB::table("books")->insert([
            "book_name" => 'The Labors of Hercules', 
            "book_price" => 149000, 
            "book_author" => 4
        ]);
        DB::table("books")->insert([
            "book_name" => 'The Lie Tree', 
            "book_price" => 115000, 
            "book_author" => 5
        ]);
    }

    public function run()
    {
        self::seed();
    }
}
