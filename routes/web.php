<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Portal\PortalDashboardController;
use App\Http\Controllers\Portal\IntakeController as PortalIntakeController;
use App\Http\Controllers\Portal\ManageAlumniController;
use App\Http\Controllers\ITAdmin\CaptchaSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ITAdmin\UserManagementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Portal\AlumniEncodingController;
use App\Http\Controllers\ITAdmin\ProgramController;
use App\Http\Controllers\ITAdmin\StrandController;
use App\Http\Controllers\Id\User\AlumniIdRequestController;
use App\Http\Controllers\Id\Officer\AlumniIdOfficerController;
use App\Http\Controllers\Portal\CareerPostAdminController;
use App\Http\Controllers\PublicCareerController;

Route::get('/', fn () => view('welcome'));

/* ================= PUBLIC EVENTS ================= */
Route::get('/events/calendar', [EventController::class, 'public'])->name('events.calendar');
Route::get('/events/{event}', [EventController::class, 'showPublic'])->name('events.show');

/* ================= PUBLIC CAREERS ================= */
Route::get('/careers', [PublicCareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{post}', [PublicCareerController::class, 'show'])->name('careers.show');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [PortalDashboardController::class, 'index'])->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Intake
    Route::get('/intake', [PortalIntakeController::class, 'form'])->name('intake.form');
    Route::post('/intake', [PortalIntakeController::class, 'save'])->name('intake.save');

    // Portal Records
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

/* ================= IT ADMIN ================= */
Route::middleware(['auth', 'verified', 'role:it_admin'])
    ->prefix('it-admin')
    ->name('itadmin.')
    ->group(function () {

        Route::get('/captcha', [CaptchaSettingsController::class, 'edit'])->name('captcha.edit');
        Route::post('/captcha', [CaptchaSettingsController::class, 'update'])->name('captcha.update');

        // User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');

        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');

        Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset_password');
        Route::post('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('users.toggle_active');


          // ================= PROGRAMS =================
        Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
        Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');
        Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
        Route::get('/programs/{program}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
        Route::put('/programs/{program}', [ProgramController::class, 'update'])->name('programs.update');
        Route::post('/programs/{program}/toggle', [ProgramController::class, 'toggle'])->name('programs.toggle');

        Route::get('/programs/upload', [ProgramController::class, 'uploadForm'])->name('programs.upload_form');
        Route::post('/programs/upload', [ProgramController::class, 'upload'])->name('programs.upload');

        // ================= STRANDS =================
        Route::get('/strands', [StrandController::class, 'index'])->name('strands.index');
        Route::get('/strands/create', [StrandController::class, 'create'])->name('strands.create');
        Route::post('/strands', [StrandController::class, 'store'])->name('strands.store');
        Route::get('/strands/{strand}/edit', [StrandController::class, 'edit'])->name('strands.edit');
        Route::put('/strands/{strand}', [StrandController::class, 'update'])->name('strands.update');
        Route::post('/strands/{strand}/toggle', [StrandController::class, 'toggle'])->name('strands.toggle');

        Route::get('/strands/upload', [StrandController::class, 'uploadForm'])->name('strands.upload_form');
        Route::post('/strands/upload', [StrandController::class, 'upload'])->name('strands.upload');

        // Programs template download
        Route::get('/programs/template', [ProgramController::class, 'downloadTemplate'])
            ->name('programs.template');

        // Strands template download
        Route::get('/strands/template', [StrandController::class, 'downloadTemplate'])
            ->name('strands.template');
    });

/* ================= PORTAL EVENTS (Officer/Admin) ================= */
Route::middleware(['auth', 'role:alumni_officer,it_admin'])
    ->prefix('portal/events')
    ->name('portal.events.')
    ->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
    });


    /* ================= PORTAL CAREERS (Officer/Admin) ================= */
Route::middleware(['auth', 'role:alumni_officer,it_admin'])
    ->prefix('portal/careers')
    ->name('portal.careers.admin.')
    ->group(function () {
        Route::get('/', [CareerPostAdminController::class, 'index'])->name('index');
        Route::get('/create', [CareerPostAdminController::class, 'create'])->name('create');
        Route::post('/', [CareerPostAdminController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [CareerPostAdminController::class, 'edit'])->name('edit');
        Route::put('/{post}', [CareerPostAdminController::class, 'update'])->name('update');
        Route::delete('/{post}', [CareerPostAdminController::class, 'destroy'])->name('destroy');
    });


    //This is added for alumni_encoding
    Route::middleware(['auth','role:alumni_officer,it_admin,admin'])
    ->prefix('portal/alumni-encoding')
    ->name('portal.alumni_encoding.')
    ->group(function () {

        Route::get('/', [AlumniEncodingController::class, 'index'])->name('index');
        Route::get('/create', [AlumniEncodingController::class, 'create'])->name('create');
        Route::post('/', [AlumniEncodingController::class, 'store'])->name('store');

        Route::get('/{alumnus}/edit', [AlumniEncodingController::class, 'edit'])->name('edit');
        Route::put('/{alumnus}', [AlumniEncodingController::class, 'update'])->name('update');

        // user actions
        Route::post('/{alumnus}/link-user', [AlumniEncodingController::class, 'linkUser'])->name('link_user');
        Route::put('/{alumnus}/user', [AlumniEncodingController::class, 'updateUserBasic'])->name('user_update');

        // workflow
        Route::post('/{alumnus}/submit', [AlumniEncodingController::class, 'submit'])->name('submit');
        Route::post('/{alumnus}/validate', [AlumniEncodingController::class, 'validateRecord'])->name('validate');
        Route::post('/{alumnus}/return', [AlumniEncodingController::class, 'returnForRevision'])->name('return');

        // audit viewer
        Route::get('/{alumnus}/audit', [AlumniEncodingController::class, 'audit'])->name('audit');
    });

    //This is for ID requesting process routes

    /*
|--------------------------------------------------------------------------
| Alumni ID - User Side
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth'])
        ->prefix('id/user/request')
        ->name('id.user.request.')
        ->group(function () {
            Route::get('/', [AlumniIdRequestController::class, 'status'])->name('status');
            Route::get('/create', [AlumniIdRequestController::class, 'create'])->name('create');
            Route::post('/', [AlumniIdRequestController::class, 'store'])->name('store');
        });

/*
|--------------------------------------------------------------------------
| Alumni ID - Officer / IT Admin Side
|--------------------------------------------------------------------------
*/
    Route::middleware(['auth', 'role:alumni_officer,it_admin'])
        ->prefix('id/officer/requests')
        ->name('id.officer.requests.')
        ->group(function () {
            Route::get('/', [AlumniIdOfficerController::class, 'index'])->name('index');
            Route::get('/{id}', [AlumniIdOfficerController::class, 'show'])->name('show');
            Route::post('/{id}/update-status', [AlumniIdOfficerController::class, 'updateStatus'])->name('updateStatus');

        Route::get('/assisted/create', [\App\Http\Controllers\Id\Officer\AlumniIdOfficerAssistedController::class, 'create'])->name('assisted.create');
        Route::post('/assisted/store', [\App\Http\Controllers\Id\Officer\AlumniIdOfficerAssistedController::class, 'store'])->name('assisted.store');
        });



require __DIR__.'/auth.php';
