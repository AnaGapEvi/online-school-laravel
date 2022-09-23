<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogsController extends Controller
{
    public function courseTeacher(): JsonResponse
    {
        $courses = User::where('id', Auth::id())->whereHas('courses')->with('courses')->get();

        return response()->json($courses);
    }

    public function getLogs(): JsonResponse
    {
        $logs = Logs::orderBy('id', 'DESC')->with('user')->get();

        return response()->json($logs);
    }

    public function searchLogs(Request $request):JsonResponse
    {
       $logs = Logs::orderBy('id', 'DESC')
           ->with('user')
           ->where('page_name', 'LIKE', '%'.$request->keyword.'%')
           ->get();

        return response()->json($logs);
    }

    public function logs(Request  $request): JsonResponse
    {
        $log = new Logs;
        $log->user_id=Auth::id();
        $log->page_name=$request->pageName;
        $log->save();

        return response()->json(['message'=>'component mounted'], 200);
    }
}
