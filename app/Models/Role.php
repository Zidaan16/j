<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $fillable = [
        'name',
        // 'abilities'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    use HasFactory;
}
