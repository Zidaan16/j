<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Classroom;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function login()
    {
        $classroom = Classroom::select('id', 'name')->get();

        return response()->json([
            'attributes' => $classroom
        ]);
    }
}
