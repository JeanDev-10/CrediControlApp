<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //manejo de contactos
    Route::resource('contacts', ContactController::class);
    //manejo de transacciones
    Route::resource('transactions', TransactionController::class);

    Route::post('budget/setup', [TransactionController::class, 'setupBudget'])
        ->name('budget.setup');
});

require __DIR__.'/auth.php';
