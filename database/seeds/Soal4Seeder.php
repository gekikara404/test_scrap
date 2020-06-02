<?php

use Illuminate\Database\Seeder;

class Soal4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $device = 'iphone';
        $used = 'used';
        foreach (range(1,100) as $index) {
            DB::table('iphone_prod')->insert([
                'device' => $device,
                'condition' => $used,
                'model' => $device,
                'network' => $device,
                'size' => $device,
            ]);
        }
       
    }
}
