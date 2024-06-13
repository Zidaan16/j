<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // seeder
        $this->call(Role::class);
        $this->call(User::class);
        $this->call(Classroom::class);

        \App\Models\Role::find(1)->user()->create([
            'classroom_id' => 1,
            'name' => 'Syed Juned',
            'email' => 'juned@info.id',
            'password' => Hash::make('juned'),
            'status' => 1
        ]);

        \App\Models\User::factory()->count(28)->for(\App\Models\Classroom::find(1))->create([
            'status' => true
        ]);

        \App\Models\User::factory()->count(30)->for(\App\Models\Classroom::find(2))->create([
            'status' => true
        ]);

        \App\Models\User::factory()->count(30)->for(\App\Models\Classroom::find(3))->create([
            'status' => false
        ]);

        $exam = Exam::create([
            'classroom_id' => 1,
            'title' => 'Matematika',

        ]);

        $exam->question()->create([
            'point' => 1,
            'description' => '5 + 5 = ...?',
            'option_1' => 5,
            'option_2' => 15,
            'option_3' => 8,
            'correct_answer' => 10
        ]);

        $exam->question()->create([
            'point' => 1,
            'description' => '10 + 10 = ...?',
            'option_1' => 5,
            'option_2' => 15,
            'option_3' => 8,
            'correct_answer' => 20
        ]);

        $exam->question()->create([
            'point' => 5,
            'description' => '1000 + 2000 = ...?',
            'auto' => false
        ]);


        $exam = Exam::create([
            'classroom_id' => 1,
            'title' => 'PPKN',

        ]);

        $exam->question()->create([
            'point' => 1,
            'description' => 'Siapakah presiden pertama Republik Indonesia?',
            'option_1' => 'Prabowo',
            'option_2' => 'Anies',
            'option_3' => 'Ganjar',
            'correct_answer' => 'Ir. Soekarno'
        ]);

        $exam->question()->create([
            'point' => 1,
            'description' => 'Warna bendera negara Republik Indonesia?',
            'option_1' => 'Hitam',
            'option_2' => 'Merah hijau',
            'option_3' => 'Merah kuning hijau',
            'correct_answer' => 'Merah Putih'
        ]);
    }
}
