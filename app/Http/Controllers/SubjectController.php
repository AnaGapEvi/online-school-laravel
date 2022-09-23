<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(): JsonResponse
    {
        $subjects = Subject::get();
        return response()->json($subjects);
    }

    public function store(Request $request): JsonResponse
    {
        $subject = new Subject();
        $subject->name = $request->name;
        $subject->save();

        return response()->json($subject);
    }

    public function destroy(int $id): JsonResponse
    {
        $subject = Subject::find($id);
        $subject->delete();
        return response()->json('Subject deleted!');
    }
}
