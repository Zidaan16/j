<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        if (User::where('role_id', 1)->where('email', $request->email)->exists()) {
            return response()->json([
                'msg' => "Users with email $request->email already exists."
            ], 400);
        }

        $classroom = Classroom::find($request->classroom);
        if (empty($classroom)) {
            return response()->json([
                'msg' => "Classroom with id $request->classroom not exists."
            ], 400);
        }

        $student = Role::find(1);
        $student->user()->create([
            'name' => $request->name,
            'email' => $request->email,
            'classroom_id' => $classroom->id,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            "msg" => "Create user with email $request->email successfully."
        ]);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))){
            return response()->json(['msg' => 'Incorrect email or password'], 401);
        }

        $student = User::where('role_id', 1)->where('status', true)->where('email', $request->email)->first();

        if (empty($student)) {
            Auth::logout();
            return response()->json(['msg' => 'Incorrect email or password'], 401);
        }

        $token = $student->tokens();

        $abilities = [
            'user:change_password',
            'exam:attempt',
            'exam:view'
        ];

        if ($token->exists()) {
            $name = $token->value('name');
            $token->delete();
            return response()->json([
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'classroom' => $student->classroom->name,
                'created_at' => $student->created_at,
                'token' => $student->createToken($name, $abilities)->plainTextToken
            ]);
        } else {
            return response()->json([
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'classroom' => $student->classroom->name,
                'created_at' => $student->created_at,
                'token' => $student->createToken($student->email, $abilities)->plainTextToken
            ]);
        }

    }

    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'classroom' => $user->classroom->name
        ]);
    }

    public function change_password(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!$user->tokenCan('user:change_password')) {
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
        $request->token()->delete();

        return response()->json([
            'msg' => 'Logout'
        ], 205);
    }

}
