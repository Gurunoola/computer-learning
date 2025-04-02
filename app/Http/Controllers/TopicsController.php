<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class TopicsController extends Controller
{
    /**
     * Display a listing of the topics with sorting, filtering, and pagination.
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
            'category_id' => '='
        ];

        $query = Topic::query();

        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $filterableFields)) {
                $operator = $filterableFields[$key];
                $query->where($key, $operator, $operator === 'like' ? '%' . $value . '%' : $value);
            }
        }

        $topics = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return TopicResource::collection($topics);
    }

    /**
     * Store a newly created topic in storage.
     *
     * @param  \App\Http\Requests\StoreTopicRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTopicRequest $request)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();

        $topic = Topic::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return (new TopicResource($topic))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified topic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $topic = Topic::findOrFail($id);

        return (new TopicResource($topic))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified topic in storage.
     *
     * @param  \App\Http\Requests\StoreTopicRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreTopicRequest $request, $id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $topic = Topic::findOrFail($id);
        $topic->update($request->validated());

        return (new TopicResource($topic))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified topic from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $topic = Topic::findOrFail($id);
        $topic->delete();

        return response()->json(['message' => 'Topic deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    /**
     * Method to retrieve soft deleted topics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function trashed()
    {
        $trashedTopics = Topic::onlyTrashed()->get();
        return response()->json($trashedTopics);
    }

    /**
     * Method to restore a soft deleted topic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $topic = Topic::onlyTrashed()->findOrFail($id);
        $topic->restore();

        return response()->json(['message' => 'Topic restored successfully'], Response::HTTP_OK);
    }

    /**
     * Method to permanently delete a soft deleted topic.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $topic = Topic::onlyTrashed()->findOrFail($id);
        $topic->forceDelete();

        return response()->json(['message' => 'Topic permanently deleted'], Response::HTTP_OK);
    }
}
