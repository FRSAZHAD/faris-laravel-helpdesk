<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CategoryController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'showTicket']);
    Route::get('/tickets/status/options', [TicketController::class, 'getStatusOptions']);
    Route::patch('/tickets/{id}', [TicketController::class, 'update']);

    Route::get('/staff', [StaffController::class, 'index']);
    Route::post('/staff', [StaffController::class, 'store']);
    Route::get('/roles', [StaffController::class, 'getRoles']);

    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);

    Route::get('/dashboard', [TicketController::class, 'dashboard']);
    Route::get('/tickets/{ticket}/histories', [TicketController::class, 'histories']);
    Route::post('/tickets/{ticket}/histories',[TicketController::class, 'storeHistory']);

});