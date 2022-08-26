<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCourseController extends Controller
{
    public function registerCourse(Request $request)
    {

        $userCourse = new CourseUser();
        $userCourse->user_id=Auth::id();
        $userCourse->course_id=$request->courseId;
        $userCourse->save();

        return 'user course created';
    }

    public function registeredCourse(){

        $courses = User::where('id', Auth::id())->with('courses')->get();
        return response()->json($courses );
    }
//    public function unregisteredCourse(){
//
//        $courses = User::where('id', Auth::id())->with('courses')->get();
//        return response()->json($courses );
//    }

    public function unregisteredCourse()
    {
//        $courses = Course::where('user_id',  Auth::id())->with('courses')->get();
        $courses = Course::with('users')->where('id' )->get();
        return response()->json($courses);
    }


}
