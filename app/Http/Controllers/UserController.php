<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseUser;
use App\Models\SubjectUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class UserController extends Controller
{
    public function userCourse()
    {
       $courses = User::where('id', Auth::id())->whereHas('courses')->with('courses')->get();
        return $courses;
    }

    public function getUserInformation()
    {
        $users = Course::with('users')->get();
        return response()->json($users);

    }

    public function studentTeacherCourse()
    {
        $role ='student';
        $userId = Auth()->id();
        $course = Course::whereHas('users', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('users')->get();
        return $course;
    }



    public function getTeachers()
    {
        $teachers = User::where('role', 'teacher')->with(['subject', 'courses'])->get();

        return response()->json($teachers);
    }
    public function getAllUsers()
    {
        $teachers = User::where('role', 'student')->with('courses')->get();
        return response()->json($teachers);
    }

    public function getTeacher($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function editTeacher(Request $request, $id)
    {
//        return $request->all();
        $user = User::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->mobile=$request->mobile;
        $user->password=Hash::make($request->password);

        $user->save();


        if($request->courseId){
            $user->courses()->sync($request->category_id);
            $course = CourseUser::create([
                'course_id'=>$request->courseId,
                'user_id'=>$user->id
            ]);
            $course->save();
        }
        return 'Teacher updated';
    }

    public function studentsCount()
    {
        $userId = Auth()->id();
        $course = Course::whereHas('users', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('users')->count();

        return response()->json($course);
    }

    public function register(Request $request)
    {
        $validator = $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'mobile'=>'required',
            'role'=>'required',
            'password' => 'required|min:8',
        ]);

        if($validator){
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
        } else{
            return response()->json($validator->errore());
        }
    }

    public function update(Request $request)
    {
            $user = Auth::user();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password = Hash::make($request->password);
            $user->mobile=$request->mobile;
            if($request->role_number){
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

            if($request->subject_id){

                $subject = SubjectUser::create([
                    'subject_id'=>$request->subject_id,
                    'user_id'=>$user->id
                ]);
                $subject->save();
            }

            return response()->json(['message' => 'user already updated']);
    }

    public function login(Request $request)
    {
        $validator = $request->validate([
            'email'=>'required|email',
            'password' => 'required',
        ]);
        if($validator){
            $user = User::query()->where('email', $request->email,)->first();

            if($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $response = ['token' => $token];
                    return response($response);
                } else {
                    $response = ["message" => "Password mismatch"];
                    return response($response, 422);
                }
            } else {
                $response = ["message" =>'User does not exist'];
                return response($response, 422);
            }
        } else{
            return response()->json($validator->erroe());
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = $request->validate([
            'email'=>'required|email',
            'password' => 'required',
            'new_password'=> 'required'
        ]);
        if($validator){
            $user = User::query()->where('email', $request->email)->first();
            if($user){
                if($user->password = Hash::make($request->password)){
                    $user->password ='';
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                    return response()->json($user);
                }else{
                    $response = ["message" =>'Password mismatch'];
                    return response()->json($response);
                }
            }else{
                $response = ["message" =>'User does not exist'];
                return response()->json($response);
            }
        }
    }

    public function me()
    {
        return response()->json(['user' => auth()->user()]);
    }


    public function meData()
    {
        $user = User::where('id', Auth::id())->with('courses')->get();
        return response()->json( $user);
    }

    public function forgotPassword(Request $request)
    {
        $user = User::query()->where('email', $request->email,)->first();
        $user->password ='';
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
