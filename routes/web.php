<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('login');
});

Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::resource('/tenants', \App\Http\Controllers\TenantController::class)->middleware([\App\Http\Middleware\AdminSubdomainMiddleware::class]);


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::patch('/profile-update-password', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/users', UserController::class);

    Route::get('/pld-notices/{noticeType}', [\App\Http\Controllers\PLDNoticeController::class, 'showForm'])->name('pld-notice.showForm');
    Route::post('/pld-notices', [\App\Http\Controllers\PLDNoticeController::class, 'makeNotice'])->name('pld-notice.makeNotice');
    Route::get('/pld-notices-download/{noticeType}', [\App\Http\Controllers\PLDNoticeController::class, 'downloadTemplate'])->name('pld-notice.downloadTemplate');
    Route::get('/person-blocked-list', [\App\Http\Controllers\PersonListController::class, 'index'])->name('person-blocked-list');
    Route::get('/person-blocked-form-finder', [\App\Http\Controllers\PersonListController::class, 'formFind'])->name('person-blocked-form-finder');
    Route::post('/person-list-blocked-find', [\App\Http\Controllers\PersonListController::class, 'find'])->name('person-list-blocked-find');
    Route::get('/person-list-blocked-result', [\App\Http\Controllers\PersonListController::class, 'result'])->name('person-list-blocked-result');
    Route::get('/person-list-blocked-download-template', [\App\Http\Controllers\PersonListController::class, 'downloadTemplate'])->name('person-list-blocked-download-template');
    Route::get('/ebr', [\App\Http\Controllers\EBRController::class, 'index'])->name('ebr.index');
    Route::post('/ebr', [\App\Http\Controllers\EBRController::class, 'store'])->name('ebr.store');
    //Route::get('/person-list-blocked-', [\App\Http\Controllers\PersonListController::class, 'index'])->name('person-list-blocked');
});

//// Landing (Guest)
//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//    ]);
//})->name('welcome');
//
//// Dashboard (Authenticated + Verified)
//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
// Dashboard - Profile (Authenticated)

