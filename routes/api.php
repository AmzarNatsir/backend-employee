<?php

use App\Http\Controllers\API\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'employee'], function () {
    Route::get('/all', [EmployeeController::class, 'index']);
    Route::get('/{id}', [EmployeeController::class, 'findOne']);
    Route::get('/department/{id}', [EmployeeController::class, 'byDepartemen']);
});
