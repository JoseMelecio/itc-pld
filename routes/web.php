<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\EBRController;
use App\Http\Controllers\EBRRiskElementController;
use App\Http\Controllers\EBRRiskElementIndicatorController;
use App\Http\Controllers\EBRRiskZoneController;
use App\Http\Controllers\PersonListController;
use App\Http\Controllers\PLDNoticeController;
use App\Http\Controllers\PLDNoticeMassiveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckDynamicPermission;
use App\Models\PLDNoticeMassive;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::patch('/profile-update-password', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'password'])->name('profile.password');

    Route::resource('/users', UserController::class);
    Route::patch('/users/{user}/set-default-password', [UserController::class, 'setDefaultPassword'])->name('users.set-default-password');
    Route::patch('/users-erase-cache', [UserController::class, 'eraseCache'])->name('users.erase-cache');
    Route::get('/users-erase-cache-done', [UserController::class, 'eraseCacheDone'])->name('users.erase-cache-done');

    Route::get('/pld-notices/{noticeType}', [PLDNoticeController::class, 'showForm'])->name('pld-notice.showForm');
    Route::post('/pld-notices', [PLDNoticeController::class, 'makeNotice'])->name('pld-notice.makeNotice');
    Route::get('/pld-notices-download/{noticeType}', [PLDNoticeController::class, 'downloadTemplate'])->name('pld-notice.downloadTemplate');

    Route::get('/person-blocked-list', [PersonListController::class, 'index'])->name('person-blocked-list');
    Route::get('/person-blocked-form-finder', [PersonListController::class, 'formFind'])->name('person-blocked-form-finder');
    Route::get('/person-blocked-form-finder-massive', [PersonListController::class, 'formFindMassive'])->name('person-blocked-form-finder-massive');
    Route::post('/person-blocked-form-finder-massive', [PersonListController::class, 'storeMassive'])->name('person-blocked-form-finder-store-massive');
    Route::post('/person-list-blocked-find', [PersonListController::class, 'find'])->name('person-list-blocked-find');
    Route::get('/person-list-blocked-result', [PersonListController::class, 'result'])->name('person-list-blocked-result');
    Route::get('/person-list-blocked-download-template', [PersonListController::class, 'downloadTemplate'])->name('person-list-blocked-download-template');

    Route::get('/ebr', [EBRController::class, 'index'])->name('ebr.index');
    Route::post('/ebr', [EBRController::class, 'store'])->name('ebr.store');
    Route::get('/ebr-client-template', [EBRController::class, 'downloadClientTemplate'])->name('ebr.downloadClientTemplate');
    Route::get('/ebr-operation-template', [EBRController::class, 'downloadOperationTemplate'])->name('ebr.downloadOperationTemplate');
    Route::get('/ebr-demo', [EBRController::class, 'downloadDemoEBR'])->name('ebr.downloadDemo');
    Route::get('/calcular/{id}', [EBRController::class, 'calcs']);
    Route::get('/graficos', [EBRController::class, 'graficos']);
    Route::post('/graficos/pdf', [EBRController::class, 'exportPDF'])->name('grafico.pdf');

    Route::get('/ebr-configuration', [EBRController::class, 'showConfiguration'])->name('ebr.configurations');
    Route::post('/ebr-configuration', [EBRController::class, 'configurationStore'])->name('ebr.ConfigurationStore');
    Route::post('/ebr-configuration-risk-element', [EBRController::class, 'riskElementConfigurationStore'])->name('ebr.riskElementConfigurationStore');

    Route::get('/ebr-risk-zones-catalog', [EBRRiskZoneController::class, 'index'])->name('ebr.riskZones.index');;
    Route::post('/ebr-risk-zones-catalog', [EBRRiskZoneController::class, 'store'])->name('ebr.riskZones.store');
    Route::patch('/ebr-risk-zones-catalog/{id}', [EBRRiskZoneController::class, 'update'])->name('ebr.riskZones.update');
    Route::delete('/ebr-risk-zones-catalog/{id}', [EBRRiskZoneController::class, 'destroy'])->name('ebr.riskZones.destroy');

    Route::get('/ebr_inherent_risk_catalog', [EBRRiskElementController::class, 'index'])->name('ebr.riskElements.index');
    Route::get('/ebr_inherent_risk_catalog_create', [EBRRiskElementController::class, 'create'])->name('ebr.riskElements.create');
    Route::post('/ebr_inherent_risk_catalog', [EBRRiskElementController::class, 'store'])->name('ebr.riskElements.store');
    Route::get('/ebr_inherent_risk_catalog/{id}', [EBRRiskElementController::class, 'show'])->name('ebr.riskElements.show');
    Route::patch('/ebr_inherent_risk_catalog/{id}', [EBRRiskElementController::class, 'update'])->name('ebr.riskElements.update');
    Route::delete('/ebr_inherent_risk_catalog/{id}', [EBRRiskElementController::class, 'destroy'])->name('ebr.riskElements.destroy');

    Route::get('/ebr_indicators_risk_catalog', [EBRRiskElementIndicatorController::class, 'index'])->name('ebr.riskElementIndicators.index');
    Route::get('/ebr_indicators_risk_catalog_create', [EBRRiskElementIndicatorController::class, 'create'])->name('ebr.riskElementIndicators.create');
    Route::post('/ebr_indicators_risk_catalog', [EBRRiskElementIndicatorController::class, 'store'])->name('ebr.riskElementIndicators.store');
    Route::get('/ebr_indicators_risk_catalog/{id}', [EBRRiskElementIndicatorController::class, 'show'])->name('ebr.riskElementIndicators.show');
    Route::patch('/ebr_indicators_risk_catalog/{id}', [EBRRiskElementIndicatorController::class, 'update'])->name('ebr.riskElementIndicators.update');
    Route::delete('/ebr_indicators_risk_catalog/{id}', [EBRRiskElementIndicatorController::class, 'destroy'])->name('ebr.riskElementIndicators.destroy');

    Route::get('/logs/pld_notice', [SystemLogController::class, 'index'])->name('logs.index');

    Route::get('notification-pld-massive', [PLDNoticeMassiveController::class, 'index'])->name('notification-pld-massive.index');
    Route::get('notification-pld-massive-download-template/{noticeType}', [PLDNoticeMassiveController::class, 'downloadTemplate'])->name('notification-pld-massive.download-template');
    Route::post('notification-pld-massive', [PLDNoticeMassiveController::class, 'store'])->name('notification-pld-massive.store');

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

