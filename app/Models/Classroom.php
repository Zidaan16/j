<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'classroom';
    protected $fillable = [
        'name'
    ];

    public function exam()
    {
        return $this->hasMany(Exam::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    use HasFactory;
}
