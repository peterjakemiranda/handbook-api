<?php

namespace App\Http\Controllers;

use App\Chapter;
use Illuminate\Http\Request;
use App\StudentAnswer;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        $chapters = Chapter::all();
        if ($request->monitoring) {
            $chapters->map(function($ch) {
                $ch->append('access_count');
            });
        }

        return response()->json($chapters);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string',
            'description' => 'nullable|string',
        ];
        $this->validate($request, $rules);

        $chapter = new Chapter();
        $chapter->title = $request->title;
        $chapter->description = $request->description;
        $chapter->save();

        return response()->json($chapter);
    }

    public function show($id)
    {
        $chapter = Chapter::findOrFail($id);

        return response()->json($chapter);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|string',
            'description' => 'nullable|string',
        ];
        $this->validate($request, $rules);

        $chapter = Chapter::findOrFail($id);

        $chapter->title = $request->input('title');
        $chapter->description = $request->input('description');
        $chapter->save();

        return response()->json($chapter);
    }

    public function destroy($id)
    {
        $chapter = Chapter::findOrFail($id);
        $chapter->delete();

        return response()->json('chapter removed successfully');
    }

    public function answer(Request $request, $id)
    {
        StudentAnswer::updateOrCreate(['student_id' => auth()->id(), 'chapter_id' => $id]);
        return response()->json('answer added successfully');
    }
}
