<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCourseController extends Controller
{
    public function registerCourse(Request $request): JsonResponse
    {
        $userCourse = new CourseUser();
        $userCourse->user_id=Auth::id();
        $userCourse->course_id=$request->courseId;
        $userCourse->save();

        return response()->json(['message'=>'user course created']);
    }

    public function registeredCourse(): JsonResponse
    {
        $courses = User::where('id', Auth::id())->with('courses')->get();

        return response()->json($courses );
    }

    public function unregisteredCourse()
    {
        $userId = Auth::id();
        $courses = Course::whereDoesntHave('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })->get();

        return response()->json($courses);
    }
}
