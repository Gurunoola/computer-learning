<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the categories with sorting, filtering, and pagination.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $filterableFields = [
            'name' => 'like',
        ];

        $query = Category::query();

        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $filterableFields)) {
                $operator = $filterableFields[$key];
                $query->where($key, $operator, $operator === 'like' ? '%' . $value . '%' : $value);
            }
        }

        $categories = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreCategoryRequest $request, $id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $category = Category::findOrFail($id);
        $category->update($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    /**
     * Method to retrieve soft deleted categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function trashed()
    {
        $trashedCategories = Category::onlyTrashed()->get();
        return response()->json($trashedCategories);
    }

    /**
     * Method to restore a soft deleted category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return response()->json(['message' => 'Category restored successfully'], Response::HTTP_OK);
    }

    /**
     * Method to permanently delete a soft deleted category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        return response()->json(['message' => 'Category permanently deleted'], Response::HTTP_OK);
    }
}
