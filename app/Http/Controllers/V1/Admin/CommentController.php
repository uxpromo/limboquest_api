<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Comment\CommentDestroyRequest;
use App\Http\Requests\V1\Admin\Comment\CommentIndexRequest;
use App\Http\Requests\V1\Admin\Comment\CommentShowRequest;
use App\Http\Requests\V1\Admin\Comment\CommentStoreRequest;
use App\Http\Requests\V1\Admin\Comment\CommentUpdateRequest;
use App\Http\Resources\V1\Admin\Comment\CommentResource;
use App\Models\Comment;
use FinzorDev\Api\ApiResponse;
use Throwable;

class CommentController extends Controller
{
    public function index(CommentIndexRequest $request): ApiResponse
    {
        $models = Comment::query()->get();
        return $this->apiResponse->withData(CommentResource::collection($models));
    }

    /**
     * @throws Throwable
     */
    public function store(CommentStoreRequest $request): ApiResponse
    {
        $this->can('create', Comment::class);

        $model = new Comment;
        $model->fill($request->validated());
        if ($model->save()) {
            return $this->apiResponse
                ->withHttpCode(201)
                ->withData(new CommentResource($model));
        }

        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка создания');
    }

    public function show(CommentShowRequest $request, int $id): ApiResponse
    {
        $model = Comment::query()->findOrFail($id);
        $this->can('view', $model);
        return $this->apiResponse->withData(new CommentResource($model));
    }

    /**
     * @throws Throwable
     */
    public function update(CommentUpdateRequest $request, int $id): ApiResponse
    {
        $model = Comment::query()->findOrFail($id);
        $this->can('update', $model);
        $updated = $model->update($request->validated());

        if ($updated) {
            return $this->apiResponse
                ->withData(new CommentResource($model));
        }

        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка обновления');
    }

    public function destroy(CommentDestroyRequest $request, int $id): ApiResponse
    {
        $model = Comment::query()->findOrFail($id);
        $this->can('delete', $model);
        if ($model->delete()) {
            return $this->apiResponse;
        }

        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка удаления');
    }
}
