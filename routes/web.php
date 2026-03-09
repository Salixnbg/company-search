<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VatController;
use App\Http\Controllers\AdminCompanyController;

Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/api/companies/search', [CompanyController::class, 'autocomplete'])->name('companies.autocomplete');

Route::get('/vat-check', [VatController::class, 'index'])->name('vat.index');
Route::post('/vat-check', [VatController::class, 'check'])->name('vat.check');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/companies', [AdminCompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [AdminCompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [AdminCompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit', [AdminCompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}', [AdminCompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy'])->name('companies.destroy');
});

require __DIR__.'/auth.php';