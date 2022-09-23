<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function createNews(Request $request): JsonResponse
    {
        $validator = $request->validate([
            'title'=>'required|min:2',
            'body'=>'required|min:8',
        ]);

        if(!$validator) response()->json(['message'=>'validator error']);

         $news = new News();
         $news->title = $request->title;
         $news->body = $request->body;
         $news->save();

         return response()->json($news);
    }

    public function index(): JsonResponse
    {
        $news = News::get();

        return response()->json($news);
    }

    public function editNews(Request $request,int $id): JsonResponse
    {
        $validator = $request->validate([
            'title'=>'required|min:2',
            'body'=>'required|min:8',
        ]);

        if (!$validator) return response()->json(['message'=>'validator error']);

        $news = News::find($id);
        $news->title = $request->title;
        $news->body = $request->body;
        $news->save();

        return response()->json($news);
    }

    public function getNews(int $id): JsonResponse
    {
        $news = News::find($id);

        return response()->json($news);
    }

    public function destroy(int $id)
    {
        $news = News::find($id);
        $news->delete();

        return response()->json('News deleted!');
    }
}
