<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
        $courses = Course::get();

        return response()->json($courses );
    }

    public function store(Request  $request): JsonResponse
    {
        $course = new Course();
        $course->name = $request->name;
        $course->save();

        return response()->json($course);
    }

    public function editCourse(Request $request,int $id): JsonResponse
    {
        $course = Course::find($id);
        $course->name = $request->name;
        $course->save();

        return response()->json($course);
    }

    public function getCourse(int $id): JsonResponse
    {
        $course = Course::find($id);

        return response()->json($course);
    }

    public  function getUserCourse(int $id): JsonResponse
    {
        $course = Course::where('id', $id)->with('users')->get();

        return response()->json($course);
    }

    public function destroy(int $id): JsonResponse
    {
        $course = Course::find($id);
        $course->delete();

        return response()->json('Course deleted!');
    }

}
