<?php

use App\Http\Controllers\Api\{
    CategoryController,
    GenreController,
};
use Illuminate\Support\Facades\Route;

Route::apiResource('/categories', CategoryController::class);
Route::apiResource(
    name: '/genres',
    controller: GenreController::class
);

Route::get('/', function () {
    return response()->json(['message' => 'success']);
});
