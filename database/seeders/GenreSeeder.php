<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    public static function seed()
    {
        DB::table("genres")->insert([
            "genre_name" => "mystery/misteri"
        ]);
        DB::table("genres")->insert([
            "genre_name" => "romance/romantis"
        ]);
        DB::table("genres")->insert([
            "genre_name" => "crime/kriminal"
        ]);
        DB::table("genres")->insert([
            "genre_name" => "murder/pembunuhan"
        ]);
        DB::table("genres")->insert([
            "genre_name" => "adventure/petualangan"
        ]);
        DB::table("genres")->insert([
            "genre_name" => "fantasy/fantasi"
        ]);
        DB::table("genres")->insert([
            "genre_name" => "comedy/komedi"
        ]);
        DB::table("genres")->insert([
            "genre_name" => "historical/sejarah"
        ]);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::seed();
    }
}
