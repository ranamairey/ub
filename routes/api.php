<?php

use App\Models\User;
use Silber\Bouncer\Bouncer;
use Illuminate\Http\Request;
use App\Http\Controllers\test;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CoverageController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DoctorVisitController;
use App\Http\Controllers\MedicalCenterController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\RoutineChildVisitController;
use App\Http\Controllers\RoutineWomenVisitController;

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



Route::post('/login', [EmployeeController::class, 'login']);
Route::get('/getCoverages' , [CoverageController::class ,  'index']);
Route::get('/getOffices' ,  [OfficeController::class ,  'index']);
Route::get('/getActivities' , [ActivityController::class ,  'index']);
Route::get('/getAgencies' , [AgencyController::class ,  'index']);
Route::get('/getAccesses' , [AccessController::class ,  'index']);
Route::get('/getPartners' , [PartnerController::class ,  'index']);
Route::get('/getMedicalCenters' , [MedicalCenterController::class ,  'index']);







Route::middleware(['auth:sanctum', 'receptionist'])->group(function () {
Route::post('/storeRecord', [MedicalRecordController::class, 'store']);
Route::post('/update/{id}', [MedicalRecordController::class, 'update']);

Route::post('/createDoctorVisit', [DoctorVisitController::class, 'createDoctorVisit']);
});

Route::middleware(['auth:sanctum', 'nutritionist'])->group(function () {
Route::post('/createChildVisit', [RoutineChildVisitController::class, 'createChildVisit']);
Route::post('/createWomenVisit', [RoutineWomenVisitController::class, 'createWomenVisit']);


});

Route::middleware(['auth:sanctum', 'statistics'])->group(function () {
  Route::post('/freezeEmployee', [EmployeeController::class, 'freezeEmployee']);
  Route::post('/store', [EmployeeController::class, 'store']);
  Route::post('/renewalEmployeeContract', [EmployeeController::class, 'renewalEmployeeContract']);


});


