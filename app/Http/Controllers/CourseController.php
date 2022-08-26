<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::get();
        return response()->json($courses );
    }

    public function store(Request  $request)
    {
        $course = new Course();
        $course->name = $request->name;
        $course->save();

        return response()->json($course);
    }

    public function editCourse(Request $request, $id)
    {
        $course = Course::find($id);
        $course->name = $request->name;
        $course->save();
        return response()->json($course);
    }
/////////////////////////////////////////////
    public function getCourse($id)
    {
        $course = Course::find($id);
        return response()->json($course);
    }
///////////////////////////////////////////
    public  function getUserCourse($id)
    {
        $course = Course::where('id', $id)->with('users')->get();
        return response()->json($course);
    }

    public function destroy(Course $course, $id)
    {
        $course = Course::find($id);
        $course->delete();
        return response()->json('Course deleted!');
    }

}
