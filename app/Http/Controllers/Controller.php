<?php

namespace App\Http\Controllers;

use FinzorDev\Api\ApiResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

abstract class Controller
{
    /**
     * @var ApiResponse
     */
    public ApiResponse $apiResponse;

    public function __construct()
    {
        $this->apiResponse = ApiResponse::create();
    }

    public function can(string $action, string|Model $model): void
    {
        Gate::authorize($action, $model);
    }
}
