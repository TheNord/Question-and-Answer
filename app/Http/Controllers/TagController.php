<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tags\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        return TagResource::collection(Tag::latest()->get());
    }

    public function store(TagRequest $request)
    {
        $Tag = Tag::create([
            'name' => $request->name,
            'slug' => str_slug($request->name)
        ]);
        return response(new TagResource($Tag), 201);
    }

    public function show(Tag $Tag)
    {
        return new TagResource($Tag);
    }

    public function update(Request $request, Tag $Tag)
    {
        $Tag->update([
            'name' => $request->name,
            'slug' => str_slug($request->name)
        ]);
        return response(new TagResource($Tag), 202);
    }

    public function destroy(Tag $Tag)
    {
        $Tag->delete();
        return response(null, 204);
    }
}
