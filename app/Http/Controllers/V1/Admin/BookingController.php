<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Booking\BookingDestroyRequest;
use App\Http\Requests\V1\Admin\Booking\BookingIndexRequest;
use App\Http\Requests\V1\Admin\Booking\BookingShowRequest;
use App\Http\Requests\V1\Admin\Booking\BookingStoreRequest;
use App\Http\Requests\V1\Admin\Booking\BookingUpdateRequest;
use App\Http\Resources\V1\Admin\Booking\BookingResource;
use App\Models\Booking;
use FinzorDev\Api\ApiResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class BookingController extends Controller
{
    public function index(BookingIndexRequest $request): ApiResponse
    {
        $models = Booking::query()->orderBy('created_at', 'desc')->get();
        return $this->apiResponse->withData(BookingResource::collection($models));
    }

    /**
     * @throws Throwable
     */
    public function store(BookingStoreRequest $request): ApiResponse
    {
        $this->can('create', Booking::class);

        $data = $request->validated();
        if (empty($data['source_id'] ?? null)) {
            $data['source_id'] = 'admin';
        }
        if (!isset($data['pricing_snapshot'])) {
            $data['pricing_snapshot'] = [];
        }

        $model = new Booking();
        $model->fill($data);
        if ($model->save()) {
            return $this->apiResponse
                ->withHttpCode(201)
                ->withData(new BookingResource($model))
                ->withMessage('Создано');
        }

        Log::error('[BookingController.store] Failed to create', ['validated' => $request->validated()]);
        return $this->apiResponse->error()->withMessage('Ошибка создания');
    }

    public function show(BookingShowRequest $request, int $id): ApiResponse
    {
        $model = Booking::query()->findOrFail($id);
        $this->can('view', $model);
        return $this->apiResponse->withData(new BookingResource($model));
    }

    /**
     * @throws Throwable
     */
    public function update(BookingUpdateRequest $request, int $id): ApiResponse
    {
        $model = Booking::query()->findOrFail($id);
        $this->can('update', $model);
        $data = $request->validated();
        if (array_key_exists('source_id', $data) && empty($data['source_id'])) {
            $data['source_id'] = 'admin';
        }
        $updated = $model->update($data);

        if ($updated) {
            return $this->apiResponse
                ->withData(new BookingResource($model->fresh()))
                ->withMessage('Обновлено');
        }

        Log::error('[BookingController.update] Failed to update', ['id' => $id]);
        return $this->apiResponse->error()->withMessage('Ошибка обновления');
    }

    public function destroy(BookingDestroyRequest $request, int $id): ApiResponse
    {
        $model = Booking::query()->findOrFail($id);
        $this->can('delete', $model);
        if ($model->delete()) {
            return $this->apiResponse->withMessage('Удалено');
        }
        Log::error('[BookingController.destroy] Failed to delete', ['id' => $id]);
        return $this->apiResponse->error()->withMessage('Ошибка удаления');
    }
}
