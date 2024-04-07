<?php

use App\Models\User;
use Silber\Bouncer\Bouncer;
use Illuminate\Http\Request;
use App\Http\Controllers\test;
<<<<<<< HEAD
use App\Http\Controllers\EmployeeController;
=======
use Illuminate\Support\Facades\Route;
>>>>>>> 0fb44f846a8929803d3e44bed42e493518c37906

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



Route::get('/test' , function(Request $request){
    $user = User::where('name', 'malak')->first();
    if($user->isAn('admin')) return "true";
    else return "false";
 ;
});



