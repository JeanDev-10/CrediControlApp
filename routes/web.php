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
    //reporte de contactos
    Route::get('contacts/export/pdf', [ContactController::class, 'export'])
    ->name('contacts.export');
    //reporte de contacto con deudas
    Route::get('contacts/{contact}/debts/export/pdf', [ContactController::class, 'exportContactWithDebtsToPdf'])
    ->name('contacts.exportWithDebts');
    // manejo de transacciones
    Route::resource('transactions', TransactionController::class);
    //reporte de transacciones
    Route::get('transactions/export/pdf', [TransactionController::class, 'export'])->name('transactions.export');
    // crear actualizar mi presupuesto
    Route::post('budget/setup', [TransactionController::class, 'setupBudget'])
        ->name('budget.setup');
    // manejo de deudas
    Route::resource('debts', DebtController::class);
    //reporte de deudas
    Route::get('debts/export/pdf', [DebtController::class, 'exportPdf'])->name('debts.export');

    // marcar deuda como pagada
    Route::post('debts/{debt}/pay', [DebtController::class, 'markAsPaid'])->name('debts.pay');
    // crud de pagos
    Route::resource('pays', PayController::class);
    //reporte de pagos
    Route::get('pays/export/pdf', [PayController::class, 'exportPdf'])->name('pays.export');

    // Eliminar una imagen específica de un pago
    Route::delete('pays/images/{id}', [PayController::class, 'destroyImage'])
        ->name('pays.images.destroy');

    // Eliminar todas las imágenes de un pago
    Route::delete('pays/{pay}/images', [PayController::class, 'destroyAllImages'])
        ->name('pays.images.destroyAll');
});

require __DIR__.'/auth.php';
