<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentScoreResource;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register_attributes()
    {
        return response()->json(['data' => Classroom::select('id', 'name')->get()]);
    }

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
            'exam:read',
            'score:read'
        ];

        if ($token->exists()) $token->delete();

        return StudentResource::make($student)->additional(['token' => $student->createToken($student->email, $abilities)->plainTextToken]);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $data = [];
        foreach ($user->classroom->exam()->where('is_expired', false)->get() as $key => $value) {
            if (!$user->score()->where('exam_id', $value['id'])->exists()) {
                $data[] = [
                    'id' => $value['id'],
                    'title' => $value['title']
                ];
            }
        }
        $resource = StudentResource::make($user)->additional(['exam' => $data]);
        return $resource;
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

    public function score(Request $request)
    {
        return StudentScoreResource::collection($request->user()->score);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'msg' => 'Logout'
        ], 205);
    }

}
