<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\QuestSession\QuestSessionDestroyRequest;
use App\Http\Requests\V1\Admin\QuestSession\QuestSessionIndexRequest;
use App\Http\Requests\V1\Admin\QuestSession\QuestSessionShowRequest;
use App\Http\Requests\V1\Admin\QuestSession\QuestSessionStoreRequest;
use App\Http\Requests\V1\Admin\QuestSession\QuestSessionUpdateRequest;
use App\Http\Resources\V1\Admin\QuestSession\QuestSessionResource;
use App\Models\QuestSession;
use FinzorDev\Api\ApiResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class QuestSessionController extends Controller
{
    public function index(QuestSessionIndexRequest $request): ApiResponse
    {
        $models = QuestSession::query()->get();
        return $this->apiResponse->withData(QuestSessionResource::collection($models));
    }

    /**
     * @throws Throwable
     */
    public function store(QuestSessionStoreRequest $request): ApiResponse
    {
        $this->can('create', QuestSession::class);

        $model = new QuestSession();
        $model->fill($request->validated());
        if ($model->save()) {
            return $this->apiResponse
                ->withHttpCode(201)
                ->withData(new QuestSessionResource($model))
                ->withMessage('Создано');
        }

        Log::error('[QuestSessionController.store] Failed to create quest session', ['validated' => $request->validated()]);
        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка создания');
    }

    public function show(QuestSessionShowRequest $request, int $id): ApiResponse
    {
        $model = QuestSession::query()->findOrFail($id);
        $this->can('view', $model);
        return $this->apiResponse->withData(new QuestSessionResource($model));
    }

    /**
     * @throws Throwable
     */
    public function update(QuestSessionUpdateRequest $request, int $id): ApiResponse
    {
        $model = QuestSession::query()->findOrFail($id);
        $this->can('update', $model);
        $updated = $model->update($request->validated());

        if ($updated) {
            return $this->apiResponse
                ->withData(new QuestSessionResource($model->fresh()))
                ->withMessage('Обновлено');
        }

        Log::error('[QuestSessionController.update] Failed to update quest session', ['id' => $id]);
        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка обновления');
    }

    public function destroy(QuestSessionDestroyRequest $request, int $id): ApiResponse
    {
        $model = QuestSession::query()->findOrFail($id);
        $this->can('delete', $model);
        if ($model->delete()) {
            return $this->apiResponse->withMessage('Удалено');
        }
        Log::error('[QuestSessionController.destroy] Failed to delete quest session', ['id' => $id]);
        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка удаления');
    }
}
