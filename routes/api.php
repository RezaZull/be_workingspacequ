<?php

use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\MMenuController;
use App\Http\Controllers\MMenuGroupController;
use App\Http\Controllers\MMenuGroupDetailController;
use App\Http\Controllers\MRoleController;
use App\Http\Controllers\MUserController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/register", [MUserController::class, "register"])->name("register");
Route::post("/login", [MUserController::class, "login"])->name("login");
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post("/logout", [MUserController::class, "logout"])->name("logout");
    Route::post("/mUser/uploadPicture/{mUser}", [MUserController::class, "uploadPicture"])->name("mUser.uploadPicture");
    Route::post("/mUser/changePassword/{mUser}", [MUserController::class, "changePassword"])->name("mUser.changePassword");
    Route::put("/appSettingBulk/update", [AppSettingController::class, "bulkUpdate"])->name("appSetting.bulkUpdate");
    Route::apiResources([
        'mUser' => MUserController::class,
        'mMenu' => MMenuController::class,
        'mRole' => MRoleController::class,
        'mMenuGroup' => MMenuGroupController::class,
        'mMenuGroupDetail' => MMenuGroupDetailController::class,
        'appSetting' => AppSettingController::class,
    ]);
});
