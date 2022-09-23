<?php

namespace App\Http\Controllers;

use App\Models\StudentAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAssignmentController extends Controller
{
    public function answer(Request $request): JsonResponse
    {
        try{
            if ($request->hasfile('file_name')) {
                $file = $request->file('file_name');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $filepath = $file->move('uploads/images/', $filename);
                $answer = new StudentAssignment();
                $answer->file_name=$filepath;
                $answer->verified = false;
                $answer->user_id =Auth::id();
                $answer->assignment_id=$request->assignmentId;
                $answer->save();

                return  response()->json(['message'=> 'File uploaded successfully!'], 200);
            } else {
                return  response()->json(['message'=> 'attach a file!'], 404);
            }
        }catch (\Exception $e){
            return  response()->json(['message'=>$e->getMessage()]);
        }
    }

    public function checkedStudentAssignments()
    {
        return StudentAssignment::where('user_id', Auth::id())->with('assignment')->get();
    }

    public function allAnswers(): JsonResponse
    {
        $answers = StudentAssignment::with( ['assignment' => function ($q)  {
            $q->with(['course', 'user']);
        }])->where('verified', true)->get();

        return response()->json($answers);
    }

    public function allUnverifiedAnswers(): JsonResponse
    {
        $answers = StudentAssignment::with(  ['assignment' => function ($q) {
            $q->with(['course', 'user']);
        }])->where('verified', false)->get();

        return response()->json($answers);
    }

    public function allUnverifiedAnswersUser(): JsonResponse
    {
            $answers = StudentAssignment::with(  ['assignment' => function ($q)  {
                $q->with(['course', 'user']);
            }])->where('verified', false)->get();

        return response()->json($answers);
    }

    public function verify(int $id): JsonResponse
    {
        $answer= StudentAssignment::find($id);
        $answer->verified=1;
        $answer->save();

        return response()->json($answer);
    }
}
