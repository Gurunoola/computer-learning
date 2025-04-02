<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserProgressRequest;
use App\Http\Resources\UserProgressResource;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class UserProgressController extends Controller
{
    /**
     * Display a listing of the user progress with sorting, filtering, and pagination.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $filterableFields = [
            'user_id' => '=',
            'topic_id' => '=',
            'status' => '='
        ];

        $query = UserProgress::query();

        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $filterableFields)) {
                $operator = $filterableFields[$key];
                $query->where($key, $operator, $value);
            }
        }

        $userProgress = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return UserProgressResource::collection($userProgress);
    }

    /**
     * Store a newly created user progress in storage.
     *
     * @param  \App\Http\Requests\StoreUserProgressRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserProgressRequest $request)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();

        $userProgress = UserProgress::create([
            'user_id' => $request->user_id,
            'topic_id' => $request->topic_id,
            'status' => $request->status,
        ]);

        return (new UserProgressResource($userProgress))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified user progress.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $userProgress = UserProgress::findOrFail($id);

        return (new UserProgressResource($userProgress))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified user progress in storage.
     *
     * @param  \App\Http\Requests\StoreUserProgressRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreUserProgressRequest $request, $id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $userProgress = UserProgress::findOrFail($id);
        $userProgress->update($request->validated());

        return (new UserProgressResource($userProgress))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified user progress from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $userProgress = UserProgress::findOrFail($id);
        $userProgress->delete();

        return response()->json(['message' => 'User Progress deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    /**
     * Method to retrieve soft deleted user progress.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function trashed()
    {
        $trashedUserProgress = UserProgress::onlyTrashed()->get();
        return response()->json($trashedUserProgress);
    }

    /**
     * Method to restore a soft deleted user progress.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $userProgress = UserProgress::onlyTrashed()->findOrFail($id);
        $userProgress->restore();

        return response()->json(['message' => 'User Progress restored successfully'], Response::HTTP_OK);
    }

    /**
     * Method to permanently delete a soft deleted user progress.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $userProgress = UserProgress::onlyTrashed()->findOrFail($id);
        $userProgress->forceDelete();

        return response()->json(['message' => 'User Progress permanently deleted'], Response::HTTP_OK);
    }
}
