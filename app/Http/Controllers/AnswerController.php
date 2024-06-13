<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnswerResource;
use App\Http\Resources\ScoreResource;
use App\Models\Answer;
use Illuminate\Http\Request;
use App\Models\Score;

class AnswerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('answer:get_all')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        return ScoreResource::collection(Score::where('exam_id', $request->exam_id)->get());
    }

    public function read(Request $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('answer:read')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        $data = Answer::where('exam_id', $request->exam_id)->where('user_id', $request->user_id)->get();
        return AnswerResource::collection($data);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('score:update')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        $data = Answer::find($request->id);
        $score = Score::where('user_id', $data->user_id)->first();

        switch ($data->status) {
            case 1:
                $result = $this->whenStatusIsTrue($data, $score);
                break;
            
            case 0:
                $result = $this->whenStatusIsFalse($data, $score);
                break;
        }

        return $result;
    }

    private function whenStatusIsFalse(Answer $answer, Score $score)
    {
        $answer->status = true;
        $score->true += $answer->point;

        $answer->save();
        $score->save();

        return response()->json([
            "msg" => "Change status id $answer->id from false to true"
        ]);
    }

    private function whenStatusIsTrue(Answer $answer, Score $score)
    {
        $answer->status = false;
        $score->true -= $answer->point;

        $answer->save();
        $score->save();

        return response()->json([
            "msg" => "Change status id $answer->id from true to false"
        ]);
    }
}
