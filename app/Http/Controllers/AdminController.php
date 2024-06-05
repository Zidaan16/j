<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))){
            return response()->json(['msg' => 'Incorrect email or password'], 401);
        }
        
        $teacher = User::where('email', $request->email)->where('role_id', 2)->first();

        if (empty($teacher)) {
            Auth::logout();
            return response()->json(['msg' => 'Incorrect email or password'], 401);
        }

        $token = $teacher->tokens();
        $abilities = $this->tokenAbilities();
        
        if ($token->exists()) {
            $name = $token->value('name');
            $token->delete();
            return response()->json([
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'created_at' => $teacher->created_at,
                'token' => $teacher->createToken($name, $abilities)->plainTextToken
            ]);
        } else {
            return response()->json([
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'created_at' => $teacher->created_at,
                'token' => $teacher->createToken($teacher->email, $abilities)->plainTextToken
            ]);
        }
    }

    private function tokenAbilities()
    {
        return [
            'user:change_password',
            'user:get_all',
            'exam:get_all',
            'exam:create',
            'classroom:create',
            'classroom:read',
            'classroom:update',
            'classroom:delete'
        ];
    }

}
