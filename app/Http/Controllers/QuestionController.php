<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $chapterId = $request->input('chapter_id');
        if ($chapterId) {
            $questions = Question::where('chapter_id', $chapterId)->get();
        } elseif ($search = $request->input('search')) {
            $fuzzySearch = implode('%', str_split($search)); // e.g. test -> t%e%s%t
            $fuzzySearch = "%$fuzzySearch%";
            $questions = Question::select(DB::raw('id, question, chapter_id'))->where('title', 'like', $fuzzySearch)->where('question', 'like', $fuzzySearch)->get();
        } else {
            $questions = Question::all();
        }

        return response()->json($questions);
    }

    public function store(Request $request)
    {
        $rules = [
            'question' => 'nullable|string',
            'answer' => 'required|integer',
            'options' => 'required|string',
            'chapter_id' => 'required|integer|exists:chapters,id',
        ];
        $this->validate($request, $rules);

        $question = new Question();
        $question->question = $request->question;
        $question->answer = $request->answer;
        $question->options = $request->options;
        $question->chapter_id = $request->input('chapter_id');
        $question->save();

        return response()->json($question);
    }

    public function show($id)
    {
        $question = Question::findOrFail($id);

        return response()->json($question);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'question' => 'nullable|string',
            'answer' => 'required|integer',
            'options' => 'required|string',
            'chapter_id' => 'required|integer|exists:chapters,id',
        ];
        $this->validate($request, $rules);

        $question = Question::findOrFail($id);

        $question->question = $request->question;
        $question->answer = $request->answer;
        $question->options = $request->options;
        $question->chapter_id = $request->input('chapter_id');
        $question->save();

        return response()->json($question);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json('question removed successfully');
    }
}
