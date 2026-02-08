<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\Location\LocationDestroyRequest;
use App\Http\Requests\Api\V1\Admin\Location\LocationIndexRequest;
use App\Http\Requests\Api\V1\Admin\Location\LocationShowRequest;
use App\Http\Requests\Api\V1\Admin\Location\LocationStoreRequest;
use App\Http\Requests\Api\V1\Admin\Location\LocationUpdateRequest;
use App\Http\Resources\Api\V1\Admin\Location\LocationResource;
use App\Models\Location;
use FinzorDev\Api\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class LocationController extends Controller
{
    public function index(LocationIndexRequest $request): ApiResponse
    {
        $models = Location::query()->get();
        return $this->apiResponse->withData(LocationResource::collection($models));
    }

    /**
     * @throws Throwable
     */
    public function store(LocationStoreRequest $request): ApiResponse
    {
        $this->can('create', Location::class);

        $model = new Location;
        $model->fill($request->validated());
        if ($model->save()) {
            return $this->apiResponse
                ->withHttpCode(201)
                ->withData(new LocationResource($model))
                ->withMessage('Создано');
        }

        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка создания');
    }

    public function show(LocationShowRequest $request, int $id): ApiResponse
    {
        $model = Location::query()->findOrFail($id);
        $this->can('view', $model);
        return $this->apiResponse->withData(new LocationResource($model));
    }

    /**
     * @throws Throwable
     */
    public function update(LocationUpdateRequest $request, int $id): ApiResponse
    {
        $model = Location::query()->findOrFail($id);
        $this->can('update', $model);
        $updated = $model->update($request->validated());

        if ($updated) {
            return $this->apiResponse
                ->withData(new LocationResource($model))
                ->withMessage('Обновлено');
        }


        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка обновления');
    }

    public function destroy(LocationDestroyRequest $request, int $id): ApiResponse
    {
        $model = Location::query()->findOrFail($id);
        $this->can('delete', $model);
        if ($model->delete()) {
            return $this->apiResponse->withMessage('Удалено');
        }
        return $this->apiResponse
            ->error()
            ->withMessage('Ошибка удаления');
    }
}
