<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'showTicket']);
    Route::get('/tickets/status/options', [TicketController::class, 'getStatusOptions']);
    Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus']);

});