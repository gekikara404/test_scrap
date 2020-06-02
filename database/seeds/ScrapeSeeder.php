<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScrapeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scrape')->insert([
            'manufacturer' => Str::random(10),
            'model' => Str::random(10),
            'carrier' => Str::random(10),
            'price' => rand(1, 1000),
        ]);
    }
}
