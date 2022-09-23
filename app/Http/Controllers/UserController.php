<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseUser;
use App\Models\SubjectUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userCourse(): JsonResponse
    {
       $courses = User::where('id', Auth::id())->whereHas('courses')->with('courses')->get();

       return response()->json($courses);
    }

    public function getUserInformation(): JsonResponse
    {
        $users = Course::with('users')->get();

        return response()->json($users);
    }

    public function studentTeacherCourse(): JsonResponse
    {
        $userId = Auth()->id();
        $course = Course::whereHas('users', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('users')->get();

        return response()->json($course);
    }

    public function getTeachers(): JsonResponse
    {
        $teachers = User::where('role', 'teacher')->with(['subject', 'courses'])->get();

        return response()->json($teachers);
    }

    public function getAllUsers(): JsonResponse
    {
        $teachers = User::where('role', 'student')->with('courses')->get();

        return response()->json($teachers);
    }

    public function getTeacher(int $id): JsonResponse
    {
        $user = User::find($id);

        return response()->json($user);
    }

    public function editTeacher(Request $request,int $id): JsonResponse
    {
        $user = User::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->mobile=$request->mobile;
        $user->password=Hash::make($request->password);
        $user->save();

        if ($request->courseId) {
            $user->courses()->sync($request->category_id);
            $course = CourseUser::create([
                'course_id'=>$request->courseId,
                'user_id'=>$user->id
            ]);
            $course->save();
        }

        return response()->json(['message'=>'Teacher updated']);
    }

    public function studentsCount(): JsonResponse
    {
        $userId = Auth()->id();
        $course = Course::whereHas('users', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('users')->count();

        return response()->json($course);
    }

    public function register(Request $request): JsonResponse
    {
        $validator = $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'mobile'=>'required',
            'role'=>'required',
            'password' => 'required|min:8',
        ]);

        if(!$validator) return response()->json($validator->error());

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password' => Hash::make($request->password),
            'role_number'=>$request->role_number,
            'role'=>$request->role,
            'mobile'=>$request->mobile,
        ]);

        $token = $user->createToken('Laravel')->accessToken;
        $user->reg_token = $token;
        $user->save();
        $course = CourseUser::create([
            'course_id'=>$request->course_id,
            'user_id'=>$user->id
        ]);
        $course->save();

        if($request->subject_id){
            $subject = SubjectUser::create([
                'subject_id'=>$request->subject_id,
                'user_id'=>$user->id
            ]);
            $subject->save();
        }

        return response()->json(['token' => $token], 200);
    }

    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password = Hash::make($request->password);
        $user->mobile=$request->mobile;

        if ($request->role_number) {
            $user->role_number=$request->role_number;
        }

        $user->save();
        $user->courses()->sync($request->category_id);

        if($request->course_id){
            $course = CourseUser::create([
                'course_id'=>$request->course_id,
                'user_id'=>$user->id
            ]);
            $course->save();
        }

        if ($request->subject_id) {
            $subject = SubjectUser::create([
                'subject_id'=>$request->subject_id,
                'user_id'=>$user->id
            ]);
            $subject->save();
        }

        return response()->json(['message' => 'user already updated']);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = $request->validate([
            'email'=>'required|email',
            'password' => 'required',
        ]);
        if(! $validator) return response()->json($validator->error());

        $user = User::query()->where('email', $request->email,)->first();

        if(!$user) return response()->json(["message" =>'User does not exist'], 422);

        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $response = ['token' => $token];
            return response()->json($response);
        } else {
            $response = ["message" => "Password mismatch"];
            return response()->json($response, 422);
        }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validator = $request->validate([
            'email'=>'required|email',
            'password' => 'required',
            'new_password'=> 'required'
        ]);
        if (! $validator) return response()->json($validator->error());

        $user = User::query()->where('email', $request->email)->first();

        if (!$user) return response()->json(["message" =>'User does not exist']);

        if ($user->password = Hash::make($request->password)) {
            $user->password ='';
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json($user);
        }else{
            $response = ["message" =>'Password mismatch'];

            return response()->json($response);
        }
    }

    public function me(): JsonResponse
    {
        return response()->json(['user' => auth()->user()]);
    }


    public function meData(): JsonResponse
    {
        $user = User::where('id', Auth::id())->with('courses')->get();

        return response()->json( $user);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $user = User::query()->where('email', $request->email,)->first();
        $user->password ='';

        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json($user);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
