<?php

use App\Http\Controllers\apiController;
use App\Http\Controllers\authController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/signup", [authController::class, "signup"]);
Route::post("/signin", [authController::class, "signin"]);

Route::any("/noAuth", [authController::class, "noAuth"])->name("login");

Route::get("/book", [apiController::class, "getAll"]);
Route::get("/book/{id}", [apiController::class, "get"]);
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/book", [apiController::class, "create"]);
    Route::put("/book", [apiController::class, "update"]);
    Route::delete("/book/{id}", [apiController::class, "delete"]);
});
