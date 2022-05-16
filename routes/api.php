<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\AuthController;

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

Route::get('/unautorize', function (Request $request) {
    return response()->json([
        'status' => false,
        'code' => 401,
        'message' => 'Unauthorized'
    ], 200);
})->name('unautorize');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    // Route::post('/employee/save_employee', [EmployeeController::class, 'save_employee']);
    // Route::post('/employee/absen', [EmployeeController::class, 'absen']);
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/employee/save_employee', [EmployeeController::class, 'save_employee']);
    Route::post('/employee/absen', [EmployeeController::class, 'absen']);
    Route::get('/employee/get_absen_all', [EmployeeController::class, 'get_absen_all']);

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Route::post('/employee/save_employee', [EmployeeController::class, 'save_employee']);
//     Route::post('/employee/absen', [EmployeeController::class, 'absen']);
//     Route::get('/employee/get_absen_all', [EmployeeController::class, 'get_absen_all']);

