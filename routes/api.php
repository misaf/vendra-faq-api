<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

Route::middleware('api')->group(function (): void {
    JsonApiRoute::server('vendra-faq')
        ->prefix('api/v1/vendra-faq')
        ->resources(function (ResourceRegistrar $server): void {
            $server->resource('categories', JsonApiController::class)->only('index');
            $server->resource('faqs', JsonApiController::class)->only('index');
        });
});
