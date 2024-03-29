<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('user_login', [ApiController::class, 'user_login']);
Route::post('reset_password', [ApiController::class, 'reset_password']);

Route::post('getTechnicianJobs', [ApiController::class, 'getTechnicianJobs']);

Route::post('getTechnicianJobsHistory', [ApiController::class, 'getTechnicianJobsHistory']);
