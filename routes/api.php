<?php

use App\Models\User;
use Silber\Bouncer\Bouncer;
use Illuminate\Http\Request;
use App\Http\Controllers\test;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\EmployeeController;
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

// hi rana

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/*
Route::post('/register', [test::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [test::class, 'logout']);
  });
Route::post('/login', [test::class, 'login']);
*/
Route::post('/store', [EmployeeController::class, 'store']);

Route::post('/freezeEmployee', [EmployeeController::class, 'freezeEmployee']);

Route::post('/login', [EmployeeController::class, 'login']);

Route::post('/storeRecord', [MedicalRecordController::class, 'store']);

Route::middleware(['auth:sanctum', 'statistics'])->group(function () {
  Route::post('/renewalEmployeeContract', [EmployeeController::class, 'renewalEmployeeContract']);
});


