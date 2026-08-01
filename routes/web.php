<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Departments
    Route::resource('departments', DepartmentController::class)->except(['show']);

    // FAQS
    Route::resource('faqs', FaqController::class)->except(['show']);

    // Usuarios
    Route::resource('users', UserController::class)->except(['show']);

    // Tickets
    Route::get('tickets/gestion', [TicketController::class, 'gestion'])->name('tickets.gestion');
    Route::get('tickets/{ticket}/detalle', [TicketController::class, 'detalle'])->name('tickets.detalle');
    Route::put('tickets/{ticket}/gestionar', [TicketController::class, 'gestionar'])->name('tickets.gestionar');
    Route::get('/tickets/index', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/datatables', [TicketController::class, 'datatables'])->name('tickets.datatables');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

    Route::get('tickets/{ticket}/show', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('tickets/{ticket}/responder', [TicketController::class, 'responder'])->name('tickets.responder');

    Route::get('attachments/{type}/{id}', [TicketController::class, 'descargarAdjunto'])
        ->name('attachments.download')
        ->where('type', 'ticket|message');
});

require __DIR__ . '/auth.php';
