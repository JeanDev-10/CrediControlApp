<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PayController;
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
    // manejo de contactos
    Route::resource('contacts', ContactController::class);
    // manejo de transacciones
    Route::resource('transactions', TransactionController::class);
    // crear actualizar mi presupuesto
    Route::post('budget/setup', [TransactionController::class, 'setupBudget'])
        ->name('budget.setup');
    // manejo de deudas
    Route::resource('debts', DebtController::class);
    // marcar deuda como pagada
    Route::post('debts/{debt}/pay', [DebtController::class, 'markAsPaid'])->name('debts.pay');
    // crud de pagos
    Route::resource('pays', PayController::class);
    // Eliminar una imagen específica de un pago
    Route::delete('pays/images/{id}', [PayController::class, 'destroyImage'])
        ->name('pays.images.destroy');

    // Eliminar todas las imágenes de un pago
    Route::delete('pays/{pay}/images', [PayController::class, 'destroyAllImages'])
        ->name('pays.images.destroyAll');
});

require __DIR__.'/auth.php';
