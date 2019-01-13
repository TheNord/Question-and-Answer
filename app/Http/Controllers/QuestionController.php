<?php

namespace App\Http\Controllers;

use App\Http\Requests\Questions\CreateRequest;
use App\Http\Resources\QuestionDetailResource;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\UseCases\Question\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    private $service;

    public function __construct(QuestionService $service)
    {
        $this->middleware('jwt', ['except' => ['index', 'show']]);
        $this->service = $service;
    }

    public function index()
    {
        return QuestionResource::collection(Question::latest()->with('vote')->limit(20)->get());
    }

    public function store(CreateRequest $request)
    {
        $question = $request->user()->question()->create($request->all());
        return response(new QuestionResource($question), 201);
    }

    public function show(Question $question)
    {
        $this->service->addView($question);
        return new QuestionDetailResource($question);
    }

    public function update(Request $request, Question $question)
    {
        try {
            $this->checkAccess($question->user_id);
            $request->validate([
                'title' => ['required', 'string', 'max:255', Rule::unique('questions')->ignore($question->id)],
                'body' => 'required|string|min:30'
            ]);
            $question->update($request->all());
            return response('Updated', 202);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Question $question)
    {
        try {
            $this->checkAccess($question->user_id);
            $question->delete();
            return response('Deleted', 200);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }

    }

    private function checkAccess($id)
    {
        if (auth()->id() != $id) {
            throw new \Exception('You can only manage your questions.');
        }

    }
}
