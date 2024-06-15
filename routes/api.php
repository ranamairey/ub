<?php

use App\Models\User;
use Silber\Bouncer\Bouncer;
use Illuminate\Http\Request;
use App\Http\Controllers\test;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CoverageController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SubdistrictController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorVisitController;
use App\Http\Controllers\MedicalCenterController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicineOrderController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\RoutineChildVisitController;
use App\Http\Controllers\RoutineWomenVisitController;
use App\Http\Controllers\ChildTreatmentProgramController;
use App\Http\Controllers\MedicalCenterMedicineController;
use App\Http\Controllers\WomenTreatmentProgramController;
use App\Http\Controllers\HealthEducationLectureController;
use App\Http\Controllers\MalnutritionChildVisitController;
use App\Http\Controllers\MalnutritionWomenVisitController;

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
Route::get('/test' , [Controller::class ,  'test']);



Route::post('/login', [EmployeeController::class, 'login']);
Route::get('/getCoverages' , [CoverageController::class ,  'index']);
Route::get('/getOffices' ,  [OfficeController::class ,  'index']);
Route::get('/getActivities' , [ActivityController::class ,  'index']);
Route::get('/getAgencies' , [AgencyController::class ,  'index']);
Route::get('/getAccesses' , [AccessController::class ,  'index']);
Route::get('/getPartners' , [PartnerController::class ,  'index']);
Route::get('/getMedicalCenters' , [MedicalCenterController::class ,  'index']);
Route::get('/getCompletedTreatmentsByRecordId/{id}' , [MedicalRecordController::class , 'getCompletedTreatmentsByRecordId']);

Route::get('/getGovernorate' , [GovernorateController::class ,  'index']);
Route::get('/getDistrict' , [DistrictController::class ,  'index']);
Route::get('/getSubdistrict' , [SubdistrictController::class ,  'index']);


Route::post('/loginUser', [AccountController::class, 'login']);

///////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
Route::get('/getAccount/{id}' , [AccountController::class , 'show']);



////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////

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
/////////////////////////////////////////////////////////////

Route::post('/createDoctorVisit', [DoctorVisitController::class, 'createDoctorVisit']);

Route::get('/showAppointment', [AppointmentController::class, 'index']);

Route::get('/search', [MedicalRecordController::class, 'search']);



});


Route::middleware(['auth:sanctum'])->group(function () {
Route::get('/medical-records/{id}', [MedicalRecordController::class, 'show']);
Route::get('/getAllVisitsByRecordId/{id}', [MedicalRecordController::class, 'getAllVisitsByRecordId']);
Route::delete('/deletenutritionistAppointment/{id}' , [AppointmentController::class , 'destroy']);
Route::get('/getChildTreatmentsByMedicalCenter/{id}', [ChildTreatmentProgramController::class , 'getChildTreatmentsByMedicalCenter']);
Route::get('/getWomenTreatmentsByMedicalCenter/{id}', [WomenTreatmentProgramController::class , 'getWomenTreatmentsByMedicalCenter']);
Route::post('/creaMalnutritionWomenVisits', [MalnutritionWomenVisitController::class , 'store']);
Route::get('/getDoctorVisitsByMedicalRecordId/{id}', [DoctorVisitController::class , 'getDoctorVisitsByMedicalRecordId']);
Route::get('/seachAboutMedicalRecordId/{id}', [MedicalRecordController::class , 'seachAboutMedicalRecordId']);
Route::get('/getRecordDetails/{id}' , [MedicalRecordController::class , 'getRecordDetails']);
// عرض الأدوية من قبل الطبيب و الأخصائي من أجل طلبات صرف الأدوية
Route::get('/getMedicalCenterMedicine',  [MedicalCenterMedicineController::class, 'getMedicalCenterMedicine']);

});

Route::middleware(['auth:sanctum', 'women-nutritionist'])->group(function () {
  Route::get('/getNutritionistAppointmentswomen' , [AppointmentController::class , 'show']);
  Route::post('/createWomenVisit', [RoutineWomenVisitController::class, 'createWomenVisit']);
  Route::get('/getWomenVisit/{id}', [RoutineWomenVisitController::class, 'index']);
  Route::get('/allMalnutritionWomenVisits/{id}', [MalnutritionWomenVisitController::class , 'index']);
  Route::post('/createWomenTreatmentProgram', [WomenTreatmentProgramController::class, 'createWomenTreatmentProgram']);
  Route::get('/getWomenTreatmentProgramByMedicalRecordId/{id}', [WomenTreatmentProgramController::class , 'getWomenTreatmentProgramByMedicalRecordId']);
  Route::post('/graduateTreatmentProgram/{id}', [WomenTreatmentProgramController::class , 'graduateTreatmentProgram']);
  // إنشاء طلبية صرف دوا
  Route::post('/womenNutritionistsMedicineOrder', [MedicineOrderController::class, 'WomenNutritionistsMedicineOrder']);
});

