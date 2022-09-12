<?php

namespace App\Http\Controllers;

use App\Models\StudentAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAssignmentController extends Controller
{
    public function answer(Request $request)
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

    public function allAnswers()
    {
        $answers = StudentAssignment::with( ['assignment' => function ($q)  {
            $q->with(['course', 'user']);
        }])->where('verified', true)->get();

        return $answers;
    }

    public function allUnverifiedAnswers()
    {
        $answers = StudentAssignment::with(  ['assignment' => function ($q)  {
            $q->with(['course', 'user']);
        }])->where('verified', false)->get();

        return $answers;
    }

    public function allUnverifiedAnswersUser()

    {
            $answers = StudentAssignment::with(  ['assignment' => function ($q)  {
                $q->with(['course', 'user']);
            }])->where('verified', false)->get();

            return $answers;
    }


    public function verify($id)
    {
        $answer= StudentAssignment::find($id);
        $answer->verified=1;
        $answer->save();
        return $answer;

    }
}
