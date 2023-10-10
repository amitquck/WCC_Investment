<?php

use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path() . '/database/seeds/wcc_withAllConditionData.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
