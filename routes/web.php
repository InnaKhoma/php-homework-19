<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

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
    return view('auth.login');
})->name('home');

Auth::routes();

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('users.show', ['user' => Auth::user()->id]);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::middleware('verified')->group(function () {
    Route::resource('users', UserController::class)
        ->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'show' => 'users.show',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy'
        ]);

    Route::resource('projects', ProjectController::class)
        ->names([
            'index' => 'projects.index',
            'create' => 'projects.create',
            'store' => 'projects.store',
            'show' => 'projects.show',
            'edit' => 'projects.edit',
            'update' => 'projects.update',
            'destroy' => 'projects.destroy'
        ]);

    Route::resource('labels', LabelController::class)
        ->names([
            'index' => 'labels.index',
            'create' => 'labels.create',
            'store' => 'labels.store',
            'show' => 'labels.show',
            'edit' => 'labels.edit',
            'update' => 'labels.update',
            'destroy' => 'labels.destroy'
        ]);

    Route::post('/detach/{project}/{label}', [LinkController::class, 'detach'])->name('detach');
    Route::post('/detach/{project}/user/{user}', [LinkController::class, 'detach_user'])->name('detach.user');

    Route::get('/attach/labels/{project}', [LinkController::class, 'labels'])->name('labels.list');
    Route::post('/attach/labels/{project}', [LinkController::class, 'attach_label'])->name('attach.label');

    Route::get('/attach/projects/{label}', [LinkController::class, 'projects'])->name('projects.list');
    Route::post('/attach/projects/{label}', [LinkController::class, 'attach_project'])->name('attach.project');

    Route::get('/attach/users/{project}', [LinkController::class, 'users'])->name('users.list');
    Route::post('/attach/users/{project}', [LinkController::class, 'attach_user'])->name('attach.user');

    Route::get('/deleteall', [\App\Http\Controllers\Controller::class, 'delete'])
        ->middleware('admin')->name('delete.all');
});
