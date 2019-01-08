<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        return CategoryResource::collection(Category::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255|unique:categories'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => str_slug($request->name)
        ]);

        return response(new CategoryResource($category), 201);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(Request $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'slug' => str_slug($request->name)
        ]);
        return response(new CategoryResource($category), 202);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response(null, 204);
    }
}
