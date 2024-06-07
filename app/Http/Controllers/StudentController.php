<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getActiveUsers(Request $request)
    {
        $user = $request->user();
        if ($user->tokenCan('user:get_all')) {
            return $this->getUser(true);
        } else {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }
    }

    public function getUnactiveUsers(Request $request)
    {
        $user = $request->user();
        if ($user->tokenCan('user:get_all')) {
            return $this->getUser(false);
        } else {
           return response()->json([
               'msg' => 'Unauthorized'
           ], 401);
        }
    }

    public function userActivation(Request $request, String $id)
    {
        $user = $request->user();
        if (!$user->tokenCan('user:activation')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }
        
        $user = User::find($id);
        if (empty($user)) {
            return response()->json([
                "msg" => "User with id $id not exists."
            ], 400);
        } elseif ($user->status) {
            return response()->json([
                "msg" => "User with id $id already active."
            ]);
        }

        $user->status = true;
        $user->save();

        return response()->json([
            "msg" => "Success"
        ]);
    }

    public function delete(Request $request, String $id)
    {
        $user = $request->user();
        if (!$user->tokenCan('user:delete')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('role_id', 1)->where('id', $id)->first();
        $name = $user->name;
        $class = $user->classroom->name;
        $user->delete();

        return response()->json([
            "msg" => "Delete user $name from classroom $class"
        ]);
    }

    private function getUser(Bool $status)
    {
        $data['data'] = [];
        foreach (User::where('role_id', 1)->where('status', $status)->get() as $value) {
            $data['data'][] = [
                'id' => $value['id'],
                'name' => $value['name'],
                'email' => $value['email'],
                'classroom' => $value->classroom->name,
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at']
            ];
        }
        return response()->json($data);
    }
}