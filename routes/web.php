<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Portal\PortalDashboardController;
use App\Http\Controllers\Portal\IntakeController as PortalIntakeController;
use App\Http\Controllers\Portal\ManageAlumniController;
use App\Http\Controllers\ITAdmin\CaptchaSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ITAdmin\UserManagementController;

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [PortalDashboardController::class, 'index'])->name('dashboard');
    //added for the profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // User intake (role check is inside controller)
    Route::get('/intake', [PortalIntakeController::class, 'form'])->name('intake.form');
    Route::post('/intake', [PortalIntakeController::class, 'save'])->name('intake.save');

    // Officer/Admin (role check inside controller)
    Route::prefix('portal')->group(function () {
        Route::get('/records', [ManageAlumniController::class, 'index'])->name('portal.records.index');
        Route::get('/records/{alumnus}', [ManageAlumniController::class, 'show'])->name('portal.records.show');
        Route::get('/records/{alumnus}/edit', [ManageAlumniController::class, 'edit'])->name('portal.records.edit');
        Route::put('/records/{alumnus}', [ManageAlumniController::class, 'update'])->name('portal.records.update');

        Route::get('/records/{alumnus}/pdf', [ManageAlumniController::class, 'downloadPdf'])->name('portal.records.pdf');
        Route::get('/records/{alumnus}/excel', [ManageAlumniController::class, 'downloadExcel'])->name('portal.records.excel');

        Route::delete('/records/{alumnus}', [ManageAlumniController::class, 'destroy'])->name('portal.records.destroy');
    });
});
//added for profile management
Route::middleware(['auth', 'verified', 'role:it_admin'])
    ->prefix('it-admin')
    ->name('itadmin.')
    ->group(function () {

        Route::get('/captcha', [CaptchaSettingsController::class, 'edit'])->name('captcha.edit');
        Route::post('/captcha', [CaptchaSettingsController::class, 'update'])->name('captcha.update');

        // ✅ User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');

        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');

        Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset_password');
        Route::post('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('users.toggle_active');
    });




Route::middleware(['auth', 'role:it_admin'])->prefix('it-admin')->name('itadmin.')->group(function () {
    Route::get('/captcha', [CaptchaSettingsController::class, 'edit'])->name('captcha.edit');
    Route::post('/captcha', [CaptchaSettingsController::class, 'update'])->name('captcha.update');
});

require __DIR__.'/auth.php';
