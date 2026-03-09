<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VatController;

Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/api/companies/search', [CompanyController::class, 'autocomplete'])->name('companies.autocomplete');

Route::get('/vat-check', [VatController::class, 'index'])->name('vat.index');
Route::post('/vat-check', [VatController::class, 'check'])->name('vat.check');