<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Quest\QuestDestroyRequest;
use App\Http\Requests\V1\Admin\Quest\QuestIndexRequest;
use App\Http\Requests\V1\Admin\Quest\QuestShowRequest;
use App\Http\Requests\V1\Admin\Quest\QuestStoreRequest;
use App\Http\Requests\V1\Admin\Quest\QuestUpdateRequest;
use App\Http\Resources\V1\Admin\Quest\QuestResource;
use App\Models\Quest;
use FinzorDev\Api\ApiResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class QuestController extends Controller
{
    public function index(QuestIndexRequest $request): ApiResponse
    {
        $models = Quest::query()->get();
        return $this->apiResponse->withData(QuestResource::collection($models));
    }

    /**
     * @throws Throwable
     */
    public function store(QuestStoreRequest $request): ApiResponse
    {
        $this->can('create', Quest::class);

        $model = new Quest;
        $model->fill($request->validated());
        if ($model->save()) {
            return $this->apiResponse
                ->withHttpCode(201)
                ->withData(new QuestResource($model))
                ->withMessage('Создано');
        }

        Log::error('[QuestController.store] Failed to create quest', ['validated' => $request->validated()]);
        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка создания');
    }

    public function show(QuestShowRequest $request, int $id): ApiResponse
    {
        $model = Quest::query()->findOrFail($id);
        $this->can('view', $model);
        return $this->apiResponse->withData(new QuestResource($model));
    }

    /**
     * @throws Throwable
     */
    public function update(QuestUpdateRequest $request, int $id): ApiResponse
    {
        $model = Quest::query()->findOrFail($id);
        $this->can('update', $model);
        $updated = $model->update($request->validated());

        if ($updated) {
            return $this->apiResponse
                ->withData(new QuestResource($model->fresh()))
                ->withMessage('Обновлено');
        }

        Log::error('[QuestController.update] Failed to update quest', ['id' => $id]);
        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка обновления');
    }

    public function destroy(QuestDestroyRequest $request, int $id): ApiResponse
    {
        $model = Quest::query()->findOrFail($id);
        $this->can('delete', $model);
        if ($model->delete()) {
            return $this->apiResponse->withMessage('Удалено');
        }
        Log::error('[QuestController.destroy] Failed to delete quest', ['id' => $id]);
        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка удаления');
    }
}
