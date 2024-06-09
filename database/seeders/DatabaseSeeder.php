<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
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
        $student = Role::create([
            'name' => 'student'
        ]);

        $teacher = Role::create([
            'name' => 'teacher'
        ]);

        $teacher->user()->create([
            'name' => 'Irpan',
            'email' => 'irpan@info.id',
            'password' => Hash::make('irpan'),
        ]);

        $rpl1 = Classroom::create([
            'name' => 'rpl1'
        ]);
        $rpl2 = Classroom::create([
            'name' => 'rpl2'
        ]);
        $tkj1 = Classroom::create([
            'name' => 'tkj1'
        ]);
        $tkj2 = Classroom::create([
            'name' => 'tkj2'
        ]);

        $student->user()->create([
            'name' => 'Syed Juned',
            'email' => 'juned@info.id',
            'password' => Hash::make('juned'),
            'classroom_id' => $rpl1->id,
            'status' => true
        ]);

        $student->user()->create([
            'name' => 'Melati',
            'email' => 'melati@info.id',
            'password' => Hash::make('melati'),
            'classroom_id' => $rpl1->id,
            'status' => true
        ]);

        $student->user()->create([
            'name' => 'Zidan',
            'email' => 'zidan@info.id',
            'password' => Hash::make('zidan'),
            'classroom_id' => $rpl1->id,
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
            'description' => '1000 + 2000cu = ...?',
            'auto' => false
        ]);
    }
}
