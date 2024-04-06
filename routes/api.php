<?php

use App\Models\User;
use Silber\Bouncer\Bouncer;
use Illuminate\Http\Request;
use App\Http\Controllers\test;
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
Route::post('/register', [test::class, 'register']);
Route::post('/login', [test::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [test::class, 'logout']);
  });

Route::get('/test' , function(Request $request){
    $user = User::where('name', 'malak')->first();
    if($user->isAn('admin')) return "true";
    else return "false";
 ;
});



