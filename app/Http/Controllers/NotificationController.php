<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = $request->validate([
            'title'=>'required',
            'body'=>'required|min:8',
        ]);

        if (!$validator) return response()->json(['message'=>'validator error']);

        $notification = new Notification();
        $notification->title = $request->title;
        $notification->body = $request->body;
        $notification->user_id=Auth::id();
        $notification->course_id='1';
        $notification->save();

        return response()->json($notification);
    }

    public function index(): JsonResponse
    {
        $notifications = Notification::get();

        return response()->json($notifications);
    }

    public function getTeacherNotification(): JsonResponse
    {
        $course= Notification::with('user', 'course')->where('user_id', Auth::id())->get();

        return response()->json($course);
    }

    public  function courseNotifications(): JsonResponse
    {
        $user = auth()->user();
        $notification = User::with(['courses' => function ($q) use ($user) {
                $q->with(['notifications'=> function ($q) use ($user) {
                    $q->with('user');
                }]);
            }])
            ->where('id', $user->id)
            ->get();

        return  response()->json($notification);
    }

    public function editNotification(Request $request,int $id): JsonResponse
    {
        $notification = Notification::find($id);
        $notification->title = $request->title;
        $notification->body = $request->body;
        $notification->save();

        return response()->json($notification);
    }

    public function getNotification(int $id): JsonResponse
    {
        $notification = Notification::find($id);

        return response()->json($notification);
    }

    public function destroy(int $id): JsonResponse
    {
        $notification = Notification::find($id);
        $notification->delete();

        return response()->json('Notification deleted!');
    }
}
