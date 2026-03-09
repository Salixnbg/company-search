<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

Route::get('/', [CompanyController::class, 'index'])->name('companies.index');

Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');