<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::controller(AuthController::class)
    ->middleware('api')
    ->prefix('auth')
    ->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
    });
Route::post('reload', function () {
    Artisan::call('migrate:refresh --seed');
});
Route::get('members/username/{username}', [MemberController::class, 'searchByUserName']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('users', UserController::class);
    Route::post('password_change', [UserController::class, 'passwordChange']);
    Route::post('status_change', [UserController::class, 'statusChange']);
    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('auth/profile', [AuthController::class, 'profile']);
    Route::apiResource('addresses', AddressController::class);
    Route::apiResource('members', MemberController::class);
    Route::apiResource('managers', ManagerController::class);
    Route::apiResource('documents', DocumentController::class);
    Route::apiResource('products', ProductController::class);
});
