<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function seed()
    {
        DB::table("authors")->insert([
            "author_name" => "Keigo Higashino"
        ]);
        DB::table("authors")->insert([
            "author_name" => "Balakarsa"
        ]);
        DB::table("authors")->insert([
            "author_name" => "Febie Gusfa"
        ]);
        DB::table("authors")->insert([
            "author_name" => "Agatha Christie"
        ]);
        DB::table("authors")->insert([
            "author_name" => "Frances Hardinge"
        ]);
    }

    public function run()
    {
        self::seed();
    }
}
