<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\StudentAssignment;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function reports(Request $request)
    {
        $start_date = Carbon::parse($request->start);
        $end_date = Carbon::parse($request->end);
        $assignment = Assignment::whereBetween('created_at', [$start_date, $end_date])->where('subject_id', $request->subject_id)->get();

        return response()->json($assignment);
    }

    ///get 1 task
    public function getTAsk($id)
    {
        $task = Assignment::find($id)->where('id', $id)->with(['student_assignment', 'user'])->get();
        return response()->json($task);
    }

    //get user task list
    public function userTasksList()
    {
        $user = auth()->user();

        $task = User::with(['courses' => function ($q) use ($user) {
            $q->with(['tasks' => function ($q) use ($user) {
                $q->whereDoesntHave('student_assignment', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }]);
        }])
            ->where('id', $user->id)
            ->get();
        return  response()->json($task);
    }

    public function assignmentTeacher()
    {
        $assignments = Assignment::where('user_id', Auth::id())->get();
        return response()->json($assignments);
    }

    public function assignmentCourse()
    {
        $user = Auth::user();
        $courses=CourseUser::where('user_id', Auth::id())->get();
        return response()->json($courses);
    }

    public function getTasksBySubject(Request $request)
    {
        $data= Subject::with('assignments')->where('name', 'LIKE', '%'.$request->keyword.'%')->get();
        return response()->json($data);

    }
    public function getTasksByTeacherName(Request $request)
    {
        $data= User::with('assignments')->where('name', 'LIKE', '%'.$request->keyword.'%')->get();
        return response()->json($data);
    }


    public function getTasksByNumber(Request $request)
    {
        $data= Assignment::where('id', 'LIKE','%'.$request->keyword.'%')->with('subject')->get();
        return response()->json($data);

    }

    public function assignments()
    {
        $assignments = Assignment::with('user')->get();
        return response()->json($assignments);
    }

    protected function normalizeGuessedAbilityName($ability)
    {
    }//verified assignment
    public function verified()
    {
        $assignments = Assignment::where('chucked', true)->get();
        return response()->json($assignments);
    }


    public function assignmentCount()
    {
        $assignments = Assignment::where('user_id', Auth::id())->count();
        if($assignments){
            return response()->json($assignments);
        } else {
            return 'no assignment';
        }
    }
    public function assignmentTask()
    {
        $assignments = Assignment::where('user_id', Auth::id());
        if($assignments){
        } else {
            return 'no assignment';
        }
    }

    ///////create assignment
    public function store(Request $request)
    {
        $validator = $request->validate([
            'title'=>'required',
            'description'=>'required|min:8',
            'mark'=>'required',
            'file'=>'required|mimes:pdf,docx,png,jpeg|max:2048',
            'subject_id'=>'required',
            'course_id'=>'required'
        ]);

        if($validator) {
            $assignment = new Assignment();

            if ($request->hasfile('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $filepath = $file->move('uploads/images/', $filename);
            }

            $assignment->file = $filepath;
            $assignment->user_id = Auth::id();
            $assignment->title = $request->title;
            $assignment->course_id = $request->course_id;
            $assignment->date = $request->date;
            $assignment->subject_id = $request->subject_id;
            $assignment->description = $request->description;
            $assignment->mark = $request->mark;

            $assignment->save();

            return response()->json($assignment);
        } else {
            return 'validator error';
        }

    }

    ////////get one assignment
    public function getAssignment($id)
    {
        $assignment = Assignment::find($id);

        return response()->json($assignment);
    }

    //// edit assignment
    public function editAssignment(Request $request, $id)
    {
        $assignment = Assignment::find($id);
        $assignment->update($request->all());

        return response()->json($assignment);

    }

    public function destroy(Assignment $assignment, $id)
    {
        $assignment = Assignment::find($id);
        $assignment->delete();
        return response()->json('$=assignment deleted!');
    }
}
