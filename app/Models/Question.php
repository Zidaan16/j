<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'question';
    protected $fillable = [
        'exam_id',
        'point',
        'description',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'correct_answer',
        'auto'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    use HasFactory;
}
