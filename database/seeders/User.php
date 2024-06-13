<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'role_id' => 2,
            'name' => 'Irpan',
            'email' => 'irpan@info.id',
            'password' => Hash::make('irpan'),
            'status' => true
        ]);
    }
}