Route::middleware(['auth:sanctum', 'child-nutritionist'])->group(function () {
  Route::get('/getNutritionistAppointmentschild' , [AppointmentController::class , 'show']);
  Route::post('/createChildVisit', [RoutineChildVisitController::class, 'createChildVisit']);
  Route::get('/getChiledVisit/{id}', [RoutineChildVisitController::class, 'index']);
  Route::get('/allMalnutritionChildVisits/{id}', [MalnutritionChildVisitController::class , 'index']);
  Route::post('/ChildTreatmentProgram', [ChildTreatmentProgramController::class, 'createChildTreatmentProgram']);
  Route::post('/createMalnutritionChildVisits', [MalnutritionChildVisitController::class , 'store']);
  Route::get('/getChildTreatmentProgramByMedicalRecordId/{id}', [ChildTreatmentProgramController::class , 'getChildTreatmentProgramByMedicalRecordId']);
  Route::post('/graduateChildTreatmentProgram/{id}', [ChildTreatmentProgramController::class , 'graduateChildTreatmentProgram']);
  Route::post('/transsformChildTreatmentProgram/{id}', [ChildTreatmentProgramController::class , 'transsformChildTreatmentProgram']);
  Route::post('/createMalnutritionChildVisits', [MalnutritionChildVisitController::class , 'store']);

  Route::post('/childNutritionistsMedicineOrder', [MedicineOrderController::class, 'ChildNutritionistsMedicineOrder']);


});


Route::middleware(['auth:sanctum', 'women-doctor'])->group(function () {
  Route::post('/createWomenDoctorVisit', [DoctorVisitController::class, 'createDoctorVisit']);
  Route::get('/getDoctorAppointments' , [AppointmentController::class , 'show']);
});
Route::middleware(['auth:sanctum', 'child-doctor'])->group(function () {
  Route::post('/createChildDoctorVisit', [DoctorVisitController::class, 'createDoctorVisit']);
  Route::get('/getChildDoctorAppointments' , [AppointmentController::class , 'show']);
  Route::post('/doctorMedicineOrder', [MedicineOrderController::class, 'doctorMedicineOrder']);
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
  Route::get('/getWomenNutritionists' , [EmployeeController::class ,  'getWomenNutritionists']);
  Route::get('/getChildNutritionists' , [EmployeeController::class ,  'getChildNutritionists']);
  Route::get('/getWomenDoctors' , [EmployeeController::class ,  'getWomenDoctors']);
  Route::get('/getChildDoctors' , [EmployeeController::class ,  'getChildDoctors']);
  Route::get('/getReceptionists' , [EmployeeController::class ,  'getReceptionists']);
  Route::get('/getPharmacists' , [EmployeeController::class ,  'getPharmacists']);
  Route::get('/getStatisticsEmployees' , [EmployeeController::class ,  'getStatisticsEmployees']);
  Route::get('/getWomenNutritionists' , [EmployeeController::class ,  'getWomenNutritionists']);
  Route::get('/getHealthEducationEmployees' , [EmployeeController::class ,  'getHealthEducationEmployees']);
  Route::get('/getAllRoles' , [EmployeeController::class ,  'getAllRoles']);
  Route::get('/getEmployeesInfo', [EmployeeController::class, 'getEmployeesInfo']);
    // إضافة دواء على جدول الأدوية
  Route::post('/addMedicine', [MedicineController::class, 'addMedicine']);
});






Route::middleware(['auth:sanctum', 'pharmacist'])->group(function () {

  // إضافة دواء على مركز طبي أو تحديث الكميات
  Route::post('/updateMedicineStock', [MedicalCenterMedicineController::class, 'updateMedicineStock']);
  Route::get('/getAllmedicines', [MedicineController::class , 'getAllmedicines']);
  Route::get('/getAllMedicineOrders' , [MedicineOrderController::class , 'getAllMedicineOrders']);
  Route::post('/medicineDestruction', [MedicineController::class , 'medicineDestruction']);
  Route::get('/acceptOrder/{id}', [MedicineOrderController::class , 'acceptOrder']);
  Route::get('/rejectOrder/{id}', [MedicineOrderController::class , 'rejectOrder']);
  Route::get('/getNotEmptyMedicalCenterMedicine', [MedicalCenterMedicineController::class , 'getNotEmptyMedicalCenterMedicine']);








});

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/showMyRecord', [MedicalRecordController::class, 'showMyRecord']);
  Route::get('/showLinkedRecords', [AccountController::class, 'showLinkedRecords']);
});




