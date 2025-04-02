<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserTestResultRequest;
use App\Http\Resources\UserTestResultResource;
use App\Models\UserTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class UserTestResultController extends Controller
{
    /**
     * Display a listing of test results with filtering, sorting, and pagination.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $sortBy = $request->get('sort_by', 'attempted_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $filterableFields = [
            'user_id' => '=',
            'topic_id' => '=',
            'score' => '>=',
        ];

        $query = UserTestResult::query();

        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $filterableFields)) {
                $operator = $filterableFields[$key];
                $query->where($key, $operator, $value);
            }
        }

        $results = $query->with(['user', 'topic'])->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return UserTestResultResource::collection($results);
    }

    /**
     * Store a new test result.
     *
     * @param StoreUserTestResultRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserTestResultRequest $request)
    {
        if (Gate::denies('isUser')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $testResult = UserTestResult::create([
            'user_id' => $request->user_id,
            'topic_id' => $request->topic_id,
            'score' => $request->score,
            'total_questions' => $request->total_questions,
            'correct_answers' => $request->correct_answers,
            'attempted_at' => now(),
        ]);

        return (new UserTestResultResource($testResult))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified test result.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $testResult = UserTestResult::with(['user', 'topic'])->findOrFail($id);

        return (new UserTestResultResource($testResult))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove a test result.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $testResult = UserTestResult::findOrFail($id);
        $testResult->delete();

        return response()->json(['message' => 'Test result deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    /**
     * Get trashed test results (soft deletes).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function trashed()
    {
        $trashedResults = UserTestResult::onlyTrashed()->get();

        return response()->json($trashedResults);
    }

    /**
     * Restore a soft deleted test result.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $testResult = UserTestResult::onlyTrashed()->findOrFail($id);
        $testResult->restore();

        return response()->json(['message' => 'Test result restored successfully']);
    }
}
