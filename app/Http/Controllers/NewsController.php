<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function createNews(Request $request){

        $validator = $request->validate([
            'title'=>'required|min:2',
            'body'=>'required|min:8',

        ]);

        if($validator) {
            $news = new News();
            $news->title = $request->title;
            $news->body = $request->body;
            $news->save();
            return response()->json($news);
        } else {
            return 'validator error';
        }
    }

    public function index(){
        $news = News::get();
        return response()->json($news);
    }


    public function editNews(Request $request, $id){

        $validator = $request->validate([
            'title'=>'required|min:2',
            'body'=>'required|min:8',

        ]);

        if($validator) {
            $news = News::find($id);
            $news->title = $request->title;
            $news->body = $request->body;
            $news->save();
            return response()->json($news);
        } else {
            return 'validator error';
        }
    }

    public function getNews($id)
    {
        $news = News::find($id);
        return response()->json($news);
    }

    public function destroy(News $news, $id)
    {
        $news = News::find($id);
        $news->delete();
        return response()->json('News deleted!');
    }

}
