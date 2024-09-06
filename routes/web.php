<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\HomeController as Home;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RuleController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\Radius\ClientBondController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\Radius\ClientController;
use App\Http\Controllers\Admin\Radius\DeviceController;
use App\Http\Controllers\Admin\Radius\RadcheckController;
use App\Models\ClientBond;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('faq/{tag}', [Home::class, 'faq'])->name('faq');

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function() {
    Route::get('/', [HomeController::class, 'index'])->name('admin');
    Route::resource('users', UserController::class);
    Route::get('users/{user}/edit/password', [UserController::class, 'editPassword'])->name('users.edit.password');
    Route::put('users/{user}/edit/password', [UserController::class, 'updatePassword'])->name('users.update.password');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::resource('rules', RuleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permission}/rules', [PermissionController::class, 'rules'])->name('permissions.rules');
    Route::put('permissions/{permission}/rules', [PermissionController::class, 'syncRules'])->name('permissions.rules.sync');
    Route::resource('groups', GroupController::class);
    Route::resource('activities', ActivityController::class)->only(['index', 'show', 'destroy']);
    Route::resource('faqs', FaqController::class);
    Route::resource('tags', TagController::class);
    Route::prefix('radius')->group(function() {
        Route::resource('devices', DeviceController::class)->parameters(['devices' => 'devices']);
        // Route::resource('radcheck', RadcheckController::class)->parameters(['radcheck' => 'radcheck']);
        Route::resource('clients', ClientController::class);
        Route::get('upload/clients', [ClientController::class, 'uploadIndex'])->name('clients.uploadIndex');
        Route::get('upload/clients/create', [ClientController::class, 'uploadCreate'])->name('clients.uploadCreate');
        Route::post('upload/clients/create', [ClientController::class, 'uploadStore'])->name('clients.uploadStore');
        Route::resource('bonds', ClientBondController::class)->parameters(['bonds' => 'client_bond']);
    });
});

require __DIR__.'/auth.php';
