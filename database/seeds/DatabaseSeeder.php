<?php

use Illuminate\Database\Seeder;
use Database\Seeders\ServiceSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CompanySeeder::class,
            UsersTableSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
