<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(){
        $announcements = Announcement::get();
        return response()->json($announcements);
    }

    public function announcementCount(){
        $announcement = Announcement::count();
        if($announcement){
            return response()->json($announcement);
        } else {
            return 'no student';
        }

    }

    public function store(Request $request){
        $announcement = new Announcement();
        $announcement->title = $request->title;
        $announcement->body = $request->body;
        $announcement->save();
        return response()->json($announcement);
    }


    public  function editAnnouncement(Request $request, $id){
        $announcement = Announcement::find($id);
        $announcement->title = $request->title;
        $announcement->body = $request->body;
        $announcement->save();
         return response()->json($announcement);
    }

    public function getAnnouncement($id)
    {
        $announcement = Announcement::find($id);
        return response()->json($announcement);
    }


    public function destroy(Announcement $announcement, $id)
    {
        $announcement = Announcement::find($id);
        $announcement->delete();
        return response()->json('announcement deleted!');
    }
}
