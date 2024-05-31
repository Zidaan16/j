<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@info.id',
        //     'password' => Hash::make('admin'),
        //     'role' => 'teacher',
        //     'class' => 'admin',
        //     'status' => true            
        // ]);
        
        // $user = User::create([
        //     'name' => 'Syed Juned',
        //     'email' => 'juned@info.id',
        //     'password' => Hash::make('juned'),
        // ]);

        // $user->student()->create([
        //     'class' => 'rpl1',
        //     'status' => true
        // ]);

        // $user = User::create([
        //     'name' => 'Irpan',
        //     'email' => 'irpan@info.id',
        //     'password' => Hash::make('irpan'),
        // ]);

        // $user->student()->create([
        //     'class' => 'rpl1',
        //     'status' => true
        // ]);

        Role::create([
            'name' => 'student'
        ]);

        Role::create([
            'name' => 'teacher'
        ]);

        Classroom::create([
            'name' => 'rpl1'
        ]);
        Classroom::create([
            'name' => 'rpl2'
        ]);
        Classroom::create([
            'name' => 'tkj1'
        ]);
        Classroom::create([
            'name' => 'rpl2'
        ]);

    }
}
