<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))){
            return response()->json(['msg' => 'Incorrect email or password'], 401);
        }

        $user = Auth::user();
        $token = $user->tokens();

        if ($token->exists()) {
            $name = $token->value('name');
            $abilities = $token->value('abilities');
            $token->delete();

            $token = $user->createToken($name, $abilities)->plainTextToken;
            return response()->json([
                'data' => $user->only('id', 'name', 'email'),
                'token' => $token
            ]);
        }

        switch ($user['role']) {
            case 'student':
                $token = $user->createToken($request->email, ['quiz:view', 'quiz:submit', 'user:change_password'])->plainTextToken;
                break;
            
            default:
                $token = $user->createToken($request->email, ['quiz:create'])->plainTextToken;
                break;
        }

        return response()->json([
            'data' => $user->only('id', 'name', 'email'),
            'token' => $token
        ], 200);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json($user->only('id', 'email', 'name'));
    }

    public function change_password(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!$user->tokenCan('user:change_password')) return redirect()->route('login');
        $user->forceFill([
            'password' => $request->new_password
        ]);
        $user->save();

        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'msg' => 'Change password successfully, please login again'
        ], 205);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'msg' => 'Logout'
        ], 205);
    }

}
