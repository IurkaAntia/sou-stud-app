<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpoTokenController;
use Illuminate\Support\Facades\Route;

Route::post("/login", [AuthController::class, "login"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/store-token", [ExpoTokenController::class, "store"]);
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/user", [AuthController::class, "getUser"]);
});
