<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Classroom extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Classroom::create([
            'name' => 'rpl1'
        ]);
        \App\Models\Classroom::create([
            'name' => 'rpl2'
        ]);
        \App\Models\Classroom::create([
            'name' => 'tkj1'
        ]);
        \App\Models\Classroom::create([
            'name' => 'tkj2'
        ]);
    }
}
