<?php

namespace App\Http\Controllers;

use App\Http\Resources\QnAResource;
use App\Models\QnA;
use App\Http\Requests\StoreQnARequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class QnAController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $filterableFields = [
            'question' => 'like',
            'answer' => 'like',
            'topic_id' => '=',
            'deleted' => '='
        ];

        $query = QnA::query();

        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $filterableFields)) {
                $operator = $filterableFields[$key];
                $query->where($key, $operator, $operator === 'like' ? '%' . $value . '%' : $value);
            }
        }

        $qnas = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);
        return QnAResource::collection($qnas);
    }

    public function store(StoreQnARequest $request)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();
        $qna = QnA::create($validated);

        return (new QnAResource($qna))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $qna = QnA::findOrFail($id);
        return (new QnAResource($qna))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function update(StoreQnARequest $request, $id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $qna = QnA::findOrFail($id);
        $qna->update($request->validated());

        return (new QnAResource($qna))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $qna = QnA::findOrFail($id);
        $qna->delete();

        return response()->json(['message' => 'QnA deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    public function trashed()
    {
        $trashedQnAs = QnA::onlyTrashed()->get();
        return response()->json($trashedQnAs);
    }

    public function restore($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $qna = QnA::onlyTrashed()->findOrFail($id);
        $qna->restore();

        return response()->json(['message' => 'QnA restored successfully'], Response::HTTP_OK);
    }

    public function forceDelete($id)
    {
        if (Gate::denies('isAdmin')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        $qna = QnA::onlyTrashed()->findOrFail($id);
        $qna->forceDelete();

        return response()->json(['message' => 'QnA permanently deleted'], Response::HTTP_OK);
    }
}
