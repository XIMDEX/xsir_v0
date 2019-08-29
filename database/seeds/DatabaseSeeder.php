<?php

use Illuminate\Database\Seeder;
use Ximdex\Seeds\NodeTypesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(NodeTypesSeeder::class);
    }
}
