<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttemptExamRequest;
use App\Http\Requests\CreateExamRequest;
use App\Models\Answer;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('exam:view')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        $exam = $user->student->classroom->exam->where('expired', false);
        return response()->json($exam);
    }

    public function create(CreateExamRequest $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('exam:create')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        $class = Classroom::find($request->classroom_id);
        if (empty($class)) {
            return response()->json([
                'msg' => "Classroom id $request->classroom_id not exists."
            ], 400);
        }
        $exam = $class->exam()->create([
            'title' => $request->title,
        ]);

        for ($i=0; $i < count($request->description); $i++) { 
            if ($request->type[$i] == 1) {
                if (empty($request->correct_answer[$i])) {
                    return response()->json([
                        'msg' => 'The answer key is required.'
                    ], 406);
                }

                $option = [$request->option_1[$i], $request->option_2[$i], $request->option_3[$i], $request->correct_answer[$i]];
                shuffle($option);

                $exam->question()->create([
                    'description' => $request->description[$i],
                    'point' => $request->point[$i],
                    'option_1' => $option[0],
                    'option_2' => $option[1],
                    'option_3' => $option[2],
                    'option_4' => $option[3],
                    'correct_answer' => $request->correct_answer[$i]
                ]);
            } else {
                $exam->question()->create([
                    'description' => $request->description[$i],
                    'point' => $request->point[$i],
                    'auto' => false
                ]);
            }
        }
        
        return response()->json([
            'msg' => "Success create $request->title on $class->name"
        ]);
    }

    public function attempt(AttemptExamRequest $request)
    {
        $user = $request->user();
        if (!$user->tokenCan('exam:attempt')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }
        $check = Answer::where('user_id', $user->id)->where('exam_id', $request->exam_id)->count();
        if ($check > 0) {
            return response()->json([
                'msg' => 'You have answering this exam.'
            ], 400);
        }

        $exam = $user->classroom->exam()->find($request->exam_id);
        $question = $exam->question;
        $clientAnswer = $request->answer;

        for ($i=0; $i < count($question); $i++) { 
            if (!empty($clientAnswer[$i])) {
                if ($question[$i]->auto) {
                    if ($question[$i]->correct_answer == $clientAnswer[$i]) {
                        $exam->answer()->create([
                            'user_id' => $user->id,
                            'question' => $question[$i]->description,
                            'answer' => $clientAnswer[$i],
                            'point' => $question[$i]->point,
                            'status' => true
                        ]);
                    } else {
                        $exam->answer()->create([
                            'user_id' => $user->id,
                            'question' => $question[$i]->description,
                            'answer' => $clientAnswer[$i],
                            'point' => $question[$i]->point,
                        ]);
                    }
                } else {
                    $exam->answer()->create([
                        'user_id' => $user->id,
                        'question' => $question[$i]->description,
                        'answer' => $clientAnswer[$i],
                        'point' => $question[$i]->point,
                    ]);
                }

            } else {
                $exam->answer()->create([
                    'user_id' => $user->id,
                    'question' => $question[$i]->description,
                    'answer' => null,
                    'point' => $question[$i]->point
                ]);
            }
        }

        return response()->json([
            'msg' => 'OK'
        ]);
    }

    public function view(Request $request, String $id)
    {
        $user = $request->user();
        if (!$user->tokenCan('exam:view')) {
            return response()->json([
                'msg' => 'Unauthorized'
            ], 401);
        }

        $exam = $user->student->classroom->exam()->with('question')->find($id);
        return response()->json($exam);
    }

}
