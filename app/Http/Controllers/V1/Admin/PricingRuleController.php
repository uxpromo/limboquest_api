<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\PricingRule\PricingRuleDestroyRequest;
use App\Http\Requests\V1\Admin\PricingRule\PricingRuleIndexRequest;
use App\Http\Requests\V1\Admin\PricingRule\PricingRuleShowRequest;
use App\Http\Requests\V1\Admin\PricingRule\PricingRuleStoreRequest;
use App\Http\Requests\V1\Admin\PricingRule\PricingRuleUpdateRequest;
use App\Http\Resources\V1\Admin\PricingRule\PricingRuleResource;
use App\Models\PricingRule;
use FinzorDev\Api\ApiResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class PricingRuleController extends Controller
{
    public function index(PricingRuleIndexRequest $request): ApiResponse
    {
        $models = PricingRule::query()->orderBy('name')->get();
        return $this->apiResponse->withData(PricingRuleResource::collection($models));
    }

    /**
     * @throws Throwable
     */
    public function store(PricingRuleStoreRequest $request): ApiResponse
    {
        $this->can('create', PricingRule::class);

        $model = new PricingRule;
        $model->fill($request->validated());
        if ($model->save()) {
            return $this->apiResponse
                ->withHttpCode(201)
                ->withData(new PricingRuleResource($model))
                ->withMessage('Создано');
        }

        Log::error('[PricingRuleController.store] Failed to create', ['validated' => $request->validated()]);
        return $this->apiResponse->error()->withMessage('Ошибка создания');
    }

    public function show(PricingRuleShowRequest $request, int $id): ApiResponse
    {
        $model = PricingRule::query()->findOrFail($id);
        $this->can('view', $model);
        return $this->apiResponse->withData(new PricingRuleResource($model));
    }

    /**
     * @throws Throwable
     */
    public function update(PricingRuleUpdateRequest $request, int $id): ApiResponse
    {
        $model = PricingRule::query()->findOrFail($id);
        $this->can('update', $model);
        $updated = $model->update($request->validated());

        if ($updated) {
            return $this->apiResponse
                ->withData(new PricingRuleResource($model->fresh()))
                ->withMessage('Обновлено');
        }

        Log::error('[PricingRuleController.update] Failed to update', ['id' => $id]);
        return $this->apiResponse->error()->withMessage('Ошибка обновления');
    }

    public function destroy(PricingRuleDestroyRequest $request, int $id): ApiResponse
    {
        $model = PricingRule::query()->findOrFail($id);
        $this->can('delete', $model);
        if ($model->delete()) {
            return $this->apiResponse->withMessage('Удалено');
        }
        Log::error('[PricingRuleController.destroy] Failed to delete', ['id' => $id]);
        return $this->apiResponse->error()->withMessage('Ошибка удаления');
    }
}
