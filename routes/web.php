<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('InsertTicket', function () {
    return Inertia::render('Ticket/InsertTicket');
})->middleware(['auth', 'verified'])->name('InsertTicket');

Route::get('ListTickets', function () {
    return Inertia::render('Ticket/ListTickets');
})->middleware(['auth', 'verified'])->name('ListTickets');

Route::get('ticket/{id}', [TicketController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('TicketDetail');


require __DIR__.'/settings.php';
