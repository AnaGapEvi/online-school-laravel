<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(): JsonResponse
    {
        $announcements = Announcement::get();
        return response()->json($announcements);
    }

    public function announcementCount(): JsonResponse
    {
        $announcement = Announcement::count();
        if($announcement){
            return response()->json($announcement);
        } else {
            return response()->json(['message'=>'no student']);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $announcement = new Announcement();
        $announcement->title = $request->title;
        $announcement->body = $request->body;
        $announcement->save();

        return response()->json($announcement);
    }


    public  function editAnnouncement(Request $request,int $id): JsonResponse
    {
        $announcement = Announcement::find($id);
        $announcement->title = $request->title;
        $announcement->body = $request->body;
        $announcement->save();

        return response()->json($announcement);
    }

    public function getAnnouncement(int $id): JsonResponse
    {
        $announcement = Announcement::find($id);

        return response()->json($announcement);
    }

    public function destroy(int $id):JsonResponse
    {
        $announcement = Announcement::find($id);
        $announcement->delete();

        return response()->json('announcement deleted!');
    }
}
