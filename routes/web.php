<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'ensureUserIsActive'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::prefix('users')->name('users.')->group(function () {

        // Rutas CRUD usando resource y aplicando middlewares individuales
        Route::resource('/', UserController::class)
            ->parameters(['' => 'user']) // Para mantener el parámetro como {user}
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'])
            ->middleware([
                'index' => 'permission:users.index',
                'show' => 'permission:users.show',
                'create' => 'permission:users.create',
                'store' => 'permission:users.create',
                'edit' => 'permission:users.update',
                'update' => 'permission:users.update',
                'destroy' => 'permission:users.destroy',
            ]);

        // Exportar a PDF
        Route::get('/export/pdf', [UserController::class, 'export'])
            ->name('export')
            ->middleware('permission:users.exportToPdf');

        // Cambiar estado activo/inactivo
        Route::patch('/{user}/toggle-active', [UserController::class, 'toggleIsActive'])
            ->name('toggleIsActive')
            ->middleware('permission:users.toogleIsActive');
    });
    // manejo de contactos
    Route::resource('contacts', ContactController::class)->middleware('role:client');
    // reporte de contactos
    Route::get('contacts/export/pdf', [ContactController::class, 'export'])
        ->name('contacts.export')->middleware('role:client');
    // reporte de contacto con deudas
    Route::get('contacts/{contact}/debts/export/pdf', [ContactController::class, 'exportContactWithDebtsToPdf'])
        ->name('contacts.exportWithDebts')->middleware('role:client');
    // manejo de transacciones
    Route::resource('transactions', TransactionController::class)->middleware('role:client');
    // reporte de transacciones
    Route::get('transactions/export/pdf', [TransactionController::class, 'export'])->name('transactions.export')->middleware('role:client');
    // crear actualizar mi presupuesto
    Route::post('budget/setup', [TransactionController::class, 'setupBudget'])
        ->name('budget.setup')->middleware('role:client');
    // manejo de deudas
    Route::resource('debts', DebtController::class)->middleware('role:client');
    // reporte de deudas
    Route::get('debts/export/pdf', [DebtController::class, 'exportPdf'])->name('debts.export')->middleware('role:client');
    // reporte de deuda con pagos
    Route::get('debts/{debt}/pays/export/pdf', [DebtController::class, 'exportDebtWithPaysToPdf'])
        ->name('debts.exportWithPays')->middleware('role:client');
    // marcar deuda como pagada
    Route::post('debts/{debt}/pay', [DebtController::class, 'markAsPaid'])->name('debts.pay')->middleware('role:client');
    // crud de pagos
    Route::resource('pays', PayController::class)->middleware('role:client');
    // reporte de pagos
    Route::get('pays/export/pdf', [PayController::class, 'exportPdf'])->name('pays.export')->middleware('role:client');

    // Eliminar una imagen específica de un pago
    Route::delete('pays/images/{id}', [PayController::class, 'destroyImage'])
        ->name('pays.images.destroy')->middleware('role:client');

    // Eliminar todas las imágenes de un pago
    Route::delete('pays/{pay}/images', [PayController::class, 'destroyAllImages'])
        ->name('pays.images.destroyAll')->middleware('role:client');

    // auditar
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    // exportar a pdf auditoria
    // routes/web.php
    Route::get('/audit/export/pdf', [AuditController::class, 'exportPdf'])->name('audits.export');

});

require __DIR__.'/auth.php';
