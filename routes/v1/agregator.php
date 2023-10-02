<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\LoginController;
use App\Http\Controllers\v1\InviteController;
use App\Http\Controllers\v1\DashboardController;
use App\Http\Controllers\v1\TransactionController;
use App\Http\Controllers\v1\RefererListController;
use App\Http\Controllers\v1\IncomingProcessController;


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


Route::group(['middleware' => 'ip.allowed'], function () {
    Route::post('invite', [InviteController::class, 'storeInvite']);
    Route::get('getinvite', [InviteController::class, 'getInvite']);
    Route::post('incommingcommission', [IncomingProcessController::class, 'handleWebhook']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('signupinvite', [InviteController::class, 'registerInvite']);
    Route::post('createreferers', [RefererListController::class, 'createReferer']);
    Route::middleware('auth:api_user','handle.api.token')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashnoardInfo']);
        Route::get('transactions', [TransactionController::class, 'transaction']);
        Route::get('referers', [RefererListController::class, 'referer']);
      

    });
    
    
});


?>