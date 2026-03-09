<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AdminCompanyController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', [CompanyController::class, 'index'])->name('companies.index');

Route::get('/companies/{company}', [CompanyController::class, 'show'])
    ->name('companies.show');

/*
|--------------------------------------------------------------------------
| Authentication routes
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [CompanyController::class, 'login'])->name('login.post');

Route::post('/logout', function () {

    Auth::logout();

    return redirect('/');

})->name('logout');

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/companies', [AdminCompanyController::class, 'index'])
        ->name('companies.index');

    Route::get('/companies/create', [AdminCompanyController::class, 'create'])
        ->name('companies.create');

    Route::post('/companies', [AdminCompanyController::class, 'store'])
        ->name('companies.store');

    Route::get('/companies/{company}/edit', [AdminCompanyController::class, 'edit'])
        ->name('companies.edit');

    Route::put('/companies/{company}', [AdminCompanyController::class, 'update'])
        ->name('companies.update');

    Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy'])
        ->name('companies.destroy');

});

/*
|--------------------------------------------------------------------------
| Temporary route to create admin user (production)
|--------------------------------------------------------------------------
*/

Route::get('/create-admin/{token}', function ($token) {

    if ($token !== env('ADMIN_SETUP_TOKEN')) {
        abort(403);
    }

    $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@companysearch.com',
        'password' => Hash::make('Admin123456')
    ]);

    return "Admin created successfully";

});