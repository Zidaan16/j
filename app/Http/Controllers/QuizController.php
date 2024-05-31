<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('quiz:view')) return response()->json(['msg' => 'Unauthorized'], 401);

        return response()->json([
            'data' => 'test'
        ]);      

    }
}
