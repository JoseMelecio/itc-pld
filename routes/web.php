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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/users', UserController::class);

    Route::get('/pld-notices/{noticeType}', [\App\Http\Controllers\PLDNoticeController::class, 'showForm'])->name('pld-notice.showForm');
    Route::post('/pld-notices', [\App\Http\Controllers\PLDNoticeController::class, 'makeNotice'])->name('pld-notice.makeNotice');
    Route::get('/pld-notices-download/{noticeType}', [\App\Http\Controllers\PLDNoticeController::class, 'downloadTemplate'])->name('pld-notice.downloadTemplate');
    Route::get('/person-list-blocked', [\App\Http\Controllers\PersonListController::class, 'index'])->name('person-list-blocked');
    Route::get('/person-list-blocked-form-find', [\App\Http\Controllers\PersonListController::class, 'formFind'])->name('person-list-blocked-form-find');
    Route::post('/person-list-blocked-find', [\App\Http\Controllers\PersonListController::class, 'find'])->name('person-list-blocked-find');
    Route::get('/person-list-blocked-result', [\App\Http\Controllers\PersonListController::class, 'result'])->name('person-list-blocked-result');
    Route::get('/person-list-blocked-download-template', [\App\Http\Controllers\PersonListController::class, 'downloadTemplate'])->name('person-list-blocked-download-template');
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


