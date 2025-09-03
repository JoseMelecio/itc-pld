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
    Route::get('/ebr-client-template', [\App\Http\Controllers\EBRController::class, 'downloadClientTemplate'])->name('ebr.downloadClientTemplate');
    Route::get('/ebr-operation-template', [\App\Http\Controllers\EBRController::class, 'downloadOperationTemplate'])->name('ebr.downloadOperationTemplate');
    Route::get('/ebr-demo', [\App\Http\Controllers\EBRController::class, 'downloadDemoEBR'])->name('ebr.downloadDemo');
    Route::get('/calcular', [\App\Http\Controllers\EBRController::class, 'calcs']);
    Route::get('/graficos', [\App\Http\Controllers\EBRController::class, 'graficos']);
    Route::post('/graficos/pdf', [\App\Http\Controllers\EBRController::class, 'exportPDF'])->name('grafico.pdf');

    Route::get('/ebr-configuration', [\App\Http\Controllers\EBRController::class, 'configuration'])->name('ebr.configurations');
    Route::post('/ebr-configuration', [\App\Http\Controllers\EBRController::class, 'configurationStore'])->name('ebr.ConfigurationStore');

    Route::get('/ebr-risk-zones-catalog', [\App\Http\Controllers\EBRRiskZoneController::class, 'index'])->name('ebr.riskZones.index');;
    Route::post('/ebr-risk-zones-catalog', [\App\Http\Controllers\EBRRiskZoneController::class, 'store'])->name('ebr.riskZones.store');
    Route::patch('/ebr-risk-zones-catalog/{id}', [\App\Http\Controllers\EBRRiskZoneController::class, 'update'])->name('ebr.riskZones.update');
    Route::delete('/ebr-risk-zones-catalog/{id}', [\App\Http\Controllers\EBRRiskZoneController::class, 'destroy'])->name('ebr.riskZones.destroy');

    Route::get('/ebr_inherent_risk_catalog', [\App\Http\Controllers\EBRRiskElementController::class, 'index'])->name('ebr.riskElements.index');
    Route::get('/ebr_inherent_risk_catalog_create', [\App\Http\Controllers\EBRRiskElementController::class, 'create'])->name('ebr.riskElements.create');
    Route::post('/ebr_inherent_risk_catalog', [\App\Http\Controllers\EBRRiskElementController::class, 'store'])->name('ebr.riskElements.store');
    Route::get('/ebr_inherent_risk_catalog/{id}', [\App\Http\Controllers\EBRRiskElementController::class, 'show'])->name('ebr.riskElements.show');
    Route::patch('/ebr_inherent_risk_catalog/{id}', [\App\Http\Controllers\EBRRiskElementController::class, 'update'])->name('ebr.riskElements.update');
    Route::delete('/ebr_inherent_risk_catalog/{id}', [\App\Http\Controllers\EBRRiskElementController::class, 'destroy'])->name('ebr.riskElements.destroy');;
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

