<?php

namespace App\Http\Controllers;

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

    public function dashboard(Request $request)
    {
        
    }

    public function change_password(ChangePasswordRequest $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('admin:change_password')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        $user->forceFill([
            'password' => $request->new_password
        ]);
        $user->save();

        $user->tokens()->delete();

        return response()->json([
            'msg' => 'Change password successfully, please login again'
        ], 205);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'msg' => 'Logout'
        ], 205);
    }

    private function tokenAbilities()
    {
        return [
            'admin:change_password',
            'user:get_all',
            'user:activation',
            'user:delete',
            'exam:get_all',
            'exam:create',
            'classroom:create',
            'classroom:read',
            'classroom:update',
            'classroom:delete',
            'answer:get_all',
            'answer:read',
            'score:update'
        ];
    }

}
