<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function subjectTeachers()
    {
        $teschers= User::where('id', Auth::id())->whereHas('subjects')->with('teachers')->get();

        return $teschers;
    }

    public function index(){
        $subjects = Subject::get();
        return response()->json($subjects);
    }

    public function store(Request $request){
        $subject = new Subject();
        $subject->name = $request->name;
        $subject->save();

        return response()->json($subject);
    }

    public function destroy(Subject $subject, $id){
        $subject = Subject::find($id);
        $subject->delete();
        return response()->json('Subject deleted!');

    }
}
