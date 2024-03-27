<?php

use Illuminate\Http\Request;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/save-employees', [EmployeeController::class, 'save_employees']);
Route::get('/list-employees/{id}', [EmployeeController::class, 'list_employees']);
Route::post('/update-employees/{id}', [EmployeeController::class, 'update_employees']);
Route::delete('/delete-employees/{id}', [EmployeeController::class, 'delete_employees']);
Route::post('/assign-employees/{id}/role', [EmployeeController::class, 'assignRole']);
Route::get('/search-employees', [EmployeeController::class, 'search_employees']);


//Admin Dashboard 
Route::get('/dashboard/total-employees', [DashboardController::class, 'totalEmployees']);
Route::get('/dashboard/total-roles', [DashboardController::class, 'totalRoles']);
Route::post('/dashboard/create-roles', [DashboardController::class, 'createRole']);
Route::delete('/dashboard/delete/roles/{role}', [DashboardController::class, 'deleteRole']);