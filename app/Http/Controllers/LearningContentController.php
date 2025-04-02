<?php

namespace App\Http\Controllers;

use App\Models\LearningContent;
use Illuminate\Http\Request;
use App\Http\Resources\LearningContentResource;
use App\Http\Requests\StoreLearningContentRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class LearningContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query = LearningContent::query();

        if ($request->has('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        $learningContents = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);
        return LearningContentResource::collection($learningContents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLearningContentRequest $request)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $learningContent = LearningContent::create($request->validated());

        return new LearningContentResource($learningContent);
    }

    /**
     * Display the specified resource.
     */
    public function show(LearningContent $learningContent)
    {
        return new LearningContentResource($learningContent);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLearningContentRequest $request, LearningContent $learningContent)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $learningContent->update($request->validated());

        return new LearningContentResource($learningContent);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LearningContent $learningContent)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $learningContent->delete();

        return response()->json(['message' => 'Learning content deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    /**
     * Restore a soft deleted resource.
     */
    public function restore($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $learningContent = LearningContent::onlyTrashed()->findOrFail($id);
        $learningContent->restore();

        return response()->json(['message' => 'Learning content restored successfully'], Response::HTTP_OK);
    }

    public function trashed()
    {
        $trashedQnAs = LearningContent::onlyTrashed()->get();
        return response()->json($trashedQnAs);
    }

    public function forceDelete($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $qna = LearningContent::onlyTrashed()->findOrFail($id);
        $qna->forceDelete();

        return response()->json(['message' => 'LearningContent permanently deleted'], Response::HTTP_OK);
    }
}
