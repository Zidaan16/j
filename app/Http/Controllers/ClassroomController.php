<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        return ClassroomResource::collection(Classroom::first());
    }

    public function create(ClassroomRequest $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('classroom:create')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        if (!empty(Classroom::where('name', $request->name)->first())) {
            return response()->json([
                'msg' => "Classroom $request->name already exists."
            ], 400);
        }
        
        Classroom::create([
            'name' => $request->name
        ]);

        return response()->json([
            'msg' => "Create classroom $request->name successfully."
        ]);
    }

    public function read(Request $request, String $id)
    {
        $user = $request->user();
        if (!$user->tokenCan('classroom:read')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        $class = Classroom::find($id);

        if (empty($class)) {
            return response()->json([
                'msg' => "Classroom id $id not found."
            ], 400);
        }

        $result = $class;
        $result['student'] = $class->user()->get(['id', 'name', 'email', 'created_at', 'updated_at']);

        return response()->json($result);
    }

    public function update(ClassroomRequest $request, String $id)
    {
        $user = $request->user();
        if (!$user->tokenCan('classroom:update')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        if (empty(Classroom::find($id))) {
            return response()->json([
                'msg' => "Classroom $request->name not found."
            ], 400);
        }

        $class = Classroom::find($id);
        $before = $class->name;
        $class->name = $request->name;
        $class->save();

        return response()->json([
            "msg" => "Update classroom $before to $request->name."
        ]);
    }

    public function delete(Request $request, String $id)
    {
        $user = $request->user();
        if (!$user->tokenCan('classroom:update')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        if (empty(Classroom::find($id))) {
            return response()->json([
                'msg' => "Classroom $id not found."
            ], 400);
        }

        Classroom::find($id)->delete();
        return response()->json([
            "msg" => "Delete classroom $id successfully."
        ]);
    }
}
