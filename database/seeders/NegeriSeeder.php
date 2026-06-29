<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NegeriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('negeri')->insert([
        ['name' => 'Johor'],
        ['name' => 'Kedah'],
        ['name' => 'Kelantan'],
        ['name' => 'Melaka'],
        ['name' => 'Negeri Sembilan'],
        ['name' => 'Pahang'],
        ['name' => 'Perak'],
        ['name' => 'Perlis'],
        ['name' => 'Pulau Pinang'],
        ['name' => 'Sabah'],
        ['name' => 'Sarawak'],
        ['name' => 'Selangor'],
        ['name' => 'Terengganu'],
        ['name' => 'WP Putrajaya'],
        ['name' => 'WP Kuala Lumpur'],
        ['name' => 'WP Labuan'],
     
    
    ]);
}
}
