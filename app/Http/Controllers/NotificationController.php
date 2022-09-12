<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $notification = new Notification();
        $notification->title = $request->title;
        $notification->body = $request->body;
        $notification->user_id=Auth::id();
        $notification->course_id='1';
        $notification->save();
        return response()->json($notification);
    }

    public function index()
    {
        $notifications = Notification::get();
        return response()->json($notifications);
    }

    public function getTeacherNotification()
    {
            $course= Notification::with('user', 'course')->where('user_id', Auth::id())->get();
            return response()->json($course);
    }

    public  function courseNotifications()
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

    public function editNotification(Request $request, $id)
    {
        $notification = Notification::find($id);
        $notification->title = $request->title;
        $notification->body = $request->body;
        $notification->save();
        return response()->json($notification);
    }

    public function getNotification($id)
    {
        $notification = Notification::find($id);
        return response()->json($notification);
    }

    public function destroy(Notification $notification, $id)
    {
        $notification = Notification::find($id);
        $notification->delete();
        return response()->json('Notification deleted!');
    }
}
