<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answer';
    protected $fillable = [
        'exam_id',
        'user_id',
        'point',
        'question',
        'answer',
        'status'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    use HasFactory;
}
