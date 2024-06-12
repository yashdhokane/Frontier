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


Route::post('get_products', [ApiController::class, 'get_products']);

Route::post('user_login', [ApiController::class, 'user_login']);
Route::post('reset_password', [ApiController::class, 'reset_password']);

Route::post('getTechnicianJobs', [ApiController::class, 'getTechnicianJobs']);
Route::post('getNotification', [ApiController::class, 'getNotification']);

Route::post('getTechnicianJobsHistory', [ApiController::class, 'getTechnicianJobsHistory']);
 Route::post('getCustomerHistory', [ApiController::class, 'getCustomerHistory']);
Route::post('jobfileUploadByTechnician', [ApiController::class, 'jobfileUploadByTechnician']);

 Route::post('getPartsByTechnicianId', [ApiController::class, 'getPartsByTechnicianId']);

 Route::post('technicianLogout', [ApiController::class, 'technicianLogout']);

 Route::post('updateTechnicianProfile', [ApiController::class, 'updateTechnicianProfile']);

 Route::post('calculateJobStatusPercentage', [ApiController::class, 'calculateJobStatusPercentage']);
Route::post('updateEnroute', [ApiController::class, 'updateEnroute']);
Route::post('updateStart', [ApiController::class, 'updateStart']);
Route::post('updateComplete', [ApiController::class, 'updateComplete']);
Route::post('getAppDisclaimer', [ApiController::class, 'getAppDisclaimer']);

