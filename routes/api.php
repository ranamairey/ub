<?php

use App\Models\User;
use Silber\Bouncer\Bouncer;
use Illuminate\Http\Request;
use App\Http\Controllers\test;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CoverageController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorVisitController;
use App\Http\Controllers\MedicalCenterController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\RoutineChildVisitController;
use App\Http\Controllers\RoutineWomenVisitController;
use App\Http\Controllers\ChildTreatmentProgramController;
use App\Http\Controllers\HealthEducationLectureController;

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





Route::post('/loginUser', [AccountController::class, 'login']);




Route::middleware(['auth:sanctum', 'receptionist'])->group(function () {
Route::post('/storeRecord', [MedicalRecordController::class, 'store']);
Route::post('/update/{id}', [MedicalRecordController::class, 'update']);
////////////////////////////
Route::post('/HealthEducationLecture', [HealthEducationLectureController::class, 'createLecture']);
///////////////////////////////
Route::post('createAccount' , [AccountController::class , 'create']);
Route::post('linkAccountToRecord' , [AccountController::class , 'linkAccountToRecord']);

Route::post('/createAppointment' , [AppointmentController::class , 'store']);
Route::delete('/deleteAppointment/{id}' , [AppointmentController::class , 'destroy']);



});

Route::middleware(['auth:sanctum', 'nutritionist'])->group(function () {
Route::post('/createChildVisit', [RoutineChildVisitController::class, 'createChildVisit']);
Route::post('/createWomenVisit', [RoutineWomenVisitController::class, 'createWomenVisit']);
Route::post('/ChildTreatmentProgram', [ChildTreatmentProgramController::class, 'createChildTreatmentProgram']);
Route::get('/medical-records/{id}', [MedicalRecordController::class, 'show']);
Route::get('/getAllVisitsByRecordId/{id}', [MedicalRecordController::class, 'getAllVisitsByRecordId']);
Route::get('/getNutritionistAppointments' , [AppointmentController::class , 'show']);

});



Route::post('/statisticsLogin', [EmployeeController::class, 'statisticsLogin']);
Route::middleware(['auth:sanctum', 'statistics'])->group(function () {
  Route::get('/getEmployeeDetails/{id}', [EmployeeController::class, 'getEmployeeDetails']);
  Route::get('/getEmployeeProfile/{id}', [EmployeeController::class, 'getEmployeeProfile']);
  Route::post('/freezeEmployee', [EmployeeController::class, 'freezeEmployee']);
  Route::post('/unFreezeEmployee', [EmployeeController::class, 'unFreezeEmployee']);
  Route::post('/store', [EmployeeController::class, 'store']);
  Route::post('/renewalEmployeeContract', [EmployeeController::class, 'renewalEmployeeContract']);
  Route::post('updateEmployee/{id}' , [EmployeeController::class , 'updateEmployee']);
  Route::get('/findEmployee/{id}' , [EmployeeController::class ,  'findEmployee']);

});

Route::middleware(['auth:sanctum', 'doctor'])->group(function () {
  Route::post('/createDoctorVisit', [DoctorVisitController::class, 'createDoctorVisit']);
  Route::get('/getDoctorAppointments' , [AppointmentController::class , 'show']);

});

Route::middleware(['auth:sanctum', 'pharmacist'])->group(function () {


});







